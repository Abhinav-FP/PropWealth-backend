<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportReady extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $suburb;
    public $filePath;
    public $filename;

    public function __construct($user, $suburb, $filePath, $filename)
    {
        $this->user = $user;
        $this->suburb = $suburb;
        $this->filePath = $filePath;
        $this->filename = $filename;
    }

    public function build()
    {
        return $this->subject('Your SuburbTrends Report is Ready!')
            ->view('emails.report-ready')
            ->attach($this->filePath, [
                'as' => $this->filename,
                'mime' => 'application/pdf',
            ]);
    }
}
