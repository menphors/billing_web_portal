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

class ActivateSubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){
        if(!Permission::check('activate', 'true'))
        {
            return view('permission.index');
        }
        $customerInfo['customer_info'] = DB::table('customer_info_report')
        ->join("users" , "users.id" , "=" , "customer_info_report.Executed_By")
        ->select('customer_info_report.*', 'users.name as user_name')
        ->paginate(10);
        return view('activate_sub.index', $customerInfo);
    }
  
    public function ActivateSub(Request $request){
        
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
            //$old_name = $cust_info[0][$i][1];--
            //$new_name = $cust_info[0][$i][2];--
            //dd($last_name);
            //dd ($new_name);
            //dd ($number);
            $this->NGBSSService = new NGBSSService($number, "en");
            $this->NGBSSService->ActivateSub($number);
        }
        Session::flash('success', 'Operation is submited!');
        return redirect()->back();
    }


    

    
        

}
