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
        $currentDate = Carbon::now();

        foreach ($user->devices as $device) {
            if ($device->pivot->expires_at !== null && $currentDate > $device->pivot->expires_at) {
                $user->devices()->detach($device);
            }
        }
//        $getValues = $user->devices->map(function ($item) {
//            return $item->only('device_id', 'channel');
//
//        });
//        $setToObject = $getValues->map(function ($item, $key) {
//            $item['id'] = $item['device_id'];
//            unset($item['device_id']);
//            return (object)$item;
//        });
//        $values = $setToObject->toArray();

        if ($request->user()->hasRole('admin')) {
            $auth_key = $request->user()->auth_key;
        } else {
            $parent_id = $request->user()->parent_id;
            $auth_key = User::find($parent_id)->auth_key;
        }

//        return json_encode(['status' => 'status', 'accessToken' => $token, 'devices'=>$user->devices]);

        if (!empty($auth_key)) {
            $params = [
                'auth_key' => $auth_key];

            $defaults = array(
                CURLOPT_URL => "https://shelly-1-eu.shelly.cloud/device/all_status",
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

            $devices = [];
            foreach ($user->devices->toArray() as $item) {
                $id = strtolower($item['device_id']);
                $status = Arr::get($result, 'data.devices_status.' . $id . '.relays.0.ison');
                $addStatusToDevice = Arr::add($item, 'status', $status);
                array_push($devices, $addStatusToDevice);

            }

            if ($err) {
                echo $err;
            }
            return json_encode(['accessToken' => $token, 'devices' => $devices]);
        } else {
            return response(['message' => 'Bad Auth_Key or device_id']);
        }
    }

    public function control(Request $request)
    {

        $token = auth()->tokenById($request->user()->id);
        $device_id = $request->input('device_id');

//        $device = $request->user()->devices->find($id);
//        $device_id = $device->device_id;
//        dd(json_decode($this->status($request))->devices);
        $devices = json_decode($this->status($request))->devices;
        $devicesCollection = collect($devices);
      $device =  $devicesCollection->where('device_id', "7AE9D2")->all();
        $status = $device[0]->status;

        if ($request->user()->hasRole('admin')) {
            $auth_key = $request->user()->auth_key;
        } else {
            $parent_id = $request->user()->parent_id;
            $auth_key = User::find($parent_id)->auth_key;
        }

        if (!empty($auth_key)) {
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

            return response(['turn' => $turn, 'accessToken' => $token]);
        } else {
            return response(['message' => 'Bad Auth_Key or device_id']);
        }

    }
}
