<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'Illuminate\Database\Events\QueryExecuted' => [
            'App\Listeners\QueryListener',
        ],

        'App\Events\BindGoodAttributeEvent' => [
            'App\Listeners\BindGoodAttributeListener',
        ],

        'App\Events\BindProductAttributeEvent' => [
            'App\Listeners\BindProductAttributeListener',
        ],

        //审核订单成功
        'App\Events\AuditOrderSuccessEvent' => [
            'App\Listeners\AuditOrderSuccessListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
