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
        $builder = $this->select('
            sales.*,
            users.full_name as cashier_name
        ')
        ->join('users', 'users.id = sales.processed_by')
        ->orderBy('sales.created_at', 'DESC');
        
        if ($startDate) {
            $builder->where('DATE(sales.created_at) >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('DATE(sales.created_at) <=', $endDate);
        }
        
        return $builder->findAll();
    }

    // Get sales summary
    public function getSalesSummary($startDate = null, $endDate = null)
    {
        $builder = $this->select('
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue,
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
}
