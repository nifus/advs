<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    public $table='search_log';



    protected $fillable = [
        'query','created_at','updated_at','config','number_of_results','type'
    ];


    public function getConfigAttribute()
    {
        return json_decode($this->attributes['config'],true);
    }

    public function setConfigAttribute($value)
    {
        $value = json_encode($value);
        $this->attributes['config']=$value;
    }

    public function getQueryAttribute(){
        return json_decode($this->attributes['query'], true);
    }

    public function setQueryAttribute($value)
    {
        $value = json_encode($value);
        $this->attributes['query']=$value;
    }
}
