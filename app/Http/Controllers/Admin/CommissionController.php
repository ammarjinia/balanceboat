<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\CenterCommissions;
use Storage;
use DB;

class CommissionController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Accomodation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['commissions'] = CenterCommissions::select("center_commissions.id", "center_commissions.name", "center_commissions.slug", "center_commissions.description", DB::raw('group_concat(`centers`.`name`) as CenterName'))
                ->leftJoin("centers", "centers.id", "=", "center_commissions.center_id")
                ->groupBy("center_commissions.id")
                ->orderBy("center_commissions.id", "DESC")
                ->get();
        return view("admin.commission.index", $data);
    }

    /**
     * Show the form for creating a new Commission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($param);
        return view('admin.commission.create', $data);
    }

    /**
     * Store a newly created Commission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request['name'];
        $center_id = ($request['center_id']) ? $request['center_id'] : NULL;
        $cancellation_policy = ($request['cancellation_policy']);
        $deposit_policy = ($request['deposit_policy']) ? $request['deposit_policy'] : 0;
        $deposit_amount = ($request['deposit_amount']) ? $request['deposit_amount'] : NULL;
        $cancellation_policy_condition = ($request['cancellation_policy_condition']) ? $request['cancellation_policy_condition'] : 0;
        $cancellation_policy_days = ($request['cancellation_policy_days']) ? $request['cancellation_policy_days'] : NULL;
        $rest_of_payment = ($request['rest_of_payment']) ? $request['rest_of_payment'] : 0;
        $rest_of_payment_days = ($request['rest_of_payment_days']) ? $request['rest_of_payment_days'] : NULL;
        $commission = ($request['commission']) ? $request['commission'] : NULL;
        $tax = ($request['tax']) ? $request['tax'] : NULL;
        $description = $request['description'];
        if (!empty($request['id'])) {
            $objCenterCommission = CenterCommissions::find($request['id']);
            if ($objCenterCommission) {
                $objCenterCommission->name = $name;
                $objCenterCommission->description = $description;
                $objCenterCommission->center_id = $center_id;
                $objCenterCommission->cancellation_policy = $cancellation_policy;
                $objCenterCommission->deposit_policy = $deposit_policy;
                $objCenterCommission->deposit_amount = $deposit_amount;
                $objCenterCommission->cancellation_policy_condition = $cancellation_policy_condition;
                $objCenterCommission->cancellation_policy_days = $cancellation_policy_days;
                $objCenterCommission->rest_of_payment = $rest_of_payment;
                $objCenterCommission->rest_of_payment_days = $rest_of_payment_days;
                $objCenterCommission->commission = $commission;
                $objCenterCommission->tax = $tax;
                try {
                    $objCenterCommission->save();
                    $center_commission_id = $request['id'];
                } catch (Exception $e) {
                    return redirect('bbadmin/commissions')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/commissions')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/commissions')
                            ->with('flash_message', 'Commission ' . $objCenterCommission->name . ' updated');
        } else {
            $objCenterCommission = new CenterCommissions();
            $objCenterCommission->name = $name;
            $objCenterCommission->description = $description;
            $objCenterCommission->center_id = $center_id;
            $objCenterCommission->cancellation_policy = $cancellation_policy;
            $objCenterCommission->deposit_policy = $deposit_policy;
            $objCenterCommission->deposit_amount = $deposit_amount;
            $objCenterCommission->cancellation_policy_condition = $cancellation_policy_condition;
            $objCenterCommission->cancellation_policy_days = $cancellation_policy_days;
            $objCenterCommission->rest_of_payment = $rest_of_payment;
            $objCenterCommission->rest_of_payment_days = $rest_of_payment_days;
            $objCenterCommission->commission = $commission;
            $objCenterCommission->tax = $tax;
            try {
                $objCenterCommission->save();
                $center_commission_id = $objCenterCommission->id;
            } catch (Exception $e) {
                return redirect('bbadmin/commissions')
                                ->with('flash_error_message', 'Something went wrong');
            }

            return redirect('bbadmin/commissions')
                            ->with('flash_message', 'Commission ' . $objCenterCommission->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Commission.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/commissions");
        }

        $cparam['order'] = "ASC";
        $cparam['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($cparam);

        $param = array("id" => $id);
        $data['ecommission'] = CenterCommissions::where($param)->first();

        return view('admin.commission.edit', $data);
    }

    /**
     * Remove the specified Commission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objCenterCommission = CenterCommissions::find($id);
            if (!empty($objCenterCommission)) {
                $objCenterCommission->delete();
            } else {
                return redirect('bbadmin/commissions')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/commissions')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/commissions')
                        ->with('flash_message', 'Commission deleted successfully');
    }

}
