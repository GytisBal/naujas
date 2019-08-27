<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RelayController extends Controller
{

    public function status(Request $request)
    {
        $token = auth()->tokenById($request->user()->id);
//        $id = $request->input('id');
//        $device = $request->user()->devices->find($id);
//
//        $device_id = $device->device_id;
        $user = $request->user();
        dd($user->devices);
        $currentDate = Carbon::now();
        foreach ($user->devices as $device) {
            if($device->pivot->expires_at !== null && $currentDate > $device->pivot->expires_at){
                $user->devices()->detach($device);
            }
        }
//        dd($user->devices->pivot);

        if($request->user()->hasRole('admin')) {
            $auth_key = $request->user()->auth_key;
        }else{
            $parent_id = $request->user()->parent_id;
            $auth_key = User::find($parent_id)->auth_key;
        }

         if (!empty($device_id) && !empty($auth_key)){
             $params = [
                 'auth_key' => $auth_key,
                 'id' => $device_id,
                 'channel' => 0];

             $defaults = array(
                 CURLOPT_URL => "https://shelly-1-eu.shelly.cloud/device/status",
                 CURLOPT_POST => true,
                 CURLOPT_POSTFIELDS => $params,
                 CURLOPT_RETURNTRANSFER => true,
             );

             $ch = curl_init();
             curl_setopt_array($ch, $defaults);
             $response = curl_exec($ch);
             $err = curl_error($ch);
             curl_close($ch);
             $result = json_decode($response, true);
            $object = Arr::get($result, 'data.device_status.relays.0.ison');

             if ($err) {
                 echo $err;
             }
             return json_encode(['status' => $object, 'accessToken'=>$token]);
        }else
            {
                return response (['message'=>'Bad Auth_Key or device_id']);
         }
    }

    public function control(Request $request)
    {

        $token = auth()->tokenById($request->user()->id);
        $id = $request->input('id');
        $device = $request->user()->devices->find($id);
        $device_id = $device->device_id;
        $status = json_decode($this->status($request))->status;

        if($request->user()->hasRole('admin')) {
            $auth_key = $request->user()->auth_key;
        }else{
            $parent_id = $request->user()->parent_id;
            $auth_key = User::find($parent_id)->auth_key;
        }
        if (!empty($device_id) && !empty($auth_key)){
        if ($status === true) {
            $turn = 'off';
        } else {
            $turn = 'on';
        }
        sleep(2);

            $params = [
                'auth_key' => $auth_key,
                'id' => $device_id,
                'turn' => $turn,
                'channel' => 0];

            $defaults = array(
                CURLOPT_URL => "https://shelly-1-eu.shelly.cloud/device/relay/control/",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_RETURNTRANSFER => true,
            );

            $ch = curl_init();
            curl_setopt_array($ch, $defaults);
            curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                echo $err;
            }

            return response(['turn' => $turn, 'accessToken'=>$token]);
        }else{
            return response (['message'=>'Bad Auth_Key or device_id']);
        }

    }
}
