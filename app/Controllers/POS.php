<?php

namespace App\Controllers;

class POS extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            session()->setFlashdata('error', 'Please login to access this page');
            return redirect()->to('/login');
        }

        // Clear any lingering error messages
        if (session()->get('is_logged_in')) {
            session()->remove('error');
        }
        
        // Get products directly from database
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM products WHERE is_active = 1 ORDER BY category ASC, name ASC");
            $products = $query->getResultArray();
            
            // Get categories
            $categoryQuery = $db->query("SELECT DISTINCT category FROM products WHERE is_active = 1 ORDER BY category ASC");
            $categories = [];
            foreach ($categoryQuery->getResultArray() as $cat) {
                $categories[] = $cat['category'];
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading products: ' . $e->getMessage());
            $products = [];
            $categories = [];
        }
        
        $data = [
            'title' => 'Point of Sale - Quick Puff Vape Shop',
            'products' => $products,
            'categories' => $categories,
            'cart' => session()->get('cart') ?? []
        ];

        return view('pos/POS', $data);
    }

    public function processSale()
    {
        // Log the attempt
        log_message('info', 'Process sale method called');
        
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            log_message('error', 'User not logged in for process sale');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to process sale'
            ]);
        }

        // Get cart data with better error handling
        try {
            // Try to get JSON from request body first
            $jsonInput = $this->request->getJSON();
            
            if ($jsonInput !== null) {
                // Got JSON directly
                $data = (array) $jsonInput;
                log_message('info', 'JSON parsed directly from request');
            } else {
                // Try raw input as fallback
                $input = $this->request->getRawInput();
                log_message('info', 'Raw input received: ' . $input);
                
                if (empty($input)) {
                    log_message('error', 'No input data received');
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No data received'
                    ]);
                }
                
                $data = json_decode($input, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    log_message('error', 'JSON decode error: ' . json_last_error_msg());
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid JSON data: ' . json_last_error_msg()
                    ]);
                }
            }
            
            $cart = $data['cart'] ?? [];
            $customerBirthdate = trim((string) ($data['customer_birthdate'] ?? ''));
            $amountPaid = (float) ($data['amount_paid'] ?? 0);

            if ($customerBirthdate === '') {
                log_message('warning', 'Age verification failed: missing customer birth date');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Customer birth date is required. Sales are only allowed for customers aged 18 and above.'
                ]);
            }

            $birthDate = \DateTime::createFromFormat('Y-m-d', $customerBirthdate);
            $birthDateErrors = \DateTime::getLastErrors();
            $hasBirthDateErrors = $birthDate === false
                || ($birthDateErrors !== false && (($birthDateErrors['warning_count'] ?? 0) > 0 || ($birthDateErrors['error_count'] ?? 0) > 0))
                || $birthDate->format('Y-m-d') !== $customerBirthdate;

            if ($hasBirthDateErrors) {
                log_message('warning', 'Age verification failed: invalid birth date format [' . $customerBirthdate . ']');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid birth date format. Please use YYYY-MM-DD.'
                ]);
            }

            $today = new \DateTime('today');
            if ($birthDate > $today) {
                log_message('warning', 'Age verification failed: future birth date [' . $customerBirthdate . ']');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Birth date cannot be in the future.'
                ]);
            }

            $customerAge = $birthDate->diff($today)->y;
            if ($customerAge < 18) {
                log_message('warning', 'Sale blocked: underage customer [' . $customerAge . ']');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sale blocked: customer must be 18 years old or above.'
                ]);
            }
            log_message('info', 'Age verification passed. Customer age: ' . $customerAge);
            
            // Validate cart structure
            if (!is_array($cart)) {
                log_message('error', 'Cart is not an array: ' . gettype($cart));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid cart data format'
                ]);
            }
            
            log_message('info', 'Cart items count: ' . count($cart));
            
            // Validate each cart item
            foreach ($cart as $index => $item) {
                // Handle both array and object formats
                if (is_array($item)) {
                    // It's an array, use as-is
                    log_message('info', 'Cart item ' . $index . ' is an array');
                } elseif (is_object($item)) {
                    // It's an object, convert to array
                    $cart[$index] = (array) $item;
                    $item = $cart[$index];
                    log_message('info', 'Cart item ' . $index . ' converted from object to array');
                } else {
                    log_message('error', 'Cart item ' . $index . ' is invalid type: ' . gettype($item));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid cart item format at index ' . $index . '. Type: ' . gettype($item)
                    ]);
                }
                
                // Required fields - check if they exist after conversion
                $requiredFields = ['id', 'name', 'price', 'quantity'];
                foreach ($requiredFields as $field) {
                    if (!isset($item[$field])) {
                        log_message('error', 'Missing field ' . $field . ' in cart item ' . $index);
                        log_message('error', 'Available fields: ' . implode(', ', array_keys($item)));
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Missing required field: ' . $field . ' in item ' . $index
                        ]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error parsing cart data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error parsing data: ' . $e->getMessage()
            ]);
        }

        if (empty($cart)) {
            log_message('error', 'Cart is empty');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cart is empty'
            ]);
        }

        // Get database connection
        $db = \Config\Database::connect();
        
        try {
            log_message('info', 'Starting database transaction');
            // Start transaction
            $db->transStart();
            
            // Calculate totals
            $subtotal = 0;
            $taxRate = 0.10; // 10% tax
            $taxAmount = 0;
            $totalAmount = 0;
            
            foreach ($cart as $item) {
                // Ensure item data is properly formatted
                $price = (float) ($item['price'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $subtotal += $price * $quantity;
            }
            
            $taxAmount = $subtotal * $taxRate;
            $totalAmount = $subtotal + $taxAmount;
            
            log_message('info', 'Totals calculated - Subtotal: ' . $subtotal . ', Tax: ' . $taxAmount . ', Total: ' . $totalAmount);
            
            // Validate payment amount
            if ($amountPaid < $totalAmount) {
                log_message('warning', 'Insufficient payment amount: ' . $amountPaid . ' < ' . $totalAmount);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Insufficient payment amount. Total is ₱' . number_format($totalAmount, 2) . ' but customer paid ₱' . number_format($amountPaid, 2)
                ]);
            }
            
            $changeAmount = $amountPaid - $totalAmount;
            log_message('info', 'Payment validated - Amount paid: ₱' . $amountPaid . ', Change: ₱' . $changeAmount);
            
            // Generate sale code
            $saleCode = 'SALE-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            log_message('info', 'Generated sale code: ' . $saleCode);
            
            // Create sale record with proper data types
            $saleData = [
                'sale_code' => $saleCode,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'subtotal' => $subtotal,
                'payment_method' => 'cash',
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'processed_by' => (int) (session()->get('user_id') ?? 1),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('info', 'Inserting sale record');
            // Insert sale into database
            $db->table('sales')->insert($saleData);
            $saleId = $db->insertID();
            
            if (!$saleId) {
                throw new \Exception('Failed to insert sale record');
            }
            
            log_message('info', 'Sale inserted with ID: ' . $saleId);
            
            // Insert sale items
            foreach ($cart as $item) {
                // Ensure all data is properly formatted
                $productId = (int) ($item['id'] ?? 0);
                $productName = (string) ($item['name'] ?? 'Unknown Product');
                $price = (float) ($item['price'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $itemTotal = $price * $quantity;
                
                $saleItemData = [
                    'sale_id' => $saleId,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                log_message('info', 'Inserting sale item: ' . $productName . ' (ID: ' . $productId . ')');
                $db->table('sale_items')->insert($saleItemData);
                
                // Update product stock
                log_message('info', 'Updating stock for product ID: ' . $productId . ', quantity: ' . $quantity);
                $db->query("UPDATE products SET stock_qty = stock_qty - ? WHERE id = ?", [
                    $quantity, 
                    $productId
                ]);
            }
            
            // Complete transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            log_message('info', 'Transaction completed successfully');
            
            // Prepare sale items for JSON response BEFORE clearing cart
            $saleItems = [];
            foreach ($cart as $item) {
                $saleItems[] = [
                    'name' => $item['name'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price']
                ];
            }
            
            // Prepare response data
            $responseData = [
                'success' => true,
                'message' => 'Sale processed successfully!',
                'sale' => [
                    'sale_code' => $saleCode,
                    'subtotal' => (float) $subtotal,
                    'tax_amount' => (float) $taxAmount,
                    'total_amount' => (float) $totalAmount,
                    'amount_paid' => (float) $amountPaid,
                    'change_amount' => (float) $changeAmount,
                    'items' => $saleItems,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
            
            log_message('info', 'Response data prepared: ' . json_encode($responseData));
            
            // Store sale details in session for receipt
            session()->set('last_sale', [
                'sale_id' => $saleId,
                'sale_code' => $saleCode,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'items' => $cart,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Clear cart AFTER preparing response
            session()->remove('cart');
            
            return $this->response->setJSON($responseData);
            
        } catch (\Exception $e) {
            // Rollback transaction
            $db->transRollback();
            
            log_message('error', 'Error processing sale: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ]);
        }
    }

    public function receipt($saleId)
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        log_message('info', 'Loading receipt for sale ID: ' . $saleId);

        // Get sale details from session or database
        $lastSale = session()->get('last_sale');
        
        if ($lastSale && $lastSale['sale_id'] == $saleId) {
            // Use session data
            log_message('info', 'Using session data for receipt');
            $sale = [
                'id' => $lastSale['sale_id'],
                'sale_code' => $lastSale['sale_code'],
                'subtotal' => $lastSale['subtotal'],
                'tax_amount' => $lastSale['tax_amount'],
                'total_amount' => $lastSale['total_amount'],
                'created_at' => $lastSale['created_at'],
                'items' => $lastSale['items'],
                'payment_method' => 'cash'
            ];
        } else {
            // Get from database
            log_message('info', 'Fetching sale from database');
            $db = \Config\Database::connect();
            $saleQuery = $db->query("SELECT * FROM sales WHERE id = ?", [$saleId]);
            $sale = $saleQuery->getRowArray();
            
            if (!$sale) {
                log_message('error', 'Sale not found: ' . $saleId);
                session()->setFlashdata('error', 'Sale not found');
                return redirect()->to('/pos');
            }
            
            // Get sale items
            $itemsQuery = $db->query("SELECT * FROM sale_items WHERE sale_id = ?", [$saleId]);
            $sale['items'] = $itemsQuery->getResultArray();
        }

        $data = [
            'title' => 'Receipt - Quick Puff Vape Shop',
            'sale' => $sale,
            'sale_id' => $saleId
        ];

        return view('pos/receipt', $data);
    }
}
