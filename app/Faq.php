<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public $table='faqs';

    protected $fillable = [
        'title','created_at','updated_at','desc','sort' ,'type','announcement_type'
    ];

    public function toArray()
    {
        $data =parent::toArray();
        $data['ReadableDate'] = $this->ReadableDate;
        return $data;
    }

    public function getReadableDateAttribute(){
        $date = new \DateTime($this->attributes['created_at']);
        return $date->format('d.m.Y H:i');
    }
    public function updateElement($data){
        $validator = [
            'title' => 'required',
            'type' => 'required',
            'desc' => 'required',
        ];
        if ($data['type']=='announcement'){
            $validator['announcement_type']='required';
        }
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
       $this->update($data);
    }



    static function createElement($data){
        $validator = [
            'title' => 'required',
            'type' => 'required',
            'desc' => 'required',
        ];
        if ($data['type']=='announcement'){
            $validator['announcement_type']='required';
        }
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create($data);
    }

    static function getAll(){
        return self::orderBy('sort', 'ASC')
            ->get();
    }

    static function getPrivateAnnouncements(){
        return self::where('type','announcement')
            ->where('announcement_type','private')
            ->orderBy('sort', 'ASC')
            ->get();
    }

    static function getBusinessAnnouncements(){
        return self::where('type','announcement')
            ->where('announcement_type','business')
            ->orderBy('sort', 'ASC')
            ->get();
    }

}
