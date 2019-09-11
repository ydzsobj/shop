<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserTraceLog extends Model
{
    protected $table = 'user_trace_logs';

    public $page_size = 20;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'device',
        'lang',
        'referer_url',
        'access_url',
        'good_id',
        'country',
        'area',
        'access_time'

    ];

    public function good(){
        return $this->belongsTo(Good::class)->withDefault();
    }

    /**
     * @param $request
     * @return array
     */
    public function get_data($request){

        list($start_date, $end_date) = recent_thirty_days();

        $start_date = $request->get('start_date') ?: $start_date;
        $end_date = $request->get('end_date') ?: $end_date;

        $per_page = $request->get('per_page') ?: $this->page_size;

        $search = compact('start_date','end_date','per_page');

        $data =  UserTraceLog::with('good')
            ->whereBetween('access_time', [$start_date, $end_date])
            ->orderBy('id', 'desc')
            ->select('*')
            ->paginate($per_page);

        return [$data, $search];
    }
}
