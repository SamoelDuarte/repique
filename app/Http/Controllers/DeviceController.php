<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeviceController extends Controller
{

    public function __construct() {
        $devicesComStatusNull = Device::whereNull('status')->get();
        $devicesComStatusNull->each->delete();
    }
    public function index(){
        return view('admin.device.index');
    }

    public function create(){
      


        $device = new Device();
    
        $device->session = Utils::createCode();
        $device->save();
        return view('admin.device.create', compact('device'));
    }

    public function getDevices(){
        $devices = Device::orderBy('id');
        return DataTables::of($devices)->make(true);

    }

    public function updateStatus(Request $request){

        $device = Device::where('id' , $request->id)->first();
        $device->status = $request->status;
        $device->name = $request->name;
        $device->picture = $request->picture;
        $device->jid = $request->jid;
        $device->update();
        
        echo json_encode(array('status' => '1'));
    }

    public function updateName(Request $request){
        $device = Device::where('id' , $request->id)->first();
        $device->name = $request->name;
        $device->update();
        echo json_encode(array('status' => '1'));
    }
}
