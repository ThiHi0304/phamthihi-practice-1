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
            // 'password' => 'string|required',
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
            // 'password.required' => 'Mật khẩu là trường bắt buộc.',
            // 'password.string' => 'Mật khẩu phải nhập dưới dạng chuỗi.',
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
        $userWithPhone = Users::with('phones')->find($id);
        if (!$userWithPhone) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        return response()->json($userWithPhone, 200);
    }
    
    
    
/**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags = {"Update a user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
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
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
        ], [
            'name.required' => 'Họ và tên bắt buộc phải nhập',
            'name.string' => 'Họ và tên bắt buộc là string',
            'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
            'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.string' => 'Password bắt buộc là string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $user = DB::table('users')->where('id', $id)->update($data);
            if ($user) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $user
                ];
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $user
                ];
            }
            return response()->json($arr, 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags = {"Delete a user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Errors"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    

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
