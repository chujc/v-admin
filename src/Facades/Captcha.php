<?php

namespace ChuJC\Admin\Facades;

use Illuminate\Support\Facades\Facade;


class Captcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ChuJC\Admin\Support\Captcha::class;
    }
}
