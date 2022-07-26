<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Admin extends BaseController
{
    use ResponseTrait;

    public function userAdminPage()
    {
        if (!auth()->user()->inGroup('superadmin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $userAdminList = model(UserModel::class)->findUserByGroup('admin');
        return view('admin/add', [
            'title' => 'Admin Page',
            'lib' => [],
            'breadcrumb' => array_reverse([
                [
                    'title' => 'Admin',
                ],
                [
                    'title' => 'Tambah Admin',
                    'href' => '/admin/add',
                ]
            ]),
            'active' => 'adminPage',
            'listUserAdmin' => $userAdminList,
        ]);
    }

    public function resetPassword()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');;
        }
        // checkRole
        if (!auth()->user()->can('admin.resetPassword')) {
            return $this->failUnauthorized();
        }

        $rules = [
            'user' => [
                'rules' => 'required',
            ]
        ];
        if (!$this->validate($rules)) {
            return $this->failNotFound();
        }

        $userId = explode('-', $this->request->getPost('user'), 2);
        if (count($userId) < 2) :
            log_message('warning', 'user ' . $this->request->getPost('user') . ' tidak ditemukan.');
            return $this->failNotFound();
        endif;

        $homeCont = new Home;

        $user = model(UserModel::class)->find($userId[1]);
        $userData = $user->getEmailIdentity();
        $newPassword = $homeCont->generatePassword(16);
        $user->setPassword($newPassword);
        if (!$user->saveNewPassword()) {
            log_message('warning', 'user ' . $user->id . ' gagal mengubah password.');
            return $this->failServerError();
        }
        log_message('warning', json_encode($userData->toArray()));
        if (!$homeCont->sendEmail(['email' => $userData->secret, 'password' => $newPassword, 'subject' => 'resPass'])) {
            log_message('warning', 'user ' . $user->id . ' gagal mengirim email.');
            return $this->failServerError();
        }

        return $this->respondDeleted(['status' => 'success', 'message' => 'Password baru telah terkirim ke alamat email.', 'url' => base_url() . '/admin']);
    }

    public function saveUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');;
        }

        if (!auth()->user()->can('superadmin.createAdmin')) {
            return $this->failUnauthorized();
        }

        $rules = [
            'nameAdmin' => [
                'label' => 'Nama',
                'rules' => 'required|max_length[255]|alpha_space',
                'errors' => [
                    'required' => '{field} diperlukan.',
                    'max_length' => 'Maksimal {value} karakter.',
                    'alpha_space' => 'Hanya karakter alphabet atau space yang diijinkan.',
                ]
            ],
            'emailAdmin' => [
                'label' => 'Email',
                'rules' => 'required|max_length[255]|valid_email|is_unique[auth_identities.secret]',
                'errors' => [
                    "is_unique" => "{field} sudah terdaftar sebelumnya.",
                    'required' => '{field} diperlukan.',
                    'max_length' => 'Maksimal {value} karakter.',
                    "valid_email" => '{field} tidak valid.',
                ]
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->respond(['errors' => service('validation')->getErrors(), 'tokenHash' => csrf_hash()], 400, 'errorValidation.');
        }
        $users = model(UserModel::class);
        $data = [
            'username' => null,
        ];

        $user = new User($data);
        if (!$users->save($user)) {
            $message = 'Gagal menyimpan user.';
            log_message('warning', $message);
            return $this->failServerError();
        }
        $userId = $users->getInsertID();
        $user = $users->findById($userId);
        if ($user === null || $user === 0) {
            $message = 'User tidak ditemukan';
            log_message('warning', $message);
            return $this->failServerError();
        }
        $password = (new Home)->generatePassword();
        if (!$user->fill([
            'fullname' => $this->request->getPost('nameAdmin'),
            'email' => $this->request->getPost('emailAdmin'),
            'password' => $password,
        ])->saveUserIdentity()) {
            $message = 'Gagal menyimpan identitas user';
            log_message('warning', $message);
            $users->delete($userId, true);
            return $this->failServerError();
        }
        if (!(new Home)->sendEmail(['email' => $this->request->getPost('emailAdmin'), 'password' => $password, 'subject' => 'newAcc'])) {
            log_message('warning', 'user ' . $user->id . ' gagal mengirim email.');
            $users->delete($userId, true);
            return $this->failServerError();
        }
        if (!$user->addGroup('admin')->inGroup('admin')) {
            $message = 'Gagal menambahkan user group';
            log_message('warning', $message);
            $users->delete($userId, true);
            return $this->failServerError();
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'User Admin berhasil dibuat.', 'url' => base_url() . '/admin']);
    }

    public function removeUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');
        }

        if (empty($this->request->getPost('user'))) {
            return $this->failNotFound();
        }
        $userId = explode('-', $this->request->getPost('user'), 2);
        if (count($userId) < 2) :
            log_message('warning', 'user ' . $this->request->getPost('user') . ' tidak ditemukan.');
            return $this->failNotFound();
        endif;
        $users = model(UserModel::class);
        $userData = $users->find($userId[1])->getEmailIdentity();

        // checkRole
        if (!auth()->user()->can('superadmin.deleteAdmin')) {
            return $this->failUnauthorized();
        }

        if (!$users->delete($userId[1])) {
            log_message('warning', 'user ' . $userId[1] . ' gagal dihapus. ' . json_encode($users->errors()));
        }
        return $this->respondDeleted(['status' => 'success', 'message' => 'User ' . $userData->name . ' berhasil dihapus.', 'url' => base_url() . '/admin']);
    }

    // order to deleted
    // public function index()
    // {
    //     $user = new User(['username' => null]);
    //     $users = model(UserModel::class);
    //     if (!$users->save($user)) {
    //         dd('gagal menyimpan user');
    //     }
    //     $user = $users->findById($users->getInsertID());
    //     // $user = $users->findById(18);
    //     d($user);
    //     $user->fill([
    //         'fullname' => 'Admin',
    //         'email' => 'admin@sewadekor.id',
    //         'password' => 'milea1910',
    //     ]);
    //     if (!$user->saveUserIdentity()) {
    //         dd('gagal menyimpan user identity');
    //     }
    //     if (!$user->addGroup('admin')) {
    //         dd('gagal menyimpan grup');
    //     }
    //     dd('berhasil menyimpan user');
    // }
}
