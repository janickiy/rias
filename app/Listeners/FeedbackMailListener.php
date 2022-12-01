<?php

namespace App\Listeners;

use App\Events\FeedbackMailEvent;
use App\Mail\FeedbackMailer;
use Illuminate\Support\Facades\Mail;
use App\Helpers\{SettingsHelper};

class FeedbackMailListener {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        // .....
    }

    /**
     * Handle the event.
     *
     * @param  FeedbackMailEvent  $event
     * @return void
     */
    public function handle(FeedbackMailEvent $event) {
        Mail::to(SettingsHelper::getSetting('EMAIL'))->send(new FeedbackMailer($event->data));
    }
}
