<?php   
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Validator;
use Carbon\Carbon;
use Redirect;
   
class RegisterController
{

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $user = DB::table('users')
            ->where('email', $email)
            // ->where('status', 1)            
            ->first();
        if(empty($user)){
            $password = "123456789";
            $input['name'] = $email;
            $input['email'] = $email;
            $input['status'] = 0;
            $input['link_start'] = Carbon::now();
            $input['link_expire'] = Carbon::now()->addMinutes(3);

            $input['password'] = bcrypt($password);
            $user = User::create($input);
            $encrptId = Crypt::encryptString($user->id);
            $link = "http://127.0.0.1:8000/api/check/".$encrptId;
            $details = [
                'title' => 'Mail from Abacus Global',
                'body' => $link
            ];            

            \Mail::to($email)->send(new \App\Mail\PortalMail($details));
            $result['msg'] = 'Email';
            return $result;
        }else if($user->status == 0){
            // check your email
            $result['msg'] = 'Already Exists';
            return $result;
        }
        else{
            // move to home screen
            $user_auth = Auth::loginUsingId($user->id, true);
            $token = "Bearer " . $user_auth->createToken('MyApp')->accessToken;            
            $encrptId = Crypt::encryptString($user->id);
            $result['token'] = $token;
            $result['id'] = $encrptId;
            $result['msg'] = "ok";
            return $result;
        } 
    }

    public function check($encrptId)
    {
        $id = Crypt::decryptString($encrptId);

        $user = User::where('id', $id)->first();
        $current_time = Carbon::now();
        if($current_time > $user->link_expire)
        {
            $link_start = Carbon::now();
            $link_expire = Carbon::now()->addMinutes(3);
            
            $update = User::where('id', $id)
                    ->update(['link_start'=> $link_start,'link_expire'=> $link_expire]);

            if(!empty($update)){
                $link = "http://127.0.0.1:8000/api/check/".$encrptId;

                $details = [
                    'title' => 'Mail from Abacus Global',
                    'body' => $link
                ];

                \Mail::to($user->email)->send(new \App\Mail\PortalMail($details));                
                return "link has Expired a new link has sent to your email address";
            }
            else{
                return "Error";
            }
        }
        else{
            $user_auth = Auth::loginUsingId($id, true);
            $token = "Bearer " . $user_auth->createToken('MyApp')->accessToken;
            $update = User::where('id', $id)
                    ->update(['status'=> 1]);
            return Redirect::to("http://localhost/portal-master/home.php?id=$encrptId&token=$token");
        }
        
    }

    public function changeEmail(Request $request){

        $email = $request->email;
        if($request->id){
            $id = Crypt::decryptString($request->id);
            $user = User::where('id', $id)
            ->where('status', 1)
            ->first();

            if(empty($user)){
                return "Invalid request";
            }
            else{
                try {
                    $update = User::where('id', $id)
                    ->update(['email'=> $email]);
                    return "Email Updated ";

                } catch (\Illuminate\Database\QueryException $e) {
                    
                    return "Error in updating Email";
                }
            }
        }
        else{
            return "You are not allowed";
        }
    }
}