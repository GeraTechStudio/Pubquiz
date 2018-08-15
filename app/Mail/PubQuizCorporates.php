<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PubQuizCorporates extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info)
    {   
         $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $this->view('CorporateMail')->with([
            'id'=>$this->info->id,
            'name'=>$this->info->name,
            'email'=>$this->info->email,
            'tel'=>$this->info->tel,
            'region'=>$this->info->region,
            'date'=>$this->info->created_at,
        ]);
    }
}
