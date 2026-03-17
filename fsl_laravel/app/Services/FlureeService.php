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

    // Build your exact old-style query URL
    private function getQueryUrl(): string
    {
        return "{$this->baseUrl}/fdb/{$this->network}/{$this->ledger}/query";
    }

    /**
     * Login using your exact custom FQL query (same as C#)
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
                ["?user", "userdetails/isactive", "1"]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->post($this->getQueryUrl(), $query);

            if ($response->successful()) {
                $result = $response->json();
                return !empty($result) ? $result[0] : null;   // return first user
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Fluree query failed: " . $e->getMessage());    
        }

        return null;
    }

    // Optional: General query method using same URL style
    public function query(array $flureeQuery): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->getQueryUrl(), $flureeQuery);

        $response->throw();
        return $response->json();
    }
}