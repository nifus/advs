<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MailTemplate extends Model
{
    public $table='mail_templates';



    protected $fillable = [
        'name','created_at','updated_at','path','header','user_id','type','body'
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data['MailTemplate'] = $this->getMailTemplate();
        return $data;
    }

    public function User(){
        return $this->hasOne('App\User','id','user_id');
    }

    static function createTemplate($data){
        $tmpl = self::create($data);
        $tmpl->updateMailTemplate($data['body']);
        //$this->update($data);
        //$this->updateMailTemplate($data['MailTemplate']);
        return $tmpl;
    }

    public function updateByUser($data, User $user){
        $data['user_id']=$user->id;
        $this->update($data);
        $this->updateMailTemplate($data['MailTemplate']);
        return true;
    }

    public function getMailTemplate(){
        $path = resource_path('views/emails/'. str_replace('.','/',$this->path).'.blade.php');
        $content = file_get_contents($path);
        $content = str_replace('{{$','[',$content );
        $content = str_replace('}}',']',$content );
        return $content;
    }
    public function updateMailTemplate($template){
        $path = resource_path('views/emails/'.str_replace('.','/',$this->path).'.blade.php');
        $template = str_replace('[','{{$',$template );
        $template = str_replace(']','}}',$template );
        file_put_contents($path, $template);
        return true;
    }

    static function getAll(){
        return self::with('User')->get();
    }

    static function getById($id){
        return self::with('User')->find($id);
    }

}
