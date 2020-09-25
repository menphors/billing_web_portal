<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MsisdnsImport;
use App\Services\NGBSSService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
class BatchPaymentController extends Controller
{
    //Function for redirect to login page If you not loggin yet.
    public function __construct()
    {
        $this->middleware('auth');
    }
    //End function for redirect to login page If you not loggin yet.


    protected $phoneNumber;
    protected $NGBSSService;

    public function index()
    {
        if(!Permission::check('payment', 'true'))
        {
            return view('permission.index');
        }
        return view('batch_payment.index');
        
    }
    
    public function save(Request $r)
    {
        $conn = oci_connect('SMART', 'Smart_%002', '10.12.5.191/suseora');
        //$conn = oci_connect('ccare', 'CRM%ccare01', '10.12.8.37/suseora');
        if (!$conn) {
            $e = oci_error();
            Log::info('Oracle Connection failed'. $e['code'] . ';' . $e['message']);
            return 0;
			//die('Connection failed; db_class.php; '. $e['code'] . '; ' . $e['message']);
        }
        else{
            Log::info('Oracle Connection success');
            $master_acc = $r->master_acc;
            $remark = $r->remark;
     
            //Import Excel
            $path = $r->file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
                })->get();  
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = [
                        'MASTER_ACCT' => $value->MASTER_ACCT
                    ];              
                }
                $bill_cycle = $r->bill_cycle;
                if($bill_cycle == "111")
                {
                    $specific_date = $r->file('date')->getRealPath();
                    $specificdate = Excel::load($specific_date, function($reader) {
                        })-get();
                    if(!empty($specificdate) && $specificdate->count()){
                        foreach($specificdate as $key => $value){
                            $specific[] = [
                                'bill_cycle_id' => $value->bill_cycle_id
                            ];
                        }
                        $bill_amt = $r->bill_amount;
                        $amt_due = $r->amount_due;
                        if($bill_amt)
                        {   
                            //Master + Exclude + Date + Specific Number + Bill Amount
                            $sql = DB::raw("
                            select t6.cust_name,
                            t.service_number,
                            t4.acct_code     Member_acct,
                            t2.acct_code     Master_Acct,
                            t7.invoice_amt/100000000 Bill_AMT,
                            t7.Open_amt/100000000 Open_AMT,
                            t7.bill_cycle_id
                            from ccare.inf_group_member      t,
                                ccare.inf_group_subscriber  t1,
                                ccare.inf_acct              t2,
                                ccare.inf_acct_relation     t3,
                                ccare.inf_acct              t4,
                                ccare.inf_acct_relation     t5,
                                ccare.inf_customer_Corp     t6,
                                ardb_1601.ar_invoice@edrdb1 t7
                            where t.group_sub_id = t1.sub_id
                                and t.member_class = '1'
                                and t1.group_type = '1'
                                and t2.acct_id = t3.acct_id
                                and t1.sub_id = t3.sub_id
                                and t4.acct_id = t5.acct_id
                                and t.sub_id = t5.sub_id
                                and t1.cust_id = t6.cust_id
                                and t.service_number = t7.pri_identity
                                and t5.exp_date > sysdate
                                and t2.acct_code='1.11634268';");
                        }
                        else if($amt_due)
                        {
                            //Master + Exclude + Date + Specific Number + Amount Due
                        $sql = DB::raw("
                        select t6.cust_name,
                        t.service_number,
                        t4.acct_code     Member_acct,
                        t2.acct_code     Master_Acct,
                        t7.invoice_amt/100000000 Bill_AMT,
                        t7.Open_amt/100000000 Open_AMT,
                        t7.bill_cycle_id
                        from ccare.inf_group_member      t,
                            ccare.inf_group_subscriber  t1,
                            ccare.inf_acct              t2,
                            ccare.inf_acct_relation     t3,
                            ccare.inf_acct              t4,
                            ccare.inf_acct_relation     t5,
                            ccare.inf_customer_Corp     t6,
                            ardb_1601.ar_invoice@edrdb1 t7
                        where t.group_sub_id = t1.sub_id
                            and t.member_class = '1'
                            and t1.group_type = '1'
                            and t2.acct_id = t3.acct_id
                            and t1.sub_id = t3.sub_id
                            and t4.acct_id = t5.acct_id
                            and t.sub_id = t5.sub_id
                            and t1.cust_id = t6.cust_id
                            and t.service_number = t7.pri_identity
                            and t5.exp_date > sysdate
                            and t2.acct_code='1.11634268';");
                        }
                    }
                    //End If
                }
                //End If
                else if($bill_cycle == "222")
                {
                    $bill_amt = $r->bill_amount;
                    $amt_due = $r->amount_due;
                    if($bill_amt)
                    {   
                        //Master + Exclude + Specific Number + Bill Amount
                        $sql = DB::raw("
                        select t6.cust_name,
                        t.service_number,
                        t4.acct_code     Member_acct,
                        t2.acct_code     Master_Acct,
                        t7.invoice_amt/100000000 Bill_AMT,
                        t7.Open_amt/100000000 Open_AMT,
                        t7.bill_cycle_id
                        from ccare.inf_group_member      t,
                            ccare.inf_group_subscriber  t1,
                            ccare.inf_acct              t2,
                            ccare.inf_acct_relation     t3,
                            ccare.inf_acct              t4,
                            ccare.inf_acct_relation     t5,
                            ccare.inf_customer_Corp     t6,
                            ardb_1601.ar_invoice@edrdb1 t7
                        where t.group_sub_id = t1.sub_id
                            and t.member_class = '1'
                            and t1.group_type = '1'
                            and t2.acct_id = t3.acct_id
                            and t1.sub_id = t3.sub_id
                            and t4.acct_id = t5.acct_id
                            and t.sub_id = t5.sub_id
                            and t1.cust_id = t6.cust_id
                            and t.service_number = t7.pri_identity
                            and t5.exp_date > sysdate
                            and t2.acct_code='1.11634268';");
                    }
                    else if($amt_due)
                    {
                        //Master + Exclude + Specific Number + Amount Due
                        $sql = DB::raw("
                        select t6.cust_name,
                        t.service_number,
                        t4.acct_code     Member_acct,
                        t2.acct_code     Master_Acct,
                        t7.invoice_amt/100000000 Bill_AMT,
                        t7.Open_amt/100000000 Open_AMT,
                        t7.bill_cycle_id
                        from ccare.inf_group_member      t,
                            ccare.inf_group_subscriber  t1,
                            ccare.inf_acct              t2,
                            ccare.inf_acct_relation     t3,
                            ccare.inf_acct              t4,
                            ccare.inf_acct_relation     t5,
                            ccare.inf_customer_Corp     t6,
                            ardb_1601.ar_invoice@edrdb1 t7
                        where t.group_sub_id = t1.sub_id
                            and t.member_class = '1'
                            and t1.group_type = '1'
                            and t2.acct_id = t3.acct_id
                            and t1.sub_id = t3.sub_id
                            and t4.acct_id = t5.acct_id
                            and t.sub_id = t5.sub_id
                            and t1.cust_id = t6.cust_id
                            and t.service_number = t7.pri_identity
                            and t5.exp_date > sysdate
                            and t2.acct_code='1.11634268';");
                    }
                }
            }
            //End Import Excel
            else{
                $bill_cycle = $r->bill_cycle;
                if($bill_cycle == "111")
                {
                    $specific_date = $r->file('date')->getRealPath();
                    $specificdate = Excel::load($specific_date, function($reader) {
                        })-get();
                    if(!empty($specificdate) && $specificdate->count()){
                        foreach($specificdate as $key => $value){
                            $specific[] = [
                                'bill_cycle_id' => $value->bill_cycle_id
                            ];
                        }
                        $bill_amt = $r->bill_amount;
                        $amt_due = $r->amount_due;
                        if($bill_amt)
                        {   
                            //Master + Exclude + Date + Specific Number + Bill Amount
                            $sql = DB::raw("
                            select t6.cust_name,
                            t.service_number,
                            t4.acct_code     Member_acct,
                            t2.acct_code     Master_Acct,
                            t7.invoice_amt/100000000 Bill_AMT,
                            t7.Open_amt/100000000 Open_AMT,
                            t7.bill_cycle_id
                            from ccare.inf_group_member      t,
                                ccare.inf_group_subscriber  t1,
                                ccare.inf_acct              t2,
                                ccare.inf_acct_relation     t3,
                                ccare.inf_acct              t4,
                                ccare.inf_acct_relation     t5,
                                ccare.inf_customer_Corp     t6,
                                ardb_1601.ar_invoice@edrdb1 t7
                            where t.group_sub_id = t1.sub_id
                                and t.member_class = '1'
                                and t1.group_type = '1'
                                and t2.acct_id = t3.acct_id
                                and t1.sub_id = t3.sub_id
                                and t4.acct_id = t5.acct_id
                                and t.sub_id = t5.sub_id
                                and t1.cust_id = t6.cust_id
                                and t.service_number = t7.pri_identity
                                and t5.exp_date > sysdate
                                and t2.acct_code='1.11634268';");
                        }
                        else if($amt_due)
                        {
                            //Master + Exclude + Date + Specific Number + Amount Due
                            $sql = DB::raw("
                            select t6.cust_name,
                            t.service_number,
                            t4.acct_code     Member_acct,
                            t2.acct_code     Master_Acct,
                            t7.invoice_amt/100000000 Bill_AMT,
                            t7.Open_amt/100000000 Open_AMT,
                            t7.bill_cycle_id
                            from ccare.inf_group_member      t,
                                ccare.inf_group_subscriber  t1,
                                ccare.inf_acct              t2,
                                ccare.inf_acct_relation     t3,
                                ccare.inf_acct              t4,
                                ccare.inf_acct_relation     t5,
                                ccare.inf_customer_Corp     t6,
                                ardb_1601.ar_invoice@edrdb1 t7
                            where t.group_sub_id = t1.sub_id
                                and t.member_class = '1'
                                and t1.group_type = '1'
                                and t2.acct_id = t3.acct_id
                                and t1.sub_id = t3.sub_id
                                and t4.acct_id = t5.acct_id
                                and t.sub_id = t5.sub_id
                                and t1.cust_id = t6.cust_id
                                and t.service_number = t7.pri_identity
                                and t5.exp_date > sysdate
                                and t2.acct_code='1.11634268';");
                        }
                    }
                    //End If
                }
                //End If
                else if($bill_cycle == "222")
                {
                    $bill_amt = $r->bill_amount;
                    $amt_due = $r->amount_due;
                    if($bill_amt)
                    {   
                        //Master + Exclude + Specific Number + Bill Amount
                        $sql = DB::raw("
                        select t6.cust_name,
                        t.service_number,
                        t4.acct_code     Member_acct,
                        t2.acct_code     Master_Acct,
                        t7.invoice_amt/100000000 Bill_AMT,
                        t7.Open_amt/100000000 Open_AMT,
                        t7.bill_cycle_id
                        from ccare.inf_group_member      t,
                            ccare.inf_group_subscriber  t1,
                            ccare.inf_acct              t2,
                            ccare.inf_acct_relation     t3,
                            ccare.inf_acct              t4,
                            ccare.inf_acct_relation     t5,
                            ccare.inf_customer_Corp     t6,
                            ardb_1601.ar_invoice@edrdb1 t7
                        where t.group_sub_id = t1.sub_id
                            and t.member_class = '1'
                            and t1.group_type = '1'
                            and t2.acct_id = t3.acct_id
                            and t1.sub_id = t3.sub_id
                            and t4.acct_id = t5.acct_id
                            and t.sub_id = t5.sub_id
                            and t1.cust_id = t6.cust_id
                            and t.service_number = t7.pri_identity
                            and t5.exp_date > sysdate
                            and t2.acct_code='1.11634268';");
                    }
                    else if($amt_due)
                    {
                        //Master + Exclude + Specific Number + Amount Due
                        $sql = DB::raw("
                        select t6.cust_name,
                        t.service_number,
                        t4.acct_code     Member_acct,
                        t2.acct_code     Master_Acct,
                        t7.invoice_amt/100000000 Bill_AMT,
                        t7.Open_amt/100000000 Open_AMT,
                        t7.bill_cycle_id
                        from ccare.inf_group_member      t,
                            ccare.inf_group_subscriber  t1,
                            ccare.inf_acct              t2,
                            ccare.inf_acct_relation     t3,
                            ccare.inf_acct              t4,
                            ccare.inf_acct_relation     t5,
                            ccare.inf_customer_Corp     t6,
                            ardb_1601.ar_invoice@edrdb1 t7
                        where t.group_sub_id = t1.sub_id
                            and t.member_class = '1'
                            and t1.group_type = '1'
                            and t2.acct_id = t3.acct_id
                            and t1.sub_id = t3.sub_id
                            and t4.acct_id = t5.acct_id
                            and t.sub_id = t5.sub_id
                            and t1.cust_id = t6.cust_id
                            and t.service_number = t7.pri_identity
                            and t5.exp_date > sysdate
                            and t2.acct_code='1.11634268';");
                    }
                }
            }
        }



    }

    public function test()
    {
        $data['testing'] = DB::table('AMOUNT');
    }
    // public function getCompanyPhoneNumbeAmountBilling() {
    //     $conn = oci_connect('SMART', 'Smart_%002', '10.12.5.191/suseora');
    //     // $conn = oci_connect('ccare', 'CRM%ccare01', '10.12.8.37/suseora');
    //            if (!$conn) {
    //     $e = oci_error();
    //     Log::info('Oracle Connection failed'. $e['code'] . ';' . $e['message']);
    //     return 0;
    //                //die('Connection failed; db_class.php; '. $e['code'] . '; ' . $e['message']);
    //            } else {
    //     // success 
    //     Log::info('Oracle Connection success');
    //     $test['test'] = "Oci successfully";
    //     $sql['testing'] = "select distinct count (1) as amount
    //     from
    //     ccare.inf_group_member gm,
    //     ccare.inf_group_subscriber gs,
    //     ccare.inf_acct_relation r,
    //     ccare.inf_acct a,
    //     ccare.inf_customer_corp c,
    //     ccare.inf_subscriber_all b1,
    //     ccare.inf_acct_relation b2,
    //     ccare.inf_acct b3
    //     where b1.sub_id=b2.sub_id
    //     and b2.acct_id=b3.acct_id
    //     and b1.msisdn=gm.service_number
    //     and b1.exp_date>sysdate
    //     and b2.exp_date>sysdate
    //     and b3.exp_date>sysdate
    //     and gm.group_sub_id = gs.sub_id
    //     and gs.sub_id = r.sub_id
    //     and r.acct_id = a.acct_id
    //     and a.cust_id = c.cust_id
    //     and r.exp_date > sysdate
    //     and gs.group_type='1'
    //     and gm.exp_date > sysdate
    //     and gs.exp_date > sysdate
    //     and gm.service_number is not null
    //     and c.cust_id = '1010053990918' ";
        
    //     $msisdn_amount = oci_parse($conn,$sql);
    //     $data = oci_execute($msisdn_amount);
    //     $row = oci_fetch_array($msisdn_amount);
    //     //echo json_encode($row['AMOUNT']);
    //     //dd($row);
    //     return $row['AMOUNT'];
    //            }

    //     return view('batch_payment.testing', $test);
    //     }
}
