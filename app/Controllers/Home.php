<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Home extends BaseController
{

    public function dashboard()
    {
        return view('home/dashboard');
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
        // dd(service('settings')->set('App.baseURL', 'http://partner.sewadekor.id'));
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
