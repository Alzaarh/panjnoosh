<?php

namespace App\Jobs;

use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;

class SendContactUsEmailToAdmin extends Job {
    private $contactUsInfo;
    public function __construct(array $contactUsInfo) {
        $this->contactUsInfo = $contactUsInfo;
    }
    public function handle() {
        //Todo -- send mail
        Mail::to($contactUsInfo['adminEmail'])->send();
    }
}
