<?php

namespace App\Controllers;

class TestSale extends BaseController
{
    public function index()
    {
        // Get the raw input for debugging
        $rawInput = $this->request->getRawInput();
        $jsonInput = $this->request->getJSON();
        
        echo "<h2>Debug Sale Processing</h2>";
        echo "<h3>Raw Input:</h3>";
        echo "<pre>" . htmlspecialchars($rawInput) . "</pre>";
        
        echo "<h3>JSON Input Type:</h3>";
        echo "<p>Type: " . gettype($jsonInput) . "</p>";
        
        echo "<h3>JSON Input:</h3>";
        if ($jsonInput !== null) {
            echo "<pre>" . print_r($jsonInput, true) . "</pre>";
            
            // Show cart details
            $data = (array) $jsonInput;
            if (isset($data['cart'])) {
                $cart = $data['cart'];
                echo "<h3>Cart Data:</h3>";
                echo "<pre>" . print_r($cart, true) . "</pre>";
                
                echo "<h3>Cart Item Analysis:</h3>";
                foreach ($cart as $index => $item) {
                    echo "<h4>Item $index:</h4>";
                    echo "<p>Type: " . gettype($item) . "</p>";
                    
                    if (is_object($item)) {
                        echo "<p>Object Properties:</p>";
                        echo "<pre>" . print_r($item, true) . "</pre>";
                        echo "<p>Converted to Array:</p>";
                        echo "<pre>" . print_r((array) $item, true) . "</pre>";
                    } elseif (is_array($item)) {
                        echo "<p>Array Keys:</p>";
                        echo "<pre>" . implode(', ', array_keys($item)) . "</pre>";
                        echo "<pre>" . print_r($item, true) . "</pre>";
                    } else {
                        echo "<p>Invalid item type!</p>";
                    }
                }
            }
        } else {
            echo "<p>NULL - JSON parsing failed</p>";
        }
        
        echo "<h3>Request Headers:</h3>";
        echo "<pre>" . print_r($this->request->getHeaders(), true) . "</pre>";
        
        echo "<h3>Request Method:</h3>";
        echo "<p>" . $this->request->getMethod() . "</p>";
    }
}
