<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelayController extends Controller
{

    public function status(Request $request)
    {

        $params = [
            'auth_key' => 'YzJkNHVpZAE6836A3F4E4C39A362E0EAB377E52C166F221554926191FEFBB8869DB6D4CF552C6EBC94151E17EF',
            'id' => '7AE9D2',
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
        $object = $result["data"]["device_status"]["relays"][0];


        return  json_encode($object['ison']);

    }

    public function control(Request $request)
    {

        $status = $this->status($request);

        if($status === "true"){
            $turn = 'off';
        }else {
            $turn = 'on';
        }
        sleep(2);

        $params = [
            'auth_key' => 'YzJkNHVpZAE6836A3F4E4C39A362E0EAB377E52C166F221554926191FEFBB8869DB6D4CF552C6EBC94151E17EF',
            'id' => '7AE9D2',
            'turn' => $turn,
            'channel'=> 0];


        $defaults = array(
            CURLOPT_URL => "https://shelly-1-eu.shelly.cloud/device/relay/control/",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        return json_encode($turn);
    }
}
