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
use App\Helpers\Helper;

class ChangeBillMediumController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){

        if(!Permission::check('changebillmedium', 'true'))
        {
            return view('permission.index');
        }
       
        return view('change_bill_medium.index');


    }
  
   

    public function ChangeBillMedium(Request $request){
        // dd($request->medium_code, $request->medium_drop);
        $account_excel = $request->file('acct_id');
        $cust_info = Excel::toArray( new MsisdnsImport, $account_excel );
        $request->medium_id;
        $request->medium_type;
        $request->remark;
               
        // Teacher Dara
        $successItems = array();
        $failedItems = array();
        // Loop 
        foreach($cust_info[0] as $item ) { 
            try { 
                $this->NGBSSService = new NGBSSService('69204963', "en");     
                $acct_id = $item[0];   // in batch format working base on acct_id data in excel   
                //$new_bill_id = $item[0][1];
                //$new_bill_code = $item[0][2]; 
                $new_bill_id = $request->medium_id;
                $new_bill_code = $request->medium_type;
                
                $oldinfo = $this->NGBSSService->QueryAcctInfo($acct_id);
                // get queryaccinfo response
                $billMedium = $oldinfo['soapenv:Envelope']['soapenv:Body']['acc:QueryAcctInfoRspMsg']['acc:Account']['com:BillMedium'];
                // $iccid = $iccid_info['soapenv:Envelope']['soapenv:Body']['sub:QuerySubInfoRspMsg']['sub:Subscriber'];
                // assign parameters
               
                $old_bill_id = $billMedium['com:BillMediumId'];
                $old_bill_code = $billMedium['com:BillMediumCode'];
                
                
                $success = $this->NGBSSService->ChangeBillMedium($acct_id, $old_bill_id, $old_bill_code,$new_bill_id, $new_bill_code);
                Log::info($success);
                $successItems[] = $item[0];
            } catch (\Throwable $th) {
                //throw $th;
                $failedItems[] = $item[0];
            } dd($item);
        }
        Session::flash('success', 'Operation is submited!');
        return redirect()->back();
    }


    

    public function getDataGUI(Request $request) //-> return request from API
    {
        # code...
        $this->NGBSSService = new NGBSSService('en');
        $data = $this->NGBSSService->QueryAcctInfo($acct_id);
        dd($request);

    }
        

}
