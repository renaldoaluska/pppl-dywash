<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

// ... (kode route lainnya)
$routes->get('/', 'AuthController::login');
$routes->get('/auth/login', 'AuthController::login');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// Rute ini sekarang menangani semua jenis dashboard
$routes->get('/dashboard', 'AuthController::dashboard');

// Rute untuk register
$routes->get('/auth/register', 'AuthController::register');
$routes->post('/auth/register', 'AuthController::register');

// Rute untuk fitur-fitur Customer
$routes->get('/customer/outlet', 'CustomerController::listOutlet');
$routes->get('/customer/order/create/(:num)', 'CustomerController::createOrder/$1');
$routes->post('/customer/order/store', 'CustomerController::storeOrder');
$routes->get('/customer/monitor', 'CustomerController::monitorOrder');
$routes->post('/customer/review/store', 'CustomerController::storeReview');