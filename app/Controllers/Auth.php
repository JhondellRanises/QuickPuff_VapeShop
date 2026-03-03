<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Process login attempt
     */
    public function attemptLogin()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        // Enhanced validation
        $validationRules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            $errorMessage = 'Please fix the following errors:<ul>';
            foreach ($errors as $error) {
                $errorMessage .= '<li>' . esc($error) . '</li>';
            }
            $errorMessage .= '</ul>';
            
            session()->setFlashdata('error', $errorMessage);
            return redirect()->to('/login')->withInput();
        }

        try {
            // Get user from database
            $user = $this->userModel->getUserByUsername($username);

            // Verify user exists and password is correct
            if ($user && password_verify($password, $user['password_hash'])) {
                // Check if user is active
                if (!$user['is_active']) {
                    session()->setFlashdata('error', 'Your account has been deactivated. Please contact the administrator.');
                    return redirect()->to('/login')->withInput();
                }

                // Set session data
                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role'],
                    'is_logged_in' => true,
                    'login_time' => date('Y-m-d H:i:s')
                ]);

                // Log successful login
                log_message('info', 'User logged in: ' . $username . ' (ID: ' . $user['id'] . ')');

                // Success message with role-based information
                $welcomeMessage = 'Welcome back, ' . $user['full_name'] . '!';
                if ($user['role'] === 'admin') {
                    $welcomeMessage .= ' You have administrator privileges.';
                } else {
                    $welcomeMessage .= ' You can access the POS system and dashboard.';
                }
                
                session()->setFlashdata('success', $welcomeMessage);
                
                return redirect()->to('/dashboard');
            } else {
                // Increment login attempts
                $loginAttempts++;
                session()->set([
                    'login_attempts' => $loginAttempts,
                    'last_attempt_time' => time()
                ]);

                // Log failed login attempt
                log_message('warning', 'Failed login attempt for username: ' . $username);

                // Generic error message for security
                session()->setFlashdata('error', 'Invalid username or password.');
                
                return redirect()->to('/login')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An error occurred during login. Please try again later.');
            return redirect()->to('/login')->withInput();
        }
    }

    /**
     * Logout user and clear session
     */
    public function logout()
    {
        $userName = session()->get('full_name') ?? 'User';
        $loginTime = session()->get('login_time');
        
        // Calculate session duration
        $sessionDuration = '';
        if ($loginTime) {
            $login = new \DateTime($loginTime);
            $now = new \DateTime();
            $interval = $login->diff($now);
            
            if ($interval->h > 0) {
                $sessionDuration = ' Session duration: ' . $interval->h . ' hour(s) and ' . $interval->i . ' minute(s).';
            } else {
                $sessionDuration = ' Session duration: ' . $interval->i . ' minute(s).';
            }
        }

        // Log the logout
        log_message('info', 'User logged out: ' . session()->get('username') . ' (ID: ' . session()->get('user_id') . ')');

        // Destroy session
        session()->destroy();
        
        session()->setFlashdata('success', 'You have been logged out successfully, ' . $userName . '!' . $sessionDuration);
        
        return redirect()->to('/login');
    }

    /**
     * Check if user is logged in (helper method)
     */
    public function isLoggedIn()
    {
        return session()->get('is_logged_in') === true;
    }

    /**
     * Get current user data
     */
    public function getCurrentUser()
    {
        return [
            'id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'full_name' => session()->get('full_name'),
            'role' => session()->get('role')
        ];
    }

    /**
     * Check if current user is admin
     */
    public function isAdmin()
    {
        return session()->get('role') === 'admin';
    }
}
