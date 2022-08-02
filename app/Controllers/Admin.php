<?php

namespace App\Controllers;

use App\Entities\User;
use App\Entities\UserIdentity;
use App\Models\GroupModel;
use App\Models\UserIdentityModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Message;
use CodeIgniter\Shield\Entities\Group;

class Admin extends BaseController
{
    use ResponseTrait;

    public function userAdminPage()
    {
        // dd(csrf_field());
        // dd(model(UserModel::class)->find(78)->getEmailIdentity());
        // $user = model(UserModel::class)->findById(59);
        // dd(model(UserModel::class)->find(57)->addGroup('admin')->inGroup('admin'));
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
            'listUserAdmin' => $userAdminList,
        ]);
    }

    public function saveUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('action forbidden');;
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
                'rules' => 'required|max_length[255]|valid_email',
                'errors' => [
                    'required' => '{field} diperlukan.',
                    'max_length' => 'Maksimal {value} karakter.',
                    "valid_email" => '{field} tidak valid.',
                ]
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->respond(['errors' => service('validation')->getErrors()], 400, 'errorValidation.');
        }
        // return $this->respondCreated(['status' => 'success', 'url' => '/admin']);
        $users = model(UserModel::class);
        $data = [
            'username' => null,
        ];

        $user = new User($data);
        if (!$users->save($user)) {
            $message = 'Gagal menyimpan user.';
            log_message('warning', $message);
            // return $this->respond(['message' => $message], 400);
            return $this->failServerError();
        }
        $userId = $users->getInsertID();
        $user = $users->findById($userId);
        if ($user === null || $user === 0) {
            $message = 'User tidak ditemukan';
            log_message('warning', $message);
            // return $this->respond(['message' => $message], 400);
            return $this->failServerError();
        }
        if (!$user->fill([
            'fullname' => $this->request->getPost('nameAdmin'),
            'email' => $this->request->getPost('emailAdmin'),
            'password' => 'milea1910',
        ])->saveUserIdentity()) {
            $message = 'Gagal menyimpan identitas user';
            log_message('warning', $message);
            $users->delete($userId, true);
            return $this->failServerError();
            // return $this->respond(['message' => $message], 400);
        }
        if (!$user->addGroup('admin')->inGroup('admin')) {
            $message = 'Gagal menambahkan user group';
            log_message('warning', $message);
            $users->delete($userId, true);
            // return $this->respond(['message' => $message], 400);
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
            return $this->fail('User tidak ditemukan', 400, null, 'User not found.');
        }
        $userId = explode('-', $this->request->getPost('user'), 2);
        if (count($userId) < 2) :
            // return $this->fail('User tidak ditemukan', 400, null, 'User not found.');
            log_message('warning', 'user ' . $this->request->getPost('user') . ' tidak ditemukan.');
            return $this->failServerError();
        endif;
        $users = model(UserModel::class);
        $userData = $users->find($userId[1])->getEmailIdentity();
        // return $this->respondDeleted([$userData]);

        if (!$users->delete($userId[1])) {
            log_message('warning', 'user ' . $userId[1] . ' gagal dihapus. ' . json_encode($users->errors()));
        }
        return $this->respondCreated(['status' => 'success', 'message' => 'User ' . $userData->name . ' berhasil dihapus.', 'url' => base_url() . '/admin', 'data' => $this->request->getVar()]);
    }

    public function listUsers()
    {
        dd(new User());
        log_message('info', 'ini adalah info');
    }

    public function index()
    {
        $user = new User(['username' => null]);
        $users = model(UserModel::class);
        if (!$users->save($user)) {
            dd('gagal menyimpan user');
        }
        $user = $users->findById($users->getInsertID());
        // $user = $users->findById(18);
        d($user);
        $user->fill([
            'fullname' => 'Admin',
            'email' => 'admin@sewadekor.id',
            'password' => 'milea1910',
        ]);
        if (!$user->saveUserIdentity()) {
            dd('gagal menyimpan user identity');
        }
        if (!$user->addGroup('admin')) {
            dd('gagal menyimpan grup');
        }
        dd('berhasil menyimpan user');
    }
}
