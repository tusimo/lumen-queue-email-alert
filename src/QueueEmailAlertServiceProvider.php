<?php

namespace Tusimo\QueueEmailAlert;

use Illuminate\Bus\Dispatcher;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class QueueEmailAlertServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $config = realpath(__DIR__ . '/config/queue-email-alert.php');

        $this->mergeConfigFrom($config, 'queue-email-alert');

        $this->app->configure('queue-email-alert');

        $config = $this->app->config['queue-email-alert'];

        if ($config['to']) {
            app('queue')->failing(function (JobFailed $jobFailed) use ($config) {
                $html = [
                    'environment' => app()->environment(),
                    'appName' => config('app.name'),
                    'connection' => $jobFailed->connectionName,
                    'queue' => $jobFailed->job->getQueue(),
                    'name' => $jobFailed->job->getName(),
                    'body' => $jobFailed->job->getRawBody(),
                    'exception' => [
                        'message' => $jobFailed->exception->getMessage(),
                        'code' => $jobFailed->exception->getCode(),
                        'file' => $jobFailed->exception->getFile(),
                        'line' => $jobFailed->exception->getLine(),
                        'trace' => $jobFailed->exception->getTraceAsString(),
                    ]
                ];
                if ($config['queue']) {
                    $job = new QueueExceptionJob($html, $config);
                    app(Dispatcher::class)->dispatch($job->onQueue($config['queue']));
                } else {
                    $job = new ExceptionJob($html, $config);
                    app(Dispatcher::class)->dispatch($job);
                }
            });
        }
    }
}
