<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvAddress extends Model
{
    protected $fillable = [
        'title','city','created_at','zip','updated_at','street','house_number','build_type','build_year','garage','country','display_house','distances','energy','floors'
    ];
}
