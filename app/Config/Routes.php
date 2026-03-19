<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root to login
$routes->get('/', 'Auth::login');

// Authentication routes
$routes->get('login', 'Auth::login');
$routes->post('auth/attemptLogin', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Simple POS route without auth filter for testing
$routes->post('pos/process-sale', 'POS::processSale');

// Protected routes (require login)
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});

$routes->group('pos', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'POS::index');
    $routes->post('add', 'POS::addToCart');
    $routes->post('update', 'POS::updateCart');
    $routes->post('remove', 'POS::removeFromCart');
    $routes->post('clear', 'POS::clearCart');
    $routes->get('receipt/(:num)', 'POS::receipt/$1');
    $routes->get('search', 'POS::searchProducts');
    $routes->get('variants', 'POS::getProductVariants');
});

// Admin only routes
$routes->group('staff', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Staff::index');
    $routes->get('create', 'Staff::create');
    $routes->post('store', 'Staff::store');
    $routes->get('edit/(:num)', 'Staff::edit/$1');
    $routes->post('update/(:num)', 'Staff::update/$1');
    $routes->get('deactivate/(:num)', 'Staff::deactivate/$1');
    $routes->get('activate/(:num)', 'Staff::activate/$1');
});

// Product stock management routes (admin only)
$routes->group('products', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('store', 'Products::store');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
    $routes->get('activate/(:num)', 'Products::activate/$1');
});

// Reports routes
$routes->group('reports', ['filter' => 'auth'], function($routes) {
    $routes->get('sales', 'Reports::sales');
    $routes->get('export-sales', 'Reports::exportSales');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
