<?php

namespace App\Entities;

use App\Models\UserIdentityModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User as ShieldUser;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Authorization\Traits\Authorizable;
use CodeIgniter\Shield\Authentication\Traits\HasAccessTokens;
use App\Models\GroupModel;
use CodeIgniter\Shield\Models\LoginModel;
use CodeIgniter\HTTP\IncomingRequest;

class User extends ShieldUser
{
    public function getEmailIdentity(): ?UserIdentity
    {
        return $this->getIdentity(Session::ID_TYPE_EMAIL_PASSWORD);
    }

    public function getIdentity(string $type): ?UserIdentity
    {
        $identities = $this->getIdentities($type);
        return count($identities) ? array_shift($identities) : null;
    }

    /**
     * ensures that all of the user's identities are loaded
     * into the instance for faster access later.
     */
    private function populateIdentities(): void
    {
        if ($this->identities === null) {
            /** @var UserIdentityModel $identityModel */
            $identityModel = model(UserIdentityModel::class);
            $this->identities = $identityModel->getIdentities($this);
        }
    }

    /**
     * Accessor method for this user's UserIdentity objects.
     * Will populate if they don't exist.
     *
     * @param string $type 'all' returns all identities.
     *
     * @return UserIdentity[]
     */
    public function getIdentities(string $type = 'all'): array
    {
        $this->populateIdentities();

        if ($type === 'all') {
            return $this->identities;
        }

        $identities = [];

        foreach ($this->identities as $identity) {
            if ($identity->type === $type) {
                $identities[] = $identity;
            }
        }

        return $identities;
    }

    public function saveUserIdentity(): bool
    {
        if (empty($this->email) && empty($this->password) && empty($this->fullname)) {
            return false;
        }

        $identity = $this->getEmailIdentity();
        if ($identity === null) {
            // Ensure we reload all identities
            $this->identities = null;

            $this->createEmailIdentity([
                'email'    => $this->email,
                'password' => '',
            ]);
            $identity = $this->getEmailIdentity();
        }

        if (!empty($this->fullname)) {
            $identity->name = $this->fullname;
        }

        if (!empty($this->email)) {
            $identity->secret = $this->email;
        }

        if (!empty($this->password)) {
            $identity->secret2 = service('passwords')->hash($this->password);
        }
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        return $identityModel->save($identity);
    }

    public function saveNewPassword(): bool
    {
        $identity = $this->getEmailIdentity();
        if (empty($this->password)) {
            return false;
        }
        $identity->secret2 = service('passwords')->hash($this->password);
        $identityModel = model(UserIdentityModel::class);
        return $identityModel->save($identity);
    }

    // public function rollbackPassword(): bool
    // {
    //     $identity = $this->getEmailIdentity();
    //     $identity->secret2 = $this->password_hash;
    //     $identityModel = model(UserIdentityModel::class);
    //     return $identityModel->save($identity);
    // }

    public function getLastLogin(): string
    {
        $time = model(LoginModel::class)->select('date')->where('user_id', $this->id)->where('ip_address', $_SERVER['REMOTE_ADDR'])->orderBy('id', 'DESC')->where('success', 1)->first()->date ?? 0;
        if ($time !== 0) {
            $time = time() - $time->getTimestamp();
            if ($time < 60) {
                return $time . ' sec';
            } elseif ($time < 3600) {
                return intval($time / 60) . ' min';
            } else {
                return intval($time / 3600) . ' hrs';
            }
        }
        return '0 min';
    }

    public function findUserByGroup($group)
    {
        $groupModel = model(GroupModel::class);
        $groupModel->getWhereGroup($group);
    }
}
