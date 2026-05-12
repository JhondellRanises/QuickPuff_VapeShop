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
                    'profile_image' => $user['profile_image'] ?? null,
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
     * Update current user's account settings (name, optional password, optional image).
     */
    public function updateAccountSettings()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            session()->setFlashdata('error', 'Invalid user session.');
            return redirect()->back();
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        $fullName = trim((string) $this->request->getPost('full_name'));
        if ($fullName === '' || mb_strlen($fullName) > 255) {
            session()->setFlashdata('error', 'Full name is required and must not exceed 255 characters.');
            return redirect()->back()->withInput();
        }

        $updateData = ['full_name' => $fullName];

        $newPassword = (string) $this->request->getPost('new_password');
        $confirmPassword = (string) $this->request->getPost('confirm_password');
        $currentPassword = (string) $this->request->getPost('current_password');
        $wantsPasswordChange = $newPassword !== '' || $confirmPassword !== '' || $currentPassword !== '';

        if ($wantsPasswordChange) {
            if ($currentPassword === '') {
                session()->setFlashdata('error', 'Current password is required to change password.');
                return redirect()->back()->withInput();
            }

            if (!password_verify($currentPassword, (string) ($user['password_hash'] ?? ''))) {
                session()->setFlashdata('error', 'Current password is incorrect.');
                return redirect()->back()->withInput();
            }

            if (strlen($newPassword) < 6) {
                session()->setFlashdata('error', 'New password must be at least 6 characters.');
                return redirect()->back()->withInput();
            }

            if ($newPassword !== $confirmPassword) {
                session()->setFlashdata('error', 'New password and confirmation do not match.');
                return redirect()->back()->withInput();
            }

            $updateData['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $imageUploadResult = $this->handleProfileImageUpload($user);
        if (!$imageUploadResult['ok']) {
            session()->setFlashdata('error', $imageUploadResult['message']);
            return redirect()->back()->withInput();
        }

        if ($imageUploadResult['profile_image'] !== null) {
            $updateData['profile_image'] = $imageUploadResult['profile_image'];
        }

        if (!$this->userModel->update($userId, $updateData)) {
            session()->setFlashdata('error', 'Failed to save account settings.');
            return redirect()->back()->withInput();
        }

        session()->set('full_name', $fullName);
        if (array_key_exists('profile_image', $updateData)) {
            session()->set('profile_image', $updateData['profile_image']);
        }

        session()->setFlashdata('success', 'Account settings updated successfully.');
        return redirect()->back();
    }

    /**
     * Update current user's password from dedicated password modal.
     */
    public function updateAccountSettingsPassword()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            session()->setFlashdata('error', 'Invalid user session.');
            return redirect()->back();
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        $currentPassword = (string) $this->request->getPost('current_password');
        $newPassword = (string) $this->request->getPost('new_password');
        $confirmPassword = (string) $this->request->getPost('confirm_password');

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            session()->setFlashdata('error', 'All password fields are required.');
            return redirect()->back()->withInput();
        }

        if (!password_verify($currentPassword, (string) ($user['password_hash'] ?? ''))) {
            session()->setFlashdata('error', 'Current password is incorrect.');
            return redirect()->back()->withInput();
        }

        if (strlen($newPassword) < 6) {
            session()->setFlashdata('error', 'New password must be at least 6 characters.');
            return redirect()->back()->withInput();
        }

        if ($newPassword !== $confirmPassword) {
            session()->setFlashdata('error', 'New password and confirmation do not match.');
            return redirect()->back()->withInput();
        }

        if (!$this->userModel->update($userId, ['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)])) {
            session()->setFlashdata('error', 'Failed to update password.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Password updated successfully.');
        return redirect()->back();
    }

    /**
     * Update current user's profile image from header avatar modal.
     */
    public function updateProfileImage()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            session()->setFlashdata('error', 'Invalid user session.');
            return redirect()->back();
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->back();
        }

        $imageUploadResult = $this->handleProfileImageUpload($user);
        if (!$imageUploadResult['ok']) {
            session()->setFlashdata('error', $imageUploadResult['message']);
            return redirect()->back();
        }

        if ($imageUploadResult['profile_image'] === null) {
            session()->setFlashdata('error', 'Please choose an image file.');
            return redirect()->back();
        }

        if (!$this->userModel->update($userId, ['profile_image' => $imageUploadResult['profile_image']])) {
            session()->setFlashdata('error', 'Failed to save profile image.');
            return redirect()->back();
        }

        session()->set('profile_image', $imageUploadResult['profile_image']);
        session()->setFlashdata('success', 'Profile image updated successfully.');

        return redirect()->back();
    }

    /**
     * Handles optional profile image upload.
     *
     * @return array{ok: bool, profile_image: ?string, message: string}
     */
    private function handleProfileImageUpload(array $user): array
    {
        $file = $this->request->getFile('profile_image_file');
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'profile_image' => null, 'message' => ''];
        }

        if (!$file->isValid()) {
            return ['ok' => false, 'profile_image' => null, 'message' => 'Profile image upload failed.'];
        }

        if ($file->getSizeByUnit('mb') > 3) {
            return ['ok' => false, 'profile_image' => null, 'message' => 'Profile image must be 3MB or below.'];
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = (string) $file->getMimeType();
        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return ['ok' => false, 'profile_image' => null, 'message' => 'Allowed image types: JPG, PNG, WEBP.'];
        }

        $uploadDir = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'profile-images';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            return ['ok' => false, 'profile_image' => null, 'message' => 'Unable to prepare profile image directory.'];
        }

        $newFilename = $file->getRandomName();
        $file->move($uploadDir, $newFilename);
        $newRelativePath = 'assets/profile-images/' . $newFilename;

        $oldProfileImage = trim((string) ($user['profile_image'] ?? ''));
        if ($oldProfileImage !== '' && str_starts_with(str_replace('\\', '/', $oldProfileImage), 'assets/profile-images/')) {
            $oldAbsolutePath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldProfileImage);
            if (is_file($oldAbsolutePath)) {
                @unlink($oldAbsolutePath);
            }
        }

        return ['ok' => true, 'profile_image' => $newRelativePath, 'message' => ''];
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
