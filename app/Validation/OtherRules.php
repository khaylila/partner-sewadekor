<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace App\Validation;

use App\Models\LocationModel;
use Config\Database;
use InvalidArgumentException;

/**
 * Validation Rules.
 */
class OtherRules
{
    public function is_not_unique_with_dbgroup(?string $str, string $field, array $data): bool
    {
        // Grab any data for exclusion of a single row.
        [$field, $whereField, $whereValue] = array_pad(explode(',', $field), 3, null);

        // Break the table and field apart
        sscanf($field, '%[^.].%[^.].%[^.]', $DBGroup, $table, $field);

        $row = Database::connect($DBGroup ?? null)
            ->table($table)
            ->select('1')
            ->where($field, $str)
            ->limit(1);

        if (!empty($whereField) && !empty($whereValue) && !preg_match('/^\{(\w+)\}$/', $whereValue)) {
            $row = $row->where($whereField, $whereValue);
        }

        return $row->get()->getRow() !== null;
    }

    public function check_urban(?string $str, string $field, array $data): bool
    {
        // Grab any data for exclusion of a single row.
        [$field, $whereField, $whereValue] = array_pad(explode(',', $field), 3, null);

        // Break the table and field apart
        sscanf($field, '%[^.].%[^.].%[^.]', $districtField, $table, $field);

        $dataLocation = model(LocationModel::class)->findFromDistrictID($data[$districtField]);

        $row = Database::connect('postalCode')
            ->table('db_postal_code_data')
            ->select('1')
            ->where('province_code', $dataLocation['province_id'])
            ->where('city', $dataLocation['regency'])
            ->where('sub_district', $dataLocation['district'])
            ->like('id', $str)
            ->limit(1);

        if (!empty($whereField) && !empty($whereValue) && !preg_match('/^\{(\w+)\}$/', $whereValue)) {
            $row = $row->where($whereField, $whereValue);
        }

        return $row->get()->getRow() !== null;
    }
}
