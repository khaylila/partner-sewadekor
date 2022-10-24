<?php

namespace App\Controllers;

use CodeIgniter\Validation\CreditCardRules;
use App\Entities\User;
use App\Models\LocationModel;
use App\Models\PartnerIdentityModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class Merchant extends BaseController
{
    use ResponseTrait;

    public function checkIdentity()
    {
        if (!setting()->get('Partner.identity', 'user:' . user_id())) {
            if (!model(PartnerIdentityModel::class)->checkPartnerIdentity(user_id())) {
                return $this->respond([
                    'url' => base_url() . '/account/merchant',
                    'hash' => csrf_hash(),
                    'title' => 'Identitas merchant tidak ditemukan!',
                    'message' => 'Tambahkan data terlebih dahulu untuk melanjutkan.',
                    'icon' => 'info',
                ], 400);
            }
            setting()->set('Partner.identity', true, 'user:' . user_id());
        }
        return $this->respond([
            'hash' => csrf_hash(),
            'title' => 'Identitas merchant ditemukan!',
            'icon' => 'info',
        ], 200);
    }

    private function explodeAddres($address)
    {
        $street = explode(',', $address, -4);
        $address = trim(str_replace($street, "", $address), ', ');
    }

    public function viewPartnerIdentity()
    {
        $lib = ['select2', 'cleave', 'dropzone'];
        $checkIdentity = setting()->get('Partner.identity', 'user:' . user_id());
        $dataIdentity = model(PartnerIdentityModel::class)->where('user_id', user_id())->first();
        if ($checkIdentity) {
            $dataIdentity['address_desc'] = model(LocationModel::class)->getDetail($dataIdentity['address_desc']);
            $apiController = new ApiController();
            $dataIdentity['coverage'] = $apiController->getListCoverage(json_decode($dataIdentity['coverage']));
        }
        return view('partnerPage/identity', [
            'title' => 'Identitas Merchant',
            'lib' => $lib ?? [],
            'breadcrumb' => array_reverse([
                ['title' => 'Account'],
                [
                    'title' => 'Merchant',
                    'href' => '/account/merchant',
                ]
            ]),
            'active' => 'account.merchant',
            'checkIdentity' => $checkIdentity,
            'dataIdentity' => $dataIdentity
        ]);
    }

    public function savePartnerIdentity()
    {
        $rules = [
            'factoryName' => [
                'label' => 'Merchant',
                'rules' => 'required|min_length[4]|max_length[256]|is_unique[partner_identities.name]|alpha_space',
                'errors' => [
                    'required' => 'Nama {field} diperlukan.',
                    'min_length' => 'Minimal {param} karakter.',
                    'max_length' => 'Maksimal {param} karakter.',
                    'is-unique' => '{value} sudah pernah digunakan sebelumnya.',
                    'alpha_space' => 'Hanya karakter alphaspace yang diijinkan.',
                ],
            ],
            'factoryNPWP' => [
                'label' => 'NPWP',
                'rules' => 'permit_empty|min_length[15]|max_length[32]|is_unique[partner_identities.npwp]',
                // 'errors' => [],
            ],
            'province' => [
                'label' => 'Provinsi',
                'rules' => 'required|is_not_unique_with_dbgroup[location.provinces.id]',
            ],
            'regency' => [
                'label' => 'Kabupaten',
                'rules' => 'required|is_not_unique_with_dbgroup[location.regencies.id]',
            ],
            'district' => [
                'label' => 'Kecamatan',
                'rules' => 'required|is_not_unique_with_dbgroup[location.districts.id]',
            ],
            'urban' => [
                'label' => 'Desa',
                'rules' => 'required|is_not_unique_with_dbgroup[postalCode.db_postal_code_data.id]',
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'required|min_length[8]|max_length[128]'
            ],
            'factoryPhone' => [
                'label' => 'Telepon',
                'rules' => 'required|min_length[10]|max_length[16]|is_unique[partner_identities.phone]',
            ],
            'coverageArea.*' => [
                'label' => 'Area Layanan',
                'rules' => 'required|is_not_unique_with_dbgroup[location.regencies.id]',
            ],
            // 'factoryLogo' => [
            //     'label' => 'Logo',
            //     'rules' => 'uploaded[factoryLogo]|max_size[factoryLogo,1024]|is_image[factoryLogo]',
            // ],
        ];
        if (!setting()->get('Partner.identity', 'user:' . user_id())) {
            $rules['factoryLogo'] = [
                'rules' => 'uploaded[factoryLogo]|max_size[factoryLogo,1024]|is_image[factoryLogo]',
                'label' => 'Logo Perusahaan',
                'errors' => [
                    'is_image' => 'hujan turun',
                    'uploaded' => 'gimana',
                ]
            ];
        }
        if (!$this->validate($rules)) {
            return $this->failValidationErrors(['status' => 'errors', 'errors' => service('validation')->getErrors(), 'message' => 'error validation', 'tokenHash' => csrf_hash()]);
        }

        $address = htmlspecialchars($this->request->getPost('address'));
        $addressDesc = $this->request->getPost('urban') . ',' . $this->request->getPost('district') . ',' . $this->request->getPost('regency') . ',' . $this->request->getPost('province');

        $factoryLogo = $this->request->getFile('factoryLogo');
        if ($factoryLogo->isValid() && !$factoryLogo->hasMoved()) {
            $newName = $factoryLogo->getRandomName();
            $factoryLogo->move('img', $newName);
        } else {
            return $this->failServerError();
        }
        $data = [
            'user_id' => user_id(),
            'name' => $this->request->getPost('factoryName'),
            'npwp' => $this->request->getPost('factoryNPWP'),
            'address' => $address,
            'address_desc' => $addressDesc,
            'phone' => preg_replace('/\s+/', '', $this->request->getPost('factoryPhone')),
            'coverage' => json_encode($this->request->getPost('coverageArea')),
            'logo' => $newName,
        ];
        if (!model(PartnerIdentityModel::class)->save($data)) {
            log_message('warning', 'user ' . user_id() . ' gagal menambahkan merchant identity. errorModel:' . json_encode(model(PartnerIdentityModel::class)->errors()) . ' userData:' . json_encode($data));
            return $this->failServerError();
        }
        return $this->respondCreated(['status' => 'success', 'data' => $data, 'url' => '/', 'message' => 'created identity success', 'tokenHash' => csrf_hash()]);
    }
    public function saveEditPartnerIdentity()
    {
        $rules = [
            'factoryName' => [
                'label' => 'Merchant',
                'rules' => 'required|min_length[4]|max_length[256]|is_unique[partner_identities.name,user_id,' . user_id() . ']|alpha_space',
                'errors' => [
                    'required' => 'Nama {field} diperlukan.',
                    'min_length' => 'Minimal {param} karakter.',
                    'max_length' => 'Maksimal {param} karakter.',
                    'is-unique' => '{value} sudah pernah digunakan sebelumnya.',
                    'alpha_space' => 'Hanya karakter alphaspace yang diijinkan.',
                ],
            ],
            'factoryNPWP' => [
                'label' => 'NPWP',
                'rules' => 'permit_empty|min_length[15]|max_length[32]|is_unique[partner_identities.npwp,user_id,' . user_id() . ']',
                // 'errors' => [],
            ],
            'province' => [
                'label' => 'Provinsi',
                'rules' => 'required|is_not_unique_with_dbgroup[location.provinces.id]',
            ],
            'regency' => [
                'label' => 'Kabupaten',
                'rules' => 'required|is_not_unique_with_dbgroup[location.regencies.id]',
            ],
            'district' => [
                'label' => 'Kecamatan',
                'rules' => 'required|is_not_unique_with_dbgroup[location.districts.id]',
            ],
            'urban' => [
                'label' => 'Desa',
                'rules' => 'required|is_not_unique_with_dbgroup[postalCode.db_postal_code_data.id]',
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'required|min_length[8]|max_length[128]'
            ],
            'factoryPhone' => [
                'label' => 'Telepon',
                'rules' => 'required|min_length[10]|max_length[16]|is_unique[partner_identities.phone,user_id,' . user_id() . ']',
            ],
            'coverageArea.*' => [
                'label' => 'Area Layanan',
                'rules' => 'required|is_not_unique_with_dbgroup[location.regencies.id]',
            ],
            // 'factoryLogo' => [
            //     'label' => 'Logo',
            //     'rules' => 'uploaded[factoryLogo]|max_size[factoryLogo,1024]|is_image[factoryLogo]',
            // ],
        ];
        // if (!setting()->get('Partner.identity', 'user:' . user_id())) {
        //     $rules['factoryLogo'] = [
        //         'rules' => 'uploaded[factoryLogo]|max_size[factoryLogo,1024]|is_image[factoryLogo]',
        //         'label' => 'Logo Perusahaan',
        //         'errors' => [
        //             'is_image' => 'hujan turun',
        //             'uploaded' => 'gimana',
        //         ]
        //     ];
        // }
        if (!$this->validate($rules)) {
            return $this->failValidationErrors(['status' => 'errors', 'errors' => service('validation')->getErrors(), 'message' => 'error validation', 'tokenHash' => csrf_hash()]);
        }

        $address = htmlspecialchars($this->request->getPost('address'));
        $addressDesc = $this->request->getPost('urban') . ',' . $this->request->getPost('district') . ',' . $this->request->getPost('regency') . ',' . $this->request->getPost('province');

        // $factoryLogo = $this->request->getFile('factoryLogo');
        // if ($factoryLogo->isValid() && !$factoryLogo->hasMoved()) {
        //     $newName = $factoryLogo->getRandomName();
        //     $factoryLogo->move('img', $newName);
        // } else {
        //     return $this->failServerError();
        // }
        $data = [
            'id' => model(PartnerIdentityModel::class)->where('user_id', user_id())->first()['id'],
            'user_id' => user_id(),
            'name' => $this->request->getPost('factoryName'),
            'npwp' => $this->request->getPost('factoryNPWP'),
            'address' => $address,
            'address_desc' => $addressDesc,
            'phone' => preg_replace('/\s+/', '', $this->request->getPost('factoryPhone')),
            'coverage' => json_encode($this->request->getPost('coverageArea')),
            // 'logo' => $newName,
        ];
        if (!model(PartnerIdentityModel::class)->save($data)) {
            log_message('warning', 'user ' . user_id() . ' gagal mengubah merchant identity. errorModel:' . json_encode(model(PartnerIdentityModel::class)->errors()) . ' userData:' . json_encode($data));
            return $this->failServerError();
        }
        return $this->respondCreated(['status' => 'success', 'data' => $data, 'url' => '/account/merchant', 'message' => 'updated identity success', 'tokenHash' => csrf_hash()]);
    }

    public function saveMerchantLogo()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $rules = [
            'factoryLogo' => 'uploaded[factoryLogo]|max_size[factoryLogo,1024]|is_image[factoryLogo]'
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors(['status' => 'errorValidation', 'errors' => service('validation')->getError('factoryLogo')]);
        }
        $factoryLogo = $this->request->getFile('factoryLogo');
        $newName = $factoryLogo->getRandomName();
        if ($factoryLogo->isValid() && !$factoryLogo->hasMoved()) {
            $factoryLogo->move('img', $newName);
            $data = [
                'id' => model(PartnerIdentityModel::class)->where('user_id', user_id())->first()['id'],
                'logo' => $newName,
            ];
            if (!model(PartnerIdentityModel::class)->save($data)) {
                log_message('warning', 'user ' . user_id() . ' gagal mengunggah logo. errorModel:' . json_encode(model(PartnerIdentityModel::class)->errors()) . ' userData:' . json_encode($data));
                return $this->failServerError();
            }

            return $this->respondCreated(['status' => 'success', 'message' => 'File has been uploaded!']);
        }
        return $this->failValidationErrors(['status' => 'errorValidation', 'message' => 'unable to upload file.']);
    }
}
