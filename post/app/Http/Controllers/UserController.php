<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Tên API",
 *     version="1.0.0",
 *     description="Mô tả API"
 * )
 */
class UserController extends Controller
{

    private $users;
    public function __construct()
    {
        $this->users = new Users();
    }
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $userList = $this->users->getAllUser();
        // dd($userList);
        return  $userList;
    }
    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="The name of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *           @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="The password of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'string|required|min:3|max:15',
            'email' => 'string|required|unique:users|email',
            'password' => 'string|required',
        ];
        $messages = [
            'name.required' => 'Tên là trường bắt buộc.',
            'name.string' => 'Tên phải nhập kiểu chuỗi.',
            'name.min' => 'Tên phải chứa ít nhất 3 ký tự.',
            'name.max' => 'Tên không được vượt quá 15 ký tự.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.string' => 'Email phải nhập kiểu chuỗi.',
            'email.unique' => 'Email đã tồn tại',
            'email.email' => 'Email phải đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.string' => 'Mật khẩu phải nhập dưới dạng chuỗi.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); 
        }
        $user = Users::create($request->all()); 

        return response()->json($user, 200);
    }
    
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
        $getUser = DB::table('users')->where('id', $id)->get();
        return $getUser;
    }
    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="The name of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'String|required|min:3|max:15',
            'email' => 'String|required|unique:users|email',
            // 'password' => 'String|required|confirmed',
            'password' => 'String|required',

        ];
        $messages = [
            'name.required' => 'Tên là trường bắt buộc.',
            'title.String' => 'Tên phải nhập kiểu chuỗi.',
            'name.min' => 'Tên phải chứa ít nhất 3 ký tự.',
            'name.max' => 'Tên không được vượt quá 15 ký tự.',
            'email.String' => 'Email phải nhập kiểu chuỗi.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.unique' => 'Email đã tồn tại',
            'email.email' => 'Email phải đúng định dạng',
            'password.string' => 'Mật khẩu phải nhập dưới dạng chuỗi.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            // 'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $request->all();
        $updateUser = DB::table('users')->where('id', $id)->update($data);
        return $updateUser;
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        $destroyUser = DB::table('users')->where('id', $id)->delete();
        return $destroyUser;
    }
}
