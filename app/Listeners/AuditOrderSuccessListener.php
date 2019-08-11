<?php

namespace App\Listeners;

use App\Events\AuditOrderSuccessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuditOrderSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AuditOrderSuccessEvent  $event
     * @return void
     */
    public function handle(AuditOrderSuccessEvent $event)
    {
        $order = $event->order;
        $remark = $event->remark;

        $order->audit_logs()->create([
            'admin_user_id' => $order->last_audited_admin_user_id,
            'status' => $order->status,
            'remark' => $remark
        ]);

    }
}
