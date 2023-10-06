<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'mssv' => 'required|numeric',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()){
            return $this->responseJSON([
                'error' => 1,
                'message' => 'Vui lòng truyền đủ tham số',
                'details' => $validator->errors()
            ], 400);
        }

        $user = User::where('student_code', $request->mssv)->first();
        if (! $user || !Hash::check($request->password, $user->password))
            return $this->responseJSON([
                'error' => 1,
                'message' => 'Sai mật khẩu hoặc mã sinh viên !'
            ], 400);

        $roles = $this->getRoleNames($user);
        $token = $user->createToken($request->input('device_name', 'null'), $roles);
        return $this->responseJSON([
            'error' => 0,
            'message' => 'Đăng nhập thành công',
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'device_name' => $token->accessToken->name,
        ]);
    }

    public function getCurrentUser(Request $request){
        $user = $request->user();
        return $this->responseJSON([
            'name' => $user->name,
            'username' => $user->username,
            'student_code' => $user->student_code,
            'is_active' => $user->is_active,
            'email' => $user->email,
            'role' => $user->roles->map(function ($item){
                return $item['name'];
            })->join('|')
        ]);

    }

    public function logout(Request $request){
        if ($request->user() != null){
            $request->user()->currentAccessToken()->delete();
            return $this->responseJSON([
                'error' => 0,
                'message' => 'Đăng xuất thành công'
            ]);
        }
        return $this->responseJSON([
            'error' => 1,
            'message' => 'Không thể đăng xuất'
        ]);
    }

    private function getRoleNames($user): array {
        $roleNames = [];
        $roles = $user->roles()->get();
        foreach ($roles as $role){
            $roleNames[] = $role->name;
        }
        return $roleNames;
    }
}
