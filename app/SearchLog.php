<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    public $table='search_log';



    protected $fillable = [
        'query','created_at','updated_at'
    ];


    public function getQueryAttribute(){
        return json_decode($this->attributes['query']);
    }
}
