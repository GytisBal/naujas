<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelayController extends Controller
{
    public function control(Request $request)
    {
        $request->validate([
            'turn' => 'required',
        ]);


        $params = [
            'auth_key' => 'YzJkNHVpZAE6836A3F4E4C39A362E0EAB377E52C166F221554926191FEFBB8869DB6D4CF552C6EBC94151E17EF',
            'id' => '7AE9D2',
            'turn' => $request->turn,
            'channel'=> 0];

        $defaults = array(
            CURLOPT_URL => "https://shelly-1-eu.shelly.cloud/device/relay/control/",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

    }
}
