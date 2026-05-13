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
        $perPage = 10;
        $currentPage = max(1, (int) ($this->request->getGet('page_sales') ?? 1));

        $data = [
            'title' => 'Sales Report - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'sales' => [],
            'pager' => null,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'summary' => [
                'total_sales' => 0,
                'total_revenue' => 0,
                'total_subtotal' => 0,
                'avg_sale_value' => 0,
                'total_items_sold' => 0,
                'top_product_name' => 'N/A',
                'top_product_qty' => 0
            ]
        ];

        // Handle filters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $singleDate = $this->request->getGet('date');

        // Default range: start of current month up to today
        if (!$singleDate && !$startDate && !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
            $singleDate = null;
        }

        if ($singleDate) {
            $startDate = $singleDate;
            $endDate = $singleDate;
        } elseif ($startDate && !$endDate) {
            $endDate = $startDate;
        } elseif (!$startDate && $endDate) {
            $startDate = $endDate;
        }

        // Get sales data
        if ($startDate) {
            $data['sales'] = $this->saleModel->getSalesReportPaginated($startDate, $endDate, $perPage, $currentPage);
            $data['pager'] = $this->saleModel->pager;
            $summary = $this->saleModel->getSalesSummary($startDate, $endDate);
            $insights = $this->saleModel->getSalesInsights($startDate, $endDate);
            $totalSales = (int) ($summary['total_sales'] ?? 0);
            $totalRevenue = (float) ($summary['total_revenue'] ?? 0);

            $data['summary'] = array_merge($summary, $insights, [
                'avg_sale_value' => $totalSales > 0 ? ($totalRevenue / $totalSales) : 0
            ]);
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
            'Items',
            'Subtotal',
            'Total Amount',
            'Payment Method'
        ]);

        // CSV Data
        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale['sale_code'],
                date('Y-m-d H:i A', strtotime($sale['created_at'])),
                $sale['cashier_name'],
                $sale['items_summary'] ?? '',
                $sale['subtotal'],
                $sale['subtotal'],
                ucfirst($sale['payment_method'])
            ]);
        }

        // Summary Row
        fputcsv($output, []);
        fputcsv($output, ['SUMMARY']);
        fputcsv($output, ['Total Sales', $summary['total_sales']]);
        fputcsv($output, ['Total Subtotal', $summary['total_subtotal']]);
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
