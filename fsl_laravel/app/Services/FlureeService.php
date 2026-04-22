<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlureeService
{
    protected string $baseUrl;
    protected string $network;
    protected string $ledger;

    public function __construct()
    {
        $this->baseUrl = config('fluree.base_url');
        $this->network = config('fluree.network');
        $this->ledger  = config('fluree.ledger');
    }

    // Build query URL
    private function getQueryUrl(): string
    {
        return "{$this->baseUrl}/fdb/{$this->network}/{$this->ledger}/query";
    }

    /**
     * Check login with proper Fluree FQL query reference.
     * 
     * Proper FQL v2 Block Syntax for Login:
     * {
     *   "selectDistinct": ["?user", ["?user", "field1"], ...],
     *   "where": [
     *     ["?user", "userdetails/username", "?username"],
     *     ["userdetails/username", "?user", "?username"],  // reverse/incoming link
     *     ["?user", "userdetails/password", "?password"],
     *     ["?user", "userdetails/isactive", true]
     *   ],
     *   "findOne": true  // for single user lookup
     * }
     * 
     * Reference: https://docs.fluree.com/fql/block/
     * Endpoint: POST /fdb/{network}/{ledger}/query
     */
    public function checkLogin(string $username, string $encryptedPassword): ?array
    {
       
        $isActive = "1"; // string, not boolean

        $query = [
            "selectDistinct" => [
                "?user" => [
                    "_id",
                    "userid",
                    "firstname",
                    "lastname",
                    "username",
                    "password",
                    "designation",
                    "status",
                    ["role_id" => ["role"]],
                    ["inst_id" => ["inst_name", "inst_code", "inst_id", "location"]],
                    ["dept_id" => ["dept_name", "dept_code", "dept_id"]],
                    ["div_id" => ["div_name", "div_code"]]
                ]
            ],
            "where" => [
                ["?user", "userdetails/username", $username],
                ["?user", "userdetails/password", $encryptedPassword],
                ["?user", "userdetails/is_deleted", false],
                ["?user", "userdetails/isactive", $isActive]
            ],
            "opts" => ["limit" => 1]
        ];
        // Note: Confirm predicate direction in your Fluree schema.
        // If incoming links: ["userdetails/username", "?user", $username]

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        if (config('fluree.api_key')) {
            $headers['Fluree-API-Key'] = config('fluree.api_key');
        }

        try {
            $response = Http::withHeaders($headers)
                ->post($this->getQueryUrl(), $query);   // ← sent as JSON automatically

            if ($response->successful()) {
                $result = $response->json();

                // Fluree usually returns array of results
                return !empty($result) ? ($result[0] ?? $result) : null;
            } else {
                Log::error("Fluree login failed - Status: " . $response->status() . " Body: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Fluree checkLogin exception: " . $e->getMessage());
        }
        return null;
    }

    public function query(array $flureeQuery): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (config('fluree.api_key')) {
            $headers['Fluree-API-Key'] = config('fluree.api_key');
        }

        $response = Http::withHeaders($headers)->post($this->getQueryUrl(), $flureeQuery);
        $response->throw();
        return $response->json();
    }

    private function getTransactUrl(): string
    {
        return "{$this->baseUrl}/fdb/{$this->network}/{$this->ledger}/transact";
    }

    public function transact(array $blocks): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (config('fluree.api_key')) {
            $headers['Fluree-API-Key'] = config('fluree.api_key');
        }

        $response = Http::withHeaders($headers)->post($this->getTransactUrl(), $blocks);
        $response->throw();
        return $response->json();
    }

public function getDepartments(string $instId): array
    {
        $query = [
            "selectDistinct" => [
                "?dept" => [
                    "dept_id",
                    "dept_code",
                    "dept_name",
                    "_id"
                ]
            ],
            "where" => [
                ["?dept", "department_master/dept_name", "?name"],
                ["?dept", "department_master/inst_id", ["institute_master/inst_id", $instId]],
                ["?dept", "department_master/is_deleted", false]
            ],
            "opts" => [
                "filter" => "(and(not (= ?name \"ALL Department\")(not (= ?name \"Other Sample Warden\"))))",
                "orderBy" => "?name"
            ]
        ];
        $result = $this->query($query);
        return $result ?: [];
    }

    public function getDivisionsByDept(string $deptId): array
    {
        $query = [
            "selectDistinct" => [
                "?div" => [
                    "div_id",
                    "div_name",
                    "div_code",
                    "_id"
                ]
            ],
            "where" => [
                ["?div", "division_master/div_name", "?name"],
                ["?div", "division_master/dept_id", ["department_master/dept_id", $deptId]],
                ["?div", "division_master/is_deleted", false]
            ],
            "opts" => ["orderBy" => "?name"]
        ];
        $result = $this->query($query);
        return $result ?: [];
    }

    public function getDepartmentMembers(string $deptId): array
    {
        $query = [
            "selectDistinct" => [
                "?user" => [
                    "_id",
                    "userid",
                    "firstname",
                    "lastname",
                    "username",
                    "email",
                    "designation",
                    "status",
                    ["role_id" => ["role"]]
                ]
            ],
            "where" => [
                ["?user", "userdetails/dept_id", "?dept"],
                ["?dept", "department_master/dept_id", $deptId],
                ["?user", "userdetails/is_deleted", false],
                ["?user", "userdetails/isactive", "1"]
            ]
        ];

        $result = $this->query($query);
        
        // Sort by firstname in PHP after fetching
        if (!empty($result)) {
            usort($result, function($a, $b) {
                $nameA = $a['firstname'] ?? '';
                $nameB = $b['firstname'] ?? '';
                return strcmp($nameA, $nameB);
            });
        }
        
        return $result ?: [];
    }

    /**
     * Get users by department code
     * 
     * Equivalent to GetUsersDeptcodewise from ASP.NET
     * 
     * @param string $deptCode - Department code to filter by
     * @param string|null $excludeUserId - Optional user ID to exclude (pass null to include all)
     * @return array - Array of users with userid, firstname, lastname
     */
    public function getUsersByDeptCode(string $deptCode, ?string $excludeUserId = null): array
    {
        $query = [
            "selectDistinct" => [
                "?user" => [
                    "_id",
                    "userid",
                    "firstname",
                    "lastname",
                    "username",
                    "email",
                    "designation",
                    "status",
                    ["role_id" => ["role"]]
                ]
            ],
            "where" => [
                ["?user", "userdetails/dept_id", "?dept"],
                ["?dept", "department_master/dept_code", $deptCode],
                ["?user", "userdetails/is_deleted", false],
                ["?user", "userdetails/isactive", "1"]
            ]
        ];

        $result = $this->query($query);

        // Exclude specific user if provided (and not "-1")
        if ($excludeUserId && $excludeUserId !== "-1") {
            $result = array_filter($result, function($item) use ($excludeUserId) {
                $itemUserId = $item['userid'] ?? $item['_id'] ?? '';
                return $itemUserId !== $excludeUserId;
            });
            // Re-index array after filter
            $result = array_values($result);
        }

        // Sort by firstname
        if (!empty($result)) {
            usort($result, function($a, $b) {
                $nameA = $a['firstname'] ?? '';
                $nameB = $b['firstname'] ?? '';
                return strcmp($nameA, $nameB);
            });
        }

        return $result ?: [];
    }

    public function getCaseCountByFilter(array $filter): int
    {
        $where = [];
        if (isset($filter['dept_id'])) {
            $where[] = ["?case", "evidence_acceptancedetails/department_code", $filter['dept_id']];
        }
        if (isset($filter['div_id'])) {
            $where[] = ["?case", "evidence_acceptancedetails/div_code", $filter['div_id']];
        }
        if (isset($filter['year'])) {
            $where[] = ["?case", "evidence_acceptancedetails/caseno", "?caseno"];
        }

        if (empty($where)) {
            return 0;
        }

        $query = [
            "selectDistinct" => ["?case" => ["evidence_acceptancedetails/evidenceid", "evidence_acceptancedetails/caseno"]],
            "where" => $where,
            "opts" => ["limit" => 10000]
        ];
        $result = $this->query($query);
        
        // Filter by year if provided (by parsing caseno)
        if (isset($filter['year'])) {
            $result = array_filter($result, function($item) use ($filter) {
                $caseno = $item[1] ?? ''; // caseno is second element
                return strpos($caseno, (string)$filter['year']) !== false;
            });
        }
        
        return count($result);
    }

    public function caseExists(string $caseNumber): bool
    {
        $query = [
            "selectDistinct" => ["?case" => ["evidence_acceptancedetails/caseno"]],
            "where" => [
                ["?case", "evidence_acceptancedetails/caseno", $caseNumber]
            ],
            "opts" => ["limit" => 1]
        ];

        $result = $this->query($query);
        return !empty($result);
    }

    public function hashExists(string $hash): bool
    {
        $query = [
            "selectDistinct" => ["?evidence" => ["evidence_acceptancedetails/hash"]],
            "where" => [
                ["?evidence", "evidence_acceptancedetails/hash", $hash]
            ],
            "opts" => ["limit" => 1]
        ];

        $result = $this->query($query);
        return !empty($result);
    }

    public function getUserAcceptanceDetails(string $caseNumber): array
    {
        $query = [
            "selectDistinct" => [
                "?evidence" => [
                    "_id",
                    "evidenceid",
                    "caseno",
                    "receiptfilepath",
                    "agencyreferanceno",
                    "agencyname",
                    "notes",
                    "status",
                    "hash",
                    "department_code",
                    "inst_code",
                    "div_code",
                    "noof_exhibits",
                    "caseassign_userid",
                    "enteredby",
                    "createddate",
                    "updateddate"
                ]
            ],
            "where" => [
                ["?evidence", "evidence_acceptancedetails/caseno", $caseNumber]
            ],
            "opts" => ["limit" => 1]
        ];

        $result = $this->query($query);
        return $result[0] ?? [];
    }

    public function getEvidenceById(string $evidenceId): array
    {
        $query = [
            "selectDistinct" => [
                "?evidence" => [
                    "_id",
                    "evidence_acceptancedetails/evidenceid",
                    "evidence_acceptancedetails/caseno",
                    "evidence_acceptancedetails/receiptfilepath",
                    "evidence_acceptancedetails/agencyreferanceno",
                    "evidence_acceptancedetails/agencyname",
                    "evidence_acceptancedetails/notes",
                    "evidence_acceptancedetails/status",
                    "evidence_acceptancedetails/hash",
                    "evidence_acceptancedetails/department_code",
                    "evidence_acceptancedetails/inst_code",
                    "evidence_acceptancedetails/div_code",
                    "evidence_acceptancedetails/noof_exhibits",
                    "evidence_acceptancedetails/caseassign_userid",
                    "evidence_acceptancedetails/enteredby",
                    "evidence_acceptancedetails/createddate",
                    "evidence_acceptancedetails/updateddate"
                ]
            ],
            "where" => [
                ["?evidence", "evidence_acceptancedetails/evidenceid", $evidenceId]
            ],
            "opts" => ["limit" => 1]
        ];

        $result = $this->query($query);
        return $result[0] ?? [];
    }
}
