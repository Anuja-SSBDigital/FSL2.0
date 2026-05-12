<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MasterController extends Controller
{
    protected $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    /**
     * User Master - List all users
     */
    public function userMaster()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        $query = [
            "select" => [
                "?u" => [
                    "_id",
                    "firstname",
                    "lastname",
                    "email",
                    "username",
                    "mobileno",
                    "designation",
                    "isactive",
                    "role_id",
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

        $users = $this->fluree->query($query);

        return view('master.users', [
            'users' => $users,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Divisions List
     */
    public function divisions()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        $query = [
            "select" => [
                "?d" => [
                    "_id",
                    "div_name",
                    "div_code",
                    "is_deleted"
                ]
            ],
            "where" => [
                ["?d", "division_master/is_deleted", false]
            ],
            "opts" => [
                "limit" => 100
            ]
        ];

        $divisions = $this->fluree->query($query) ?? [];

        return view('master.divisions', [
            'divisions' => $divisions,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Add Division
     */
    public function addDivision(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('master.add-division');
        }

        $request->validate([
            'div_name' => 'required|string|max:255',
            'div_code' => 'required|string|max:50'
        ]);

        try {
            $data = [
                "div_name" => $request->div_name,
                "div_code" => $request->div_code,
                "is_deleted" => false
            ];

            $result = $this->fluree->transact([$data]);

            if ($result) {
                return redirect()->route('master.divisions')->with('success', 'Division added successfully');
            }

            return back()->with('error', 'Failed to add division');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Departments List
     */
    public function departments()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        $query = [
            "select" => [
                "?d" => [
                    "_id",
                    "dept_name",
                    "dept_code",
                    "div_id",
                    "is_deleted"
                ]
            ],
            "where" => [
                ["?d", "department_master/is_deleted", false]
            ],
            "opts" => [
                "limit" => 100
            ]
        ];

        $departments = $this->fluree->query($query) ?? [];

        return view('master.departments', [
            'departments' => $departments,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Add Department
     */
    public function addDepartment(Request $request)
    {
        if ($request->isMethod('get')) {
            // Get divisions for dropdown
            $query = [
                "select" => [
                    "?d" => [
                        "_id",
                        "div_name"
                    ]
                ],
                "where" => [
                    ["?d", "division_master/is_deleted", false]
                ]
            ];

            $divisions = $this->fluree->query($query) ?? [];

            return view('master.add-department', compact('divisions'));
        }

        $request->validate([
            'dept_name' => 'required|string|max:255',
            'dept_code' => 'required|string|max:50',
            'div_id' => 'required'
        ]);

        try {
            $data = [
                "dept_name" => $request->dept_name,
                "dept_code" => $request->dept_code,
                "div_id" => $request->div_id,
                "is_deleted" => false
            ];

            $result = $this->fluree->transact([$data]);

            if ($result) {
                return redirect()->route('master.departments')->with('success', 'Department added successfully');
            }

            return back()->with('error', 'Failed to add department');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
