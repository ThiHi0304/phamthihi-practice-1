<?php

namespace App\Models;
use App\Models\User; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    
    protected $fillable = ['title', 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function getAllPost(){
        $posts=DB::table('posts')->get();
        return $posts;
    }
    public function addPost($data){
        $addPost=DB::table('posts')->insert($data);
        return $addPost;
    }
    public function showPost($id){
        $getPost=DB::table('posts')->where('id',$id)->get();
        return $getPost;
    }
    public function updatePost($id, $data){
        $updatePost=DB::table('posts')->where('id', $id)->update($data);
        return $updatePost;
    }
    public function delele($id){
        $destroyPost= DB::table('posts')->where('id', $id)->delete();
        return $destroyPost;
    }
}
