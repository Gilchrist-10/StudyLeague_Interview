<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Mail\SendPromotionEmailComponent;
use Mews\Captcha\Captcha;
use Response;
use Cache;

class RegisterUserController extends Controller
{
    public function registerUserView(){
        return view('registerUserView');
    }

    public function updatePromotion(){
        \Log::info("Cron is working fine!");
        if(Cache::get('pool_status') == '' || Cache::get('pool_status') == 'pool_second'){
            $userData = DB::table('USERS')->select('id','name','email')->orderBy('ID','ASC')->get()->toArray();
            if(count($userData) != 15){
                $supportId = 1;
                if(Cache::get('pool_status') == 'pool_second'){
                    $poolNo = 3;
                    $amount = 28;
                    Cache::put('pool_status','second_pool');
                }else{
                    $amount = 14;
                    $poolNo = 2;
                    Cache::put('pool_status','started');
                }


                for ($i=1; $i <= 14; $i++) { 
                    DB::table('USERS')->whereId($userData[$i]->id)->update(['SUPPORT_ID' => $supportId]);
                }
                    DB::table('USERS')->whereId($userData[0]->id)->update(['SUPPORT_ID' => $supportId,'POOL_NO' => $poolNo,'AMOUNT'=>$amount]);

                // $maildata = [
                //     'name' => $userData[0]->name,
                //     'amount' => $amount,
                //     'pool_no' => $poolNo
                // ];

                // \Mail::to($userData[0]->email)->send(new SendPromotionEmailComponent($maildata));
            }
        }

        if(Cache::get('pool_status') != ''){
            if(Cache::get('pool_status') == 'second_pool'){
                $secondPoolData = DB::table('USERS')->where('POOL_NO',2)->get()->toArray();
                for ($i=0; $i < count($secondPoolData); $i++) { 
                    $dataUpdateIf8 = DB::table('USERS')->where('SUPPORT_ID',$secondPoolData[$i]->id)->where('POOL_NO',2);
                    if($dataUpdateIf8->count() == 8){
                        DB::table('USERS')->whereId($secondPoolData[$i]->id)->update(['POOL_NO' => 3,'AMOUNT'=>16]);
                    }
                }
            }

            $getNullSupportIdData = DB::table('USERS')->where('SUPPORT_ID',NULL)->orderBy('ID')->get()->toArray();

            for ($i=0; $i < count($getNullSupportIdData); $i++){ 
                $countUserSupport = DB::table('USERS')->select('SUPPORT_ID', DB::raw('count(*) as total'))->where('SUPPORT_ID','<>',null)
                                         ->groupBy('SUPPORT_ID')
                                         ->orderBy('ID','DESC')
                                         ->get()->toArray();
                $getSupportId = (array) current($countUserSupport);

                //first find the data is 8.
                if(Cache::get('updateSupportId') == ''){
                    $updateSupportId = $getNullSupportIdData[$i]->id + 7;
                    $countIFexist = DB::table('USERS')->where('ID',$updateSupportId)->count();
                    if($countIFexist == 0){
                        break;
                    }
                }

                $updateSupportId = Cache::get('updateSupportId');
                if($getSupportId['total'] <= 7 && $updateSupportId <= $getNullSupportIdData[$i]->id){
                    $promotePoolId = $getSupportId['SUPPORT_ID'];
                }else{
                    $getCalc = count($countUserSupport) - 2;
                    if($getCalc < 0){
                        $promotePoolId = '0';
                    }else{
                        $promotePoolId = 7 * abs($getCalc);
                        if($promotePoolId == 0){
                            $promotePoolId = 7;
                        }else{
                            $getCalc = count($countUserSupport) - 1;
                            $promotePoolId = 7 * abs($getCalc);
                        }
                    }
                    $promotePoolId = $getNullSupportIdData[$i]->id - 14 - $promotePoolId; 
                    $promotePoolId = abs($promotePoolId);
                }
                if($updateSupportId != ''){ 
                    if($updateSupportId == $getNullSupportIdData[$i]->id){
                        $check14RecorsToPool2 = DB::table('USERS')->where('POOL_NO',2)->where('SUPPORT_ID',1)->where('AMOUNT',8)->count();
                        if($check14RecorsToPool2 == 14 && Cache::get('pool_status') == 'started'){
                            Cache::put('pool_status','pool_second');
                        }
                        DB::table('USERS')->whereId($promotePoolId)->update(['POOL_NO' => 2,'AMOUNT'=>8]);
                        Cache::forget('updateSupportId');
                        continue;
                    }else{
                        continue;
                    }
                }else{
                    $updateSupportId = $getNullSupportIdData[$i]->id + 7;
                    Cache::put('updateSupportId',$updateSupportId);
                }
                DB::table('USERS')->whereBetween('ID',[$getNullSupportIdData[$i]->id,$updateSupportId])->update(['SUPPORT_ID' => $promotePoolId]);
            }
        }
    }

    public function registerUserAccount(Request $request){
        $userData = $request->all();
        if(isset($userData['captcha']) && $userData['captcha'] != ''){
            $checkCaptcha = captcha_check($userData['captcha']);
            if(!$checkCaptcha){
                return Response::json(['error' => 'Please Enter An Valid Captcha - ID'], 404);
            }

        }

        if(isset($userData['email']) && $userData['email'] != ''){
            $count = DB::table('USERS')->where('EMAIL',$userData['email'])->count();
            if($count >= 1){
                return Response::json(['error' => 'Email-Id Already Exists.Please Try with different Email-id!!!'], 404);
            }else{
                DB::table('USERS')->insert(['email' => $userData['email'],'name'=>$userData['name'],'CREATED_AT'=>Carbon::now()]);
                return response()->json(['success'=>'User Registered Successfully']);
            }
        }
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}
