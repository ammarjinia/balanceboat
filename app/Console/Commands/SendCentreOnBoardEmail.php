<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
class SendCentreOnBoardEmail extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'centreonboard:sendemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Centre On Board Email';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $objCentre = \App\CentreOnboard::whereNull("status")->where("email", "!=", "")->orderBy("id", "ASC")->first();
        if ($objCentre) {
            if (!empty($objCentre->email)) {
                $emailTemplate = DB::table("centre_onboard_email_template")->first();
                $body = str_replace("[CENTRE]", @$objCentre->name, @$emailTemplate->body);
                Mail::send('emails.centreonboard', ["body" => $body], function ($message) use ($objCentre, $emailTemplate) {
                    $message->subject($emailTemplate->subject);
                    $message->to(trim($objCentre->email));
                });

                if (Mail::failures()) {
                    $objCentre->status = "Email Failed";
                    
                } else {
                    $objCentre->status = "Email Sent";
                    
                }
                $objCentre->save();
            }
        }

        
    }

}
