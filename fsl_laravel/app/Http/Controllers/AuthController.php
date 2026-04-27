<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected FlureeService $fluree;

    public function __construct(FlureeService $fluree)
    {
        $this->fluree = $fluree;
    }

    // Show Login Page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // IMPORTANT: Encrypt password exactly the same way as your old C# EncryptString()
        $encryptedPass = $this->encryptPassword($request->password);   // We'll create this helper

        // Call your custom Fluree login query
        $userData = $this->fluree->checkLogin($request->username, $encryptedPass);

        if ($userData) {
            Session::put('fluree_user', $userData);
            Session::put('is_authenticated', true);

            return redirect()->route('dashboard')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'username' => 'Invalid username or password.'
        ])->withInput();
    }

    // Dashboard Page
    public function dashboard()
    {
        $user = Session::get('fluree_user');
        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = $user['role_id']['role'] ?? null;
        $userDeptCode = $user['dept_id']['dept_code'] ?? null;
        $instId = $user['inst_id']['_id'] ?? null;

        $allUsers = [];
        $userStats = [];
        $totalCases = 0;
        $pendingCases = 0;
        $completedCases = 0;

        if ($userRole === 'SuperAdmin') {
            // Admin sees all users and their case counts
            $allUsers = $this->fluree->getAllUsers();
            
            // Get all cases and count by user
            $allCases = $this->fluree->getAllCases();
            $totalCases = count($allCases);
            $pendingCases = count(array_filter($allCases, fn($c) => ($c['status'] ?? '') === 'Pending for Assign' || ($c['status'] ?? '') === 'Assigned'));
            $completedCases = count(array_filter($allCases, fn($c) => ($c['status'] ?? '') === 'Completed'));
            
            // Build user statistics
            foreach ($allUsers as $u) {
                $userId = $u['userid'] ?? '';
                $userCases = array_filter($allCases, function($case) use ($userId) {
                    return ($case['enteredby'] ?? null) === $userId || 
                           ($case['caseassign_userid'] ?? null) === $userId;
                });
                
                $userStats[] = [
                    'user' => $u,
                    'total_cases' => count($userCases),
                    'pending_cases' => count(array_filter($userCases, fn($c) => ($c['status'] ?? '') === 'Pending for Assign' || ($c['status'] ?? '') === 'Assigned')),
                    'completed_cases' => count(array_filter($userCases, fn($c) => ($c['status'] ?? '') === 'Completed'))
                ];
            }
        } else {
            // Regular user sees only their department's cases
            $deptCases = $this->fluree->getCasesByDepartment($userDeptCode);
            $totalCases = count($deptCases);
            $pendingCases = count(array_filter($deptCases, fn($c) => ($c['status'] ?? '') === 'Pending for Assign' || ($c['status'] ?? '') === 'Assigned'));
            $completedCases = count(array_filter($deptCases, fn($c) => ($c['status'] ?? '') === 'Completed'));
        }

        return view('dashboard', compact('user', 'userRole', 'allUsers', 'userStats', 'totalCases', 'pendingCases', 'completedCases'));
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }

    // Register Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // TODO: Implement Fluree registration
        return back()->with('success', 'Registration form submitted. (Fluree integration pending)')
                    ->withInput();
    }

    // Forgot Password Form  
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // TODO: Send reset email / Fluree notification
        return back()->with('status', 'Reset link sent to your email! (Demo mode)');
    }

        /**
     * Encrypt password same as your old C# EncryptString() method
     * Change the logic according to your actual encryption (MD5, SHA256, AES, etc.)
     */
    private function encryptPassword(string $plainPassword): string
    {
        // Example 1: If you were using simple MD5 (common in old systems)
        // return md5($plainPassword);

        // Example 2: If you were using SHA256
        // return hash('sha256', $plainPassword);

        // Example 3: If you have a custom encryption (AES, etc.), put your logic here
        // For now, let's assume it's MD5 - CHANGE THIS to match your C# code
        return md5($plainPassword);   // ←←← CHANGE THIS LINE
    }
}
