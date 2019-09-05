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
        $currentDate = Carbon::now()->toDateString();

        foreach ($request->user()->devices as $device) {
            if ($device->pivot->expires_at !== null && $currentDate > $device->pivot->expires_at) {
                $request->user()->devices()->detach($device);
            }
        }

        $user = User::find($request->user()->id);

        if ($request->user()->hasRole('admin')) {
            $auth_key = $request->user()->auth_key;
        } else {
            $parent_id = $request->user()->parent_id;
            $auth_key = User::find($parent_id)->auth_key;
        }

        sleep(1);

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
                $device_id = strtolower($item['device_id']);
                $status = Arr::get($result, 'data.devices_status.' . $device_id . '.relays.0.ison');
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

        $statusResponse = json_decode($this->status($request));
        $token=$statusResponse->accessToken;
        $devices = $statusResponse->devices;
        $device_id = $request->input('device_id');

        $device = collect($devices)->where('device_id', $device_id)->all();

        if (count($device) <= 0) {
            return response(['devices' => $devices, 'accessToken' => $token]);
        } else {
            $channel = collect($device)->pluck('channel')[0];
            $status = collect($device)->pluck('status')[0];

            if ($request->user()->hasRole('admin')) {
                $auth_key = $request->user()->auth_key;
            } else {
                $parent_id = $request->user()->parent_id;
                $auth_key = User::find($parent_id)->auth_key;
            }

            if (!empty($auth_key)) {
                $newDevices = collect($devices)->map(function ($item) use ($device_id) {
                    if ($item->device_id === $device_id) {
                        if ($item->status === true) {
                            $turn = false;
                        } else {
                            $turn = true;
                        }
                        return collect($item)->put('turn', $turn);
                    } else {
                        return $item;
                    }
                });

                if ($status === true) {
                    $turn = 'off';
                } else {
                    $turn = 'on';
                }

                usleep(1800000);

                $params = [
                    'auth_key' => $auth_key,
                    'id' => $device_id,
                    'turn' => $turn,
                    'channel' => $channel];

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
                return response(['devices' => $newDevices, 'accessToken' => $token]);
            } else {
                return response(['message' => 'Bad Auth_Key or device_id']);
            }
        }
    }
}
