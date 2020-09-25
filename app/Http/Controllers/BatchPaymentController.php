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
use App\Services\SOAPApiService;
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
        return view('batch_payment.testing');
        
    }
    
    public function save($cust_id) {
        $conn = oci_connect('SMART', 'Smart_%002', '10.12.5.191/suseora');
        //   $conn = oci_connect('ccare', 'CRM%ccare01', '10.12.8.37/suseora');
		if (!$conn) {
            $e = oci_error();
            Log::info('Oracle Connection failed'. $e['code'] . ';' . $e['message']);
            return 0;
			//die('Connection failed; db_class.php; '. $e['code'] . '; ' . $e['message']);
		} else {
            // success 
            $sql = "select distinct count (1) as amount
            from
            ccare.inf_group_member gm,
            ccare.inf_group_subscriber gs,
            ccare.inf_acct_relation r,
            ccare.inf_acct a,
            ccare.inf_customer_corp c,
            ccare.inf_subscriber_all b1,
            ccare.inf_acct_relation b2,
            ccare.inf_acct b3
            where b1.sub_id=b2.sub_id
            and b2.acct_id=b3.acct_id
            and b1.msisdn=gm.service_number
            and b1.exp_date>sysdate
            and b2.exp_date>sysdate
            and b3.exp_date>sysdate
            and gm.group_sub_id = gs.sub_id
            and gs.sub_id = r.sub_id
            and r.acct_id = a.acct_id
            and a.cust_id = c.cust_id
            and r.exp_date > sysdate
            and gs.group_type='1'
            and gm.exp_date > sysdate
            and gs.exp_date > sysdate
            and gm.service_number is not null
            and c.cust_id = '". $cust_id ."' ";
                
            $msisdn_amount = oci_parse($conn,$sql);
            $data = oci_execute($msisdn_amount);
            $row = oci_fetch_array($msisdn_amount);
            //echo json_encode($row['AMOUNT']);
            //dd($row);
            return $row['AMOUNT'];
		}
	
    }
}
