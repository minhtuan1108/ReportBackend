<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Code test tạo token
//Route::post('/tokens/create', function (Request $request) {
//    if ($request->user){
//        $user = $request->user;
//        echo "$user->name";
//    }
//    else {
//        $user = User::find(1);
//        $token = $user->createToken('authToken');
//        return ['token' => $token->plainTextToken];
//    }
//});

// Api version 1
Route::prefix('v1')
    ->namespace('App\Http\Controllers\Api\v1')
    ->group(function(){
        // Login
        Route::post('/login', [AuthController::class, 'login']);

        // Quên mật khẩu (làm sau)
    })
    // Đã login
    ->middleware('auth:sanctum')->group(function(){

        // Logout
        Route::get('/user-info', [AuthController::class, 'logout']);

        // Lấy thông tin cá nhân
        Route::get('/user-info', [AuthController::class, 'getCurrentUser']);

        // Cập nhật thông tin cá nhân
        Route::get('/user-info', [AuthController::class, 'getCurrentUser']);

        Route::middleware('abilities:user')->group(function(){
            // Lấy danh sách task đã gửi (trả về các task của request->user()). Sau đó phân trang, khi kéo xuống hết dữ liệu, react native sẽ yêu cầu load thêm các task
            // Lấy thông tin chi tiết từng task (CHỈ ĐƯỢC XEM TASK CỦA BẢN THÂN GỬI và thông tin feedback của thợ (nếu có) )
            // Thực hiện tạo task (gửi thông tin và các ảnh: cần tạo đối tượng request chứa task và list ảnh) (cần kiểm tra người dùng có bị chặn báo cáo hay không isActive)
            // Xóa các task trạng thái đã gửi, còn xóa nháp thì ở react
        });

        Route::middleware('abilities:manager')->group(function(){
            // Lấy tất cả các task (lọc theo thời gian và tình trạng), phải phan trang, kéo xuống thêm dữ liệu
            // Lấy thông tin chi tiết của task và feedback của thợ nếu có
            // Tìm kiếm thợ
            // Trả về danh sách thợ
            // Xem chi tiết thông tin của thợ và các việc người đó đã làm
            // Giao việc cho thợ
            // Tạo feedback spam từ admin cho việc
            // Xóa (ẩn) việc làm
            // Chặn các báo cáo từ người dùng
            // Mở chặn các báo cáo từ người dùng trong cá nhân
        });

        Route::middleware('abilities:worker')->group(function(){
            // Xem danh sách các việc dược giao (CHỈ XEM VIỆC CỦA MÌNH ĐƯỢC GIAO)
            // Xem chi tiết công việc được giao (CHỈ XEM VIỆC CỦA MÌNH ĐƯỢC GIAO, CÓ THỂ XEM ĐƯỢC NOTE DO QUẢN TRỊ VIÊN TRONG ASSIGNMENT)
            // Tạo feedback cho công việc (tải ảnh và ghi chú)
        });
});
