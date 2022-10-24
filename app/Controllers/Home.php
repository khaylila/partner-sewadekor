<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Home extends BaseController
{
    public function generatePassword($length = 8, $type = 'alphanum', $uppercase = false)
    {
        $alpha = 'abcdefghijklmnopqrstuvwxyz';
        $num = '1234567890';
        $alphaLen = 26;
        $numLen = 10;
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            if (rand(0, 1) === 1) {
                $tempPassword = $alpha[rand(0, $alphaLen - 1)];
                if ($uppercase) {
                    if (rand(0, 1) === 1) {
                        $tempPassword = strtoupper($tempPassword);
                    }
                }
                $password .= $tempPassword;
            } else {
                $password .= $num[rand(0, $numLen - 1)];
            }
        }
        return $password;
    }

    public function sendEmail(array $data)
    {
        $settings = service('settings');
        $email = service('email')->initialize([
            'fromEmail' => $settings->get('Email.fromEmail'),
            'fromName' => $settings->get('Email.fromName'),
            'SMTPHost' => $settings->get('Email.SMTPHost'),
            'SMTPUser' => $settings->get('Email.SMTPUser'),
            'SMTPPass' => $settings->get('Email.SMTPPass'),
        ]);

        $email->setFrom(service('settings')->get('Email.fromEmail'), service('settings')->get('Email.fromName'));
        $email->setTo($data['email']);
        if ($data['subject'] == 'newAcc') {
            $data['subject'] = "New Account Information for swd.id";
            $data['message'] = "
                swd.id New Account Information
                <br>
                email / username: " . $data["email"] . "
                <br>
                password: " . $data["password"] . "
                <br>
                <br>
                We strongly suggest you to login to " . base_url() . "/profile/ account and change your password immediately!
                <br>
                ~~
                <br>
                If it's not you, you can report to:
                <br>
                <a href='mailto:abuse@swd.id'>abuse@swd.id</a>
            ";
        } else {
            $data['subject'] = "Reset Password Information for swd.id";
            $data['message'] = "
                swd.id Reset Password Information
                <br>
                Your new password: " . $data["password"] . "
                <br>
                <br>
                We strongly suggest you to login to " . base_url() . "/profile/ account and change your password immediately!
                <br>
                ~~
                <br>
                If it's not you, you can report to:
                <br>
                <a href='mailto:abuse@swd.id'>abuse@swd.id</a>
            ";
        }
        $email->setSubject($data['subject']);
        $email->setMessage($data['message']);
        if (!$email->send()) {
            log_message('warning', json_encode($email->printDebugger()) . '|' . json_encode($data));
            return false;
        }
        return true;
    }

    public function adminDashboard()
    {
        return view('home/dashboard', [
            'title' => 'Admin Dashboard',
            'lib' => [],
            'breadcrumb' => array_reverse([
                [
                    'title' => 'Dashboard',
                    'href' => '/dashboard',
                ]
            ]),
            'active' => 'dashboard',
        ]);
    }

    public function partnerDashboard()
    {
        if (!setting()->get('Partner.identity', 'user:' . user_id())) {
            $lib = ['jquerySparkline', 'chartJs', 'owlCarousel', 'summernote', 'jqueryChocolat'];
        }
        return view('home/partnerDashboard', [
            'sectionHeader' => false,
            'title' => 'Partner Dashboard',
            'lib' => $lib ?? [],
            'breadcrumb' => array_reverse([
                ['title' => 'Dashboard'],
                [
                    'title' => 'Index',
                    'href' => '/',
                ]
            ]),
            'active' => 'dashboard',
        ]);
    }

    public function credits()
    {
        return view('home/credits');
    }

    public function createUser()
    {
        $users = model(UserModel::class);
        $user = new User([
            'username' => 'khaylila',
        ]);
        if (!$users->save($user)) {
            echo "gagal menambahkan user";
        }
        $user = $users->findById($users->getInsertID());
        if (!$user === null) {
            echo 'unable to find user';
        }
        if (!$user->fill([
            'email' => 'mochamadroiyan@gmail.com',
            'password' => 'milea1910'
        ])->saveEmailIdentity()) {
            echo 'gagal menyimpan email password';
        }
        d($user);
        $users->addToDefaultGroup($user);
        d('berhasil menyimpan akun');
    }

    public function index()
    {
        auth()->logout();
        dd(auth()->loggedIn());
        // dd(service('settings')->set('App.baseURL', 'http://partner.swd.id'));
        // dd(service('settings')->forget('App.baseURL'));
        // dd(setting('Auth.recordActiveDate'));
        // dd(auth('session')->getAuthenticator());

        // $credentials = [
        //     'email'    => 'mochamadroiyan@gmail.com',
        //     'password' => 'milea1910',
        // ];
        // $loginAttempt = auth()->attempt($credentials);
        // dd($loginAttempt);
        // if (!$loginAttempt->isOK()) {
        //     return redirect()->back()->with('error', $loginAttempt->reason());
        // }

        // dd(auth()->loggedIn());

        // // 448a9c59aed212c8fcf8f3eecb002867f047df824c61725cb4622aa38c5291b4 id=4
        // $token = auth()->user()->generateAccessToken('hujanTurun');
        // dd($token->raw_token);

        // dd(auth()->user()->revokeAccessToken('c1befcaacba292fb5e5a1365c4722f468f41dc4e35338a32ec16e38f3cab7e44'));

        // $token = auth()->user()->generateAccessToken('ibuLinda', ['user.*']);
        // dd($token->raw_token);

        // dd(auth()->user()->accessTokens());

        // $credentials = [
        //     'token'    => '448a9c59aed212c8fcf8f3eecb002867f047df824c61725cb4622aa38c5291b4',
        // ];
        // $loginAttempt = auth()->setAuthenticator('tokens')->attempt($credentials);
        // dd($loginAttempt);
        // if (!$loginAttempt->isOK()) {
        //     return redirect()->back()->with('error', $loginAttempt->reason());
        // }

        // dd(auth()->loggedIn());

        // dd(auth()->user()->can('beta.access'));
        dd(auth()->user()->syncGroups(['developer'])->getGroups());
    }
}
