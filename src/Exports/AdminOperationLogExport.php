<?php

namespace ChuJC\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminOperationLogExport implements FromCollection, WithMapping, WithHeadings
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
            $row->oper_name,
            $row->method,
            $row->url,
            $row->ip,
            $row->location,
            $row->params,
            $row->status,
            $row->result,
            $row->message,
            $row->os,
            $row->browser,
            $row->http_user_agent,
            $row->created_at,
            $row->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            '操作员',
            '请求方式',
            '请求地址',
            'IP',
            '地址',
            '请求参数',
            '反馈状态',
            '消息体',
            '操作系统',
            '浏览器',
            'http_user_agent',
            '请求时间',
            '响应时间',
        ];
    }
}
