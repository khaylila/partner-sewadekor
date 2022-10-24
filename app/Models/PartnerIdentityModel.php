<?php

namespace App\Models;

use CodeIgniter\Model;

class PartnerIdentityModel extends Model
{
    protected $table      = 'partner_identities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['user_id', 'name', 'npwp', 'address', 'address_desc', 'phone', 'coverage', 'logo'];
    protected $useTimestamps = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function save($data): bool
    {
        $identityID = $this->where('user_id', user_id())->first()['id'] ?? null;
        if ($identityID !== null) {
            $data['id'] = $identityID;
        }
        $result = parent::save($data);
        return $result;
    }

    public function checkPartnerIdentity(int $userId): bool
    {
        return $this->select('1')
            ->where('user_id', $userId)
            ->limit(1)->get()->getRow() !== null;
    }
}
