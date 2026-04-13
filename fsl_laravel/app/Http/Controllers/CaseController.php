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

    public function store(Request $request)
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'dept_id' => 'required|string',
            'div_id' => 'required|string',
            'no_of_exhibits' => 'required|integer|min:1',
            'agency' => 'required|string|max:255',
            'note' => 'nullable|string'
        ]);

        $year = date('Y');
        $div = $this->fluree->getDivisionsByDept($request->dept_id);
        $selectedDiv = collect($div)->firstWhere('_id', $request->div_id);
        $div_code = $selectedDiv['div_code'] ?? 'UNK';

        $filter = [
            'dept_id' => $request->dept_id,
            'div_id' => $request->div_id,
            'year' => $year
        ];
        $currentCount = $this->fluree->getCaseCountByFilter($filter);
        $serial = str_pad($currentCount + 1, 4, '0', STR_PAD_LEFT);
        $case_number = "RFSL/EE/{$year}/{$div_code}/{$serial}";

        $caseBlock = [
            [
                '_id' => '_case/new',
                'case/dept_id' => $request->dept_id,
                'case/div_id' => $request->div_id,
                'case/case_number' => $case_number,
                'case/year' => $year,
                'case/serial' => $serial,
                'case/no_of_exhibits' => (int) $request->no_of_exhibits,
                'case/agency' => $request->agency,
                'case/note' => $request->note ?? '',
                'case/created_by' => $user['_id'],
                'case/created_at' => date('c')  // ISO datetime
            ]
        ];

        try {
            $result = $this->fluree->transact($caseBlock);
            Log::info('Case created', ['case_number' => $case_number, 'result' => $result]);

            return redirect()->route('cases.create')->with('success', "Case {$case_number} created successfully!");
        } catch (\Exception $e) {
            Log::error('Case creation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to create case. Check logs.']);
        }
    }
}
