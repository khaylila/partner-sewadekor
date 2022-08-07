<?php

namespace Config;

use App\Controllers\Admin;
use App\Controllers\AuthUser;
use App\Controllers\Home;
use App\Controllers\Partner;
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
$routes->get('/', [Home::class, 'dashboard']);
$routes->get('/credits', [Home::class, 'credits']);

$routes->get('/admin', [Admin::class, 'userAdminPage']);
$routes->post('/admin', [Admin::class, 'saveUser']);
$routes->put('/admin', [Admin::class, 'resetPassword']);
$routes->delete('/admin', [Admin::class, 'removeUser']);

$routes->get('/admin/idx', [Admin::class, 'index']);

$routes->get('/partner', [Partner::class, 'userPage']);
// $routes->get('/email', [Home::class, 'saveEmailDetail']);
// $routes->get('register', [AuthUser::class, 'register']);

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
