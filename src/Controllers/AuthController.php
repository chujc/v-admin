<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Facades\Admin;
use ChuJC\Admin\Models\AdminMenu;
use ChuJC\Admin\Models\AdminUser;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    public function __construct()
    {
        $this->model = config('admin.database.users_model', AdminUser::class);
    }

    /**
     * 登出账号
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Admin::guard()->logout();
        return Result::success();
    }

    /**
     * 获取登录账号信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        if (Admin::user()->isAdministrator()) {
            $adminMenu = AdminMenu::get();
        } else {
            $adminMenu = Admin::user()->allPermissions();
        }

        $routeTree = arrayToTree($adminMenu->filter(function ($item) {
            if ($item && $item->menu_type == 1) {
                return $item;
            }
        })->toArray(), 'menu_id');
        Admin::user()->route = $routeTree;
        Admin::user()->permission = $adminMenu->filter(function ($item) {
            if ($item && $item->permission) {
                return $item;
            }
        })->pluck('permission');


        return Result::data(Admin::user());
    }

    /**
     * 更新登录账号信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserInfo(Request $request)
    {
        $params = $request->only(['nickname']);

        Admin::User()->update($params);

        return Result::success();
    }

    /**
     * 更新登录账号密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $newPassword = $request->input('newPassword');
        $oldPassword = $request->input('oldPassword');

        if (Hash::check($oldPassword, Admin::User()->password)) {
            $params['password'] = Hash::make($newPassword);
        } else {
            return Result::failed('密码错误请重新输入');
        }

        Admin::User()->update($params);

        return Result::success();
    }


    /**
     * 更新登录账号头像
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        $avatarFile = $request->file('avatarFile');

        $disk = config('admin.upload.disk', 'admin');
        $directory = config('admin.upload.directory.avatar', 'avatar');

        $avatarPath = $avatarFile->store($directory, ['disk' => $disk]);
        Admin::User()->update(['avatar' => $avatarPath]);

        return Result::success();
    }

}
