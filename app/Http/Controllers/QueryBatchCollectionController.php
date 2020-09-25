<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Imports\MsisdnsImport;
use App\Services\NGBSSService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\batch_collection;
use App\Exports\ExcelExport;
use Excel;




class QueryBatchCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){
        if(!Permission::check('batchcollection', 'true'))
        {
            return view('permission.index');
        }

        return view('batch_collection.index');
   }
  
    public function Querycollection(Request $request){
        ini_set('max_execution_time', 0);
        $cust_excel = $request->file('change_sim');
        $cust_info = Excel::toArray( new MsisdnsImport, $cust_excel); 
        $request->remark;
        
        $successItems = array();
        $failedItems = array();
      
            for($i=1;$i<count($cust_info[0]) ; $i++){
                $msisdn = $cust_info[0][$i][0];

                $this->NGBSSService = new NGBSSService($msisdn, "en");                
                $acctinfo = $this->NGBSSService->QueryAcctInfo($msisdn);               
                // get queryaccinfo response
                $cust_number = $acctinfo['soapenv:Envelope']['soapenv:Body']['acc:QueryAcctInfoRspMsg']['acc:Account'];
                // assign parameters                 
                $cust_id = $cust_number ['acc:CustId'];
                
                
                
                $custinfo = $this->NGBSSService->QueryCustInfoCBS($cust_id); 
                
                $custdata = $custinfo['soapenv:Envelope']['soapenv:Body']['bcs:QueryCustomerInfoResultMsg']['QueryCustomerInfoResult']['bcs:Customer']['bcs:CustInfo'];
                $custcode = $custdata ['bcc:CustCode'];
             
                $resultId = DB::table("batch_collection")
                ->insertGetId(
                [   
                    "CUST_CODE" => $custcode,
                    "SERVICE_NUMBER" => $msisdn,
                    "REMARK" => $request->remark,
                ]);
                return redirect('show');
            }
          
    //    Session::flash('success', 'Operation is submited!');
    //    return redirect()->back(); 
   }

   public function show(){
    $data['data'] = DB::table('batch_collection')->get();
    return view ('batch_collection.show',$data);

   }   
    
   public function export(){
        $data= DB::table('batch_collection')->get()->toArray();
        $data[] = array('CUST_CODE','SERVICE_NUMBER','REMARK');
        foreach($data as $result){
            $data_array[] = array(
            'CUST_CODE' => $result->CUST_CODE,
            'SERVICE_NUMBER' => $result->SERVICE_NUMBER,
            'REMARK' => $result->REMARK
        );
        }   
        Excel::create('data_excel', function($excel) use ($data_array)
        {
        $excel->setTitle('data_excel');
        $excel->sheet('data_excel', function($sheet)
            use ($data_array){
            $sheet->fromArray($data_array,null,'A1',false,false);
    
            });
    
        })->download('xlsx');

   }   

}
