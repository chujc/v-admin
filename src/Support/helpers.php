<?php

/**
 * is laravel
 * @return bool
 */
function isLaravel(): bool
{
    return strpos(app()->version(), 'Lumen') !== 0;
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return base_path('config' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/' . trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix . '/' . $path;
    }
}

if (!function_exists('file_size')) {

    /**
     * Convert file size to a human readable format like `100mb`.
     *
     * @param int $bytes
     *
     * @return string
     *
     * @see https://stackoverflow.com/a/5501447/9443583
     */
    function file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists("arrayToTree")) {
    /**
     * @param array $list
     * @param string $pk
     * @param string $pid
     * @param string $children
     * @param int $root
     * @return array
     * @author john_chu <john1668@qq.com>
     */
    function arrayToTree(array $list, $pk = 'id', $pid = 'parent_id', $children = 'children', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$children][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

/**
 *
 * @param array $tree
 * @return array
 * @author john_chu
 */
function menuTreeToRouter(array $tree)
{

    $router = [];

    foreach ($tree as $item => $value) {
        if ($value['menu_type'] == 1) {

            $router[$item]['name'] = $value['path'];
            $router[$item]['path'] = $value['path'];

            if (array_key_exists('children', $value) && is_array($value['children'])) {
                $router[$item]['path'] = '/' . $value['path'];
                $router[$item]['component'] = 'Layout';
                $router[$item]['redirect'] = 'noRedirect';
                $router[$item]['alwaysShow'] = true;
                $router[$item]['children'] = menuTreeToRouter($value['children']);
            } else {
                $router[$item]['component'] = $value['component'];
            }

            // 如果是外链
            if ($value['is_link']) {
                $router[$item]['name'] = $value['menu_name'];
                $router[$item]['component'] = 'Layout';
            }

            if ($value['is_visible'] === 1) {
                $router[$item]['hidden'] = false;
            } else {
                $router[$item]['hidden'] = true;
            }

            $meta = new stdClass();
            $meta->title = $value['menu_name'];
            $meta->icon = $value['icon'];
            $router[$item]['meta'] = $meta;
        }
    }

    return $router;
}

/**
 * @param array $data
 * @param array $rules
 * @param array $messages
 * @param array $customAttributes
 * @return mixed
 * @throws \ChuJC\Admin\Exceptions\ValidaException
 * @throws \Illuminate\Contracts\Container\BindingResolutionException
 * @author john_chu
 */
function valida(array $data, array $rules, array $messages = [], array $customAttributes = [])
{
    $factory = app('validator')->make($data, $rules, $messages, $customAttributes);
    if ($factory->fails()) {
        $messages = $factory->errors()->first();
        throw new \ChuJC\Admin\Exceptions\ValidaException($messages);
    }
    return $factory;
}


/**
 * @return mixed|string
 */
function getOS()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $os = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $os)) {
            $os = 'Windows';
        } else if (preg_match('/mac/i', $os)) {
            $os = 'MAC';
        } else if (preg_match('/linux/i', $os)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $os)) {
            $os = 'Unix';
        } else if (preg_match('/bsd/i', $os)) {
            $os = 'BSD';
        } else {
            $os = 'Other';
        }
        return $os;
    } else {
        return 'unknow';
    }
}

/**
 * 获得访问者浏览器
 */
function getBrowseInfo()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $br = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $br)) {
            $br = 'MSIE';
        } else if (preg_match('/Firefox/i', $br)) {
            $br = 'Firefox';
        } else if (preg_match('/Chrome/i', $br)) {
            $br = 'Chrome';
        } else if (preg_match('/Safari/i', $br)) {
            $br = 'Safari';
        } else if (preg_match('/Opera/i', $br)) {
            $br = 'Opera';
        } else {
            $br = 'Other';
        }
        return $br;
    } else {
        return 'unknow';
    }
}

/**
 *
 * @param \Illuminate\Database\Eloquent\Model $model
 * @param array $params
 * @param array $whereField
 * @return \Illuminate\Database\Eloquent\Builder|mixed
 */
function searchModelField($model, array $params, array $whereField)
{
    if ($model instanceof \Illuminate\Database\Eloquent\Model || $model instanceof \Illuminate\Database\Eloquent\Builder) {
        foreach ($whereField as $item => $value) {
            $model = $model->when(isset($params[$item]), function ($query) use ($params, $item, $value) {
                switch ($value) {
                    case 'in':
                        if (is_array($params[$item])) {
                            $query->whereIn($item, $params[$item]);
                        }
                        break;
                    case 'not_in':
                        $query->whereNotIn($item, $params[$item]);
                        break;
                    case 'null':
                        $query->whereNull($item);
                        break;
                    case 'not_null':
                        $query->whereNotNull($item);
                        break;
                    default:
                        if (is_array($params[$item])) {
                            $query->where($item, 'in', $params[$item]);
                        } else if (is_string($params[$item]) && in_array($value, ['=', '!=', '>=', '<=', 'like'])) {
                            $query->where($item, $value, $params[$item]);
                        }
                }
            });
        }
    }
    return $model;
}

/**
 * @param $model
 * @param array $params
 * @param string $field
 * @param string $beginTime
 * @param string $endTime
 * @param string $timeFormat
 * @return \Illuminate\Database\Eloquent\Builder|mixed
 */
function searchModelDateRange($model, array $params, $field = 'created_at', $beginTime = 'beginTime', $endTime = 'endTime', $timeFormat = 'Y-m-d')
{
    if ($model instanceof \Illuminate\Database\Eloquent\Model || $model instanceof \Illuminate\Database\Eloquent\Builder) {
        $model = $model->when(isset($params[$beginTime]), function ($query) use ($params, $beginTime, $field, $timeFormat) {
            if ($timeFormat == 'Y-m-d') {
                $query->whereDate($field, '>=', date($timeFormat, strtotime($params[$beginTime])));
            } else {
                $query->where($field, '>=', date($timeFormat, strtotime($params[$beginTime])));
            }
        })->when(isset($params[$endTime]), function ($query) use ($params, $endTime, $field, $timeFormat) {
            if ($timeFormat == 'Y-m-d') {
                $query->whereDate($field, '<=', date($timeFormat, strtotime($params[$endTime])));
            } else {
                $query->where($field, '<=', date($timeFormat, strtotime($params[$endTime])));
            }
        });
    }
    return $model;
}

/**
 * 获取完整table名称
 * @param string $table
 * @return string
 */
function fullTableName(string $table): string
{
    $database = config('database');
    if (isset($database['connections'][$database['default']]['prefix'])) {
        return $database['connections'][$database['default']]['prefix'] . $table;
    }
    return $table;
}
