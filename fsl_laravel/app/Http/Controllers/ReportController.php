<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    protected $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    /**
     * Reports Index - List available reports
     */
    public function index()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        return view('reports.index', [
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Generate Case Report
     */
    public function caseReport(Request $request)
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all cases with details
            $query = [
                "select" => [
                    "?c" => [
                        "_id",
                        "caseno",
                        "caseheader",
                        "description",
                        "status",
                        "createddate",
                        "createdby",
                        "dept_id",
                        "assigned_to",
                        "case_outcome"
                    ]
                ],
                "where" => [
                    ["?c", "case/caseno", "?caseno"]
                ],
                "opts" => [
                    "limit" => 100
                ]
            ];

            $cases = $this->fluree->query($query) ?? [];

            return view('reports.case-report', [
                'cases' => $cases,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate Department Report
     */
    public function departmentReport()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get statistics by department
            $query = [
                "select" => [
                    "?d" => [
                        "_id",
                        "dept_name",
                        "dept_code"
                    ]
                ],
                "where" => [
                    ["?d", "department_master/is_deleted", false]
                ],
                "opts" => [
                    "limit" => 50
                ]
            ];

            $departments = $this->fluree->query($query) ?? [];

            return view('reports.department-report', [
                'departments' => $departments,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate User Performance Report
     */
    public function userPerformanceReport()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all users with their case statistics
            $query = [
                "select" => [
                    "?u" => [
                        "_id",
                        "firstname",
                        "lastname",
                        "email",
                        "username",
                        "dept_id"
                    ]
                ],
                "where" => [
                    ["?u", "userdetails/is_deleted", false]
                ],
                "opts" => [
                    "limit" => 100
                ]
            ];

            $users = $this->fluree->query($query) ?? [];

            return view('reports.user-performance-report', [
                'users' => $users,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Download Report as PDF
     */
    public function downloadReport(Request $request)
    {
        $reportType = $request->report_type ?? 'case';
        
        // This would integrate with a PDF library like TCPDF or mPDF
        // For now, returning JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Report download initiated',
            'report_type' => $reportType
        ]);
    }

    /**
     * Export Report as CSV
     */
    public function exportReport(Request $request)
    {
        $reportType = $request->report_type ?? 'case';
        
        // This would export data as CSV
        // For now, returning JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Report exported successfully',
            'report_type' => $reportType
        ]);
    }
}
