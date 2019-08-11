<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';


    public function goods(){
        return $this->hasMany(Good::class);
    }

    static function get_data(){

        return AdminUser::find(1)->goods;
    }
}
