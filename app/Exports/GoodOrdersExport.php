<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoodOrdersExport implements FromCollection,WithHeadings
{

    protected $export_data;

    function __construct($data)
    {
        $this->export_data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return $this->export_data;
    }

    public function headings(): array
    {
        return [
            '下单时间',
            '最后审核时间',
            '订单sn',
            '总价格',
            '收货人',
            '收货人电话',
            '收货人邮箱',
            '省市区',
            '详细地址',
            '留言',
            '客服备注',
            '审核状态',
            '付款方式',
            'SKU信息'
        ];
    }
}
