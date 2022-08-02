<?php

namespace App\Models;

use CodeIgniter\Model;
// use CodeIgniter\Shield\Entities\User;
use App\Entities\User;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Models\CheckQueryReturnTrait;
use CodeIgniter\Shield\Models\GroupModel as ShieldGroupModel;

class GroupModel extends ShieldGroupModel
{
    use CheckQueryReturnTrait;

    // protected $table          = 'auth_groups_users';
    // protected $primaryKey     = 'id';
    protected $returnType     = Group::class;
    // protected $useSoftDeletes = false;
    // protected $allowedFields  = [
    //     'user_id',
    //     'group',
    //     'created_at',
    // ];
    // protected $useTimestamps      = false;
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    // protected $afterFind     = ['fetchIdentities'];

    public function findUserByGroup($group)
    {
        return $this->select('user_id')->where('group', $group)->findAll();
    }

    protected function fetchIdentities(array $data): array
    {
        $userIds = $data['singleton']
            ? array_column($data, 'id')
            : array_column($data['data'], 'id');

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        // Get our identities for all users
        $identities = $identityModel->getIdentitiesByUserIds($userIds);

        if (empty($identities)) {
            return $data;
        }

        // Map our users by ID to make assigning simpler
        $mappedUsers = [];
        $users       = $data['singleton']
            ? $data
            : $data['data'];

        foreach ($users as $user) {
            $mappedUsers[$user->id] = $user;
        }
        unset($users);

        // Now assign the identities to the user
        foreach ($identities as $id) {
            $array                                 = $mappedUsers[$id->user_id]->identities;
            $array[]                               = $id;
            $mappedUsers[$id->user_id]->identities = $array;
        }

        $data['data'] = $mappedUsers;

        return $data;
    }
}
