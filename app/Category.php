<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category
{
    static private $categories = [
        [ 'id'=>1, 'title'=>'Flat', 'is_sale_only'=>false, 'ic_business'=>false],
        [ 'id'=>2, 'title'=>'House', 'is_sale_only'=>false, 'ic_business'=>false],
        [ 'id'=>3, 'title'=>'Garage / car space', 'is_sale_only'=>false, 'ic_business'=>false],

        [ 'id'=>5, 'title'=>'Building ground', 'is_sale_only'=>true, 'ic_business'=>false ],

        [ 'id'=>4, 'title'=>'Office / Praxis', 'is_sale_only'=>false, 'ic_business'=>true],
        [ 'id'=>6, 'title'=>'Gastronomy / Hotel', 'is_sale_only'=>false, 'ic_business'=>true],
        [ 'id'=>7, 'title'=>'Hall / Production / Warehouse', 'is_sale_only'=>false, 'ic_business'=>true],
        [ 'id'=>8, 'title'=>'Retail trade', 'is_sale_only'=>false, 'ic_business'=>true],
        [ 'id'=>9, 'title'=>'Commercial land', 'is_sale_only'=>false, 'ic_business'=>true],
    ];

    static function getCategories()
    {
        foreach( self::$categories as $i=>$category){
            self::$categories[$i]['title'] = trans('main.category_'.self::$categories[$i]['title']);
        }
        return self::$categories;
    }
}
