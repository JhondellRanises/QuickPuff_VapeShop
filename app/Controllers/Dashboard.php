<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        
        // Require login for all dashboard methods
        $this->requireLogin();
    }

    /**
     * Display main dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Dashboard - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'stats' => $this->getDashboardStats()
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $stats = [
            'total_products' => 0,
            'low_stock_products' => 0,
            'total_users' => 0,
            'active_users' => 0
        ];

        try {
            // Get product stats
            $products = $this->productModel->getActiveProducts();
            $stats['total_products'] = count($products);
            
            $lowStock = $this->productModel->getLowStockProducts(10);
            $stats['low_stock_products'] = count($lowStock);

            // Get user stats (admin only)
            if ($this->isAdmin()) {
                $stats['total_users'] = count($this->userModel->findAll());
                $stats['active_users'] = count($this->userModel->getActiveUsers());
            }
        } catch (\Exception $e) {
            // Log error but don't break the dashboard
            log_message('error', 'Dashboard stats error: ' . $e->getMessage());
        }

        return $stats;
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

    /**
     * Check if current user is admin
     */
    private function isAdmin()
    {
        return session()->get('role') === 'admin';
    }
}
