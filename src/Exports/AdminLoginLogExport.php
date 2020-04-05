<?php

namespace ChuJC\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminLoginLogExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    private $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->username,
            $row->ip,
            $row->location,
            $row->login_time,
            $row->status ? '成功' : '失败',
            $row->error,
            $row->os,
            $row->browser,
            $row->http_user_agent,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            '登录账号',
            'IP',
            '地址',
            '登录时间',
            '登录状态',
            '错误信息',
            '操作系统',
            '浏览器',
            'http_user_agent',
        ];
    }
}
