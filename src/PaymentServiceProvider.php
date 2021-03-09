<?php


namespace Faza13\Payment;


//use Faza13\Payment\Contracts\PaymentInterface;
use Faza13\Payment\Commands\PaymentCommand;
use Faza13\Payment\Contracts\PaymentInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(){

        $this->mergeConfigFrom(__DIR__ . '/../config/payment.php', 'payment');


        $this->publishes([
            __DIR__ . '/../config/payment.php' => $this->app->basePath('config/payment.php'),
        ]);

        $this->app->bind('payment', PaymentInterface::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                PaymentCommand::class,
            ]);
        }

//        $this->app->singleton(PaymentInterface::class, function ($app) {
//            return new PaymentManager($app);
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentInterface::class, function ($app) {
            return new PaymentManager($app);
        });

//        $this->app->bind(PaymentInterface::class, PaymentManager::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['payment'];
    }
}
