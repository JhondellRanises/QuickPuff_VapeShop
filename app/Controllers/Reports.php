<?php

namespace App\Controllers;

use App\Models\SaleModel;

class Reports extends BaseController
{
    protected $saleModel;

    public function __construct()
    {
        $this->saleModel = new SaleModel();
        
        // Require login for all reports
        $this->requireLogin();
    }

    /**
     * Display sales report page
     */
    public function sales()
    {
        $data = [
            'title' => 'Sales Report - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'sales' => [],
            'summary' => [
                'total_sales' => 0,
                'total_revenue' => 0,
                'total_tax' => 0,
                'total_subtotal' => 0
            ]
        ];

        // Handle filters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $singleDate = $this->request->getGet('date');

        // Set default dates (today)
        if (!$singleDate && !$startDate) {
            $singleDate = date('Y-m-d');
        }

        if ($singleDate) {
            $startDate = $singleDate;
            $endDate = $singleDate;
        }

        // Get sales data
        if ($startDate) {
            $data['sales'] = $this->saleModel->getSalesReport($startDate, $endDate);
            $data['summary'] = $this->saleModel->getSalesSummary($startDate, $endDate);
        }

        // Pass filter values back to view
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['single_date'] = $singleDate;

        return view('reports/sales', $data);
    }

    /**
     * Export sales report (CSV)
     */
    public function exportSales()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$startDate) {
            $startDate = date('Y-m-d');
        }
        if (!$endDate) {
            $endDate = $startDate;
        }

        $sales = $this->saleModel->getSalesReport($startDate, $endDate);
        $summary = $this->saleModel->getSalesSummary($startDate, $endDate);

        // Generate CSV
        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');

        // CSV Header
        fputcsv($output, [
            'Sale Code',
            'Date',
            'Cashier',
            'Subtotal',
            'Tax',
            'Total Amount',
            'Payment Method'
        ]);

        // CSV Data
        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale['sale_code'],
                date('Y-m-d H:i A', strtotime($sale['created_at'])),
                $sale['cashier_name'],
                $sale['subtotal'],
                $sale['tax_amount'],
                $sale['total_amount'],
                ucfirst($sale['payment_method'])
            ]);
        }

        // Summary Row
        fputcsv($output, []);
        fputcsv($output, ['SUMMARY']);
        fputcsv($output, ['Total Sales', $summary['total_sales']]);
        fputcsv($output, ['Total Subtotal', $summary['total_subtotal']]);
        fputcsv($output, ['Total Tax', $summary['total_tax']]);
        fputcsv($output, ['Total Revenue', $summary['total_revenue']]);

        fclose($output);
        exit;
    }

    /**
     * Require login - redirect to login if not authenticated
     */
    private function requireLogin()
    {
        if (!session()->get('isLoggedIn')) {
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
}
