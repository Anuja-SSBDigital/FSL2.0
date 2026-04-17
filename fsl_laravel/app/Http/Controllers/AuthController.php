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

        // Optional: Fetch some data from Fluree after login
        $exampleData = [];
        try {
            $exampleData = $this->fluree->query([
                'select' => ['*'],
                'from'   => 'Person',        // Change this according to your ledger
                'limit'  => 10,
            ]);
        } catch (\Exception $e) {
            // Silently fail if query has issue
        }

        return view('dashboard', compact('user', 'exampleData'));
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
