<?php

namespace ChuJC\Admin\Controllers;

use Carbon\Carbon;
use ChuJC\Admin\Facades\Admin;
use ChuJC\Admin\Facades\Captcha;
use ChuJC\Admin\Models\AdminLoginLog;
use ChuJC\Admin\Models\AdminUser;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController
{

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $adminLoginLogModel;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.users_model', AdminUser::class);
        $this->adminLoginLogModel = config('admin.database.login_logs_model', AdminLoginLog::class);
        $this->request = $request;
    }

    /**
     * 登录
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function login()
    {
        $rule = [
            'username' => 'required',
            'password' => 'required'
        ];
        if (config('admin.captcha.login')) {
            $rule['code'] = 'required';
            $rule['uuid'] = 'required';
        }

        valida($this->request->all(), $rule, [
            'username.required' => '请输入账号',
            'password.required' => '请输入密码',
            'code.required' => '请输入验证码',
        ]);
        $username = $this->request->get('username');
        $password = $this->request->get('password');

        if (config('admin.captcha.login')) {
            $uuid = $this->request->get('uuid');
            $code = $this->request->get('code');
            if (!Captcha::check($code, $uuid)) {
                $error = "验证码错误";
                $this->loginLogs($username, 0, $error);
                return Result::failed($error);
            }
        }

        $user = $this->model::where('username', $username)->first();
        $error = "账号密码错误";
        if ($user) {
            if ($user->status == 1) {
                if (Hash::check($password, $user->password)) {
                    $this->loginLogs($username);
                    $user->update([
                        'login_ip' => $this->request->getClientIp(),
                        'login_date' => Carbon::now(),
                    ]);
                    return Result::data([
                        'token' => Admin::guard()->login($user)
                    ], '登录成功');
                }
            } else {
                $error = "账号已被禁止使用，请联系管理员处理";
            }
        }
        $this->loginLogs($username, 0, $error);

        return Result::failed($error);
    }

    /**
     * 获取验证码
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function captcha()
    {
        if (config('admin.captcha.login')) {
            $captcha = Captcha::create();
            return Result::data($captcha);
        }

        return Result::data([
            'uuid' => null,
            'image' => null
        ]);
    }

    /**
     * 登录日志记录
     * @param string $username
     * @param int $status
     * @param string $error
     */
    private function loginLogs(string $username, $status = 1, string $error = '')
    {

        $ip2region = new \Ip2Region();
        try {
            $info = $ip2region->btreeSearch($this->request->getClientIp());
        } catch (\Exception $exception) {
            report($exception);
            $info['region'] = '未知地址';
        }

        $adminLoginLog = [
            'username' => $username,
            'ip' => $this->request->getClientIp(),
            'location' => $info['region'],
            'login_time' => Carbon::now(),
            'browser' => getBrowseInfo(),
            'os' => getOS(),
            'http_user_agent' => $this->request->server('HTTP_USER_AGENT'),
            'status' => $status,
            'error' => $error
        ];

        $this->adminLoginLogModel::create($adminLoginLog);
    }

}
