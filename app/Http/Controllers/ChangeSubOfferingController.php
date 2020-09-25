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

class ChangeSubOfferingController extends Controller
{

    
    protected $phoneNumber;
    protected $NGBSSService;
    
   
     
    public function index(){
         
        $completedTrns['completed_trns'] = DB::table('change_offering_schedules')
        ->join('users', 'users.id', '=', 'change_offering_schedules.user_id')
        ->select('change_offering_schedules.*', 'users.name as user_name')
        ->where("completed" , true)
        ->orderBy("id" , "desc")
        ->paginate(20);
        return view('completed.index', $completedTrns);
    }
    //todo
    public function todo()
    {
        $todos['todos'] = DB::table('change_offering_schedules')
        ->join('users', 'users.id', '=', 'change_offering_schedules.user_id')
        ->select('change_offering_schedules.*', 'users.name as user_name')
        ->where("completed" , false)
        ->orderBy("id" , "desc")
        ->paginate(20);
        return view('to_do.index', $todos);
    }
    //Index View
    public function home()
    {
        if(!Permission::check('home', 'true'))
        {
            return view('permission.index');
        }
        return view('change_prioffering.index');
    }

    public function changeUserPrimaryOffering(Request $request){

        $msisdns = $request->file('msisdns'); 
        $msisdns = Excel::toArray( new MsisdnsImport, $msisdns );
        $now = Carbon::now();
       
          
 
 
        try{
            
            if($request->execution_mode == "execute_schedule"){

                switch($request->effective_mode){
                    case 'effective_schedule':

                            $executeSchedule = Carbon::parse($request->execute_schedule);
                            $effectiveSchedule = Carbon::parse($request->effective_schedule)->subHours(7)->format('YmdHis');

                            $resultId = DB::table("change_offering_schedules")
                            ->insertGetId(
                                [   
                                    "user_id" => Auth::user()->id,
                                    "offering_id" => $request->new_primary_offering,
                                    "effective_date" => $effectiveSchedule ,
                                    "execute_date" => $executeSchedule,
                                    "remark" => $request->remark,
                                    "created_at" => Carbon::now(),
                                    "updated_at" => Carbon::now()
                                ]
                            ); 

                     break;
                    //  case 'effective_immediately':

                    //         $afterNow = $now->addHours(5); 
                    //         $afterNow = Carbon::parse($afterNow)->format('YmdHis');

                    //         $resultId = DB::table("change_offering_schedules")
                    //         ->insertGetId(
                    //             [   
                    //                 "user_id" => Auth::user()->id,
                    //                 "offering_id" => $request->new_primary_offering,
                    //                 "effective_date" => $afterNow ,
                    //                 "execute_date" => $request->execute_schedule,
                    //                 "remark" => $request->remark,
                    //                 "created_at" => Carbon::now(),
                    //                 "updated_at" => Carbon::now()
                    //             ]
                    //         );
                             
                    //  break;
                     case 'next_bill_cycle':

                            $thisMonth = new Carbon('first day of this month');
                            $nextMonth = $thisMonth->addMonth(1);
                            $nextMonth = $thisMonth->subDay();
                            $nextMonth = Carbon::parse($nextMonth)->format('Ymd170000');

                            $resultId = DB::table("change_offering_schedules")
                            ->insertGetId(
                                [   
                                    "user_id" => Auth::user()->id,
                                    "offering_id" => $request->new_primary_offering,
                                    "effective_date" => $nextMonth ,
                                    "execute_date" => $request->execute_schedule,
                                    "remark" => $request->remark,
                                    "created_at" => Carbon::now(),
                                    "updated_at" => Carbon::now()
                                ]
                            );
                     
                     break;
                     default:
                      
                }
                
                for($i=1;$i<count($msisdns[0]) ; $i++){
                    $msisdn = $msisdns[0][$i][0];
  
                    try{
                        DB::table("change_offering_msisdns")
                        ->insert([
                            "msisdn" => explode("." ,$msisdn)[0],
                            "change_offering_schedule_id" => $resultId,
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now()
                        ]);
                      }catch(Exception $ex){
            
                      }
                }

               
            }else{
                
               $execute_date_now = Carbon::parse(Carbon::now())->format('Y-m-d');
                 
               switch($request->effective_mode){
                   case 'effective_schedule':
                             
                        $scheduleDate = new Carbon( $request->effective_schedule);
                        $scheduleDate = Carbon::parse($scheduleDate)->subHours(7);
                          

                        $resultId = DB::table("change_offering_schedules")
                        ->insertGetId(
                            [   
                                "user_id" => Auth::user()->id,
                                "offering_id" => $request->new_primary_offering,
                                "effective_date" =>$scheduleDate,
                                "execute_date" => $execute_date_now ,
                                "remark" => $request->remark,
                                "completed" => true,
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now()
                            ]
                        ); 


                        for($i=1;$i<count($msisdns[0]) ; $i++){
                            $msisdn = $msisdns[0][$i][0];   
                            $msisdn = explode("." , $msisdn)[0]; 
                            
                            $this->requestChangePrimaryOffering($msisdn,$request->new_primary_offering,$scheduleDate );
                        }

                    break;
                    case 'effective_immediately':
 
                        

                        $afterNow = $now->addMinute(1); 
                        $afterNow = Carbon::parse($afterNow)->format('YmdHis');
                             

                    $resultId = DB::table("change_offering_schedules")
                    ->insertGetId(
                        [   
                            "user_id" => Auth::user()->id,
                            "offering_id" => $request->new_primary_offering,
                            "effective_date" => $afterNow ,
                            "execute_date" => $execute_date_now ,
                            "remark" => $request->remark,
                            "completed" => true,
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now()
                        ]
                    );
                      

                    for($i=1;$i<count($msisdns[0]) ; $i++){
                        $msisdn = $msisdns[0][$i][0];   
                        $msisdn = explode("." , $msisdn)[0];  

                        $afterNow = $now->addMinute(1); 
                        $afterNow = Carbon::parse($afterNow)->format('YmdHis');
                       $this->requestChangePrimaryOfferingImmediately($msisdn,$request->new_primary_offering);
                    }

                    break;
                    case 'next_bill_cycle':
                         

                        $thisMonth = new Carbon('first day of this month');
                        $nextMonth = $thisMonth->addMonth(1);
                        $nextMonth = Carbon::parse($nextMonth)->format('YmdHis');
                        
                        $resultId = DB::table("change_offering_schedules")
                        ->insertGetId(
                            [   
                                "user_id" => Auth::user()->id,
                                "offering_id" => $request->new_primary_offering,
                                "effective_date" => $nextMonth ,
                                "execute_date" =>$execute_date_now ,
                                "remark" => $request->remark,
                                "completed" => true,
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now()
                            ]
                        );
                        
                        for($i=1;$i<count($msisdns[0]) ; $i++){
                            $msisdn = $msisdns[0][$i][0];   
                            $msisdn = explode("." , $msisdn)[0];

                            $this->requestChangePrimaryOfferingNextBill($msisdn,$request->new_primary_offering);
                        }

                    break;
                    default:
                     
               }
  
            }

             

        }catch(Exception $ex){
            
        } 

        Session::flash('success', 'Operation is submited!');
        

        return redirect()->back();
     }
     
     public function requestChangePrimaryOffering($phoneNumber,$newOffering,$effectiveDate){

            // dd($effectiveDate);
            $this->NGBSSService = new NGBSSService($phoneNumber, "en");
            

            $offeringId = $this->NGBSSService->getSubscribedPlan();

            $result =  $this->NGBSSService->changePrimaryOffering($offeringId,$newOffering,$effectiveDate);
             
 
            $result['msisdn'] = $phoneNumber;

            if($result['status']){
                Log::info($result);
            }else{
                Log::alert($result); 
            }
            
     } 

     public function requestChangePrimaryOfferingImmediately($phoneNumber,$newOffering){
        $this->NGBSSService = new NGBSSService($phoneNumber, "en");
        

        $offeringId = $this->NGBSSService->getSubscribedPlan();

        $result =  $this->NGBSSService->changePrimaryOfferingImmediately($offeringId,$newOffering);
         

        $result['msisdn'] = $phoneNumber;

        if($result['status']){
            Log::info($result);
        }else{
            Log::alert($result); 
        }
        
 }

 public function requestChangePrimaryOfferingNextBill($phoneNumber,$newOffering){
    $this->NGBSSService = new NGBSSService($phoneNumber, "en");
    

    $offeringId = $this->NGBSSService->getSubscribedPlan();

    $result =  $this->NGBSSService->changePrimaryOfferingNextBill($offeringId,$newOffering);
     

    $result['msisdn'] = $phoneNumber;

    if($result['status']){
        Log::info($result);
    }else{
        Log::alert($result); 
    }
    
}



        public function scheduleChangePrimaryOffering(){
         
 

        $requestChange =  DB::table("change_offering_schedules")
                          ->where('completed' , false)    
                          ->whereDate('execute_date' , "<=" , Carbon::now())    
                          ->first();
          

        if($requestChange != null){

           $status = DB::table("change_offering_schedules")
                    ->where('id' , $requestChange->id)    
                    ->update(["completed" => true]);

            if($status > 0){

                    $msisdns =  DB::table("change_offering_msisdns")
                    ->where('change_offering_schedule_id' , $requestChange->id)    
                    ->get();
                    
                    


                    $effectiveDate = Carbon::parse($requestChange->effective_date)->format('YmdHis');
                        
                    foreach($msisdns as $msisdn){
                        $this->NGBSSService = new NGBSSService($msisdn->msisdn, "en");
                        $planId = $this->NGBSSService->getSubscribedPlan();
                    
                        $result =  $this->NGBSSService->changePrimaryOffering($planId,$requestChange->offering_id,$effectiveDate);
                          
                        $result['msisdn'] = $msisdn;

                        if($result['status']){
                            Log::info($result);
                        }else{ 
                            Log::alert($result); 
                        }
                    }            
            }
             
            
}


       
        
 }

}
