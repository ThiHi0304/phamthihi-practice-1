<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Users extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password',
    ];
    public function phone()
{
    return $this->hasOne(Phone::class);
}

    
    public function getAllUser(){
        $users=DB::table('users')->get();
        return $users;
    }
    public function addUser($data){
        $addUser=DB::table('users')->insert($data);
        return $addUser;
    }
    public function showUser($id){
        $getUser=DB::table('users')->where('id',$id)->get();
        return $getUser;
    }
    public function updateUser($id, $data){
        $updateUser=DB::table('users')->where('id', $id)->update($data);
        return $updateUser;
    }
    public function delele($id){
        $destroyUser= DB::table('users')->where('id', $id)->delete();
        return $destroyUser;
    }
}
