<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

// ... (kode route lainnya)
$routes->get('/', 'PageController::landing');
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
$routes->get('/customer/profil', 'CustomerController::cekProfil');
    
    // Rute untuk Menampilkan Form Edit Profil
    $routes->get('/customer/profil/edit', 'CustomerController::editProfil');
    
    // Rute untuk Memproses Update Profil (via POST dari form)
    $routes->post('/customer/profil/update', 'CustomerController::updateProfil');
        // RUTE BARU: Untuk menampilkan form Ganti Password
    $routes->get('/customer/profil/ganti-password', 'CustomerController::showChangePasswordForm'); 
    // Untuk memproses form Ganti Password
    $routes->post('/customer/profil/ganti-password-process', 'CustomerController::processChangePassword'); 

    $routes->get('/customer/order/detail/(:num)', 'CustomerController::orderDetail/$1');

// Rute untuk fitur-fitur Outlet
// Rute untuk manajemen banyak outlet
$routes->get('/outlet/my-outlets', 'OutletController::listMyOutlets');
$routes->get('/outlet/create', 'OutletController::createOutletForm');
$routes->get('/outlet/edit/(:num)', 'OutletController::editOutletForm/$1');
$routes->post('/outlet/store-update', 'OutletController::storeOrUpdateOutlet');

// Rute lainnya tetap sama
$routes->get('/outlet/orders', 'OutletController::listOrders');
$routes->post('/outlet/orders/update/(:num)', 'OutletController::updateOrderStatus/$1');
$routes->get('/outlet/reviews', 'OutletController::listReviews');
// ... (rute untuk services)
// Rute untuk Kelola Layanan oleh Outlet
$routes->get('/outlet/services', 'OutletController::listServices');
$routes->get('/outlet/services/create', 'OutletController::createService');
$routes->get('/outlet/services/edit/(:num)', 'OutletController::editService/$1');
$routes->post('/outlet/services/store', 'OutletController::storeService');
$routes->get('/outlet/services/delete/(:num)', 'OutletController::deleteService/$1');
$routes->post('/outlet/orders/update/(:num)', 'OutletController::updateOrderStatus/$1');
// Daftarkan rute untuk halaman "Outlet Saya"
$routes->get('/outlet/manage', 'OutletController::listMyOutlets');
// Rute untuk menampilkan ulasan dari satu outlet spesifik
// (:num) adalah placeholder untuk angka (ID outlet)
$routes->get('/outlet/(:num)/reviews', 'OutletController::showReviewsForOutlet/$1');

// Rute untuk fitur Admin
$routes->get('/admin/verify', 'AdminController::listPendingOutlets');
$routes->get('/admin/verify/action/(:num)/(:segment)', 'AdminController::verifyOutlet/$1/$2');

// Rute untuk alur pembayaran customer
$routes->get('/customer/payment/(:num)', 'CustomerController::paymentForm/$1');
$routes->post('/customer/payment/process', 'CustomerController::processPayment');

// Rute untuk verifikasi pembayaran oleh admin
$routes->get('/admin/payments/verify', 'AdminController::listPendingPayments');
$routes->get('/admin/payments/verify/action/(:num)', 'AdminController::verifyPayment/$1');
// di dalam app/Config/Routes.php
$routes->get('/admin/outlets', 'AdminController::listAllOutlets');
$routes->get('/admin/orders', 'AdminController::listAllOrders');
