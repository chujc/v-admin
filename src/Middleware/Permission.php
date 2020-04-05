<?php

namespace ChuJC\Admin\Middleware;

use Carbon\Carbon;
use ChuJC\Admin\Exceptions\UnauthorizedException;
use ChuJC\Admin\Facades\Admin;
use ChuJC\Admin\Models\AdminMenu;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Route;

class Permission
{

    public function handle($request, \Closure $next)
    {
        /**
         * 获取路由名称，先判断路由名称是否在权限列表中定义，如果没有定义直接放行
         */
        if (isLaravel()) {
            $routeName = Route::currentRouteName();
        } else {
            if (!array_key_exists('as', $route = $request->route()[1])) {
                return $next($request);
            }
            $routeName = $request->route()[1]['as'];
        }

        if ($this->hasRouteName($routeName)) {
            return $next($request);
        }

        /**
         * 判断用户是否有对应路由权限，有则放行
         */
        if (Admin::user()->can($routeName)) {
            return $next($request);
        }

        throw new UnauthorizedException($routeName);
    }

    /**
     * 判断是否有路由名称对应的权限
     * @param $routeName
     * @return bool
     */
    private function hasRouteName($routeName)
    {
        $menuModel = config('admin.database.menus_model', AdminMenu::class);

        if (config('admin.cache.permission')) {
            $adminMenu = Cache::remember(
                config('admin.cache.menus.key', 'admin.menus.permission'),
                Carbon::now()->addSeconds(config('admin.cache.menus.ttl', 600)),
                function () use ($menuModel) {
                    return $menuModel::where('permission', '!=', '')->get('permission');
                });

            if (!$adminMenu->where('permission', $routeName)->first()) {
                return true;
            }
        } else {
            if (!$menuModel::wherePermission($routeName)->exists()) {
                return true;
            }
        }

        return false;
    }
}
