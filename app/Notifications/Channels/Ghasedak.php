<?php namespace App\Notifications\Channels;

use App\Notifications\LoginWithGhasedakNotification;
use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Ghasedak\GhasedakApi;

class Ghasedak
{
    public function send($notifiable, LoginWithGhasedakNotification $notification)
    {
        if (!method_exists($notification, 'ghasedakSms')) {
            throw new \Exception("Error Processing Request");
        }

        // get data like activation code, user phone and ...
        $data = $notification->ghasedakSms($notifiable);

        // send data via ghasedak
        try {
            $message = $data['message'];
            $receptor = $data['phone'];
            $lineNumber = "10008566";
            $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
            $api->SendSimple($receptor, $message, $lineNumber);
        }
        catch(ApiException $e){
            throw $e;
        }
        catch(HttpException $e){
            throw $e;
        }
    }
}
