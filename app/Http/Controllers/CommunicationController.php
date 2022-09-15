<?php

namespace App\Http\Controllers;

use App\Communication;
use App\User;
use App\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

class CommunicationController extends Controller
{
    public function sendNotifications($data, $object){
        $emails = ['email','correo'];
        $sms = ['sms','mensaje'];
        $validations = Validation::all();

        foreach ($validations as $val){
            if( in_array(strtolower($val->name),$sms) &&  $val->active) {
                if(!is_null($data['cellphone']['phone_code']))
                    $this->sms_nexmo(['to' => $data['cellphone']['lada'] . $data['cellphone']['info'],
                        'from' => trans('user.app_title'),
                        'text' => $this->getText($object, 'sms'),
                        'country_code' => $data['cellphone']['phone_code']->phone_code]);
            }
            else if(in_array(strtolower($val->name),$emails) &&  $val->active)
                $this->sendmail(['to'   => [$data['email']['info']],
                    'subject' => trans('admin.mail_subject'),
                    'text' => $this->getText($object)]);
        }

    }

    public function sendmail($data, $attachment = null){
        Mail::to($data['to'])->send(new FeedbackMail($data, $attachment));

        if (Mail::failures())
            return response()->json(['success' => false]);
        return response()->json(['success' => true]);
    }

    # send sms using nexmo
    public function sms_nexmo($data){
        $basic  = new \Nexmo\Client\Credentials\Basic(env('SMS_API_KEY'), env('SMS_API_SECRET'));
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => $data['country_code'].$data['to'],
            'from' => $data['from'],
            'text' => $data['text']
        ]);
    }

    # send sms using SMS_MASIVO
    public function sms_masivo($data){
        $params = array(
            "message" => $data['text'],
            "numbers" => $data['to'],
            "country_code" => $data['country_code']
        );
        $headers = array(
            "apikey: ".env('SMS_MASIVO_KEY')
        );

        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.smsmasivos.com.mx/sms/send",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => 1
        ));
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function getText($person, $type_of_message = 'email'){
        $msg = '';
        $data = [];
        $user = User::where('person_id',$person->id)->first();
        if(is_null($user))
            return false;
        $data = [
            'user' => $user->name,
            'system_name' => trans('user.app_title'),
            'url' => route('person.verify',$user->temp_address)
        ];

        switch($type_of_message){
            case 'email':
                    $msg = sprintf(trans('admin.mail_msg'), $data['system_name'], $data['url']);
                break;
            case 'sms':
                    $msg = sprintf(trans('admin.sms_msg'), $data['system_name'], $data['url']);
                break;
        }
        return $msg;
    }

    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        return $this->internalFilter($data, with(new Communication())->getTable(), $exceptions);
    }
}
