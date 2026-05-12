<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    protected $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    /**
     * Show change password form
     */
    public function showChangeForm()
    {
        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        return view('auth.change-password', compact('currentUser'));
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $currentUser = Session::get('fluree_user');
        if (!$currentUser) {
            return redirect()->route('login');
        }

        try {
            // Get user details
            $query = [
                "select" => [
                    "?u" => [
                        "_id",
                        "password"
                    ]
                ],
                "where" => [
                    ["?u", "userdetails/_id", $currentUser['_id']]
                ]
            ];

            $user = $this->fluree->query($query);

            if (!$user || empty($user)) {
                return back()->with('error', 'User not found');
            }

            // Verify current password (Note: This is a simplified example. 
            // In production, implement proper password verification with hashing)
            $userPassword = $user[0]['password'] ?? null;
            if (!$userPassword || $userPassword !== $request->current_password) {
                return back()->with('error', 'Current password is incorrect');
            }

            // Update password
            $updateQuery = [
                "_id" => $currentUser['_id'],
                "password" => $request->password
            ];

            $result = $this->fluree->transact([$updateQuery]);

            if ($result) {
                return redirect()->route('dashboard')->with('success', 'Password changed successfully');
            }

            return back()->with('error', 'Failed to update password');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
