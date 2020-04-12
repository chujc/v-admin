<?php

return [

    /*
    |--------------------------------------------------------------------------
    | route settings
    |--------------------------------------------------------------------------
    */
    'route' => [

        'prefix' => env('ADMIN_ROUTE_PREFIX', 'api/admin'),

        'namespace' => 'App\\Admin\\Controllers',
        // lumen没有中间件组所以必须完整展示中间件名称，laravel可以直接使用 'admin'
        'middleware' => ['admin.auth', 'admin.permission', 'admin.operationLogs'],
    ],

    /*
    |--------------------------------------------------------------------------
    | install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Admin'),

    /*
    |--------------------------------------------------------------------------
    | auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all admin pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'guard' => 'admin',

        'guards' => [
            'admin' => [
                'driver' => 'jwt',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => ChuJC\Admin\Models\AdminUser::class,
            ],
        ]
    ],

    'captcha' => [
        // 登录是否开启
        'login' => true,
        //验证码位数
        'length' => 4,
        // 验证码字符集合
        'codeSet' => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码过期时间
        'expire' => 1800,
        // 是否使用算术验证码
        'math' => true,
        // 字体文件夹 绝对路径 不设置使用默认
        'fontsDirectory' => null,
        // 字体 只能设置字体文件夹中的字体
        'font' => null,
        //验证码字符大小
        'fontSize' => 20,
        // 是否使用混淆曲线
        'useCurve' => true,
        //是否添加杂点
        'useNoise' => true,
        //背景颜色
        'bg' => [200, 250, 250],
        // 验证码图片高度
        'imageH' => 40,
        // 验证码图片宽度
        'imageW' => 120,
    ],

    // 系统内置 是否可以删除 true可以，false不可以
    'system' => [
        'config' => true,
        'dict_type' => false,
        'dict_data' => false
    ],

    // 缓存
    'cache' => [
        // 是否开启权限缓存
        'permission' => true,
        // 菜单新增，编辑更新，删除会更新权限缓存
        'menus' => [
            'key' => 'admin.menus.permission',
            // 单位秒
            'ttl' => 600,
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | upload setting
    |--------------------------------------------------------------------------
    |
    | File system configuration for form upload files and images, including
    | disk and upload path.
    |
    */
    'upload' => [
        // Disk in `config/filesystem.php`.
        'disk' => 'admin',
        'directory' => [
            'avatar' => 'avatars',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for v-admin builtin model & tables.
    |
    */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => ChuJC\Admin\Models\AdminUser::class,

        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => ChuJC\Admin\Models\AdminRole::class,

        // Menu table and model.
        'menus_table' => 'admin_menus',
        'menus_model' => ChuJC\Admin\Models\AdminMenu::class,

        // Config table and model.
        'configs_table' => 'admin_configs',
        'configs_model' => ChuJC\Admin\Models\AdminConfig::class,

        // DictType table and model.
        'dict_types_table' => 'admin_dict_types',
        'dict_types_model' => ChuJC\Admin\Models\AdminDictType::class,

        // DictData table and model.
        'dict_data_table' => 'admin_dict_data',
        'dict_data_model' => ChuJC\Admin\Models\AdminDictDatum::class,

        // Login Log table and model.
        'login_logs_table' => 'admin_login_logs',
        'login_logs_model' => ChuJC\Admin\Models\AdminLoginLog::class,

        // Login Log table and model.
        'operation_logs_table' => 'admin_operation_logs',
        'operation_logs_model' => ChuJC\Admin\Models\AdminOperationLog::class,

        // Pivot table for table above.
        'user_role_table' => 'admin_user_role',
        'role_menu_table' => 'admin_role_menu',
    ],

    /*
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close operation log in laravel-admin.
    |
    */
    'operation_log' => [
        'enable' => true,
        'result_log' => true,
        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            'api/admin/logs*',
        ],
    ],
    // 后续规划扩展配置
    'extensions' => [
        'tools' => [

        ]
    ]

];
