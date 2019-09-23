<?php

namespace App\Providers;

use App\Models\RuleFixed;
use App\Models\RuleFullReduction;
use App\Models\RulePercent;
use Illuminate\Support\ServiceProvider;

use Encore\Admin\Config\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $table = config('admin.extensions.config.table', 'admin_config');
        if (Schema::hasTable($table)) {
            Config::load();
        }

        Relation::morphMap([
            'rule_percents' => RulePercent::class,
            'rule_fixed' => RuleFixed::class,
            'rule_full_reductions' => RuleFullReduction::class,
        ]);
    }
}
