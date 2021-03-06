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

class ChangePreToPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){
        if(!Permission::check('changepretopost', 'true'))
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
        return view('pre_to_post.index', $customerInfo);
    }
  
    public function ChangePreToPost(Request $request){
        
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

        for($i=1;$i<count($cust_info[0]) ; $i++){
            $number = $cust_info[0][$i][0];
            $offer_name = $cust_info[0][$i][1];
            $offer_id = $cust_info[0][$i][2];
            //dd($last_name);
            //dd ($new_name);
          
            $this->NGBSSService = new NGBSSService($number, "en");
            $this->NGBSSService->ChangePreToPost($number, $offer_name,$offer_id);
        }
        Session::flash('success', 'Operation is submited!');
        return redirect()->back();
    }


    

    
        

}
