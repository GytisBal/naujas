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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


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
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $devices = Device::all();
        $user=User::find($id);
        $userDevices = $user->devices;
//        $testas = $devices->map(function ($item){
//            return $item->name;
//        });
       $testas = $devices->pluck('name', 'device_id');



        return view('inc.deviceManagement')->with(['devices' => $testas,
            'userDevices'=>$userDevices, 'user'=>$user]);
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

    }

    public function addDevice(Request $request, $id)
    {
        $expires = Carbon::parse($request->input('date'));
        $deviceId = $request->input('device');
        $user = User::find($id);
        $device = Device::where('device_id', $deviceId)->get();
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
