<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\News as NewsModel;


class News extends Controller
{

    function getLastNews($type='private'){

        switch($type){
            case('private'):
                $news = NewsModel::getLastPrivateNews();
                break;
            case('business'):
                $news = NewsModel::getLastBusinessNews();
                break;
            case('all'):
                $news = NewsModel::getLastAllNews();
                break;
        }
        return response()->json($news);
    }

}
