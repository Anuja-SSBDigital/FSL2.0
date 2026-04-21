<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CaseController extends Controller
{
    protected FlureeService $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    public function create()
    {
$user = Session::get('fluree_user');
$instId = $user['inst_id']['_id'] ?? null;
$departments = $instId ? $this->fluree->getDepartments($instId) : [];

        return view('cases.create', compact('departments', 'user'));
    }

    public function divisions(string $deptId)
    {
        $divisions = $this->fluree->getDivisionsByDept($deptId);
        return response()->json($divisions);
    }

    public function checkNumber(Request $request)
    {
        $request->validate([
            'case_number' => ['required', 'string', 'regex:/^RFSL\/EE\/\d{4}\/[A-Z0-9]+\/\d{4}$/i']
        ]);

        $caseNumber = strtoupper(trim($request->case_number));
        $exists = $this->fluree->caseExists($caseNumber);

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'dept_id' => 'required|string',
            'div_id' => 'required|string',
            'case_number_suffix' => 'nullable|string|max:4|regex:/^\d{1,4}$/',
            'no_of_exhibits' => 'required|integer|min:1',
            'agency' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:pdf|max:26214' // 25MB max
        ]);

        $year = date('Y');
        $divisions = $this->fluree->getDivisionsByDept($request->dept_id);
        $selectedDiv = collect($divisions)->firstWhere('_id', $request->div_id);
        $div_code = strtoupper($selectedDiv['div_code'] ?? 'UNK');

        // Build case number
        $caseNumberSuffix = trim($request->input('case_number_suffix', ''));
        if ($caseNumberSuffix) {
            $serial = str_pad($caseNumberSuffix, 4, '0', STR_PAD_LEFT);
            $case_number = "RFSL/EE/{$year}/{$div_code}/{$serial}";

            // Check if case number exists
            if ($this->fluree->caseExists($case_number)) {
                return back()->withErrors(['case_number_suffix' => 'This case number already exists.'])->withInput();
            }
        } else {
            // Auto-generate case number
            $filter = [
                'dept_id' => $request->dept_id,
                'div_id' => $request->div_id,
                'year' => $year
            ];
            $currentCount = $this->fluree->getCaseCountByFilter($filter);
            $serial = str_pad($currentCount + 1, 4, '0', STR_PAD_LEFT);
            $case_number = "RFSL/EE/{$year}/{$div_code}/{$serial}";
        }

        // Handle file upload and hash validation
        $receiptFilePath = null;
        $hash = null;

        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');

            // Calculate hash
            $hash = hash_file('sha256', $file->getRealPath());

            // Check if hash already exists
            if ($this->fluree->hashExists($hash)) {
                return back()->withErrors(['receipt_file' => 'This file hash already exists in the system. Please choose another file.'])->withInput();
            }

            // Store file
            $uploadPath = "uploads/{$user['inst_id']['inst_code']}/{$div_code}/EvidenceAccept";
            $fileName = str_replace('/', '_', $case_number) . '_' . time() . '_' . rand(1000, 9999) . '.pdf';
            $receiptFilePath = $file->storeAs($uploadPath, $fileName, 'public');
        }

        // Determine status based on user role
        $status = 'Pending for Assign';
        $caseAssignUserId = '';

        if (isset($user['role_id']['role']) && $user['role_id']['role'] === 'Officer') {
            $status = 'Assigned'; // or whatever the default status should be
            $caseAssignUserId = $user['_id'];
        }

        // Create evidence acceptance record
        $evidenceId = 'evidence_' . uniqid();

        $evidenceBlock = [
            [
                '_id' => 'evidence_acceptancedetails',
                'evidence_acceptancedetails/evidenceid' => $evidenceId,
                'evidence_acceptancedetails/caseno' => $case_number,
                'evidence_acceptancedetails/receiptfilepath' => $receiptFilePath ? asset('storage/' . $receiptFilePath) : null,
                'evidence_acceptancedetails/agencyreferanceno' => $request->reference_no ?? '',
                'evidence_acceptancedetails/agencyname' => $request->agency,
                'evidence_acceptancedetails/notes' => $request->note ?? '',
                'evidence_acceptancedetails/status' => $status,
                'evidence_acceptancedetails/hash' => $hash,
                'evidence_acceptancedetails/department_code' => $div_code,
                'evidence_acceptancedetails/inst_code' => $user['inst_id']['inst_code'],
                'evidence_acceptancedetails/div_code' => $div_code,
                'evidence_acceptancedetails/noof_exhibits' => (string) $request->no_of_exhibits,
                'evidence_acceptancedetails/caseassign_userid' => $caseAssignUserId,
                'evidence_acceptancedetails/enteredby' => $user['username'],
                'evidence_acceptancedetails/createddate' => date('c'),
                'evidence_acceptancedetails/updateddate' => date('c')
            ]
        ];

        // Create track record
        $trackBlock = [
            [
                '_id' => 'trackmaster',
                'trackmaster/trackid' => 'track_' . uniqid(),
                'trackmaster/caseno' => $case_number,
                'trackmaster/status' => $status,
                'trackmaster/caseassignby' => $user['username'],
                'trackmaster/assignto' => $user['_id'],
                'trackmaster/notes' => $request->note ?? '',
                'trackmaster/statuschangedby' => $user['firstname'] . ' ' . $user['lastname'],
                'trackmaster/createddate' => date('c')
            ]
        ];

        // Note: Assignment tracking is handled via trackmaster (above)
        // If you need to store case assignment info, use usercase collection
        $assignmentBlock = [];

        try {
            // Execute all transactions
            $transactions = array_merge([$evidenceBlock, $trackBlock], $assignmentBlock ? [$assignmentBlock] : []);

            foreach ($transactions as $transaction) {
                $result = $this->fluree->transact($transaction);
                Log::info('Transaction executed', ['transaction' => $transaction, 'result' => $result]);
            }

            $message = "Case {$case_number} created successfully!";
            if (isset($user['role_id']['role']) && $user['role_id']['role'] === 'Officer') {
                return redirect()->route('cases.add-details', ['caseno' => $case_number])->with('success', $message);
            } else {
                return redirect()->route('cases.assign', ['caseno' => $case_number])->with('success', $message);
            }

        } catch (\Exception $e) {
            Log::error('Case creation failed', ['error' => $e->getMessage()]);

            // Clean up uploaded file if transaction failed
            if ($receiptFilePath && \Storage::disk('public')->exists($receiptFilePath)) {
                \Storage::disk('public')->delete($receiptFilePath);
            }

            return back()->withErrors(['error' => 'Failed to create case. Please try again.'])->withInput();
        }
    }

    public function addDetails(string $caseNo)
    {
        $user = Session::get('fluree_user');
        $caseDetails = $this->fluree->getUserAcceptanceDetails($caseNo);

        return view('cases.add-details', compact('caseDetails', 'caseNo', 'user'));
    }

    public function assign(string $caseNo)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return redirect()->route('login');
        }

        // Get case details
        $caseDetails = $this->fluree->getUserAcceptanceDetails($caseNo);
        if (!$caseDetails) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Case not found.']);
        }

        // Get department members from the user's department
        $deptId = $user['dept_id']['_id'] ?? null;
        $departmentMembers = $deptId ? $this->fluree->getDepartmentMembers($deptId) : [];

        return view('cases.assign', compact('caseDetails', 'caseNo', 'user', 'departmentMembers'));
    }

    public function updateDetails(Request $request, string $caseNo)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:In Progress,Completed,Pending',
            'notes' => 'nullable|string',
            'additional_evidence.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);

        try {
            // Get the evidence record by case number
            $evidence = $this->fluree->getUserAcceptanceDetails($caseNo);
            if (!$evidence || empty($evidence['_id'])) {
                return back()->withErrors(['error' => 'Case not found.']);
            }

            $evidenceDocId = $evidence['_id'];

            // Update evidence record
            $updateBlock = [
                [
                    '_id' => $evidenceDocId,
                    'evidence_acceptancedetails/status' => $request->status,
                    'evidence_acceptancedetails/notes' => $request->notes ?? '',
                    'evidence_acceptancedetails/updateddate' => date('c')
                ]
            ];

            // Add track record for status change
            $trackBlock = [
                [
                    '_id' => 'trackmaster',
                    'trackmaster/trackid' => 'track_' . uniqid(),
                    'trackmaster/caseno' => $caseNo,
                    'trackmaster/status' => $request->status,
                    'trackmaster/caseassignby' => $user['username'],
                    'trackmaster/assignto' => $user['_id'],
                    'trackmaster/notes' => $request->notes ?? '',
                    'trackmaster/statuschangedby' => $user['firstname'] . ' ' . $user['lastname'],
                    'trackmaster/createddate' => date('c')
                ]
            ];

            // Execute transactions
            foreach ([$updateBlock, $trackBlock] as $transaction) {
                $result = $this->fluree->transact($transaction);
                Log::info('Case details updated', ['caseNo' => $caseNo, 'result' => $result]);
            }

            return redirect()->route('cases.add-details', $caseNo)->with('success', 'Case details updated successfully!');

        } catch (\Exception $e) {
            Log::error('Case update failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to update case details.']);
        }
    }

    /**
     * Get users by department code (AJAX endpoint)
     * Used to populate user dropdown when department changes
     */
    public function getUsersByDepartment(Request $request)
    {
        $deptCode = $request->input('dept_code');
        $excludeUserId = $request->input('exclude_user_id', null);

        if (!$deptCode) {
            return response()->json(['error' => 'Department code required'], 400);
        }

        try {
            $users = $this->fluree->getUsersByDeptCode($deptCode, $excludeUserId);
            return response()->json(['data' => $users]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch users by department', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    public function assignCase(Request $request, string $caseNo)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'assigned_user' => 'required|string',
            'priority' => 'nullable|string|in:Normal,High,Urgent',
            'assignment_notes' => 'nullable|string'
        ]);

        try {
            // Get case details first
            $caseDetails = $this->fluree->getUserAcceptanceDetails($caseNo);
            if (!$caseDetails) {
                return back()->withErrors(['error' => 'Case not found.']);
            }

            // Update evidence status to "Assigned"
            $updateBlock = [
                [
                    '_id' => $caseDetails['_id'],
                    'evidence_acceptancedetails/status' => 'Assigned',
                    'evidence_acceptancedetails/caseassign_userid' => $request->assigned_user,
                    'evidence_acceptancedetails/updateddate' => date('c')
                ]
            ];

            // Track case assignment via trackmaster
            $trackBlock = [
                [
                    '_id' => 'trackmaster',
                    'trackmaster/trackid' => 'track_' . uniqid(),
                    'trackmaster/caseno' => $caseNo,
                    'trackmaster/status' => 'Assigned',
                    'trackmaster/caseassignby' => $user['username'],
                    'trackmaster/assignto' => $request->assigned_user,
                    'trackmaster/notes' => $request->assignment_notes ?? '',
                    'trackmaster/statuschangedby' => $user['firstname'] . ' ' . $user['lastname'],
                    'trackmaster/createddate' => date('c')
                ]
            ];

            // Execute both transactions
            $transactions = [$updateBlock, $trackBlock];

            foreach ($transactions as $transaction) {
                $result = $this->fluree->transact($transaction);
                Log::info('Case assignment transaction', ['transaction' => $transaction, 'result' => $result]);
            }

            return redirect()->route('dashboard')->with('success', "Case {$caseNo} assigned successfully to assigned officer!");

        } catch (\Exception $e) {
            Log::error('Case assignment failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to assign case.']);
        }
    }

    /**
     * Show Evidence Acceptance Form
     */
    public function acceptanceForm()
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $deptCode = $user['dept_code']['dept_code'] ?? null;
        $role = $user['role_id']['role'] ?? null;
        $departments = [];

        // Load departments if SuperAdmin
        if ($role === 'SuperAdmin') {
            $instId = $user['inst_id']['_id'] ?? null;
            $departments = $instId ? $this->fluree->getDepartments($instId) : [];
        }

        return view('cases.acceptance-form', compact('user', 'deptCode', 'role', 'departments'));
    }

    /**
     * Store Evidence Acceptance
     */
    public function acceptanceStore(Request $request)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Determine department code
        $deptCode = $request->input('dept_code') ?? $user['dept_code']['dept_code'] ?? null;
        $role = $user['role_id']['role'] ?? null;

        // Build case number based on department
        if ($deptCode === 'BA') {
            $request->validate([
                'year' => 'required|digits:4',
                'ba_number' => 'required|digits_between:1,4',
                'no_of_exhibits' => 'required|integer|min:1',
                'agency' => 'required|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'note' => 'nullable|string',
                'receipt_file' => 'nullable|file|mimes:pdf|max:26214'
            ]);

            $caseNumber = "RFSL/BA/" . $request->year . "/" . str_pad($request->ba_number, 4, '0', STR_PAD_LEFT);
            $divCode = 'BA';
        } elseif ($deptCode === 'FP') {
            $request->validate([
                'fp_short_name' => 'required|string|max:10',
                'fp_number' => 'required|digits_between:1,4',
                'fp_year' => 'required|digits:4',
                'fp_date' => 'required|date',
                'no_of_exhibits' => 'required|integer|min:1',
                'agency' => 'required|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'note' => 'nullable|string',
                'receipt_file' => 'nullable|file|mimes:pdf|max:26214'
            ]);

            $caseNumber = "FP/CHP/OP/" . $request->fp_short_name . "/" . str_pad($request->fp_number, 4, '0', STR_PAD_LEFT) . "/" . $request->fp_year . "/" . $request->fp_date;
            $divCode = 'FP';
        } else {
            // PSY or other divisions (RFSL/EE)
            $request->validate([
                'year' => 'required|digits:4',
                'div_code' => 'required|string|max:20',
                'number' => 'required|digits_between:1,4',
                'no_of_exhibits' => 'required|integer|min:1',
                'agency' => 'required|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'note' => 'nullable|string',
                'receipt_file' => 'nullable|file|mimes:pdf|max:26214'
            ]);

            $caseNumber = "RFSL/EE/" . $request->year . "/" . strtoupper($request->div_code) . "/" . str_pad($request->number, 4, '0', STR_PAD_LEFT);
            $divCode = strtoupper($request->div_code);
        }

        // Check if case already exists
        $existingCase = $this->fluree->getUserAcceptanceDetails($caseNumber);
        if ($existingCase) {
            return back()->withErrors(['case_number' => 'This case number already exists.'])->withInput();
        }

        // Handle file upload
        $receiptFilePath = null;
        $hash = null;

        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $hash = hash_file('sha256', $file->getRealPath());

            // Check if hash exists
            if ($this->fluree->hashExists($hash)) {
                return back()->withErrors(['receipt_file' => 'This file hash already exists in the system. Please choose another file.'])->withInput();
            }

            // Store file
            $uploadPath = "uploads/{$user['inst_id']['inst_code']}/{$divCode}/EvidenceAccept";
            $fileName = str_replace('/', '_', $caseNumber) . '_' . time() . '_' . rand(1000, 9999) . '.pdf';
            $receiptFilePath = $file->storeAs($uploadPath, $fileName, 'public');
        }

        // Determine status based on role
        $status = 'Assigned';
        $caseAssignUserId = '';

        if ($role !== 'Officer') {
            $status = 'Pending for Assign';
        } else {
            $caseAssignUserId = $user['_id'];
        }

        try {
            $evidenceId = 'evidence_' . uniqid();

            // Evidence acceptance record
            $evidenceBlock = [
                [
                    '_id' => 'evidence_acceptancedetails',
                    'evidence_acceptancedetails/evidenceid' => $evidenceId,
                    'evidence_acceptancedetails/caseno' => $case_number,
                    'evidence_acceptancedetails/receiptfilepath' => $receiptFilePath ? asset('storage/' . $receiptFilePath) : null,
                    'evidence_acceptancedetails/agencyreferanceno' => $request->reference_no ?? '',
                    'evidence_acceptancedetails/agencyname' => $request->agency,
                    'evidence_acceptancedetails/notes' => $request->note ?? '',
                    'evidence_acceptancedetails/status' => $status,
                    'evidence_acceptancedetails/hash' => $hash,
                    'evidence_acceptancedetails/department_code' => $div_code,
                    'evidence_acceptancedetails/inst_code' => $user['inst_id']['inst_code'],
                    'evidence_acceptancedetails/div_code' => $div_code,
                    'evidence_acceptancedetails/noof_exhibits' => (string) $request->no_of_exhibits,
                    'evidence_acceptancedetails/caseassign_userid' => $caseAssignUserId,
                    'evidence_acceptancedetails/enteredby' => $user['username'],
                    'evidence_acceptancedetails/createddate' => date('c'),
                    'evidence_acceptancedetails/updateddate' => date('c')
                ]
            ];

            // Track record
            $trackBlock = [
                [
                    '_id' => 'trackmaster',
                    'trackmaster/trackid' => 'track_' . uniqid(),
                    'trackmaster/caseno' => strtoupper($caseNumber),
                    'trackmaster/status' => $status,
                    'trackmaster/caseassignby' => $user['username'],
                    'trackmaster/assignto' => $user['_id'],
                    'trackmaster/notes' => $request->note ?? '',
                    'trackmaster/statuschangedby' => $user['firstname'] . ' ' . $user['lastname'],
                    'trackmaster/createddate' => date('c')
                ]
            ];

            // Build transactions (track via trackmaster only)
            $transactions = [$evidenceBlock, $trackBlock];

            foreach ($transactions as $transaction) {
                $result = $this->fluree->transact($transaction);
                Log::info('Evidence acceptance transaction', ['result' => $result]);
            }

            $successMsg = "Evidence accepted successfully for case: " . strtoupper($caseNumber);

            if ($role === 'Officer') {
                return redirect()->route('cases.add-details', ['caseno' => strtoupper($caseNumber)])
                    ->with('success', $successMsg);
            } else {
                return redirect()->route('cases.assign', ['caseno' => strtoupper($caseNumber)])
                    ->with('success', $successMsg);
            }

        } catch (\Exception $e) {
            Log::error('Evidence acceptance failed', ['error' => $e->getMessage()]);

            if ($receiptFilePath && \Storage::disk('public')->exists($receiptFilePath)) {
                \Storage::disk('public')->delete($receiptFilePath);
            }

            return back()->withErrors(['error' => 'Failed to accept evidence. Please try again.'])->withInput();
        }
    }

    /**
     * Show Evidence Acceptance Edit Form
     */
    public function acceptanceEdit(string $evidenceId)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $evidence = $this->fluree->getEvidenceById($evidenceId);
        if (!$evidence) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Evidence not found.']);
        }

        return view('cases.acceptance-edit', compact('evidence', 'user'));
    }

    /**
     * Update Evidence Acceptance
     */
    public function acceptanceUpdate(Request $request, string $evidenceId)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'agency' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'no_of_exhibits' => 'required|integer|min:1',
            'receipt_file' => 'nullable|file|mimes:pdf|max:26214'
        ]);

        try {
            $receiptFilePath = null;
            $hash = null;

            // Get the evidence record to find its _id
            $evidence = $this->fluree->getEvidenceById($evidenceId);
            if (!$evidence || empty($evidence['_id'])) {
                return back()->withErrors(['error' => 'Evidence not found.']);
            }

            $evidenceDocId = $evidence['_id'];

            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');
                $hash = hash_file('sha256', $file->getRealPath());

                if ($this->fluree->hashExists($hash)) {
                    return back()->withErrors(['receipt_file' => 'This file hash already exists in the system. Please choose another file.'])->withInput();
                }

                $caseNumber = $evidence['caseno'] ?? 'CASE';
                $divCode = $evidence['div_code'] ?? 'UNK';

                $uploadPath = "uploads/{$user['inst_id']['inst_code']}/{$divCode}/EvidenceAccept";
                $fileName = str_replace('/', '_', $caseNumber) . '_' . time() . '_' . rand(1000, 9999) . '.pdf';
                $receiptFilePath = $file->storeAs($uploadPath, $fileName, 'public');
            }

            $updateData = [
                [
                    '_id' => $evidenceDocId,
                    'evidence_acceptancedetails/agencyname' => $request->agency,
                    'evidence_acceptancedetails/agencyreferanceno' => $request->reference_no ?? '',
                    'evidence_acceptancedetails/notes' => $request->note ?? '',
                    'evidence_acceptancedetails/noof_exhibits' => (string) $request->no_of_exhibits,  // Schema defines as string
                    'evidence_acceptancedetails/updateddate' => date('c')
                ]
            ];

            if ($receiptFilePath) {
                $updateData[0]['evidence_acceptancedetails/receiptfilepath'] = asset('storage/' . $receiptFilePath);
                if ($hash) {
                    $updateData[0]['evidence_acceptancedetails/hash'] = $hash;
                }
            }

            $result = $this->fluree->transact($updateData);
            Log::info('Evidence updated successfully', ['evidenceId' => $evidenceId, 'result' => $result]);

            return redirect()->route('cases.acceptance-edit', $evidenceId)
                ->with('success', 'Evidence details updated successfully!');

        } catch (\Exception $e) {
            Log::error('Evidence update failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to update evidence. Please try again.'])->withInput();
        }
    }
}
