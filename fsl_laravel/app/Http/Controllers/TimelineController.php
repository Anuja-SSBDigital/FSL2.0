<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Support\Facades\Session;

class TimelineController extends Controller
{
    protected $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    /**
     * Timeline Index - Show timeline view selector
     */
    public function index()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        // Get all cases for timeline display through evidence acceptance details
        try {
            $query = [
                "select" => [
                    "?caseno",
                    "?agencyname",
                    "?notes",
                    "?status",
                    "?createddate"
                ],
                "where" => [
                    ["?c", "evidence_acceptancedetails/caseno", "?caseno"],
                    ["?c", "evidence_acceptancedetails/agencyname", "?agencyname"],
                    ["?c", "evidence_acceptancedetails/notes", "?notes"],
                    ["?c", "evidence_acceptancedetails/status", "?status"],
                    ["?c", "evidence_acceptancedetails/createddate", "?createddate"]
                ],
                "opts" => [
                    "limit" => 50,
                    "orderBy" => [["?createddate", false]]
                ]
            ];


            $cases = $this->fluree->query($query) ?? [];

            return view('timeline.index', [
                'cases' => $cases,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View timeline for specific case
     */
    public function viewCaseTimeline($caseno)
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get case details
            $caseQuery = [
                "select" => [
                    "?caseno",
                    "?agencyname",
                    "?notes",
                    "?status",
                    "?createddate",
                    "?enteredby",
                    "?caseassign_userid"
                ],
                "where" => [
                    ["?c", "evidence_acceptancedetails/caseno", $caseno],
                    ["?c", "evidence_acceptancedetails/agencyname", "?agencyname"],
                    ["?c", "evidence_acceptancedetails/notes", "?notes"],
                    ["?c", "evidence_acceptancedetails/status", "?status"],
                    ["?c", "evidence_acceptancedetails/createddate", "?createddate"],
                    ["?c", "evidence_acceptancedetails/enteredby", "?enteredby"],
                    ["?c", "evidence_acceptancedetails/caseassign_userid", "?caseassign_userid"]
                ]
            ];


            $caseDetails = $this->fluree->query($caseQuery);

            if (!$caseDetails || empty($caseDetails)) {
                return back()->with('error', 'Case not found');
            }

            $case = $caseDetails[0];
            $case['caseno'] = $caseno; // Ensure caseno is included

            // Get case activities from trackmaster
            $activitiesQuery = [
                "select" => [
                    "?caseno",
                    "?status",
                    "?caseassignby",
                    "?assignto",
                    "?notes",
                    "?statuschangedby",
                    "?createddate"
                ],
                "where" => [
                    ["?t", "trackmaster/caseno", $caseno],
                    ["?t", "trackmaster/status", "?status"],
                    ["?t", "trackmaster/caseassignby", "?caseassignby"],
                    ["?t", "trackmaster/assignto", "?assignto"],
                    ["?t", "trackmaster/notes", "?notes"],
                    ["?t", "trackmaster/statuschangedby", "?statuschangedby"],
                    ["?t", "trackmaster/createddate", "?createddate"]
                ],
                "opts" => [
                    "orderBy" => [["?createddate", false]]
                ]
            ];


            $activities = $this->fluree->query($activitiesQuery) ?? [];

            return view('timeline.case-timeline', [
                'case' => $case,
                'activities' => $activities,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Timeline Statistics
     */
    public function statistics()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get various timeline statistics
            $stats = [
                'total_cases' => 0,
                'active_cases' => 0,
                'completed_cases' => 0,
                'pending_cases' => 0,
                'recent_activities' => []
            ];

            // Get cases count by status
            $query = [
                "select" => [
                    "?caseno",
                    "?status",
                    "?createddate"
                ],
                "where" => [
                    ["?c", "evidence_acceptancedetails/caseno", "?caseno"],
                    ["?c", "evidence_acceptancedetails/status", "?status"],
                    ["?c", "evidence_acceptancedetails/createddate", "?createddate"]
                ],
                "opts" => [
                    "limit" => 100
                ]
            ];


            $cases = $this->fluree->query($query) ?? [];

            foreach ($cases as $case) {
                $stats['total_cases']++;
                $status = $case['status'] ?? '';
                if ($status === 'Completed') {
                    $stats['completed_cases']++;
                } else {
                    $stats['active_cases']++;
                    if ($status === 'Pending for Assign' || $status === 'Pending') {
                        $stats['pending_cases']++;
                    }
                }
            }


            return view('timeline.statistics', [
                'stats' => $stats,
                'currentUser' => $currentUser
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
