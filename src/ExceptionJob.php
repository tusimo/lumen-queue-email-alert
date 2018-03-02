<?php

namespace Tusimo\QueueEmailAlert;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ExceptionJob
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $html;

    protected $config;

    public function __construct($html, $config)
    {
        $this->html = $html;
        $this->config = $config;
    }

    public function handle()
    {
        $html = $this->html;
        $subject = '[' . $html['environment'] . ']' .
            $html['appName'] . ':' . $html['queue'] .
            "队列出错了哦";
        Mail::raw(var_export($html, true), function ($mail) use ($subject) {
            $to = is_array($this->config['to']) ? $this->config['to'] : [$this->config['to']];
            $mail->to($to)->subject($subject);
        });
    }
}