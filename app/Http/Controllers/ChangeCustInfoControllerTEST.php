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

class ChangeCustInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $phoneNumber;
    protected $NGBSSService;
    
     
    public function index(){
        if(!Permission::check('cust', 'true'))
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
        return view('change_cust_info.index', $customerInfo);
    }
    public function changeCustInfo(Request $request){

        $path = $request->file('cust_id');
        $cust_info = Excel::toArray( new MsisdnsImport, $path );
        //$request->remark

        //dd($request->remark);
        $resultId = DB::table("customer_info_report")
                        ->insertGetId(
                            [   
                                "Executed_By" => Auth::user()->id,
                                "Executed_Date" =>Carbon::now(),
                                "remark" => $request->remark,
                                "Amount" => count($cust_info[0]) -1
                            ]
                        );

        // for($i=1;$i<count($cust_info[0]) ; $i++){     

        //     $cust_id = $cust_info[0][$i][0];
        //     $phone_number = $cust_info[0][$i][1];
        //     $first_name = $cust_info[0][$i][2];
        //     $last_name = $cust_info[0][$i][3];
        //     $title = $cust_info[0][$i][4];
        //     $country = $cust_info[0][$i][5];
        //     $birthday = $cust_info[0][$i][6];
        //     $nationality = $cust_info[0][$i][7];
        //     $province = $cust_info[0][$i][8];
        //     $district = $cust_info[0][$i][9];
        //     $commune = $cust_info[0][$i][10];
        //     $village = $cust_info[0][$i][11];
        //     $street = $cust_info[0][$i][12];
        //     $block = $cust_info[0][$i][13];
        //     $houseno = $cust_info[0][$i][14];
        //     $addressid = $cust_info[0][$i][15];  
        //     //NGBSSService -> CustInfo AddressType = 3 (correct in API value)
        //     //dd($cust_info);
        //     $this->NGBSSService = new NGBSSService($phone_number, "en");

        //     $this->NGBSSService->changeCustInfo($cust_id, $title, $first_name, $last_name, $country, $birthday,
        //     $nationality, $province, $district, $commune, $village, $street, $block, $houseno, $addressid);
        // } 

        // Teacher Dara
        $successItems = array();
        $failedItems = array();
        // Loop 
        foreach($cust_info[0] as $item ) { 
            try { 
                $this->NGBSSService = new NGBSSService($phone_number, "en");     
                //$acct_id = $item[0];   // in batch format working base on acct_id data in excel
                $cust_id = $item[0];
            $phone_number = $item[1];
            $first_name = $item[2];
            $last_name = $item[3];
            $title = $item[4];
            $country = $item[5];
            $birthday = $item[6];
            $nationality = $item[7];
            $province = $item[8];
            $district = $item[9];
            $commune = $item[10];
            $village = $item[11];
            $street = $item[12];
            $block = $item[13];
            $houseno = $item[14];
            //$addressid = $cust_info[0][$i][15];   
              
                $oldinfo = $this->NGBSSService->QueryCustInfo($acct_id);

                // get queryaccinfo response
                $address = $oldinfo['soapenv:Envelope']['soapenv:Body']["cus:QueryCustInfoRspMsg"]["com:Address"]["com:AddressId"];
                
                // assign parameters
                $addressid = $address['com:AddressId'];
               
                
                $success = $this->NGBSSService->changeCustInfo($cust_id, $title, $first_name, $last_name, $country, $birthday,
                $nationality, $province, $district, $commune, $village, $street, $block, $houseno, $addressid);
                Log::info($success);
                $successItems[] = $item[0];
            } catch (\Throwable $th) {
                //throw $th;
                $failedItems[] = $item[0];
            }  dd($oldinfo);
        }
        Session::flash('success', 'Operation is submited!');
        return redirect()->back();
    }


    
        

}
