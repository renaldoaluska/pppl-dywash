<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

// ... (kode route lainnya)

$routes->get('/', 'AuthController::login');
$routes->match(['post'], 'auth/login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->get('dashboard/admin', 'DashboardController::admin');
$routes->get('dashboard/outlet', 'DashboardController::outlet');
$routes->get('dashboard/customer', 'DashboardController::customer');