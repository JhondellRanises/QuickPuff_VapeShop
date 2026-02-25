<?php

if (!function_exists('require_login')) {
    /**
     * Check if user is logged in, redirect to login if not
     */
    function require_login() {
        $session = session();
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page');
            return redirect()->to('/login');
        }
    }
}

if (!function_exists('require_admin')) {
    /**
     * Check if user is admin, redirect to dashboard if not
     */
    function require_admin() {
        $session = session();
        if ($session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin role required.');
            return redirect()->to('/dashboard');
        }
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     */
    function is_logged_in() {
        return session()->get('is_logged_in') === true;
    }
}

if (!function_exists('current_user')) {
    /**
     * Get current user data
     */
    function current_user() {
        return [
            'id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'full_name' => session()->get('full_name'),
            'role' => session()->get('role')
        ];
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     */
    function is_admin() {
        return session()->get('role') === 'admin';
    }
}

if (!function_exists('user_role')) {
    /**
     * Get current user role
     */
    function user_role() {
        return session()->get('role');
    }
}

if (!function_exists('user_full_name')) {
    /**
     * Get current user full name
     */
    function user_full_name() {
        return session()->get('full_name');
    }
}
