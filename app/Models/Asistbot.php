<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Asistbot extends Model
{
    use HasFactory;

    public static function getClient(){
        return new Client([
            'base_uri'=>'http://asistbot.com/api/',
            'verify' => false,
            'exceptions' => false,
            'connect_timeout' => 10,
            'query' => ['access_token' => env('ASISTBOT_ACCESS_TOKEN')]
        ]);
    }

    public static function logout(){
        self::getClient()->post('resetinstance.php', ['query' => [
            'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
            'instance_id'  => auth()->user()->whatsapp_instance_id
        ]]);

        Storage::append('whatsapp_hook.log', auth()->user()->whatsapp_instance_id . " requested logout");

        auth()->user()->update([
            'whatsapp_status'      => 'offline',
            'whatsapp_instance_id' => null
        ]);
    }

    public static function getInstanceId(){
        return Cache::remember('instance_id', 84600, function(){
            $response = self::getClient()->post("createinstance.php/?access_token=" . env('ASISTBOT_ACCESS_TOKEN'));

            if( $response->getStatusCode() != 200) return null;

            $data = json_decode( $response->getBody(), true );
            if( !array_key_exists('instance_id', $data) ) return null;
            return $data['instance_id'];
        });
    }

    public static function getQrCode(){
        $instance_id = self::getInstanceId();
        if( !$instance_id ) return ['status' => 'error', 'message' => 'No hay instancia'];

        $http = self::getClient();
        $response = $http->post("getqrcode.php", ["query"=>[
            'access_token' => env('ASISTBOT_ACCESS_TOKEN'),
            'instance_id'  => $instance_id,
        ]]);

        $data = json_decode($response->getBody(), 1);

        if( $response->getStatusCode() != 200){
            $data = [
                'base64' => null,
                'status' => 'error',
                'statusCode' => $response->getStatusCode()
            ];
            return $data;
        }

        if( $data['status'] == 'error'){
            if( $data['message'] == 'This Instance ID has been used' ){
                Cache::forget('instance_id');
                return self::getQrCode();
            }
        }

        self::setWebHook();
        return $data;
    }

    public static function setWebHook(){
        $instance_id = self::getInstanceId();
        if( !$instance_id ) return ['status' => 'error', 'message' => 'No hay instancia'];

        $http = self::getClient();
        $response = $http->post("getqrcode.php", ["query"=>[
            'webhook_url'  => 'http://phenlinea.com/whatsapp/hook',
            'enable'       => true,
            'access_token' => env('ASISTBOT_ACCESS_TOKEN'),
            'instance_id'  => $instance_id,
        ]]);
    }
}
