<?php

namespace Tabatii\LocalMail\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Tabatii\LocalMail\Mail\Fake;

class SendFakeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localmail:fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a fake email using the default mailer.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mail::to(fake()->unique()->safeEmail())->send(new Fake);
        return $this->info('Your email has been sent successfully.');
    }
}
