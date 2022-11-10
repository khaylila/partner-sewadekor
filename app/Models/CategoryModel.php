<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'category';

    protected $allowedFields = ['category'];

    protected $validationRules    = ['category' => "is_unique[category.category]"];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
