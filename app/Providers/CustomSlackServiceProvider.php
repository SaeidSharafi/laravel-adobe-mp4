<?php

namespace App\Providers;

use App\Notifications\AlertDevInSlackNotification;
use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class CustomSlackServiceProvider extends ServiceProvider
{
    private string $path;
    private string $level;
    private array $context;
    private string $message;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            //listen to all events
            Event::listen('*', function ($event, $details) {
                //check if the event message logged event which is triggered when we call Log::<level>
                if ("Illuminate\Log\Events\MessageLogged" === $event) {
                    // dump($event);
                    //$details contain all the information we need and it comes in array of object
                    foreach ($details as $detail) {
                        //check if the log level is from error to emergency
                        if (in_array($detail->level, ['emergency', 'critical', 'alert', 'error'])) {
                            //@todo - exclude: Error while reading line from the server. [tcp://cache:6379] = restart

                            //check if the context has exception and is an instance of exception
                            //This is to prevent: "Serialization of 'Closure' is not allowed" which prevents jobs from being pushed to the queue
                            if (isset($detail->context['exception'])) {
                                if ($detail->context['exception'] instanceof Exception) {
                                    $this->level = $detail->level;
                                    //to keep consistency on all the log message, putting the filename as the header
                                    $this->message         = $detail->context['exception']->getFile();
                                    $this->context['user'] = auth()->check() ? auth()->user()->id . ' - '
                                        . auth()->user()->first_name . ' ' . auth()->user()->last_name : null;
                                    $this->context['message']   = $detail->context['exception']->getMessage();
                                    $this->context['line']      = $detail->context['exception']->getLine();
                                    $this->context['path']      = request()->path();
                                    $this->context['exception'] = '```' .
                                        Str::limit($detail->context['exception']->getTraceAsString(), '300')
                                        . '```';
                                    $this->runNotification();
                                    continue;
                                }
                            }

                            $this->level   = $detail->level;
                            $this->context = $detail->context;
                            $this->message = $detail->message;

                            $this->runNotification();
                        }
                    }
                }
            });
        } catch (Exception $e) {
            report($e);
        }
    }

    public function runNotification()
    {
        Notification::route('slack', config('logging.slack_web_hook'))
            ->notify(new AlertDevInSlackNotification($this->message, $this->level, $this->context));
    }
}
