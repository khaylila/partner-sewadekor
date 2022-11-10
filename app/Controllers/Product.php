<?php

namespace App\Controllers;

class Product extends BaseController
{
    public function index()
    {
        return view('product/list', [
            'title' => 'Product List',
            'lib' => ['jquery'],
            'active' => 'product',
            'breadcrumb' => array_reverse([
                ['title' => 'Product'],
                [
                    'title' => 'List',
                    'href' => '/product',
                ]
            ]),
        ]);
    }

    public function add()
    {
        return view('product/add', [
            'title' => 'Product Add',
            'lib' => ['jquery'],
            'active' => 'product',
            'breadcrumb' => array_reverse([
                ['title' => 'Product'],
                [
                    'title' => 'List',
                    'href' => '/product',
                ]
            ]),
        ]);
    }
}
