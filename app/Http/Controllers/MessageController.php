<?php

namespace App\Http\Controllers;

use App\Models\Asistbot;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{

    public function index(){
        if( !auth()->user()->phone ) return to_route('messages.edit_phone');
        if( !auth()->user()->instance_id ) return to_route('messages.scan');
    }

    public function scan(){
        $qr = Asistbot::getQrCode();
        return Inertia::render('Scan', ['qr' => $qr['base64'] ]);
    }

    public function updatePhone(Request $request){
        $request->validate(['phone'=>'required']);
        auth()->user()->update(['phone'=>$request->phone]);
        return to_route('messages.index');
    }

    public function editPhone(){
        return Inertia::render('Phone', ['phone'=>auth()->user()->phone]);
    }

    public function hook(Request $request)
    {
        $data    = json_encode($request->all());
        $arrData = json_decode($data);
        Storage::append('whatsapp_hook.log', json_encode($arrData));

        if ($arrData->event == 'ready') {
            $phone = explode(':', $arrData->data->id)[0];

            User::where('phone', $phone)->update([
                'whatsapp_status'      => 'online',
                'whatsapp_instance_id' => $arrData->instance_id
            ]);
            Storage::append('whatsapp_logins.log', json_encode($arrData));
            return 1;
        }

        if ($arrData->event == 'logout') {
            User::where('instance_id', $arrData->instance_id)->update([
                'whatsapp_status'      => 'offline',
                'whatsapp_instance_id' => null
            ]);
            Storage::append('whatsapp_logouts.log', json_encode($arrData));
        }
    }

    public function logout()
    {
        Asistbot::logout();
        return to_route('messages.index');
    }
}
