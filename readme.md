<h1 align="center"> VAdmin </h1>

<p align="center"> 项目快速开发后台，采用前后端分离的方式，同时支持Laravel、Lumen 5.5LTS以上版本。</p>

## 说明
可以配合由专用的后台UI使用（稍后上传，正在矫正中）。使用中可以多查看配置项`config/admin.php`，根据需求调整。

Laravel预览地址: (http://laravel.team.hanguosoft.com/admin)

Lumen预览地址: (http://Lumen.team.hanguosoft.com/admin)

账号: `admin` 密码：`123456`

## 功能
- [x] 管理员管理
- [x] 角色管理
- [x] 菜单管理
- [x] 字典管理
- [x] 配置管理
- [x] 日志管理
- [x] 无验证登录、有验证码登录、个人资料修改

## 依赖
- 依赖`tymon/jwt-auth` 做JWT认证 需要先安装 [wiki](https://jwt-auth.readthedocs.io/en/develop)
> 因为版本太多以及适配问题，这个包需要自行使用composer加载 1.0以上版本都可以 [版本选择](https://github.com/tymondesigns/jwt-auth/releases)
- 依赖`laravel-excel` 已经添加 lumen需要自己注册一下 [wiki](https://docs.laravel-excel.com/3.1/getting-started/)


## laravel安装
1. `composer require chujc/v-admin`
2. php artisan vendor:publish --provider="ChuJC\Admin\AdminServiceProvider"
   > 在该命令会生成配置文件config/admin.php，可以在里面修改安装的地址、数据库连接、以及表名，建议都是用默认配置不修改。
3. php artisan admin:install
   > 启动服务后，可以在按接口文档中使用调用对应的接口 ,使用用户名 admin 和密码 123456登录.
   
   
## Lumen安装
1. `composer require chujc/v-admin` 
2. ...
   > 在该命令会生成配置文件config/admin.php，可以在里面修改安装的地址、数据库连接、以及表名，建议都是用默认配置不修改。
3. 在`bootstrap/app.php` 文件中添加
```php
// 打开下面的注释
$app->withFacades();
$app->withEloquent();
// 添加内容
$app->configure('filesystems');
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class); // jwt
$app->register(ChuJC\Admin\AdminServiceProvider::class);
$app->register(Maatwebsite\Excel\ExcelServiceProvider::class); // 因需要导出excel 所以需要添加laravel-excel依赖包,如果不需要导出功能可以不需要
$app->factory('Admin');
$app->factory('Captcha');
```
4. php artisan vendor:publish --provider="ChuJC\Admin\AdminServiceProvider"
   > 在该命令会生成配置文件config/admin.php，可以在里面修改安装的地址、数据库连接、以及表名，建议都是用默认配置不修改。

** 必须先安装`tymon/jwt-auth`**在执行如下命令
5. php artisan admin:install
   > 启动服务后，可以在按接口文档中使用调用对应的接口 ,使用用户名 admin 和密码 123456登录.
5. 在Lumen中注入FormRequest表单验证类 需要在`bootstrap/app.php` 文件中添加 完整之后就可以如同laravel一样使用
   > $app->register(\ChuJC\Admin\Providers\RequestServiceProvider::class);
   > 验证失败 会抛出 `Illuminate\Validation\ValidationException` 异常，如果对格式有要求可以参照如下代码
```php
if ($exception instanceof ValidationException) {
   return Result::failedData($exception->response->original, $exception->getMessage(), 422);
}
```

## 其他扩展
- 脚手架[https://github.com/chujc/laravel-generator](https://github.com/chujc/laravel-generator)
> laravel 与 lumen的脚手架 快速方便的生成 model, controller，RESTFul 路由
- 表单验证扩展[https://github.com/chujc/validation-support](https://github.com/chujc/validation-support)
> laravel 与 lumen的表单验证扩展 其他框架也可以使用 主要包含手机号，密码强度，中文字符，银行卡，身份证🆔等判断

## 其他
项目借鉴[laravel-admin](https://laravel-admin.org/)的思想可以自定义配置model等
