<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\Login;
// use CodeIgniter\Shield\Entities\User;
use App\Entities\User;
use Exception;
use Faker\Generator;

use CodeIgniter\Shield\Models\LoginModel as ShieldLoginModel;
use CodeIgniter\Shield\Models\CheckQueryReturnTrait;

class LoginModelApp extends ShieldLoginModel
{
}
