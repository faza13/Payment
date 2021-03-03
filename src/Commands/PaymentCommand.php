<?php

namespace Faza13\Payment\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PaymentCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'payment:traits';

    /**
     * @var string
     */
    protected $description = 'Create trait for orders to make payment';

    /**
     * @var string
     */
    protected $stub = __dir__ .'/stubs/order_payment.stub';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new notifications table command instance.
     *

     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $path = app_path(Config::get('payment.stubs.path'));
//        $this->files->put($path, $this->files->get($this->stub));
        $fullpath = $path . "/OrderPayment.php";
//
        if(!$this->files->exists($path))
          $this->files->makeDirectory($path, 0755, true);

        $this->files->put($fullpath, $this->files->get($this->stub));

        $content = file_get_contents($fullpath);

//        str_replace('{{namespage}}', $content);

        file_put_contents($path, $content);

//
//
//        $this->info($path);

//        $this->files->put($fullpath, $this->files->get($this->stub));

//        $content = file_get_contents($path);

        // Update the file content with additional data (regular expressions)


        $this->info('Traits Imported');

//        $this->composer->dumpAutoloads();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . str_replace('/', '\/', Config::get('payment.stubs.path'));
    }
}
