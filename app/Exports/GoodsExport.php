<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoodsExport implements FromCollection,WithHeadings
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
            '商品ID',
            '单品名',
            '单品展示名',
            '商品原价',
            '商品现价',
            '商品描述',
            '发布人',
            '发布时间',
            '商品视频地址'
        ];
    }
}
