<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Models\UserIdentityModel as ShieldUserIdentityModel;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Authentication\Passwords;
use App\Entities\User;
use App\Entities\UserIdentity;

use CodeIgniter\Shield\Models\CheckQueryReturnTrait;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Shield\Exceptions\RuntimeException;

class UserIdentityModel extends ShieldUserIdentityModel
{
    use CheckQueryReturnTrait;

    protected $returnType     = UserIdentity::class;

    /**
     * Creates a new identity for this user with an email/password
     * combination.
     *
     * @phpstan-param array{email: string, password: string} $credentials
     */
    public function createUserIdentity(User $user): void
    {
        /** @var Passwords $passwords */
        $passwords = service('passwords');

        $return = $this->insert([
            'user_id' => $user->id,
            'type'    => Session::ID_TYPE_EMAIL_PASSWORD,
            'secret'  => $user->email,
            'secret2' => $passwords->hash($user->password),
            'name'    => $user->fullname,
        ]);

        $this->checkQueryReturn($return);
    }

    // public function save($data): bool
    // {
    //     try {
    //         $result = parent::save($data);
    //         if ($result && $data instanceof User) {
    //             /** @var User $user */
    //             $user = $data->id === null
    //                 ? $this->find($this->db->insertID())
    //                 : $data;

    //             if (!$user->saveEmailIdentity()) {
    //                 throw new RuntimeException('Unable to save email identity.');
    //             }
    //         }

    //         return $result;
    //     } catch (DataException $e) {
    //         $messages = [
    //             lang('Database.emptyDataset', ['insert']),
    //             lang('Database.emptyDataset', ['update']),
    //         ];
    //         if (in_array($e->getMessage(), $messages, true)) {
    //             return true;
    //         }

    //         throw $e;
    //     }
    // }
}
