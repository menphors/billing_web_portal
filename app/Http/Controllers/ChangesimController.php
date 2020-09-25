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

class ChangesimController extends Controller
{
    protected $phoneNumber;
    protected $NGBSSService;
    
   
     
    public function index(){

        if(!Permission::check('supoffering', 'true'))
        {
            return view('permission.index');
        }
       
            return view('change_sim.index');
    }

    public function change_sim(Request $request){
    
        $cust_excel = $request->file('change_sim');
        $cust_info = Excel::toArray( new MsisdnsImport, $cust_excel); 
        $request->remark;
        
        $successItems = array();
        $failedItems = array();
      
            for($i=1;$i<count($cust_info[0]) ; $i++){
                $msisdn = $cust_info[0][$i][0];
                $new_iccid = $cust_info[0][$i][1];

                dd($msisdn);
                
                $this->NGBSSService = new NGBSSService($msisdn, "en"); 
               
                $iccid_info = $this->NGBSSService->QuerySubInfo($msisdn);
                
                // get queryaccinfo response
                $iccid = $iccid_info['soapenv:Envelope']['soapenv:Body']['sub:QuerySubInfoRspMsg']['sub:Subscriber'];
                // assign parameters
               
                $old_iccid = $iccid['sub:ICCID'] ;
               
                 
                $success= $this->NGBSSService->ChangeSim($msisdn, $new_iccid, $old_iccid);
                Log::info($success);
             }
     
        Session::flash('success', 'Operation submited!');
        return redirect()->back();
    
    }

    public function getDataGUI(Request $request) //-> return request from API
    {
        # code...
        $this->NGBSSService = new NGBSSService('en');
        $data = $this->NGBSSService->QuerySubInfo($msisdn);
        // dd($request);
}
}