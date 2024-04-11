<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $user_id = auth()->user()->id ?? 1;
    
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $user_id,
            ];
    
            $post = Post::create($data);
    
            if ($post) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $post
                ];
                return response()->json($arr, 200);
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $post
                ];
                return response()->json($arr, 400);
            }
        }
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
        $user = DB::table('users')->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $postCount = DB::table('posts')->where('user_id', $id)->count();
        $posts = DB::table('posts')->where('user_id', $id)->get();
        foreach ($posts as $post) {
            $post->created_by = $user->name;
        }
        return response()->json([
            'user_id' => $id,
            'username' => $user->name,
            'post_count' => $postCount,
            'posts' => $posts
        ]);
    }
    
    
    
   /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a post",
     *     tags = {"Update a post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="post's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="post's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="post's description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Successfully"),
     *     @OA\Response(response="400", description="Errors")
     * )
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
            ];
            $post = DB::table('posts')->where('id', $id)->update($data);
            if ($post) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $post
                ];
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $post
                ];
            }
            return response()->json($arr, 200);
        }
    }

}
