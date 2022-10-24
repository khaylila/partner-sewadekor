<?php

namespace Config;

use App\Controllers\Admin;
use App\Controllers\ApiController;
use App\Controllers\AuthUser;
use App\Controllers\Home;
use App\Controllers\Partner;
use App\Controllers\Merchant;
use CodeIgniter\Shield\Controllers\LoginController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

service('auth')->routes($routes, ['except' => ['login']]);
// service('auth')->routes($routes);

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// route for login
$routes->get('login', [AuthUser::class, 'login']);
$routes->post('login', [LoginController::class, 'loginAction']);
// own routes
if (service('auth')->loggedIn()) {
    if (service('auth')->user()->inGroup('partner')) {
        $routes->get('/', [Home::class, 'partnerDashboard']);
    } else {
        $routes->get('/', [Home::class, 'adminDashboard']);
    }
} else {
    $routes->get('/', [AuthUser::class, 'login']);
}
$routes->get('/credits', [Home::class, 'credits']);

// admin
$routes->get('/admin', [Admin::class, 'userAdminPage']);
$routes->post('/admin', [Admin::class, 'saveUser']);
$routes->put('/admin', [Admin::class, 'resetPassword']);
$routes->delete('/admin', [Admin::class, 'removeUser']);

// partner
$routes->get('/partner', [Partner::class, 'userPage']);
$routes->post('/partner', [Partner::class, 'saveUser']);
$routes->put('/partner', [Partner::class, 'resetPassword']);
$routes->delete('/partner', [Partner::class, 'removeUser']);
// $routes->get('/email', [Home::class, 'saveEmailDetail']);
// $routes->get('register', [AuthUser::class, 'register']);

// product
$routes->get('/product', [Product::class, '']);

$routes->get('/account/merchant', [Merchant::class, 'viewPartnerIdentity']);
$routes->post('/account/merchant', [Merchant::class, 'savePartnerIdentity']);
$routes->put('/account/merchant', [Merchant::class, 'saveEditPartnerIdentity']);
$routes->post('/account/merchant/check', [Merchant::class, 'checkIdentity']);
$routes->post('/account/merchant/logo', [Merchant::class, 'saveMerchantLogo']);

$routes->group('api', static function ($routes) {
    $routes->get('provinces', [ApiController::class, 'listProvinces']);
    $routes->get('regencies/(:num)', [ApiController::class, 'listRegencies']);
    $routes->get('districts/(:num)', [ApiController::class, 'listDistricts']);
    $routes->get('urban/(:num)', [ApiController::class, 'listUrbans']);
    $routes->get('regencies/find', [ApiController::class, 'findRegencies']);
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
