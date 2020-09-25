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

class AddSupOfferingController extends Controller
{

    
    protected $phoneNumber;
    protected $NGBSSService;
    
   
     
    public function index(){

        if(!Permission::check('supoffering', 'true'))
        {
            return view('permission.index');
        }
       
        return view('add_supplement_offer.index');


    }
    

    
    
    
    public function addSupOffering(Request $request){

        echo ('Hello World!');

     }
     
     

     

 



        

}
