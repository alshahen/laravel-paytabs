<?php


namespace Alshahen\Paytabs;


use Illuminate\Support\ServiceProvider;

class PaytabsServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }


    public function register()
    {
        $this->app->bind('paytabs', function(){
            return new Paytabs(config('paytabs.paytabs_serverkey'));
        });
    }
}