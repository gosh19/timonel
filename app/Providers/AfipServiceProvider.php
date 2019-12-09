<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AfipServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \App::bind( 'Afip');
        \App::bind( 'TokenAutorization');
        \App::bind( 'AfipWebService');
        \App::bind(

'ElectronicBiling',

'App\Libs\Afip\Classe\ElectronicBiling'

);
        //\App::bind( 'ElectronicBiling');
        \App::bind( 'RegisterScopeFive');
        \App::bind( 'RegisterScoperFour');
        \App::bind( 'RegisterScopeTen');
    }
}
