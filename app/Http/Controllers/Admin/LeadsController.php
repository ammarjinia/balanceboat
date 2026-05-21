<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Inquiry;
use Storage;
use DB;

class LeadsController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a deal of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $ajax = Request()->input('ajax') ? Request()->input('ajax') : false;
        if ($ajax == true) {
            if (!Request()->has('download')) {
                $page = $_REQUEST['length'];
                $offset = $_REQUEST['start'];
            }
            $search = (!Request()->has('download')) ? @$_REQUEST['search']['value'] : @$_REQUEST['search'];
            //$listingId = @$_REQUEST['listing_id'];
            
            $inquiry = Inquiry::select('inquiries.*');
            /*if ($listingId) {
                $inquiry->join('inquiry_sent_to_listings', function($query) use($listingId) {
                    $query->on('inquiry_sent_to_listings.inquiry_id', 'inquiries.id');
                    $query->where('inquiry_sent_to_listings.listing_id', $listingId);
                });
            }*/
            
            if (!empty($search)) {
                $inquiry->where(function($q){
                    $q->where("name", "like", "%" . $search . "%");
                    $q->orWhere("phone", "like", "%" . $search . "%");
                    $q->orWhere("email", "like", "%" . $search . "%");
                });
            }

            if (@$_REQUEST['start_date']) {
                $startDate = \Carbon\Carbon::parse($_REQUEST['start_date'])->format("Y-m-d");
                $inquiry->whereDate("created_at", ">=", $startDate);
            }

            if (@$_REQUEST['end_date']) {
                $endDate = \Carbon\Carbon::parse($_REQUEST['end_date'])->format("Y-m-d");
                $inquiry->whereDate("created_at", "<=", $endDate);
            }
            
            $inquiryTotal = clone $inquiry;
            $order = "DESC";
            $orderBy = "inquiries.updated_at";
            if (Request()->has('order.0.column')) {
                switch (Request()->input('order.0.column')) {
                    case '0' :
                        $orderBy = "inquiries.id";
                        break;
                    case '1' :
                        $orderBy = "name";
                        break;
                    case '2' :
                        $orderBy = "email";
                        break;
                    case '3' :
                        $orderBy = "phone";
                        break;
                    default :
                        break;
                }
                if (Request()->input('order.0.dir') == 'desc')
                    $order = "DESC";
                else
                    $order = "ASC";
            }
            if (!Request()->has('download')) {
                $inquiry = $inquiry->orderBy($orderBy, $order)->skip($offset)->limit($page)->get();
            } else {
                $inquiry = $inquiry->orderBy($orderBy, $order)->get();
            }
            $inquiryTotal = $inquiryTotal->count();
            
            if (Request()->has('download')) {
                $delimiter = ","; 
                $filename = "leads-data_" . date('Y-m-d') . ".csv"; 
                 
                // Create a file pointer 
                $f = fopen('php://memory', 'w');
                
                $fields = array('ID', 'NAME', 'EMAIL', 'PHONE', 'MESSAGE', 'REF_URL', 'NOTE','DATE');
                fputcsv($f, $fields, $delimiter); 

                if ($inquiry) {
                    foreach ($inquiry as $objInquiry) {
                        $inquiryData = array(
                            $objInquiry->id,
                            $objInquiry->name,
                            $objInquiry->email,
                            $objInquiry->phone,
                            $objInquiry->message,
                            $objInquiry->ref_url,
                            $objInquiry->note,
                            \Carbon\Carbon::parse($objInquiry->created_at)->format("d-m-Y")
                        );
                        fputcsv($f, $inquiryData, $delimiter); 
                    }
                    fseek($f, 0); 
                    // Set headers to download file rather than displayed 
                    header('Content-Type: text/csv'); 
                    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
                     
                    //output all remaining data on a file pointer 
                    fpassthru($f); 
                }
            } else {
                $inquiryData = array();
                if ($inquiry) {
                    foreach ($inquiry as $objInquiry) {
                        $action = '<div class="btn-group">
                            <button type = "button" class = "btn btn-info dropdown-toggle" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">Action</button>
                            <div class = "dropdown-menu" x-placement = "bottom-start"><a class="dropdown-item text-info" href="' . url('bbadmin/lead/details/' . $objInquiry->id) . '">Details</a></div>'
                                . '</div>';
                        $inquiryData[] = array(
                            $objInquiry->id,
                            $objInquiry->name,
                            //$objInquiry->email.((@$objInquiry->InquirySentToListing->count() > 0) ? "<br /><span class='badge badge-primary'>Email Sent <span class='badge badge-light'>".@$objInquiry->InquirySentToListing->count()."</span></span>" : ""),
                            $objInquiry->email,
                            ($objInquiry->country_code)." ".$objInquiry->phone,
                            //$objInquiry->message,
                            //$inqFor,
                            \Carbon\Carbon::parse($objInquiry->created_at)->format("d-m-Y"),
                            ($objInquiry->whatsapp_verified ? '<i class="fa fa-whatsapp" style="font-size:1.5em;" title="WhatsApp Verified"></i> ' : '').($objInquiry->email_verified ? '<i class="fa fa-envelope" style="font-size:1.5em;" title="Email Verified"></i> ' : '').($objInquiry->instagram_verified ? '<i class="fa fa-instagram" style="font-size:1.5em;" title="Instagram Verified"></i>' : ''),
                            $action
                        );
                        
                    }
                }
                $json_data = array(
                    "draw" => intval($_REQUEST['draw']),
                    "recordsTotal" => intval($inquiryTotal),
                    "recordsFiltered" => intval($inquiryTotal),
                    "data" => $inquiryData
                );
                echo json_encode($json_data);
            }
        } else {
            return view("admin.leads.index", $data);
        }
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.leads.create');
    }

    /**
     * Store a newly created Deal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (@$request->has('submit_note')) {
            $leadId = @$request->id;
            $objInquiry = Inquiry::where("id", $leadId)->first();
            $objInquiry->note = $request->note;
            $objInquiry->whatsapp_verified = $request->input('whatsapp_verified',0);
            $objInquiry->email_verified = $request->input('email_verified',0);
            $objInquiry->instagram_verified = $request->input('instagram_verified',0);
            $objInquiry->save();
            return redirect('bbadmin/leads')->with('flash_message', 'Lead Updated Successfully!');
        } else {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
            ]);
            
            try {
                $objInquiry = new Inquiry();
                $objInquiry->name = $request->name;
                $objInquiry->email = $request->email;
                $objInquiry->phone = @$request->country_code.$request->phone;
                $objInquiry->ref_url = $request->ref_url;
                $objInquiry->source = 'admin';
                $objInquiry->message = $request->message;
                $objInquiry->note = $request->note;
                $objInquiry->whatsapp_verified = $request->input('whatsapp_verified',0);
                $objInquiry->email_verified = $request->input('email_verified',0);
                $objInquiry->instagram_verified = $request->input('instagram_verified',0);
                $objInquiry->save();
                return redirect('bbadmin/leads')->with('flash_message', 'Lead submitted successfully!');
            } catch (Exception $e) {
                return redirect('bbadmin/leads')->with('flash_error_message', 'Something went wrong');
            }

        }
        
        /*if (@$request->has('submit_note')) {
            $leadId = @$request->id;
            $objInquiry = Inquiry::where("id", $leadId)->first();
            $objInquiry->tags = $request->tags;
            $objInquiry->whatsapp_verified = $request->input('whatsapp_verified',0);
            $objInquiry->email_verified = $request->input('email_verified',0);
            $objInquiry->note = $request->note;
            $objInquiry->save();
            return redirect('bbadmin/leads')->with('flash_message', 'Lead Updated Successfully!');
        } else if (@$request->listing) {
            foreach (@$request->listing as $listing) {
                $objInqList = new \App\InquirySentToListings();
                $objInqList->inquiry_id = $request->id;
                $objInqList->listing_id = $listing;
                try {
                    $objInqList->save();
                    
                    $data = array();
                    $data['inquiry'] = $objInqList->inquiry; 
                    $data['listing'] = $objInqList->listing;
                    if (@$data['listing']->email) {
                        dispatch(new \App\Jobs\SendEmailListingAgentJob($data))->delay(now()->addMinutes(2));
                    }

                } catch (Exception $e) {
                    return redirect('bbadmin/leads')->with('flash_error_message', 'Something went wrong with Listing Id - '.$listing);
                }
            }
            return redirect('bbadmin/leads')->with('flash_message', 'Lead Sent to Listings!');
        } else {
            return redirect('bbadmin/leads')->with('flash_error_message', 'Something went wrong');
        }*/
    }

    /**
     * Show the form for ediing a Deal.
     *
     * @return \Illuminate\Http\Response
     */
    public function details($id = '') {
        $data = array();
        if (!$id) {
            redirect("bbadmin/leads");
        }
        $data['elead'] = Inquiry::where("id", $id)->first();
        $objListings = $eleadListingSent = array();
        /*if ($data['elead']->listing_id && $data['elead']->type == "Listing") {
            $listing = $data['elead']->listing;
            $inqId = $data['elead']->id;
            if ($listing->city) {
                $objListingCats = \App\ListingCategory::select(DB::raw('distinct category_id'))->where("listing_id", $listing->id)->get()->pluck('category_id');
                if ($objListingCats) {
                    $objListings = \App\Listings::select('listings.id', 'listings.name', 'listings.email', 'listings.is_pro_listing', 'listings.user_id', 'inquiry_sent_to_listings.id as sentToListinId')
                                    ->join('listing_category', function($query) use($objListingCats) {
                                        $query->on('listing_id','=','listings.id');
                                        $query->whereIn('category_id', $objListingCats);
                                    })
                                    ->leftJoin('inquiry_sent_to_listings', function($query) use($inqId) {
                                        $query->on('inquiry_sent_to_listings.listing_id','=','listings.id');
                                        $query->where('inquiry_sent_to_listings.inquiry_id', $inqId);
                                    })
                                    ->where("city", $listing->city)
                                    ->where("listings.id","!=",$listing->id)
                                    ->orderBy("listings.is_pro_listing", "DESC")->orderBy("listings.name", "ASC")
                                    ->distinct()->get();
                }
            }
            
            $eleadListingSent = \App\InquirySentToListings::where("listing_id", $listing->id)->first();
        }
        $data['eleadListingSent'] = $eleadListingSent;
        $data['listings'] = $objListings;*/
        return view('admin.leads.details', $data);
    }

}
