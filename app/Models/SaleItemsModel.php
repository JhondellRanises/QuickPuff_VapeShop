<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemsModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sale_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total',
        'created_at'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}
