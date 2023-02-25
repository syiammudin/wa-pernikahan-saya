<?php

namespace App\Jobs;

use App\Models\logMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendBoxJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $user, $number, $message, $name, $text;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $number, $message, $name, $text)
    {
        $this->user = $user;
        $this->number = $number;
        $this->message = $message;
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::post(env('URL_WA_SERVER') . '/chats/send?id=' . $this->user->name, [
            'receiver' => $this->number,
            'message' => $this->message
        ]);

        $res = json_decode($response->getBody());
        print($res->success);

        $data = [
            'status' => $res->success,
            'nomor' => $this->number,
            'message' => $this->text,
            'nama' => $this->name,
            'user_id' => $this->user->id
        ];

        logMessage::create($data);
    }
}
