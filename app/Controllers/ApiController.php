<?php

namespace App\Controllers;

use App\Models\LocationModel;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function listProvinces()
    {
        return $this->respond(['status' => 'success', 'provinces' => model(LocationModel::class)->listProvinces($this->request->getGet('query') ?? '')]);
    }

    public function listRegencies($provinceID)
    {
        return $this->respond(['status' => 'success', 'regencies' => model(LocationModel::class)->listRegencies($provinceID, $this->request->getGet('query') ?? '')]);
    }

    public function listDistricts($regencyID)
    {
        return $this->respond(['status' => 'success', 'districts' => model(LocationModel::class)->listDistricts($regencyID, $this->request->getGet('query') ?? '')]);
    }

    public function listUrbans($urbanID = 3515110)
    {
        return $this->respond(['status' => 'success', 'urbans' => model(LocationModel::class)->listUrbans($urbanID, $this->request->getGet('query') ?? '')]);
    }

    public function findRegencies()
    {
        $query = $this->request->getGet('query') ?? '';
        return $this->respond(['status' => 'success', 'regencies' => model(LocationModel::class)->findRegencies($query)]);
    }

    public function getListCoverage($coverages)
    {
        $cover = [];
        foreach ($coverages as $coverage) {
            $cover[] = model(LocationModel::class)->findRegenciesByID($coverage);
        }
        return $cover;
    }
}
