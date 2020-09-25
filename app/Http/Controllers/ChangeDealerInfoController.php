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

class ChangeDealerInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;

   
    
     
    public function index(){
        if(!Permission::check('dealer', 'true'))
        {
            return view('permission.index');
        }
        // $customerInfo = DB::table('change_offering_schedules')
        // ->join("users" , "users.id" , "=" , "change_offering_schedules.user_id")
        // ->select('change_offering_schedules.*', 'users.name as user_name')
        // ->where("completed" , true)
        // ->orderBy("id" , "desc")
        // ->paginate(10); 
        // return view('change_sub_offering.index')->with(['customer_info' => $customerInfo]);
        $customerInfo['customer_info'] = DB::table('customer_info_report')
        ->join("users" , "users.id" , "=" , "customer_info_report.Executed_By")
        ->select('customer_info_report.*', 'users.name as user_name')
        ->paginate(10);
        return view('change_dealer.index', $customerInfo);
    }
  
    public function ChangeDealerInfo(Request $request){

        $cust_info = $request->file('number');
        $cust_info = Excel::toArray( new MsisdnsImport, $cust_info );
        //$request->remark

        $request->remark;
        // $resultId = DB::table("customer_info_report")
        //                 ->insertGetId(
        //                     [   
        //                         "Executed_By" => Auth::user()->id,
        //                         "Executed_Date" =>Carbon::now(),
        //                         "remark" => $request->remark,
        //                         "Amount" => count($cust_info[0]) -1
        //                     ]
        //                 );
            
        $conn = oci_connect("ccare", "CRM%ccare01", "10.12.8.37:1526/suseora");
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }else{
        }
        for($i=1;$i<count($cust_info[0]) ; $i++){
            $number = $cust_info[0][$i][0];
            $dealer_id = $cust_info[0][$i][1];
            $dealer_name= $cust_info[0][$i][2];
            // $this->NGBSSService = new NGBSSService($number, "en");
            // $this->NGBSSService->ChangeDealerInfo($number, $dealer_id, $dealer_name);
            $sql="update channel.chm_dealer set dealer_name= :dealer_name where dealer_id= :dealer_id";
            $stid = oci_parse($conn,$sql);
            // Bind input value
            oci_bind_by_name($stid,':dealer_id',$dealer_id);
            oci_bind_by_name($stid,':dealer_name',$dealer_name);
            //echo $sql;
            $data = oci_execute($stid);
        }
        oci_free_statement($stid);
        oci_close($conn);       
            
        Session::flash('success', 'Operation is submited!');
        return redirect()->back();
    }


    

    
        

}
