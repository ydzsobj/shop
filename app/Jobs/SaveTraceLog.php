<?php

namespace App\Jobs;

use App\Models\UserTraceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use itbdw\Ip\IpLocation;

class SaveTraceLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $req;

    public $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($req, $ip)
    {
        $this->req = $req;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ip_info = IpLocation::getLocation($this->ip);

        if($ip_info){
            $country = $ip_info['country'] ?? '';
            $area = $ip_info['area'] ?? '';
            $province = $ip_info['province'] ?? '';
            $city = $ip_info['city'] ?? '';
        }

        $mod = UserTraceLog::create(array_merge($this->req, compact('country','area','province','city')));

        if($mod){
            echo '添加成功，id='. $mod->id ."\n";
        }

    }
}
