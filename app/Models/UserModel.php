<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use App\Entities\User;
use App\Models\UserIdentityModel;

class UserModel extends ShieldUserModel
{

    protected $returnType     = User::class;
    // protected $afterFind     = ['fetchIdentities'];

    public function findById($id): ?User
    {
        return $this->find($id);
    }

    public function findUserByGroup($group)
    {
        /** @var GroupModel $groupModel */
        $groupModel = model(GroupModel::class);
        $userGroupId = [];
        foreach ($groupModel->findUserByGroup($group) as $userGroup) :
            $userGroupId[] = $userGroup->user_id;
        endforeach;
        // return $this->find($userGroupId);
        $userGroupList = [];
        foreach ($this->find($userGroupId) as $userList) {
            $tempUser = $userList->getEmailIdentity()->toArray();
            $tempUser['email'] = $tempUser['secret'];
            $tempUser['password'] = $tempUser['secret2'];
            unset($tempUser['secret2'], $tempUser['secret']);
            $userGroupList[] = $tempUser;
        }
        return $userGroupList;
    }
}
