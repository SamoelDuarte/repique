<?php

namespace App\Http\Controllers;

use App\Models\Calculo;
use App\Models\Device;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function send()
    {
        $calculos = Calculo::where('send', 1)->limit(5)->get();

        foreach ($calculos as $key => $calculo) {
            if ($calculo->user->phone) {
                // dd($calculo->user->phone);
                $evento = new Events();
                $device = Device::first();
                // dd($device);

                $evento->sendMessagem($device->session,$calculo->user->phone,"https://play.google.com/store/apps/details?id=br.com.repique");

                

            } 
            $calculo->send = 0;
                $calculo->update();
        }
    }
}
