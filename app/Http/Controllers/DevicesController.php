<?php

namespace App\Http\Controllers;

use App\Device;
use App\User;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    {
        $devices = Device::all();

        return view('pages.devices')->with(['devices' => $devices]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deletedDevice = Device::onlyTrashed()
            ->where('device_id', $request->input('device_id'));

        if (count($deletedDevice->get()) > 0) {
            $getId = $deletedDevice->get()->toArray()[0]['id'];
            $deletedDevice->restore();
            $device = Device::find($getId);
        } else {
            $request->validate([
                'name' => 'required|unique:devices,name',
                'device_id' => 'required|unique:devices,device_id'
            ]);
            $device = new Device;
            $device->name = $request->input('name');
            $device->device_id = $request->input('device_id');
        }

        $device->user_id = auth()->user()->id;
        $device->active = 1;
        $device->save();

        $users = User::whereHas('roles', function ($role) {
            $role->where('name', 'admin');
        })->get();

        $device->users()->attach($users);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->userId;
        $device = Device::find($id);
        $users = User::all();
        $device->users()->detach($users);
        $device->delete();

        return redirect()->back();
    }
}
