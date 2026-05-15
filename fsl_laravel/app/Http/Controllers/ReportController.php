<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Barryvdh\DomPDF\Facade\Pdf;
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
     * Corrected to use evidence_acceptancedetails schema instead of non-existent case predicates
     */
    public function caseReport(Request $request)
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all cases with details from evidence_acceptancedetails
            // This uses the correct Fluree schema predicates
            $query = [
                "selectDistinct" => [
                    "?evidence" => [
                        "_id",
                        "evidenceid",
                        "caseno",
                        "agencyname",
                        "agencyreferanceno",
                        "notes",
                        "status",
                        "department_code",
                        "inst_code",
                        "div_code",
                        "noof_exhibits",
                        "enteredby",
                        "caseassign_userid",
                        "createddate",
                        "updateddate"
                    ]
                ],
                "where" => [
                    ["?evidence", "evidence_acceptancedetails/caseno", "?caseno"]
                ],
                "opts" => [
                    "limit" => 1000,
                    "orderBy" => [["?evidence", "evidence_acceptancedetails/createddate"]]
                ]
            ];

            $cases = $this->fluree->query($query) ?? [];
            
            // Ensure unique results by removing duplicates based on caseno
            $uniqueCases = [];
            $seenCaseNos = [];
            
            foreach ($cases as $case) {
                $caseno = $case['caseno'] ?? '';
                if (!in_array($caseno, $seenCaseNos)) {
                    $uniqueCases[] = $case;
                    $seenCaseNos[] = $caseno;
                }
            }

            return view('reports.case-report', [
                'cases' => $uniqueCases,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate Department Report
     * Shows case statistics grouped by department
     */
    public function departmentReport()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all departments with is_deleted = false
            $query = [
                "selectDistinct" => [
                    "?dept" => [
                        "_id",
                        "dept_name",
                        "dept_code",
                        "dept_id"
                    ]
                ],
                "where" => [
                    ["?dept", "department_master/is_deleted", false],
                    ["?dept", "department_master/dept_name", "?name"]
                ],
                "opts" => [
                    "limit" => 50,
                    "filter" => "(and (not (= ?name \"ALL Department\")) (not (= ?name \"Other Sample Warden\")))",
                    "orderBy" => "?name"
                ]
            ];

            $departments = $this->fluree->query($query) ?? [];
            
            // Ensure unique departments and calculate case counts
            $uniqueDepts = [];
            $seenDeptIds = [];
            
            foreach ($departments as $dept) {
                $deptId = $dept['dept_id'] ?? '';
                if (!in_array($deptId, $seenDeptIds)) {
                    // Count cases for this department
                    $caseCountQuery = [
                        "selectDistinct" => [
                            "?evidence" => ["evidence_acceptancedetails/caseno"]
                        ],
                        "where" => [
                            ["?evidence", "evidence_acceptancedetails/department_code", $deptId]
                        ],
                        "opts" => ["limit" => 10000]
                    ];
                    
                    $cases = $this->fluree->query($caseCountQuery) ?? [];
                    $dept['case_count'] = count($cases);
                    
                    $uniqueDepts[] = $dept;
                    $seenDeptIds[] = $deptId;
                }
            }

            return view('reports.department-report', [
                'departments' => $uniqueDepts,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate User Performance Report
     * Shows user productivity metrics and case handling statistics
     */
    public function userPerformanceReport()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all active users with their case statistics
            $query = [
                "selectDistinct" => [
                    "?user" => [
                        "_id",
                        "userid",
                        "firstname",
                        "lastname",
                        "email",
                        "username",
                        "dept_id"
                    ]
                ],
                "where" => [
                    ["?user", "userdetails/is_deleted", false],
                    ["?user", "userdetails/isactive", "1"],
                    ["?user", "userdetails/firstname", "?firstname"]
                ],
                "opts" => [
                    "limit" => 100,
                    "orderBy" => "?firstname"
                ]
            ];

            $users = $this->fluree->query($query) ?? [];
            
            // Ensure unique users and calculate case statistics
            $uniqueUsers = [];
            $seenUserIds = [];
            
            foreach ($users as $user) {
                $userId = $user['userid'] ?? $user['_id'] ?? '';
                if (!in_array($userId, $seenUserIds)) {
                    // Count cases assigned/entered by this user
                    $assignedQuery = [
                        "selectDistinct" => [
                            "?evidence" => ["evidence_acceptancedetails/caseno"]
                        ],
                        "where" => [
                            ["?evidence", "evidence_acceptancedetails/caseassign_userid", $userId]
                        ],
                        "opts" => ["limit" => 10000]
                    ];
                    
                    $enteredQuery = [
                        "selectDistinct" => [
                            "?evidence" => ["evidence_acceptancedetails/caseno"]
                        ],
                        "where" => [
                            ["?evidence", "evidence_acceptancedetails/enteredby", $userId]
                        ],
                        "opts" => ["limit" => 10000]
                    ];
                    
                    $assignedCases = $this->fluree->query($assignedQuery) ?? [];
                    $enteredCases = $this->fluree->query($enteredQuery) ?? [];
                    
                    $user['assigned_count'] = count($assignedCases);
                    $user['entered_count'] = count($enteredCases);
                    
                    $uniqueUsers[] = $user;
                    $seenUserIds[] = $userId;
                }
            }

            return view('reports.user-performance-report', [
                'users' => $uniqueUsers,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate Project Report
     * Shows case processing workflow and completion statistics
     */
    public function projectReport()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get all cases grouped by status for project overview
            $query = [
                "selectDistinct" => [
                    "?evidence" => [
                        "_id",
                        "evidenceid",
                        "caseno",
                        "agencyname",
                        "status",
                        "department_code",
                        "enteredby",
                        "caseassign_userid",
                        "createddate",
                        "updateddate"
                    ]
                ],
                "where" => [
                    ["?evidence", "evidence_acceptancedetails/caseno", "?caseno"]
                ],
                "opts" => [
                    "limit" => 1000,
                    "orderBy" => [["?evidence", "evidence_acceptancedetails/status"]]
                ]
            ];

            $allCases = $this->fluree->query($query) ?? [];
            
            // Remove duplicates and categorize by status
            $uniqueCases = [];
            $seenCaseNos = [];
            $statusCounts = [
                'pending' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'total' => 0
            ];
            
            foreach ($allCases as $case) {
                $caseno = $case['caseno'] ?? '';
                if (!in_array($caseno, $seenCaseNos)) {
                    $uniqueCases[] = $case;
                    $seenCaseNos[] = $caseno;
                    
                    $status = $case['status'] ?? 'unknown';
                    if (strtolower($status) === 'pending') {
                        $statusCounts['pending']++;
                    } elseif (strtolower($status) === 'completed') {
                        $statusCounts['completed']++;
                    } else {
                        $statusCounts['in_progress']++;
                    }
                    $statusCounts['total']++;
                }
            }
            
            // Calculate completion percentage
            $completionRate = $statusCounts['total'] > 0 
                ? round(($statusCounts['completed'] / $statusCounts['total']) * 100, 2)
                : 0;

            return view('reports.project-report', [
                'cases' => $uniqueCases,
                'statusCounts' => $statusCounts,
                'completionRate' => $completionRate,
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
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        $reportType = $request->report_type ?? 'case';

        try {
            switch ($reportType) {
                case 'department':
                    // replicate departmentReport() data query
                    $query = [
                        "selectDistinct" => [
                            "?dept" => [
                                "_id",
                                "dept_name",
                                "dept_code",
                                "dept_id"
                            ]
                        ],
                        "where" => [
                            ["?dept", "department_master/is_deleted", false],
                            ["?dept", "department_master/dept_name", "?name"]
                        ],
                        "opts" => [
                            "limit" => 50,
                            "filter" => "(and (not (= ?name \"ALL Department\")) (not (= ?name \"Other Sample Warden\")))",
                            "orderBy" => "?name"
                        ]
                    ];

                    $departments = $this->fluree->query($query) ?? [];

                    $uniqueDepts = [];
                    $seenDeptIds = [];

                    foreach ($departments as $dept) {
                        $deptId = $dept['dept_id'] ?? '';
                        if (!in_array($deptId, $seenDeptIds)) {
                            $caseCountQuery = [
                                "selectDistinct" => [
                                    "?evidence" => ["evidence_acceptancedetails/caseno"]
                                ],
                                "where" => [
                                    ["?evidence", "evidence_acceptancedetails/department_code", $deptId]
                                ],
                                "opts" => ["limit" => 10000]
                            ];

                            $cases = $this->fluree->query($caseCountQuery) ?? [];
                            $dept['case_count'] = count($cases);

                            $uniqueDepts[] = $dept;
                            $seenDeptIds[] = $deptId;
                        }
                    }

                    $pdf = Pdf::loadView('reports.pdf.department-report-pdf', [
                        'departments' => $uniqueDepts,
                        'currentUser' => $currentUser
                    ])->setPaper('a4', 'portrait');

                    return $pdf->download('department-report.pdf');

                case 'project':
                    // replicate projectReport() data query
                    $query = [
                        "selectDistinct" => [
                            "?evidence" => [
                                "_id",
                                "evidenceid",
                                "caseno",
                                "agencyname",
                                "status",
                                "department_code",
                                "enteredby",
                                "caseassign_userid",
                                "createddate",
                                "updateddate"
                            ]
                        ],
                        "where" => [
                            ["?evidence", "evidence_acceptancedetails/caseno", "?caseno"]
                        ],
                        "opts" => [
                            "limit" => 1000,
                            "orderBy" => [["?evidence", "evidence_acceptancedetails/status"]]
                        ]
                    ];

                    $allCases = $this->fluree->query($query) ?? [];

                    $uniqueCases = [];
                    $seenCaseNos = [];
                    $statusCounts = [
                        'pending' => 0,
                        'in_progress' => 0,
                        'completed' => 0,
                        'total' => 0
                    ];

                    foreach ($allCases as $case) {
                        $caseno = $case['caseno'] ?? '';
                        if (!in_array($caseno, $seenCaseNos)) {
                            $uniqueCases[] = $case;
                            $seenCaseNos[] = $caseno;

                            $status = $case['status'] ?? 'unknown';
                            if (strtolower($status) === 'pending') {
                                $statusCounts['pending']++;
                            } elseif (strtolower($status) === 'completed') {
                                $statusCounts['completed']++;
                            } else {
                                $statusCounts['in_progress']++;
                            }
                            $statusCounts['total']++;
                        }
                    }

                    $completionRate = $statusCounts['total'] > 0
                        ? round(($statusCounts['completed'] / $statusCounts['total']) * 100, 2)
                        : 0;

                    $pdf = Pdf::loadView('reports.pdf.project-report-pdf', [
                        'cases' => $uniqueCases,
                        'statusCounts' => $statusCounts,
                        'completionRate' => $completionRate,
                        'currentUser' => $currentUser
                    ])->setPaper('a4', 'portrait');

                    return $pdf->download('project-report.pdf');

                case 'user-performance':
                    // replicate userPerformanceReport() data query
                    $query = [
                        "selectDistinct" => [
                            "?user" => [
                                "_id",
                                "userid",
                                "firstname",
                                "lastname",
                                "email",
                                "username",
                                "dept_id"
                            ]
                        ],
                        "where" => [
                            ["?user", "userdetails/is_deleted", false],
                            ["?user", "userdetails/isactive", "1"],
                            ["?user", "userdetails/firstname", "?firstname"]
                        ],
                        "opts" => [
                            "limit" => 100,
                            "orderBy" => "?firstname"
                        ]
                    ];

                    $users = $this->fluree->query($query) ?? [];

                    $uniqueUsers = [];
                    $seenUserIds = [];

                    foreach ($users as $user) {
                        $userId = $user['userid'] ?? $user['_id'] ?? '';
                        if (!in_array($userId, $seenUserIds)) {
                            $assignedQuery = [
                                "selectDistinct" => [
                                    "?evidence" => ["evidence_acceptancedetails/caseno"]
                                ],
                                "where" => [
                                    ["?evidence", "evidence_acceptancedetails/caseassign_userid", $userId]
                                ],
                                "opts" => ["limit" => 10000]
                            ];

                            $enteredQuery = [
                                "selectDistinct" => [
                                    "?evidence" => ["evidence_acceptancedetails/caseno"]
                                ],
                                "where" => [
                                    ["?evidence", "evidence_acceptancedetails/enteredby", $userId]
                                ],
                                "opts" => ["limit" => 10000]
                            ];

                            $assignedCases = $this->fluree->query($assignedQuery) ?? [];
                            $enteredCases = $this->fluree->query($enteredQuery) ?? [];

                            $user['assigned_count'] = count($assignedCases);
                            $user['entered_count'] = count($enteredCases);

                            $uniqueUsers[] = $user;
                            $seenUserIds[] = $userId;
                        }
                    }

                    $pdf = Pdf::loadView('reports.pdf.user-performance-report-pdf', [
                        'users' => $uniqueUsers,
                        'currentUser' => $currentUser
                    ])->setPaper('a4', 'portrait');

                    return $pdf->download('user-performance-report.pdf');

                case 'case':
                default:
                    // replicate caseReport() data query
                    $query = [
                        "selectDistinct" => [
                            "?evidence" => [
                                "_id",
                                "evidenceid",
                                "caseno",
                                "agencyname",
                                "agencyreferanceno",
                                "notes",
                                "status",
                                "department_code",
                                "inst_code",
                                "div_code",
                                "noof_exhibits",
                                "enteredby",
                                "caseassign_userid",
                                "createddate",
                                "updateddate"
                            ]
                        ],
                        "where" => [
                            ["?evidence", "evidence_acceptancedetails/caseno", "?caseno"]
                        ],
                        "opts" => [
                            "limit" => 1000,
                            "orderBy" => [["?evidence", "evidence_acceptancedetails/createddate"]]
                        ]
                    ];

                    $cases = $this->fluree->query($query) ?? [];

                    $uniqueCases = [];
                    $seenCaseNos = [];

                    foreach ($cases as $case) {
                        $caseno = $case['caseno'] ?? '';
                        if (!in_array($caseno, $seenCaseNos)) {
                            $uniqueCases[] = $case;
                            $seenCaseNos[] = $caseno;
                        }
                    }

                    $pdf = Pdf::loadView('reports.pdf.case-report-pdf', [
                        'cases' => $uniqueCases,
                        'currentUser' => $currentUser
                    ])->setPaper('a4', 'portrait');

                    return $pdf->download('case-report.pdf');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
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
