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
     *   "select": ["?user", ["?user", "field1"], ...],
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
            "select" => [
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
            "select" => [
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
            "select" => [
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

    public function getCaseCountByFilter(array $filter): int
    {
        $where = [];
        if (isset($filter['dept_id'])) {
            $where[] = ["?case", "case/dept_id", $filter['dept_id']];
        }
        if (isset($filter['div_id'])) {
            $where[] = ["?case", "case/div_id", $filter['div_id']];
        }
        if (isset($filter['year'])) {
            $where[] = ["?case", "case/year", $filter['year']];
        }

        $query = [
            "select" => ["*"],
            "where" => $where,
            "opts" => ["limit" => 1]
        ];
        $result = $this->query($query);
        return count($result);
    }
}
