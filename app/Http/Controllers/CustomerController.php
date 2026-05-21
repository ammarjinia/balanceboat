<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Mail;

class CustomerController extends Controller {

    public $customerMessage = 1;
    public $organizerMessage = 2;

    public function inquiry($conversationid = '') {
        $customer = new Customer();
        $mixConverseId = explode('-', $conversationid);
        $guestType = $mixConverseId[1];
        $conversationid = $mixConverseId[0];
        $experienceId = $customer->getExpID($conversationid);
        $customerMessage = $customer->getMessage($conversationid);
        if ($experienceId && $customerMessage) {
            return view('customer', compact('customerMessage', 'guestType', 'experienceId', 'conversationid'));
        } else {
            return redirect("/");
        }
        
    }

    public function savemessage() {
        $data['message'] = request()->input('message');
        $data['experience_id'] = request()->input('experience_id');
        $data['message_type'] = request()->input('message_type');
        $data['conversationId'] = request()->input('conversationId');

        $objComments = new \App\Customer();
        $objComments->message = $data['message'];
        $objComments->conversation_id = $data['conversationId'];
        $objComments->experience_id = $data['experience_id'];
        $objComments->message_type = $data['message_type'];
        $objComments->save();
        $this->sendNotifications($data);
        return redirect()->back()->with('flash_message', 'Message sent!');
    }

    public function sendNotifications($data) {

        $customer = new Customer();
        $expInfo = $customer->getExpID($data['conversationId']);
        $customerInfo = $customer->getCustomerInfo($data['conversationId']);
        if ($data['message_type'] == $this->organizerMessage) {
            $name = $expInfo->name;
            $replyIdforcustomer = $data['conversationId'] . '-1';
            $email = $customerInfo->email; //die;
            try {
                Mail::send('emails.inquiry.reply', ['name' => @$name, 'bodymessage' => @$data['message'], 'conversation' => @$replyIdforcustomer], function ($message) use($email) {
                    $message->subject("Inquiry");
                    $message->from('support@balanceboat.com');
                    $message->to(@$email);
                });
            } catch (Exception $ex) {
                
            }
        } else {

            $name = $customerInfo->name . ' ' . $customerInfo->lastname;
            $replyIdforcenter = $data['conversationId'] . '-2';
            $email = $expInfo->email_address;
            try {
                Mail::send('emails.inquiry.reply', ['name' => @$name, 'bodymessage' => @$data['message'], 'conversation' => @$replyIdforcenter], function ($message) use($email) {
                    $message->subject("Inquiry");
                    $message->from('support@balanceboat.com');
                    $message->to(@$email);
                });
            } catch (Exception $ex) {
                
            }
        }

        try {
            Mail::send('emails.inquiry.reply', ['name' => @$name, 'bodymessage' => @$data['message'], 'conversation' => @$replyIdforcenter], function ($message) use($email) {
                $message->subject("Inquiry");
                $message->from('support@balanceboat.com');
                $message->to('support@balanceboat.com', 'Balanceboat');
            });
        } catch (Exception $ex) {
            
        }
    }

}
