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
                ["?user", "userdetails/isactive", true]
            ],
            "findOne" => true
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
            $response = Http::withHeaders($headers)->post($this->getQueryUrl(), $query);

            if ($response->successful()) {
                $result = $response->json();
                return $result ?: null;
            }
        } catch (\Exception $e) {
            Log::error("Fluree checkLogin failed: " . $e->getMessage());
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
}
