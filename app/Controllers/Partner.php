<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Partner extends BaseController
{
    use ResponseTrait;

    // password ffxc123k3q865v1p
    // public function __construct()
    // {
    // }

    public function userPage()
    {
        if (!auth()->user()->inGroup('admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $userList = model(UserModel::class)->findUserByGroup('partner');
        return view('partner/index', [
            'title' => 'Partner Page',
            'lib' => [],
            'breadcrumb' => array_reverse([
                [
                    'title' => 'Partner',
                ],
                [
                    'title' => 'List',
                    'href' => '/partner',
                ]
            ]),
            'active' => 'partnerPage',
            'listUser' => $userList,
        ]);
    }

    public function resetPassword()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');;
        }

        if (!auth()->user()->can('partner.resetPassword')) {
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

        return $this->respondDeleted(['status' => 'success', 'message' => 'Password baru telah terkirim ke alamat email.', 'url' => base_url() . '/partner']);
    }

    public function saveUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');;
        }

        if (!auth()->user()->can('admin.createPartner')) {
            return $this->failUnauthorized();
        }

        $rules = [
            'namePartner' => [
                'label' => 'Nama',
                'rules' => 'required|max_length[255]|alpha_space',
                'errors' => [
                    'required' => '{field} diperlukan.',
                    'max_length' => 'Maksimal {value} karakter.',
                    'alpha_space' => 'Hanya karakter alphabet atau space yang diijinkan.',
                ]
            ],
            'emailPartner' => [
                'label' => 'Email',
                'rules' => 'required|max_length[255]|valid_email|is_unique[auth_identities.secret]',
                'errors' => [
                    'required' => '{field} diperlukan.',
                    'max_length' => 'Maksimal {value} karakter.',
                    "valid_email" => '{field} tidak valid.',
                    "is_unique" => "{field} sudah terdaftar sebelumnya.",
                ]
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->respond(['errors' => service('validation')->getErrors(), 'tokenHash' => csrf_hash(), 'data' => $this->request->getPost()], 400, 'errorValidation.');
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
            'fullname' => $this->request->getPost('namePartner'),
            'email' => $this->request->getPost('emailPartner'),
            'password' => $password,
        ])->saveUserIdentity()) {
            $message = 'Gagal menyimpan identitas user';
            log_message('warning', $message);
            $users->delete($userId, true);
            return $this->failServerError();
        }
        if (!(new Home)->sendEmail(['email' => $this->request->getPost('emailPartner'), 'password' => $password, 'subject' => 'newAcc'])) {
            log_message('warning', 'user ' . $user->id . ' gagal mengirim email.');
            $users->delete($userId, true);
            return $this->failServerError();
        }
        if (!$user->addGroup('partner')->inGroup('partner')) {
            $message = 'Gagal menambahkan user group';
            log_message('warning', $message);
            $users->delete($userId, true);
            return $this->failServerError();
        }
        service('setting')->set('Partner.identity', false, "user:" . $userId);

        return $this->respondCreated(['status' => 'success', 'message' => 'User ' . $this->request->getPost('fullname') . ' dengan role partner berhasil dibuat.', 'url' => base_url() . '/partner']);
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
        if (!auth()->user()->can('admin.removePartner')) {
            return $this->failUnauthorized();
        }

        if (!$users->delete($userId[1])) {
            log_message('warning', 'user ' . $userId[1] . ' gagal dihapus. ' . json_encode($users->errors()));
            return $this->failServerError();
        }
        return $this->respondDeleted(['status' => 'success', 'message' => 'User ' . $userData->name . ' berhasil dihapus.', 'url' => base_url() . '/partner']);
    }
}
