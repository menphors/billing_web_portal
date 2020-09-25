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

class ChangeAcctInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){
        if(!Permission::check('changeacctinfo', 'true'))
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
        return view('change_acc_info.index', $customerInfo);
    }
  
    public function ChangeAcctInfo(Request $request){
        
        $cust_info = $request->file('number');
        $cust_info = Excel::toArray( new MsisdnsImport, $cust_info );
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

       for($i=1;$i<count($cust_info[0]) ; $i++){
           $number = $cust_info[0][$i][0];
           $acct_id = $cust_info[0][$i][1];
           $new_cust_language = $cust_info[0][$i][2];
           $last_name = $cust_info[0][$i][3];
           $first_name = $cust_info[0][$i][4];
           $title = $cust_info[0][$i][5];
           $Nationality = $cust_info[0][$i][6];
           $province = $cust_info[0][$i][7];
           $district = $cust_info[0][$i][8];
           $Commune = $cust_info[0][$i][9];
           $Village = $cust_info[0][$i][10];
           $Street = $cust_info[0][$i][11];
           $block = $cust_info[0][$i][12];
           $houseNo = $cust_info[0][$i][13];            
           $marketing_message = $cust_info[0][$i][14];
           $billinggroup = $cust_info[0][$i][15];
           $freeBillmediumFlag = $cust_info[0][$i][16];           
           //NGBSSService -> AcctInfo AddressType = 4 (correct in API value)
           //dd($cust_info);
           $this->NGBSSService = new NGBSSService($acct_id, "en");
           
           $this->NGBSSService->ChangeAcctInfo($number, $acct_id, $new_cust_language, $last_name, $first_name, $title,
           $Nationality, $province, $district, $Commune, $Village, $Street, $houseNo, $block, $marketing_message ,$billinggroup, $freeBillmediumFlag);
           
        } 
       Session::flash('success', 'Operation is submited!');
       return redirect()->back(); 
   }
    

    

    
        

}
