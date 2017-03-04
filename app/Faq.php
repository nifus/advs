<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public $table='faqs';

    protected $fillable = [
        'title','created_at','updated_at','desc','sort' ,'type'
    ];

    static function createElement($data){
        $validator = [
            'title' => 'required',
            'type' => 'required',
            'desc' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create($data);
    }
    public function updateElement($data){
        $validator = [
            'title' => 'required',
            'type' => 'required',
            'desc' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
       $this->update($data);
    }


    static function getAll(){
        return self::orderBy('sort', 'ASC')
            ->get();
    }

}
