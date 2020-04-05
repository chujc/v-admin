<?php

namespace ChuJC\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Admin
 * @package ChuJC\Admin\Facades
 * @author john_chu
 * @version 2020/3/9
 *
 * @method static \ChuJC\Admin\Admin user()
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ChuJC\Admin\Admin::class;
    }
}
