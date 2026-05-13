<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sale_code',
        'total_amount',
        'tax_amount',
        'subtotal',
        'payment_method',
        'processed_by',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Generate unique sale code
    public function generateSaleCode()
    {
        $date = date('Ymd');
        $prefix = 'SALE-' . $date . '-';
        
        // Get the last sale code for today
        $lastSale = $this->orderBy('id', 'DESC')
                       ->like('sale_code', $prefix, 'after')
                       ->first();
        
        if ($lastSale) {
            $lastNumber = intval(str_replace($prefix, '', $lastSale['sale_code']));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Create sale with items
    public function createSaleWithItems($saleData, $items)
    {
        $db = \Config\Database::connect();
        
        try {
            // Begin transaction
            $db->transStart();
            
            // Insert sale
            $this->insert($saleData);
            $saleId = $this->getInsertID();
            
            // Insert sale items
            $saleItemsModel = new \App\Models\SaleItemsModel();
            foreach ($items as $item) {
                $item['sale_id'] = $saleId;
                $saleItemsModel->insert($item);
            }
            
            // Commit transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return false;
            }
            
            return $saleId;
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating sale: ' . $e->getMessage());
            return false;
        }
    }

    // Get sale with items
    public function getSaleWithItems($saleId)
    {
        $sale = $this->find($saleId);
        if (!$sale) {
            return null;
        }
        
        $saleItemsModel = new \App\Models\SaleItemsModel();
        $sale['items'] = $saleItemsModel->where('sale_id', $saleId)->findAll();
        
        // Get cashier info
        $userModel = new \App\Models\UserModel();
        $cashier = $userModel->find($sale['processed_by']);
        $sale['cashier_name'] = $cashier ? $cashier['full_name'] : 'Unknown';
        
        return $sale;
    }

    // Get sales for reporting
    public function getSalesReport($startDate = null, $endDate = null)
    {
        $saleItemsSummarySubquery = '(SELECT sale_id, GROUP_CONCAT(product_name ORDER BY id SEPARATOR ", ") AS items_summary FROM sale_items GROUP BY sale_id) sale_items_summary';

        $builder = $this->select('
            sales.*,
            users.full_name as cashier_name,
            COALESCE(sale_items_summary.items_summary, "") as items_summary
        ')
        ->join('users', 'users.id = sales.processed_by')
        ->join($saleItemsSummarySubquery, 'sale_items_summary.sale_id = sales.id', 'left', false)
        ->orderBy('sales.created_at', 'DESC');
        
        if ($startDate) {
            $builder->where('DATE(sales.created_at) >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('DATE(sales.created_at) <=', $endDate);
        }
        
        return $builder->findAll();
    }

    // Get paginated sales for reporting
    public function getSalesReportPaginated($startDate = null, $endDate = null, int $perPage = 10, int $page = 1)
    {
        $saleItemsSummarySubquery = '(SELECT sale_id, GROUP_CONCAT(product_name ORDER BY id SEPARATOR ", ") AS items_summary FROM sale_items GROUP BY sale_id) sale_items_summary';

        $builder = $this->select('
            sales.*,
            users.full_name as cashier_name,
            COALESCE(sale_items_summary.items_summary, "") as items_summary
        ')
        ->join('users', 'users.id = sales.processed_by')
        ->join($saleItemsSummarySubquery, 'sale_items_summary.sale_id = sales.id', 'left', false)
        ->orderBy('sales.created_at', 'DESC');

        if ($startDate) {
            $builder->where('DATE(sales.created_at) >=', $startDate);
        }

        if ($endDate) {
            $builder->where('DATE(sales.created_at) <=', $endDate);
        }

        return $builder->paginate($perPage, 'sales', $page);
    }

    // Get sales summary
    public function getSalesSummary($startDate = null, $endDate = null)
    {
        $builder = $this->select('
            COUNT(*) as total_sales,
            SUM(subtotal) as total_revenue,
            SUM(tax_amount) as total_tax,
            SUM(subtotal) as total_subtotal
        ');
        
        if ($startDate) {
            $builder->where('DATE(created_at) >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('DATE(created_at) <=', $endDate);
        }
        
        $result = $builder->first();
        
        return [
            'total_sales' => $result['total_sales'] ?? 0,
            'total_revenue' => $result['total_revenue'] ?? 0,
            'total_tax' => $result['total_tax'] ?? 0,
            'total_subtotal' => $result['total_subtotal'] ?? 0,
        ];
    }

    // Get additional KPI insights for sales report cards
    public function getSalesInsights($startDate = null, $endDate = null)
    {
        $db = \Config\Database::connect();

        $itemsBuilder = $db->table('sale_items si')
            ->select('COALESCE(SUM(si.quantity), 0) AS total_items_sold', false)
            ->join('sales s', 's.id = si.sale_id', 'inner');

        if ($startDate) {
            $itemsBuilder->where('DATE(s.created_at) >=', $startDate);
        }

        if ($endDate) {
            $itemsBuilder->where('DATE(s.created_at) <=', $endDate);
        }

        $itemsResult = $itemsBuilder->get()->getRowArray();
        $totalItemsSold = (int) ($itemsResult['total_items_sold'] ?? 0);

        $topProductBuilder = $db->table('sale_items si')
            ->select('si.product_name, SUM(si.quantity) AS total_qty', false)
            ->join('sales s', 's.id = si.sale_id', 'inner')
            ->groupBy('si.product_name')
            ->orderBy('total_qty', 'DESC')
            ->orderBy('si.product_name', 'ASC')
            ->limit(1);

        if ($startDate) {
            $topProductBuilder->where('DATE(s.created_at) >=', $startDate);
        }

        if ($endDate) {
            $topProductBuilder->where('DATE(s.created_at) <=', $endDate);
        }

        $topProduct = $topProductBuilder->get()->getRowArray();

        return [
            'total_items_sold' => $totalItemsSold,
            'top_product_name' => $topProduct['product_name'] ?? 'N/A',
            'top_product_qty' => (int) ($topProduct['total_qty'] ?? 0),
        ];
    }
}
