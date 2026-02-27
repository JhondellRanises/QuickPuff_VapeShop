<?php

namespace App\Controllers;

use App\Models\UserModel;

class Staff extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        
        // Require login and admin role for all staff management methods
        $this->requireLogin();
        $this->requireAdmin();
    }

    /**
     * Display staff list
     */
    public function index()
    {
        $data = [
            'title' => 'Staff Management - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'staff' => $this->userModel->getAllUsers()
        ];

        return view('staff/index', $data);
    }

    /**
     * Display create staff form
     */
    public function create()
    {
        $data = [
            'title' => 'Create Staff - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser()
        ];

        return view('staff/create', $data);
    }

    /**
     * Process create staff
     */
    public function store()
    {
        $data = [
            'username' => trim($this->request->getPost('username')),
            'full_name' => trim($this->request->getPost('full_name')),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Enhanced validation
        $validationRules = [
            'username' => 'required|min_length[3]|max_length[100]|alpha_numeric',
            'full_name' => 'required|min_length[2]|max_length[255]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,staff]'
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            $errorMessage = 'Please fix the following errors:<ul>';
            foreach ($errors as $error) {
                $errorMessage .= '<li>' . esc($error) . '</li>';
            }
            $errorMessage .= '</ul>';
            
            session()->setFlashdata('error', $errorMessage);
            return redirect()->to('/staff/create')->withInput();
        }

        try {
            // Check if username already exists
            if ($this->userModel->getUserByUsername($data['username'])) {
                session()->setFlashdata('error', 'Username "' . esc($data['username']) . '" already exists. Please choose a different username.');
                return redirect()->to('/staff/create')->withInput();
            }

            if ($this->userModel->createUser($data)) {
                // Log the action
                log_message('info', 'Staff account created: ' . $data['username'] . ' by admin: ' . session()->get('username'));
                
                $successMessage = 'Staff account for "' . esc($data['full_name']) . '" has been created successfully!';
                $successMessage .= '<br><small>Username: ' . esc($data['username']) . ' | Role: ' . ucfirst($data['role']) . '</small>';
                session()->setFlashdata('success', $successMessage);
                
                return redirect()->to('/staff');
            } else {
                session()->setFlashdata('error', 'Failed to create staff account due to a database error. Please try again.');
                return redirect()->to('/staff/create')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Staff creation error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while creating the staff account. Please try again later.');
            return redirect()->to('/staff/create')->withInput();
        }
    }

    /**
     * Display edit staff form
     */
    public function edit($id)
    {
        $staff = $this->userModel->find($id);
        
        if (!$staff) {
            session()->setFlashdata('error', 'Staff not found');
            return redirect()->to('/staff');
        }

        // Prevent editing own account
        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            log_message('warning', 'User attempted to edit own account: ' . session()->get('username') . ' (ID: ' . $currentUserId . ')');
            session()->setFlashdata('error', 'You cannot edit your own account for security reasons.');
            return redirect()->to('/staff');
        }

        $data = [
            'title' => 'Edit Staff - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'staff' => $staff
        ];

        return view('staff/edit', $data);
    }

    /**
     * Process update staff
     */
    public function update($id)
    {
        $staff = $this->userModel->find($id);
        
        if (!$staff) {
            session()->setFlashdata('error', 'Staff not found');
            return redirect()->to('/staff');
        }

        // Prevent updating own account
        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            log_message('warning', 'User attempted to update own account: ' . session()->get('username') . ' (ID: ' . $currentUserId . ')');
            session()->setFlashdata('error', 'You cannot update your own account for security reasons.');
            return redirect()->to('/staff');
        }

        $data = [
            'full_name' => trim($this->request->getPost('full_name')),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Add password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                session()->setFlashdata('error', 'Password must be at least 6 characters long');
                return redirect()->to("/staff/edit/{$id}")->withInput();
            }
            $data['password'] = $password;
        }

        // Validate input
        if (empty($data['full_name'])) {
            session()->setFlashdata('error', 'Full name is required');
            return redirect()->to("/staff/edit/{$id}")->withInput();
        }

        // Validate role
        if (!in_array($data['role'], ['admin', 'staff'])) {
            session()->setFlashdata('error', 'Invalid role selected');
            return redirect()->to("/staff/edit/{$id}")->withInput();
        }

        try {
            if ($this->userModel->updateUser($id, $data)) {
                session()->setFlashdata('success', 'Staff account updated successfully');
                return redirect()->to('/staff');
            } else {
                session()->setFlashdata('error', 'Failed to update staff account');
                return redirect()->to("/staff/edit/{$id}")->withInput();
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error updating staff account: ' . $e->getMessage());
            return redirect()->to("/staff/edit/{$id}")->withInput();
        }
    }

    /**
     * Deactivate staff (soft delete)
     */
    public function deactivate($id)
    {
        $staff = $this->userModel->find($id);
        
        if (!$staff) {
            session()->setFlashdata('error', 'Staff not found');
            return redirect()->to('/staff');
        }

        // Prevent deactivating yourself
        if ($staff['id'] == session()->get('user_id')) {
            session()->setFlashdata('error', 'You cannot deactivate your own account');
            return redirect()->to('/staff');
        }

        try {
            if ($this->userModel->deactivateUser($id)) {
                session()->setFlashdata('success', 'Staff account deactivated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to deactivate staff account');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error deactivating staff account: ' . $e->getMessage());
        }

        return redirect()->to('/staff');
    }

    /**
     * Activate staff
     */
    public function activate($id)
    {
        $staff = $this->userModel->find($id);
        
        if (!$staff) {
            session()->setFlashdata('error', 'Staff not found');
            return redirect()->to('/staff');
        }

        // Prevent activating yourself (security measure)
        if ($staff['id'] == session()->get('user_id')) {
            session()->setFlashdata('error', 'You cannot activate your own account for security reasons.');
            return redirect()->to('/staff');
        }

        try {
            if ($this->userModel->activateUser($id)) {
                session()->setFlashdata('success', 'Staff account activated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to activate staff account');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error activating staff account: ' . $e->getMessage());
        }

        return redirect()->to('/staff');
    }

    /**
     * Require login - redirect to login if not authenticated
     */
    private function requireLogin()
    {
        if (!session()->get('is_logged_in')) {
            session()->setFlashdata('error', 'Please login to access this page');
            return redirect()->to('/login');
        }
    }

    /**
     * Require admin role - redirect if not admin
     */
    private function requireAdmin()
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Access denied. Admin role required.');
            return redirect()->to('/dashboard');
        }
    }

    /**
     * Get current user from session
     */
    private function getCurrentUser()
    {
        return [
            'id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'full_name' => session()->get('full_name'),
            'role' => session()->get('role')
        ];
    }
}
