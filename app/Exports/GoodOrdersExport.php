<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet ;

class GoodOrdersExport implements FromCollection,WithHeadings,withEvents
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
            '审核时间',
            '订单sn',
            '收件人',
            '收货地邮编',
            '收货人电话',
            '收件省份',
            '收件城市',
            '收件地区',
            '收件详细地址',
            '代收货款',
            'SKUID',
            '备注',
            '中文品名',
            '英文品名',
            '件数',
            '物品描述',
            '所属人',
            '审核状态',
            '付款方式',
            '客服备注',

        ];
    }

    public function registerEvents(): array
    {
        $num = count($this->export_data) + 1;
        $cell_num = 'A1:P'.$num;
        return [
            AfterSheet::class  => function(AfterSheet $event) use ($cell_num) {
                $event->sheet->getDelegate()->getStyle($cell_num)->getAlignment()->setVertical('center')->setWrapText(true);
                $event->sheet->autoSize();

            }
        ];
    }
}
