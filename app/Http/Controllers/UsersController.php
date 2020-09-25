<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;
use Session;
use Illuminate\Support\Facades\Hash; 
 
use Unirest; 
use Helper;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 

        $result = DB::table('users')->where('id','!=',Auth::user()->id)->orderBy('id', 'asc')->paginate(10);
   
        return view('user.index',['header'=>'user','users'=>$result]);
    }


    public function activateUser(Request $request){

         DB::table('users')
        ->where('id', $request->userId)
        ->update(['status' => 1]);
        
          return redirect('user');
   
    }

    public function deactivateUser(Request $request){

        DB::table('users')
        ->where('id', $request->userId)
        ->update(['status' => 0]);
        
          return redirect('user');
  
   }

   public function addUser(){
    $user_types = DB::table('user_types')->where('code','!=',2)->get();
    $smart_shop_locations = DB::table('smart_shop_locations')->get();
 
      return view('user.add_user',['header'=>'user','user_types'=>$user_types,'smart_shop_locations'=> $smart_shop_locations]);
  
    }

    public function editUser(Request $request){

        $user_types = DB::table('user_types')->where('code','!=',2)->get();
        $smart_shop_locations = DB::table('smart_shop_locations')->get();
        $user = DB::table('users')->where('id',$request->userId)->first();
     
          return view('user.edit_user',['header'=>'user','user_types'=>$user_types,'smart_shop_locations'=> $smart_shop_locations,'user' => $user]);
      
        }

        
        public function updateUser(Request $request){

            $password = $request->password;
    
            if($password == null){
                DB::table('users')->where('id',$request->userId)->update([
                    'name' => $request->username,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'position' => $request->position,
                    'user_type' => $request->user_type,
                    'location_id' => $request->location_id,
                 ]);
    
            }else{
                $password = $password->trim();

                if($password == ""){
                    DB::table('users')->where('id',$request->userId)->update([
                        'name' => $request->username,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'position' => $request->position,
                        'user_type' => $request->user_type,
                        'location_id' => $request->location_id,
                     ]);
                }else{
                    DB::table('users')->where('id',$request->userId)->update([
                        'name' => $request->username,
                        'email' => $request->email,
                        'password' => $request->password,
                        'phone_number' => $request->phone_number,
                        'position' => $request->position,
                        'user_type' => $request->user_type,
                        'location_id' => $request->location_id,
                     ]);
                }
               
    
            }
             
          
            
              return redirect('/user');
          
        }


    public function saveUser(Request $request){
         
 
         DB::table('users')->insert([
            'name' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'phone_number' => $request->phone_number,
            'position' => $request->position,
            'user_type' => $request->user_type,
            'location_id' => $request->location_id,
         ]);

         return redirect('/user/add');
      
    }

    public function agentLogin(Request $request){
      
        $results = DB::table('access_users')
        ->where('status', true)
        ->select('name')
        ->get();
        
        $listIfUsers = [];

        foreach($results  as $key => $value ){
            $listIfUsers[$key] = $value->name;
        } 
 
        

        if(in_array($request->username, $listIfUsers)){
            
            

            $headers = array('Authorization'=>'Bearer '. $this->getAccessToken()->access_token ,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json');
        
            $data = array('username' => $request->username,
                            'password'  =>  $request->password ,
            );
             
            $body = Unirest\Request\Body::json($data);
    
            $baseUrl = "https://single-signon.smart.com.kh/public/";
            $baseUrlLocal = "http://localhost:3000/";
            
    
            $response = Unirest\Request::post($baseUrl .'api/ldap/authenticate',$headers ,  $body);
           
        
    
             if($response->body->code == 200){
                $ldapUser = $response->body->user;
                $user = DB::table('users')->where('name','=',$ldapUser->username)->first();
                
                if($user){
                    DB::table('users')
                        ->where('name', $ldapUser->username)
                        ->update(['phone_number' =>  $ldapUser->phone_number,
                                  'position' =>  $ldapUser->title,
                                  'location' =>  $ldapUser->location,
                                 
                        ]);
                }else{
                    
                    DB::table('users')
                    ->insert(['name' =>  $ldapUser->username,
                              'email' =>  $ldapUser->email,
                              'phone_number' =>  $ldapUser->phone_number,
                              'position' =>  $ldapUser->title,
                              'location' =>  $ldapUser->location,
                              'status' =>  1,
                    ]);
                   
                   
                }
    
                
                   // dd($ldapUser->username);
             
                if(Auth::attempt(['name' => $ldapUser->username,'password'=>  'BelieveInYourself' ,'status' => 1])){
                    return redirect()->intended('home');
                 }else{
                    return view('home',['error'=>1]);
                 } 
                
             }else if($response->body->code == 401){
    
                return view('home',['error'=>1]);
             }    
        }else{ 
            
            return redirect()->back();
        }

       

    }


    public function getAccessToken(){
        $headers = array('Authorization'=>'Basic dkFJUU5Wd2J5alZqOGZNOU0xOHZ4UXA4YVJBYTpzZm4xTlc1SThiNU1aTnVoNWlmZnpLUHN3QXdh',
                         'Content-Type' => 'application/x-www-form-urlencoded');
        //$data = array('grant_type' => 'client_credentials', 'username' => 'smartpayways','password' => 'Penlymeng123','Scope' => 'PRODUCTION');
        $data = array('grant_type' => 'client_credentials');
        $body = Unirest\Request\Body::form($data);
    
        $response = Unirest\Request::post('https://mife.smart.com.kh:8243/token',$headers ,  $body);
     
        if($response->code === 200){
            return  $response->body; 
        }
    
       
    }

    public function agentLogout(Request $request){
        Auth::logout();
        return redirect('/');
    }
    

    public function welcome(Request $request){
        if(Auth::check()){
            return redirect('/agent');
        }else{
            return view('welcome',['error'=>0]);
        }
         

    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
