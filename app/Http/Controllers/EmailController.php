<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Auth;
use Session;
use Mail;
use App\Libraries\Akismet;

class EmailController extends Controller
{

    public function __construct()
    {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return redirect("/");
        //return view('posts.index', compact('posts'));
    }

    /**
     * Send Register your Business Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_register_your_business_email(Request $request)
    {
        $this->validate($request, [
            'business_name' => 'required',
            'business_email' => 'required|email',
            'business_website' => 'required|url'
        ]);

        $business_name = $request->has('business_name') ? $request->input('business_name') : "";
        $business_email = $request->has('business_email') ? $request->input('business_email') : "";
        $business_website = $request->has('business_website') ? $request->input('business_website') : "";

        if (filter_var($business_email, FILTER_VALIDATE_EMAIL)) {

            try {
                Mail::send('emails.register_your_business', ['business_name' => @$business_name, 'business_email' => @$business_email, 'business_website' => @$business_website], function ($message) use ($business_email) {
                    $message->subject("Register your Business");
                    $message->from(@$business_email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                    //$message->to('kunal@balanceboat.com', 'Balanceboat');
                });

                Mail::send('emails.register_your_business_response', ['business_name' => @$business_name, 'business_email' => @$business_email, 'business_website' => @$business_website], function ($message) use ($business_email) {
                    $message->subject("Register your Business");
                    $message->from('zen@balanceboat.com', 'Balanceboat');
                    $message->to(@$business_email);
                });
            } catch (Exception $ex) {
                return redirect("/contact-us")->with('flash_business_error_message', 'Something went wrong');
            }
        }
        return redirect("/contact-us")->with('flash_business_message', 'Your request has been successfully sent. We will contact you very soon!');
        exit;
    }

    /**
     * Send Contact Us Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_contact_us_email(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha'
        ], ['g-recaptcha-response.required' => "The Captcha field is required."]);

        $name = $request->has('name') ? $request->input('name') : "";
        $email = $request->has('email') ? $request->input('email') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $content = $request->has('message') ? $request->input('message') : "";
        $ref_url = url()->previous();

        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = '';
        $objComments->email = $email;
        $objComments->phone = $phone;
        $objComments->message = $content;
        $objComments->conversation_id = '';
        $objComments->source = "send_contact_us_email";
        $objComments->ref_url = $ref_url;
        $objComments->save();

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {

                $waData = array(
                    "name" => $objComments->name ? $objComments->name : 'Send Contact Us',
                    "phone" => $objComments->phone,
                    "email" => $objComments->email ? $objComments->email : "contact@balanceboat.com",
                    "notes" => $objComments->message ? $objComments->message : 'Contact Us Notes',
                    'Source' => "I want to know more about the retreat and the best offers " . @$ref_url
                );
                (new \App\Services\WaService())->sendMessage($waData);

                //ActiveCampaign Start
                $eventData = array(
                    "firstName" => $waData['name'],
                    "lastName" => '',
                    "phone" => $waData['phone'],
                    "source" => @$ref_url
                );
                (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, ["Lead: New"]);
                //Active Campaign End

                //$akismet = new Akismet();
                //if(!$akismet->check($request->all())) {
                Mail::send('emails.contactus', ['name' => @$name, 'email' => @$email, 'phone' => @$phone, 'bodymessage' => @$content, 'ref_url' => @$ref_url], function ($message) use ($email) {
                    $message->subject("Contact Us From BalanceBoat");
                    $message->from($email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                });
                //}
            } catch (Exception $ex) {
                return redirect("/contact-us#contact_us")->with('flash_error_message', 'Something went wrong');
            }
        }
        return redirect("/contact-us#contact_us")->with('flash_message', 'Your message has been successfully sent. <br />We will contact you very soon!');
        exit;
    }

    /**
     * Send Inquiry Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_inquiry_email(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|confirmed'
        ]);

        $firstname = $request->has('firstname') ? $request->input('firstname') : "";
        $lastname = $request->has('lastname') ? $request->input('lastname') : "";
        $email = $request->has('email') ? $request->input('email') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $content = $request->has('message') ? $request->input('message') : "";
        $exp_id = $request->has('exp_id') ? $request->input('exp_id') : "";
        if ($request->has('current_url') or $request->input('current_url')) {
            $current_url = $request->has('current_url') ? $request->input('current_url') : "";
        } else {
            $current_url = url()->previous();
        }

        $objComments = new \App\Inquiry();
        $objComments->name = $firstname;
        $objComments->lastname = $lastname;
        $objComments->email = $email;
        $objComments->phone = $phone;
        $objComments->experience_id = @$exp_id;
        $objComments->message = 'Experience:' . $exp_id . '<br />' . $content;
        $objComments->conversation_id = '';
        $objComments->source = "send_inquiry_email";
        $objComments->ref_url = $current_url;
        $objComments->save();

        $objComments->refresh();

        $experience = \App\Experiences::select("id", "name", "slug")->where("id", @$exp_id)->first();
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($experience) && sizeof($experience) > 0) {
            try {

                //$akismet = new Akismet();
                //if(!$akismet->check($request->all())) {
                Mail::send('emails.inquiry', ['firstname' => @$firstname, 'lastname' => @$lastname, 'email' => @$email, 'phone' => @$phone, 'bodymessage' => @$content, 'experience' => @$experience, 'current_url' => @$current_url], function ($message) use ($email) {
                    $message->subject("Inquiry");
                    $message->from(@$email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                });
                //}

                $waData = array(
                    "name" => $objComments->name ? $objComments->name : 'Inquiry',
                    "phone" => $objComments->phone,
                    "email" => $objComments->email ? $objComments->email : "inquiry@balanceboat.com",
                    "notes" => $objComments->message ? $objComments->message : 'Inquiry Notes',
                    'Source' => @$objComments->ref_url
                );
                (new \App\Services\WaService())->sendMessage($waData);

                //ActiveCampaign Start
                $retreat_interest = $experience->name ?? '';
                $preferred_location = $experience->destinations?->pluck('name')->join(', ') ?? '';
                $eventData = array(
                    "firstName" => @$firstname,
                    "lastName" => @$lastname,
                    "phone" => $phone,
                    "source" => @$current_url,
                    "retreat_interest" => @$retreat_interest,
                    "preferred_location" => @$preferred_location
                );
                (new \App\Services\ActiveCampaignService())->trackCartActivity($email, $eventData,  ["Interest: $retreat_interest", "Location: $preferred_location"]);
                //Active Campaign End

            } catch (Exception $ex) {
                return redirect("/experience-inquiry/" . $experience->slug)->with('flash_error_message', 'Something went wrong');
            }
        }
        return redirect("/experience-inquiry/" . $experience->slug)->with('flash_message', 'Thanks for your inquiry. Our team shall get back to you soon!');
        exit;
    }

    /**
     * Send Subscription Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_subscription(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'phone' => 'required'
        ];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $email = request()->input('email');
        $phone = request()->input('phone');
        if (request()->input('ref_url')) {
            $ref_url = request()->input('ref_url');
        } else {
            $ref_url = url()->previous();
        }

        $objSubscriptions = new \App\Subscriptions();
        $objSubscriptions->email = $email;
        $objSubscriptions->phone = $phone;
        $objSubscriptions->save();

        try {
            //$akismet = new Akismet();
            //if(!$akismet->check($request->all())) {
            Mail::send('emails.subscription.admin', [
                'email' => @$email,
                'phone' => @$phone,
                'bodymessage' => @$ref_url,
            ], function ($message) use ($email) {
                $message->subject("lead for Ayurveda retreat via BalanceBoat");
                $message->from(@$email);
                $message->to('zen@balanceboat.com', 'Balanceboat');
                $message->cc('kunal@balanceboat.com', 'Balanceboat');
                $message->cc('shivam@balanceboat.com', 'Balanceboat');
            });
            //}
        } catch (Exception $ex) {
            return "Something went wrong!!";
        }
    }

    /**
     * Send Blog Subscription Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_blog_subscription(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $email = request()->input('email');
        if (url()->previous()) {
            $ref_url = url()->previous();
        } else {
            $ref_url = url()->previous();
        }
        $objSubscriptions = \App\Subscriptions::firstOrNew(array("email" => $email));
        $objSubscriptions->email = $email;
        $objSubscriptions->save();

        try {
            //$akismet = new Akismet();
            //if(!$akismet->check($request->all())) {
            // Send Email to Admin
            Mail::send('emails.subscription.admin', [
                'email' => @$email,
                'phone' => @$phone,
                'bodymessage' => @$ref_url,
            ], function ($message) use ($email) {
                $message->subject("lead for Ayurveda retreat via BalanceBoat");
                $message->from(@$email);
                $message->to('zen@balanceboat.com', 'Balanceboat');
                $message->cc('kunal@balanceboat.com', 'Balanceboat');
                $message->cc('shivam@balanceboat.com', 'Balanceboat');
            });
            // }
        } catch (Exception $ex) {
            return "Something went wrong!!";
        }
    }

    /**
     * Send Chat Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_chat(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'phone' => 'required'
        ];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $name = request()->input('name');
        $email = request()->input('email');
        $country_code = request()->input('country_code');
        $phone = request()->input('phone');
        $bodymessage = request()->input('message');
        if (request()->input('ref_url')) {
            $ref_url = request()->input('ref_url');
        } else {
            $ref_url = url()->previous();
        }


        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = '';
        $objComments->email = $email;
        $objComments->country_code = $country_code;
        $objComments->phone = $phone;
        $objComments->experience_id = null;
        $objComments->message = $bodymessage;
        $objComments->conversation_id = '';
        $objComments->source = "chat_popup";
        $objComments->ref_url = $ref_url;
        $objComments->save();

        $objComments->refresh();

        try {
            $waData = array(
                "name" => $objComments->name ? $objComments->name : 'Chat Popup',
                "phone" => "+" . $objComments->country_code . $objComments->phone,
                "email" => $objComments->email ? $objComments->email : "chatpopup@balanceboat.com",
                "notes" => $objComments->message ? $objComments->message : 'Chat Popup Notes',
                'Source' => "I want to know more about the retreat and the best offers " . @$ref_url
            );
            (new \App\Services\WaService())->sendMessage($waData);

            //ActiveCampaign Start
            $eventData = array(
                "firstName" => $waData['name'],
                "lastName" => '',
                "phone" => $waData['phone'],
                "source" => @$ref_url
            );
            $acTags = ["Lead: New"];
            
            $path = parse_url($ref_url ?? '', PHP_URL_PATH);
            $segments = explode('/', trim((string) $path, '/'));
            $lastSegment = $segments ? end($segments) : '';
            if ($lastSegment) {
                $parts = explode('-', $lastSegment);;
                $eventData['retreat_interest'] = $lastSegment; 
                $acTags[]= "Interest: $lastSegment";
                $preferred_location = $parts ? end($parts) : '';
                $eventData['preferred_location'] = $preferred_location; 
                $acTags[]= "Location: $preferred_location";
            }
            \Log::info(json_encode($eventData));
            (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, $acTags);
            //Active Campaign End

        } catch (Exception $ex) {
            \Log::info("Curl WA Error: " . @$ex->getMessage());
            \Log::info(json_encode($waData));
        }
        try {
            //$akismet = new Akismet();
            //if(!$akismet->check($request->all())) {
            Mail::send('emails.chat.admin', [
                'name' => $name,
                'email' => @$email,
                'country_code' => @$country_code,
                'phone' => @$phone,
                'bodymessage' => @$bodymessage,
                'ref_url' => @$ref_url,
            ], function ($message) use ($email) {
                $message->subject("Greetings from BalanceBoat");
                $message->from(@$email);
                $message->to('zen@balanceboat.com', 'Balanceboat');
                $message->cc('kunal@balanceboat.com', 'Balanceboat');
                $message->cc('shivam@balanceboat.com', 'Balanceboat');
            });
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::send('emails.chat.customer', [
                    'name' => $name,
                    'email' => @$email,
                    'country_code' => @$country_code,
                    'phone' => @$phone,
                    'bodymessage' => @$bodymessage,
                ], function ($message) use ($email) {
                    $message->subject("Greetings from BalanceBoat");
                    $message->to($email);
                });
            }
            // }

        } catch (Exception $ex) {
            return "Something went wrong!!";
        }
    }

    /**
     * Send Contact Us Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_request_call_back(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha'
        ];
        $message = ['g-recaptcha-response.required' => "The Captcha field is required."];
        $validator = validator()->make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $name = $request->has('name') ? $request->input('name') : "";
        $email = $request->has('email') ? $request->input('email') : "";
        $country_code = $request->has('country_code') ? $request->input('country_code') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $inquiry_for = $request->has('inquiry_for') ? $request->input('inquiry_for') : "";
        $subject = $request->has('inquiry_subject') ? $request->input('inquiry_subject') : " BalanceBoat Received your Request for Call Back";
        $content = $request->has('message') ? $request->input('message') : "";
        $exp_id = @$request->has('exp_id') ? @$request->input('exp_id') : "";
        if ($request->input('ref_url')) {
            $ref_url = $request->has('ref_url') ? $request->input('ref_url') : "";
        } else {
            $ref_url = url()->previous();
        }
        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = '';
        $objComments->email = $email;
        $objComments->country_code = @$country_code;
        $objComments->phone = $phone;
        $objComments->experience_id = @$exp_id;
        $objComments->message = 'Experience:' . $exp_id . '<br />Inquiry For:' . $inquiry_for . '<br />Subject:' . $subject . '<br />' . $content;
        $objComments->conversation_id = '';
        $objComments->source = "send_request_call_back";
        $objComments->ref_url = $ref_url;
        $objComments->save();

        $objComments->refresh();

        try {
            $waData = array(
                "name" => $objComments->name ? $objComments->name : 'Request Call Back',
                "phone" => "+" . $objComments->country_code . "-" . $objComments->phone,
                "email" => $objComments->email ? $objComments->email : "rcb@balanceboat.com",
                "notes" => $objComments->message ? $objComments->message : 'RCB Notes',
                'Source' => "I want to know more about the retreat and the best offers " . @$ref_url
            );
            (new \App\Services\WaService())->sendMessage($waData);

            //ActiveCampaign Start
            $eventData = array(
                "firstName" => $waData['name'],
                "lastName" => '',
                "phone" => $waData['phone'],
                "source" => @$ref_url
            );
            $acTags = ["Lead: New"];
            
            $path = parse_url($ref_url ?? '', PHP_URL_PATH);
            $segments = explode('/', trim((string) $path, '/'));
            $lastSegment = $segments ? end($segments) : '';
            if ($lastSegment) {
                $parts = explode('-', $lastSegment);;
                $eventData['retreat_interest'] = $lastSegment; 
                $acTags[]= "Interest: $lastSegment";
                $preferred_location = $parts ? end($parts) : '';
                $eventData['preferred_location'] = $preferred_location; 
                $acTags[]= "Location: $preferred_location";
            }
            \Log::info(json_encode($eventData));
            (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, $acTags);
            //Active Campaign End

        } catch (Exception $ex) {
            \Log::info("Curl WA Error: " . @$ex->getMessage());
            \Log::info(json_encode($waData));
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                //$akismet = new Akismet();
                //if(!$akismet->check($request->all())) {
                $centerInfo = '';
                if (@$exp_id) {
                    $experience = \App\Experiences::select("id", "name", "slug", "center_id")->where("id", @$exp_id)->first();
                    if (@$experience) {
                        if (@$experience->center_id) {
                            $objCenter = \App\Centers::select("name", "email_address", "contact_number")->where("id", @$experience->center_id)->first();
                            if ($objCenter) {
                                $centerInfo = '<strong>' . $objCenter->name . '</strong>';
                                if ($objCenter->contact_number) {
                                    $centerInfo .= '<br />' . $objCenter->contact_number;
                                }
                                if ($objCenter->email_address) {
                                    $centerInfo .= '<br />' . $objCenter->email_address;
                                }
                            }
                        }


                        //ActiveCampaign Start
                        $retreat_interest = $experience->name ?? '';
                        $preferred_location = $experience->destinations?->pluck('name')->join(', ') ?? '';
                        $eventData = array(
                            "firstName" => @$waData['name'],
                            "lastName" => '',
                            "phone" => @$waData['phone'],
                            "source" => @$ref_url,
                            "retreat_interest" => @$retreat_interest,
                            "preferred_location" => @$preferred_location,
                        );
                        (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, ["Interest: $retreat_interest", "Location: $preferred_location"]);
                        //Active Campaign End
                    }
                }
                Mail::send('emails.contactus', ['name' => @$name, 'email' => @$email, 'ref_url' => @$ref_url, 'country_code' => @$country_code, 'phone' => @$phone, 'bodymessage' => @$content, 'inquiry_for' => @$inquiry_for, "centerInfo" => @$centerInfo], function ($message) use ($email, $subject) {
                    $message->subject($subject);
                    $message->from($email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                });

                Mail::send('emails.contactus_customer', ['name' => @$name, 'email' => @$email, 'ref_url' => @$ref_url, 'country_code' => @$country_code, 'phone' => @$phone, 'bodymessage' => @$content, 'inquiry_for' => @$inquiry_for], function ($message) use ($email, $subject) {
                    $message->subject($subject);
                    $message->to($email);
                    //$message->from('zen@balanceboat.com', 'Balanceboat');
                });
                //}
            } catch (Exception $ex) {
                //return redirect("/contact-us#contact_us")->with('flash_error_message', 'Something went wrong');
                return response()->json(['errors' => "Something went wrong!"]);
            }
        }
        return response()->json(['success' => "Your message has been successfully sent. We will contact you very soon!"]);
        //return redirect("/contact-us#contact_us")->with('flash_message', 'Your message has been successfully sent. We will contact you very soon!');
        //exit;
    }


    /**
     * Send Contact Us Blog Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_request_call_back_blog(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $name = $request->has('name') ? $request->input('name') : "";
        $email = $request->has('email') ? $request->input('email') : "";
        $country_code = $request->has('country_code') ? $request->input('country_code') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $inquiry_for = $request->has('inquiry_for') ? $request->input('inquiry_for') : "";
        $subject = $request->has('inquiry_subject') ? $request->input('inquiry_subject') : " BalanceBoat Received your Request for Call Back";
        $content = $request->has('message') ? $request->input('message') : "";
        if ($request->has('ref_url') or $request->input('ref_url')) {
            $ref_url = $request->has('ref_url') ? $request->input('ref_url') : "";
        } else {
            $ref_url = url()->previous();
        }

        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = '';
        $objComments->email = $email;
        $objComments->country_code = @$country_code;
        $objComments->phone = $phone;
        $objComments->message = 'Inquiry For:' . $inquiry_for . '<br />Subject:' . $subject . '<br />' . $content;
        $objComments->conversation_id = '';
        $objComments->source = "send_request_call_back_blog";
        $objComments->ref_url = $ref_url;
        $objComments->save();
        $objComments->refresh();

        try {
            $waData = array(
                "name" => $objComments->name ? $objComments->name : 'Request Call Back Blog',
                "phone" => "+" . $objComments->country_code . "-" . $objComments->phone,
                "email" => $objComments->email ? $objComments->email : "rcbb@balanceboat.com",
                "notes" => $objComments->message ? $objComments->message : 'RCBB Notes',
                'Source' => "I want to know more about the retreat and the best offers " . @$ref_url
            );
            (new \App\Services\WaService())->sendMessage($waData);

            //ActiveCampaign Start
            $eventData = array(
                "firstName" => $waData['name'],
                "lastName" => '',
                "phone" => $waData['phone'],
                "source" => @$ref_url
            );
            (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, ["Lead: New"]);
            //Active Campaign End

        } catch (Exception $ex) {
            \Log::info("Curl WA Error: " . @$ex->getMessage());
            \Log::info(json_encode($waData));
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                //$akismet = new Akismet();
                //if(!$akismet->check($request->all())) {
                Mail::send('emails.contactus', ['name' => @$name, 'email' => @$email, 'country_code' => @$country_code, 'ref_url' => @$ref_url, 'phone' => @$phone, 'bodymessage' => @$content, 'inquiry_for' => @$inquiry_for], function ($message) use ($email, $subject) {
                    $message->subject($subject);
                    $message->from($email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                });

                Mail::send('emails.contactus_customer', ['name' => @$name, 'email' => @$email, 'ref_url' => @$ref_url, 'country_code' => @$country_code, 'phone' => @$phone, 'bodymessage' => @$content, 'inquiry_for' => @$inquiry_for], function ($message) use ($email, $subject) {
                    $message->subject($subject);
                    $message->to($email);
                    // $message->from('zen@balanceboat.com', 'Balanceboat');
                });
                //}
            } catch (Exception $ex) {
                //return redirect("/contact-us#contact_us")->with('flash_error_message', 'Something went wrong');
                return response()->json(['errors' => "Something went wrong!"]);
            }
        }
        return response()->json(['success' => "Your message has been successfully sent. We will contact you very soon!"]);
        //return redirect("/contact-us#contact_us")->with('flash_message', 'Your message has been successfully sent. We will contact you very soon!');
        //exit;
    }

    /**
     * Send Pro Listing Contact Us Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_pro_listing_contact_us_email(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'email' => 'required|email'
        ]);

        $firstname = $request->has('firstname') ? $request->input('firstname') : "";
        $lastname = $request->has('lastname') ? $request->input('lastname') : "";
        $name = $firstname . " " . $lastname;
        $email = $request->has('email') ? $request->input('email') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $content = $request->has('message') ? $request->input('message') : "";
        if ($request->has('ref_url') or $request->input('ref_url')) {
            $ref_url = $request->has('ref_url') ? $request->input('ref_url') : "";
        } else {
            $ref_url = url()->previous();
        }

        $objComments = new \App\Inquiry();
        $objComments->name = $firstname;
        $objComments->lastname = $lastname;
        $objComments->email = $email;
        $objComments->phone = $phone;
        $objComments->message = $content;
        $objComments->conversation_id = '';
        $objComments->source = "send_pro_listing_contact_us_email";
        $objComments->ref_url = $ref_url;
        $objComments->save();

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                //$akismet = new Akismet();
                //if(!$akismet->check($request->all())) {
                Mail::send('emails.contactus', ['name' => @$name, 'email' => @$email, 'phone' => @$phone, 'ref_url' => @$ref_url, 'bodymessage' => @$content], function ($message) use ($email) {
                    $message->subject("Pro Listing Contact Us From BalanceBoat");
                    $message->from($email);
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                    $message->cc('kunal@balanceboat.com', 'Balanceboat');
                    $message->cc('shivam@balanceboat.com', 'Balanceboat');
                });
                //}
            } catch (Exception $ex) {
                return redirect("/list-your-retreats-get-more-sales")->with('flash_error_message', 'Something went wrong');
            }
        }
        return redirect("/list-your-retreats-get-more-sales")->with('flash_message', 'Your message has been successfully sent. We will contact you very soon!');
        exit;
    }

    public function testemail()
    {
        $bookingId = 271;
        $data = array();
        $data['booking'] = \App\Bookings::where("id", $bookingId)->first();
        $data['experience'] = \App\Experiences::where("id", $data['booking']->experience_id)->first();
        $data['user'] = \App\BookingUserInfo::where("booking_id", $data['booking']->id)->first();
        $customerEmail = "ammarjinia@gmail.com";
        if (@$customerEmail) {
            Mail::send('emails.booking.customer', $data, function ($message) use ($customerEmail) {
                $message->subject("Booking Information");
                $message->to($customerEmail);
            });
        }

        return view('emails.booking.admin', $data);
    }



    /**
     * Send Check Availability Email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_check_availability_email(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'date' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ];
        $message = ['g-recaptcha-response.required' => "The Captcha field is required."];
        $validator = validator()->make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $name = $request->has('name') ? $request->input('name') : "";
        $email = $request->has('email') ? $request->input('email') : "";
        $country_code = $request->has('country_code') ? $request->input('country_code') : "";
        $phone = $request->has('phone') ? $request->input('phone') : "";
        $date = $request->has('date') ? $request->input('date') : "";
        $message = $request->has('message') ? $request->input('message') : "";
        $exp_id = @$request->has('exp_id') ? @$request->input('exp_id') : "";
        if ($request->input('ref_url')) {
            $ref_url = $request->has('ref_url') ? $request->input('ref_url') : "";
        } else {
            $ref_url = url()->previous();
        }


        $newmessage = "Check Availability Date: " . $date . "<br />" . $message;

        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = '';
        $objComments->email = $email;
        $objComments->country_code = @$country_code;
        $objComments->phone = $phone;
        $objComments->experience_id = @$exp_id;
        $objComments->message = $newmessage;
        $objComments->conversation_id = '';
        $objComments->source = "check_availability";
        $objComments->ref_url = $ref_url;
        $objComments->save();

        $objComments->refresh();

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            try {
                $centerInfo = '';
                $centerEmail = false;
                if (@$exp_id) {
                    $experience = \App\Experiences::select("id", "name", "slug", "center_id")->where("id", @$exp_id)->first();
                    if (@$experience) {
                        if (@$experience->center_id) {
                            $objCenter = \App\Centers::select("name", "email_address", "contact_number")->where("id", @$experience->center_id)->first();
                            if ($objCenter) {
                                $centerInfo = '<strong>' . $objCenter->name . '</strong>';
                                if ($objCenter->contact_number) {
                                    $centerInfo .= '<br />' . $objCenter->contact_number;
                                }
                                if ($objCenter->email_address) {
                                    $centerInfo .= '<br />' . $objCenter->email_address;
                                    $centerEmail = true;
                                }

                                $waData = array(
                                    "name" => $objComments->name ? $objComments->name : 'Check Availability',
                                    "phone" => "+" . $objComments->country_code . "-" . $objComments->phone,
                                    "email" => $objComments->email ? $objComments->email : "availability@balanceboat.com",
                                    "notes" => $objComments->message ? $objComments->message : 'Availability Notes',
                                    'Source' => "I want to know more about the retreat and the best offers " . @$ref_url
                                );
                                (new \App\Services\WaService())->sendMessage($waData);

                                //ActiveCampaign Start
                                $retreat_interest = $experience->name ?? '';
                                $preferred_location = $experience->destinations?->pluck('name')->join(', ') ?? '';
                                $eventData = array(
                                    "firstName" => @$waData['name'],
                                    "lastName" => '',
                                    "phone" => @$waData['phone'],
                                    "source" => @$ref_url,
                                    "retreat_interest" => @$retreat_interest,
                                    "preferred_location" => @$preferred_location,
                                );
                                (new \App\Services\ActiveCampaignService())->trackCartActivity($waData['email'], $eventData, ["Interest: $retreat_interest", "Location: $preferred_location"]);
                                //Active Campaign End

                                // Send to Organizer
                                Mail::send('emails.check_availability.organizer', ['name' => @$name, 'email' => @$email, 'ref_url' => @$ref_url, 'country_code' => @$country_code, 'phone' => @$phone, 'message' => @$newmessage, 'inquiry_id' => @$objComments->id, 'date' => $date, 'experience' => $experience], function ($message) use ($objCenter) {
                                    $message->subject("Inquiry for Check Availability");
                                    $message->to($objCenter->email_address);
                                });
                            }
                        }
                    }
                }

                // Send to Admin 
                Mail::send('emails.check_availability.admin', ['inquiry' => @$objComments, 'experience' => $experience], function ($message) {
                    $message->subject("Inquiry for Check Availability");
                    $message->to('zen@balanceboat.com', 'Balanceboat');
                });

                if (!$centerEmail) {
                    // Send to Admin instead of Organizer
                    Mail::send('emails.check_availability.organizer', ['name' => @$name, 'email' => @$email, 'ref_url' => @$ref_url, 'country_code' => @$country_code, 'phone' => @$phone, 'message' => @$newmessage, 'inquiry_id' => @$objComments->id, 'date' => $date, 'experience' => $experience], function ($message) {
                        $message->subject("Inquiry for Check Availability");
                        $message->to('zen@balanceboat.com', 'Balanceboat');
                    });
                }

                if ($email) {
                    // Send to Customer
                    Mail::send('emails.check_availability.customer', ['name' => @$name], function ($message) use ($email) {
                        $message->subject("Thank You for Your Inquiry - We'll Notify You Soon");
                        $message->to($email);
                    });
                }
            } catch (Exception $ex) {
                return response()->json(['errors' => "Something went wrong!"]);
            }
        }
        return response()->json(['success' => "Your message has been successfully sent. We will contact you very soon!"]);
    }


    public function check_availability_response(Request $request)
    {
        $inquiry_id = $request->has('inquiry_id') ? $request->input('inquiry_id') : "";
        $response = $request->has('response') ? $request->input('response') : "";

        if (!empty($inquiry_id)) {
            try {
                $inquiry =  \App\Inquiry::find($inquiry_id);
                if ($inquiry && $response) {

                    $experience = \App\Experiences::select("id", "name", "slug", "center_id")->where("id", @$inquiry->experience_id)->first();
                    $cnd = ' AND e.id = '.@$experience->id;
                    $experiencePriceInfo = \App\Experiences::get_exp_deal_price_data($cnd, 'e.id', 'ASC', 1);
                    $experiencePriceInfo = @$experiencePriceInfo[0];
                    
                    // Send to Admin 
                    Mail::send('emails.check_availability.response_admin', ['inquiry' => @$inquiry, 'response' => @$response, 'experience' => @$experience, 'experiencePriceInfo' => @$experiencePriceInfo], function ($message) {
                        $message->subject("Response - Inquiry for Check Availability");
                        $message->to('zen@balanceboat.com', 'Balanceboat');
                    });

                    if ($inquiry->email) {
                        $email = @$inquiry->email;
                        // Send to Customer
                        Mail::send('emails.check_availability.response_customer', ['inquiry' => @$inquiry, 'response' => @$response, 'experience' => @$experience, 'experiencePriceInfo' => @$experiencePriceInfo], function ($message) use ($email) {
                            $message->subject("Response - Inquiry for Check Availability");
                            $message->to($email);
                            //$message->to("ammarjinia@gmail.com");
                        });
                    }
                }
            } catch (Exception $ex) {
                return redirect("/")->with('flash_error_message', 'Something went wrong!');
            }
        }
        return redirect("/")->with('flash_message', 'Thank you for the response!');
    }

    /**
     * Save Quick Lead from Qualify Form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save_quick_lead(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:2',
            'phone' => 'required|regex:/^[6-9]\d{9}$/',
            'email' => 'required|email',
            'retreat_type' => 'required|string',
            'destination' => 'required|string',
            'budget' => 'required|string',
            'timeline' => 'required|string'
        ];
        
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $retreat_type = $request->input('retreat_type');
        $destination = $request->input('destination');
        $budget = $request->input('budget');
        $timeline = $request->input('timeline');
        $whatsapp = $request->input('whatsapp', 0);
        $ref_url = $request->input('ref_url', url()->previous());

        try {
            // Save to Inquiry table
            $inquiry = new \App\Inquiry();
            $inquiry->name = $name;
            $inquiry->lastname = '';
            $inquiry->email = $email;
            $inquiry->phone = '91' . $phone; // Assuming India by default
            $inquiry->message = 'Quick Lead Submission<br />Retreat Type: ' . $retreat_type . '<br />Destination: ' . $destination . '<br />Budget: ' . $budget . '<br />Timeline: ' . $timeline;
            $inquiry->source = "quick_lead_form";
            $inquiry->ref_url = $ref_url;
            $inquiry->save();

            // Send WhatsApp notification if number is on WhatsApp
            try {
                if ($whatsapp) {
                    $waData = array(
                        "name" => $name,
                        "phone" => "+91-" . $phone,
                        "email" => $email,
                        "notes" => 'Quick Lead - Interested in: ' . $retreat_type . ' at ' . $destination,
                        'Source' => "Quick Lead Form from " . $ref_url
                    );
                    (new \App\Services\WaService())->sendMessage($waData);
                }

                // Send to ActiveCampaign
                $eventData = array(
                    "firstName" => $name,
                    "lastName" => '',
                    "phone" => "+91-" . $phone,
                    "email" => $email,
                    "source" => "quick_lead_form",
                    "retreat_interest" => $retreat_type,
                    "preferred_location" => $destination,
                    "budget" => $budget,
                    "timeline" => $timeline
                );
                
                $acTags = ["Lead: Quick", "Type: $retreat_type", "Location: $destination", "Budget: $budget", "Timeline: $timeline"];
                (new \App\Services\ActiveCampaignService())->trackCartActivity($email, $eventData, $acTags);
            } catch (Exception $ex) {
                \Log::info("Quick Lead Service Error: " . $ex->getMessage());
            }

            // Send Email to Admin
            try {
                $adminData = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => '91-' . $phone,
                    'retreat_type' => $retreat_type,
                    'destination' => $destination,
                    'budget' => $budget,
                    'timeline' => $timeline,
                    'whatsapp' => $whatsapp ? 'Yes' : 'No',
                    'source' => $ref_url
                ];
                
                Mail::send('emails.quick_lead_admin', $adminData, function ($message) use ($email, $name) {
                    $message->subject('New Quick Lead: ' . $name);
                    $message->from($email);
                    $message->to('zen@balanceboat.com', 'BalanceBoat');
                    $message->cc('kunal@balanceboat.com', 'BalanceBoat');
                });

                // Send confirmation email to customer
                Mail::send('emails.quick_lead_customer', $adminData, function ($message) use ($email, $name) {
                    $message->subject('Your Retreat Search - BalanceBoat');
                    $message->to($email);
                });
            } catch (Exception $ex) {
                \Log::info("Quick Lead Email Error: " . $ex->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Your inquiry has been received. We will contact you within 2 hours!']);
        } catch (Exception $ex) {
            \Log::error('Quick Lead Save Error: ' . $ex->getMessage());
            return response()->json(['success' => false, 'errors' => ['Something went wrong! Please try again.']], 500);
        }
    }
}
