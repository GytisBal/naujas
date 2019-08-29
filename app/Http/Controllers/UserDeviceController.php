<?php

namespace App\Http\Controllers;

use App\Device;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $devices = Device::all();
        $user = User::find($id);
        $userDevices = $user->devices;
        $devices = $devices->pluck('name', 'device_id');


        return view('inc.deviceManagement')->with(['devices' => $devices,
            'userDevices' => $userDevices, 'user' => $user]);
    }

    public function addDevice(Request $request, $id)
    {

        if ($request->input('date') === null) {
            $expires = $request->input('date');
        } else {
            $expires = Carbon::parse($request->input('date'))->toDateString();
        }
        $deviceId = $request->input('device');

        $user = User::find($id);
        $device = Device::where('device_id', $deviceId)->get();
        $devices = $user->devices;

        foreach ($devices as $item) {
            if (collect($item)->contains($deviceId) === true) {
                $user->devices()->detach($item);
            }
        }

        $user->devices()->attach($device, ['expires_at' => $expires]);
        return redirect()->back();
    }

    public function removeDevice(Request $request)
    {
        $deviceId = $request->deviceId;
        $userId = $request->user_id;
        $user = User::find($userId);
        $device = Device::find($deviceId);
        $user->devices()->detach($device);

        return redirect()->back();
    }
}
