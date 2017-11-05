<?php

namespace App;


class Category
{
    static private $categories = [
        [ 'id'=>1, 'title'=>'Flat', 'is_sale_only'=>false, 'ic_business'=>false ,'src'=>'http://c8.alamy.com/comp/HB2RHA/three-storey-house-flat-icon-HB2RHA.jpg'],
        [ 'id'=>2, 'title'=>'House', 'is_sale_only'=>false, 'ic_business'=>false ,'src'=>'http://www.webhostingreviewsbynerds.com/wp-content/plugins/rss-poster/cache/e17b1_fnal-570x456.png'],
        [ 'id'=>3, 'title'=>'Garage / car space', 'is_sale_only'=>false, 'ic_business'=>false, 'src'=>'https://maxcdn.icons8.com/Share/icon/Household//garage1600.png'],

        [ 'id'=>5, 'title'=>'Building ground', 'is_sale_only'=>true, 'ic_business'=>false ,'src'=>'https://maxcdn.icons8.com/Share/icon/office/Weather//earthquakes1600.png' ],

        [ 'id'=>4, 'title'=>'Office / Praxis', 'is_sale_only'=>false, 'ic_business'=>true, 'src'=>'https://www.iconexperience.com/_img/g_collection_png/standard/512x512/office_building.png'],
        [ 'id'=>6, 'title'=>'Gastronomy / Hotel', 'is_sale_only'=>false, 'ic_business'=>true, 'src'=>'https://www.iconexperience.com/_img/g_collection_png/standard/512x512/hotel.png'],
        [ 'id'=>7, 'title'=>'Hall / Production / Warehouse', 'is_sale_only'=>false, 'ic_business'=>true,'src'=>'https://cdn1.iconfinder.com/data/icons/buildings-4/32/building-dome-512.png'],
        [ 'id'=>8, 'title'=>'Retail trade', 'is_sale_only'=>false, 'ic_business'=>true, 'src'=>'https://cdn0.iconfinder.com/data/icons/e-commerce-and-shopping-2/512/shop_store_market_shopping_cafe_retail_sale_trading_trade_products_commerce_marketplace_bar_bistro_grocery_building_service_business_flat_design_icon-512.png'],
        [ 'id'=>9, 'title'=>'Commercial land', 'is_sale_only'=>false, 'ic_business'=>true, 'src'=>'http://advantagetaber.ca/images/land_icon.png'],
    ];

    static function getCategories()
    {
        foreach( self::$categories as $i=>$category){
            self::$categories[$i]['title'] = trans('main.category_'.self::$categories[$i]['title']);
        }
        return self::$categories;
    }

}
