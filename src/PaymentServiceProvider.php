<?php


namespace Faza13\Payment;


use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(){
        $this->publishes([
            __DIR__ . '/config/payment.php' => $this->app->basePath('config/payment.php'),
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/otp.php', 'otp');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->singleton(OTPInterface::class, function ($app) {
            switch ($app->make('config')->get('otp.default')) {
                case 'firebase':
                    return new OTPFirebase($app->make('config')->get('otp.firebase.api_key'));
                default:
                    throw new \RuntimeException("Unknown Stock Checker Service");
            }
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['otp'];
    }
}
