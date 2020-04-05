<?php
/**
 * Class Admin
 * @package ChuJC\Admin
 * @author john_chu
 */

namespace ChuJC\Admin;


use Illuminate\Support\Facades\Auth;

class Admin
{

    public function version()
    {
        return '0.1';
    }

    public function routes()
    {
        if (isLaravel()) {
            $this->laravelRoutes();
        } else {
            $this->lumenRoutes();
        }

    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->guard()->user();
    }

    /**
     * Attempt to get the guard from the local cache.
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        $guard = config('admin.auth.guard') ?: 'admin';

        return Auth::guard($guard);
    }

    /**
     * register lumen route
     */
    public function lumenRoutes()
    {
        $attributes = [
            'prefix' => config('admin.route.prefix'),
        ];
        app('router')->group($attributes, function ($router) {
            // 登录
            $router->post('auth/login', 'ChuJC\Admin\Controllers\LoginController@login');
            $router->get('auth/login/captcha', 'ChuJC\Admin\Controllers\LoginController@captcha');
            /* @var \Illuminate\Support\Facades\Route $router */
            $router->group([
                'namespace' => 'ChuJC\Admin\Controllers',
                'middleware' => config('admin.route.middleware'),
            ], function ($router) {
                // 个人相关
                $router->get('auth/user', 'AuthController@show');
                $router->put('auth/user/info', 'AuthController@updateUserInfo');
                $router->put('auth/user/password', 'AuthController@updatePassword');
                $router->post('auth/user/avatar', 'AuthController@updateAvatar');
                $router->get('auth/logout', 'AuthController@logout');
                // 管理员
                $router->get('users', ['as' => 'admin.admin.index', 'uses' => 'AdminUsersController@index']);
                $router->get('users/{id}', ['as' => 'admin.users.show', 'uses' => 'AdminUsersController@show']);
                $router->post('users', ['as' => 'admin.users.store', 'uses' => 'AdminUsersController@store']);
                $router->put('users/{id}', ['as' => 'admin.users.update', 'uses' => 'AdminUsersController@update']);
                $router->delete('users/{id}', ['as' => 'admin.users.destroy', 'uses' => 'AdminUsersController@destroy']);
                // 角色
                $router->get('roles', ['as' => 'admin.roles.index', 'uses' => 'RolesController@index']);
                $router->get('roles/{id}', ['as' => 'admin.roles.show', 'uses' => 'RolesController@show']);
                $router->post('roles', ['as' => 'admin.roles.store', 'uses' => 'RolesController@store']);
                $router->put('roles/{id}', ['as' => 'admin.roles.update', 'uses' => 'RolesController@update']);
                $router->delete('roles/{id}', ['as' => 'admin.roles.destroy', 'uses' => 'RolesController@destroy']);
                // 字典
                $router->get('dict/type', ['as' => 'admin.dict.types.index', 'uses' => 'DictTypesController@index']);
                $router->get('dict/type/{id}', ['as' => 'admin.dict.types.show', 'uses' => 'DictTypesController@show']);
                $router->post('dict/type', ['as' => 'admin.dict.types.store', 'uses' => 'DictTypesController@store']);
                $router->put('dict/type/{id}', ['as' => 'admin.dict.types.update', 'uses' => 'DictTypesController@update']);
                $router->delete('dict/type/{id}', ['as' => 'admin.dict.types.destroy', 'uses' => 'DictTypesController@destroy']);
                // 字典数据
                $router->get('dict/data', ['as' => 'admin.dict.data.index', 'uses' => 'DictDataController@index']);
                $router->get('dict/data/{id}', ['as' => 'admin.dict.data.show', 'uses' => 'DictDataController@show']);
                $router->post('dict/data', ['as' => 'admin.dict.data.store', 'uses' => 'DictDataController@store']);
                $router->put('dict/data/{id}', ['as' => 'admin.dict.data.update', 'uses' => 'DictDataController@update']);
                $router->delete('dict/data/{id}', ['as' => 'admin.dict.data.destroy', 'uses' => 'DictDataController@destroy']);
                $router->get('dict/data/all/{dataType}', 'DictDataController@byDictType');
                // 菜单
                $router->get('menus', ['as' => 'admin.menus.index', 'uses' => 'MenusController@index']);
                $router->get('menus/{id}', ['as' => 'admin.menus.show', 'uses' => 'MenusController@show']);
                $router->post('menus', ['as' => 'admin.menus.store', 'uses' => 'MenusController@store']);
                $router->put('menus/{id}', ['as' => 'admin.menus.update', 'uses' => 'MenusController@update']);
                $router->delete('menus/{id}', ['as' => 'admin.menus.destroy', 'uses' => 'MenusController@destroy']);
                $router->get('menus/tree/select', 'MenusController@treeSelect');
                // 配置
                $router->get('configs', ['as' => 'admin.configs.index', 'uses' => 'ConfigsController@index']);
                $router->get('configs/{id}', ['as' => 'admin.configs.show', 'uses' => 'ConfigsController@show']);
                $router->post('configs', ['as' => 'admin.configs.store', 'uses' => 'ConfigsController@store']);
                $router->put('configs/{id}', ['as' => 'admin.configs.update', 'uses' => 'ConfigsController@update']);
                $router->delete('configs/{id}', ['as' => 'admin.configs.destroy', 'uses' => 'ConfigsController@destroy']);
                $router->get('configs/key/{configKey}', 'ConfigsController@byConfigKey');
                // 日志相关
                $router->get('logs/login', ['as' => 'admin.logs.login.index', 'uses' => 'LoginLogsController@index']);
                $router->delete('logs/login/{ids}', ['as' => 'admin.logs.login.destroy', 'uses' => 'LoginLogsController@destroy']);
                $router->get('logs/login/export/excel', ['as' => 'admin.logs.login.export', 'uses' => 'LoginLogsController@export']);
                $router->get('logs/operation', ['as' => 'admin.logs.operation.index', 'uses' => 'OperationLogsController@index']);
                $router->delete('logs/operation/{ids}', ['as' => 'admin.logs.operation.destroy', 'uses' => 'OperationLogsController@destroy']);
                $router->get('logs/operation/export/excel', ['as' => 'admin.logs.operation.export', 'uses' => 'OperationLogsController@export']);
            });
        });

    }


    /**
     * register laravel routes
     */
    public function laravelRoutes()
    {
        $attributes = [
            'prefix' => config('admin.route.prefix'),
        ];
        app('router')->group($attributes, function ($router) {
            // 登录
            $router->post('auth/login', 'ChuJC\Admin\Controllers\LoginController@login');
            $router->get('auth/login/captcha', 'ChuJC\Admin\Controllers\LoginController@captcha');
            /* @var \Illuminate\Support\Facades\Route $router */
            $router->group([
                'namespace' => 'ChuJC\Admin\Controllers',
                'middleware' => config('admin.route.middleware'),
            ], function ($router) {
                // 个人相关
                $router->get('auth/user', 'AuthController@show')->name('admin.user.show');
                $router->put('auth/user/info', 'AuthController@updateUserInfo')->name('admin.user.updateUserInfo');
                $router->put('auth/user/password', 'AuthController@updatePassword')->name('admin.user.updatePassword');
                $router->post('auth/user/avatar', 'AuthController@updateAvatar')->name('admin.user.updateAvatar');
                $router->get('auth/logout', 'AuthController@logout')->name('admin.user.logout');
                // 管理员
                $router->apiResource('users', 'AdminUsersController')->names('admin.users');
                // 角色
                $router->apiResource('roles', 'RolesController')->names('admin.roles');
                // 字典
                $router->apiResource('dict/type', 'DictTypesController')->names('admin.dict.types');
                // 字典数据
                $router->apiResource('dict/data', 'DictDataController')->names('admin.dict.data');
                $router->get('dict/data/all/{dataType}', 'DictDataController@byDictType');
                // 菜单
                $router->apiResource('menus', 'MenusController')->names('admin.menus');
                $router->get('menus/tree/select', 'MenusController@treeSelect');
                // 配置
                $router->apiResource('configs', 'ConfigsController')->names('admin.configs');
                $router->get('configs/key/{configKey}', 'ConfigsController@byConfigKey');
                // 日志相关
                $router->get('logs/login', 'LoginLogsController@index')->name('admin.logs.login.index');
                $router->delete('logs/login/{ids}', 'LoginLogsController@destroy')->name('admin.logs.login.destroy');
                $router->get('logs/login/export/excel', 'LoginLogsController@export')->name('admin.logs.login.export');
                $router->get('logs/operation', 'OperationLogsController@index')->name('admin.logs.operation');
                $router->delete('logs/operation/{ids}', 'OperationLogsController@destroy')->name('admin.logs.destroy');
                $router->get('logs/operation/export/excel', 'OperationLogsController@export')->name('admin.logs.operation.export');
            });
        });
    }

}
