<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title','desc','created_at','type','updated_at'
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data['ReadableDate'] = $this->ReadableDate;
        return $data;
    }

    public function getReadableDateAttribute(){
        $date = new \DateTime($this->attributes['created_at']);
        return $date->format('d.m.Y H:i');
    }

    static function getLastPrivateNews(){
        return self::where('type','private')->orderBy('created_at','DESC')->limit(30)->get();
    }
    static function getLastBusinessNews(){
        return self::where('type','business')->orderBy('created_at','DESC')->limit(30)->get();
    }
    static function getLastAllNews(){
        return self::orderBy('created_at','DESC')->limit(30)->get();
    }
}
