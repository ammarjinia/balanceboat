<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Customer extends Model {

    protected $table = 'customer_message';

    public function getMessage($conversationid = '') {
        $result = DB::table('customer_message')
                ->join('inquiries', 'inquiries.conversation_id', '=', 'customer_message.conversation_id')
                ->select('customer_message.*', 'inquiries.name', 'inquiries.lastname')
                ->where('customer_message.conversation_id', '=', $conversationid)
                ->orderBy('customer_message.created_at', 'DESC')
                ->get();
        return $result;
    }

    public function getExpID($conversationid = '') {
        $result = DB::table('customer_message')
                ->join('experiences', 'experiences.id', '=', 'customer_message.experience_id')
                ->join('centers', 'centers.id', '=', 'experiences.center_id')
                ->select('customer_message.experience_id', 'experiences.name', 'centers.address_of_center', 'centers.email_address', 'customer_message.message_type')
                ->where('customer_message.conversation_id', '=', $conversationid)
                ->first();
        return $result;
    }

    public function getCustomerInfo($conversationid = '') {
        $result = DB::table('customer_message')
                ->join('inquiries', 'inquiries.conversation_id', '=', 'customer_message.conversation_id')
                ->select('inquiries.name', 'inquiries.lastname', 'inquiries.email')
                ->where('customer_message.conversation_id', '=', $conversationid)
                ->first();
        return $result;
    }

}
