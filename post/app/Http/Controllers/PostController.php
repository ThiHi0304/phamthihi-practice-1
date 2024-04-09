<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="Documentation for the API"
 * )
 */
class PostController extends Controller

{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    private $posts;
    public function __construct()
    {
        $this->posts = new Post();
    }
    public function index(){
        $postList = $this->posts->getAllPost();
        // dd($postList);
        return  $postList;
    }
    /**
 * @OA\Post(
 *     path="/api/posts",
 *     summary="Create a new post",
 *     tags={"Posts"},
 *     @OA\Parameter(
 *         name="title",
 *         in="query",
 *         description="The title of the post",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="description",
 *         in="query",
 *         description="The description of the post",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     security={{"bearerAuth":{}}}
 * )
 */
    public function store(Request $request){
        $data = $request->all();
        $addPost = DB::table('posts')->insert($data);
        return $addPost;
    }
    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id){
        $getPost=DB::table('posts')->where('id',$id)->get();
        return $getPost;
    }
    /**
 * @OA\Put(
 *     path="/api/posts/{id}",
 *     summary="Update a specific post",
 *     tags={"Posts"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Post ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="content", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     security={{"bearerAuth":{}}}
 * )
 */
    public function update($id, Request $request) {
        $data = $request->all();
        $updatePost = DB::table('posts')->where('id', $id)->update($data);
        return $updatePost;
    }
    
    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id){
        $destroyPost= DB::table('posts')->where('id', $id)->delete();
        return $destroyPost;
    }
}
