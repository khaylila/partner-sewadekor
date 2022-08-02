<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AuthUser extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function loggedIn()
    {
        return 'berhasil login';
    }
}
