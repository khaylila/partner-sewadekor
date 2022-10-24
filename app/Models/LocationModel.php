<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $DBGroup = 'location';
    protected $table = 'provinces';
    protected $returnType = 'array';

    public function listProvinces($query = '')
    {
        $this->table = 'provinces';
        return $this->select('id,name AS text')->like('name', $query)->findAll();
    }

    public function listRegencies($provinceID, $query = '')
    {
        $this->table = 'regencies';
        return $this->select('id,name AS text')->where('province_id', $provinceID)->like('name', $query)->findAll();
    }

    public function listDistricts($regencyID, $query = '')
    {
        $this->table = 'districts';
        return $this->select('id,name AS text')->where('regency_id', $regencyID)->like('name', $query)->findAll();
    }

    public function listUrbans($districtID, $query = '')
    {
        $dataLocation = $this->findFromDistrictID($districtID);
        $db = \Config\Database::connect('postalCode');
        return $db->table('db_postal_code_data')->select('id,urban AS text')->where('province_code', $dataLocation['province_id'])->where('city', $dataLocation['regency'])->where('sub_district', $dataLocation['district'])->like('urban', $query)->get()->getResultArray();
    }

    public function findFromDistrictID($districtID)
    {
        // return array(district,regency,provinceID)
        $this->table = 'districts';
        $result = $this->select('districts.name AS district,regencies.name AS regency,provinces.id AS province_id')->where('districts.id', $districtID)->join('regencies', 'regencies.id = districts.regency_id')->join('provinces', 'provinces.id = regencies.province_id')->first();
        $result['regency'] = explode(' ', $result['regency'], 2)[1];
        return $result;
    }

    public function findRegencies($query = '')
    {
        $this->table = 'regencies';
        $regencies = [];
        foreach ($this->select('regencies.*,provinces.name as province')->like('regencies.name', $query)->join('provinces', 'provinces.id = regencies.province_id')->findAll() as $regency) {
            $regencies[] = ['id' => $regency['id'], 'text' => $regency['name'] . ', ' . $regency['province']];
        }
        return $regencies;
    }

    public function findRegenciesByID($id)
    {
        $this->table = 'regencies';
        return $this->select('regencies.*,provinces.name as province')->like('regencies.id', $id)->join('provinces', 'provinces.id = regencies.province_id')->first();
    }

    public function getDetail($address)
    {
        $db = \Config\Database::connect("location");
        [$urban, $district, $regency, $province] = array_pad(explode(',', $address), 4, null);
        $province = $db->table("provinces")->where('id', $province)->get(1)->getFirstRow("array");
        $regency = $db->table("regencies")->where('id', $regency)->get(1)->getFirstRow("array");
        $district = $db->table("districts")->where('id', $district)->get(1)->getFirstRow("array");
        $db = \Config\Database::connect("postalCode");
        $urban = $db->table("db_postal_code_data")->select('id,urban AS name')->where('id', $urban)->get(1)->getFirstRow("array");
        return ['urban' => $urban, 'district' => $district, 'regency' => $regency, 'province' => $province];
    }
}
