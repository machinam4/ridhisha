<?php

namespace App\Http\Controllers;

use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SMSController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function sendRequest($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $curl_response = curl_exec($ch);
        curl_close($ch);
        return $curl_response;
    }

    public function getSMS()
    {
        $from_date = date('Y-m-d', strtotime('-2 days'));
        $to_date = date('Y-m-d');
        $start = 0;
        $length = 100;
        $response = $this->sendRequest('https://userapi.helomobile.co.ke/api/v2/GetSMS?ApiKey=' . env('SMS_API_KEY') . '&ClientId=' . env('SMS_API_CLIENT_ID') . '&start=' . $start . '&length=' . $length . '&fromdate=' . $from_date . '&enddate=' . $to_date);
        return $response;
    }

    public function creditBalance()
    {
        $response = $this->sendRequest('https://userapi.helomobile.co.ke/api/v2/Balance?ApiKey=' . env('SMS_API_KEY') . '&ClientId=' . env('SMS_API_CLIENT_ID') . '');
        return $response;
    }
    public function senderId()
    {
        $response = $this->sendRequest('https://userapi.helomobile.co.ke/api/v2/SenderId?ApiKey=' . env('SMS_API_KEY') . '&ClientId=' . env('SMS_API_CLIENT_ID') . '');
        return $response;
    }

    public function smsStats()
    {
        $response = [
            'credit' => $this->creditBalance(),
            'sender' => $this->senderId(),
        ];
        return json_encode($response);
    }

    public function sendSMS($request)
    {
        $count = $request->count;
        $message = $request->message;
        $schedule = $request->schedule;
        $path = $request->excel->store('public');
        $contents = Storage::path($path);
        $spreadsheet = IOFactory::load($contents)->getActiveSheet();
        $length = $spreadsheet->getHighestDataRow();
        $sendSMS = [];
        for ($i = 1; $i < $count + 1; $i++) {
            $players = $spreadsheet->getCell('A' . rand(1, $length))->getFormattedValue();
            if ($players == null || $players == '') {
                echo "";
            } else {
                $player = explode("-", $players);
                $playerName = explode(" ", $player[1])[1];
                $playerPhone = $player[0];
                $playerPhone254 = "";
                $messageName = $message;
                if (str_starts_with($playerPhone, '07')) {
                    $playerPhone254 = preg_replace('/07/', '2547', $playerPhone, 1);
                } else if (str_starts_with($playerPhone, '01')) {
                    $playerPhone254 = preg_replace('/01/', '2541', $playerPhone, 1);
                } else if (str_starts_with($playerPhone, '254')) {
                    $playerPhone254 = $playerPhone;
                } else {
                    echo "";
                }
                // echo $playerPhone254;
                if (str_contains($message, '#name')) {
                    $messageName = str_replace('#name', $playerName, $message);
                }
                $smsDetails = [
                    'Number' => $playerPhone254,
                    'Text' => $messageName
                ];
                // print_r($player_details);
                array_push($sendSMS, $smsDetails);
            }
        }
        // print_r($sendSMS);

        $Data = [
            "SenderId" => env("SMS_SENDER_ID"),
            // "Is_Unicode" => true,
            // "Is_Flash" => true,
            "scheduleDateTime" => $schedule,
            "messageParameters" => $sendSMS,
            "ApiKey" => env("SMS_API_KEY"),
            "ClientId" => env("SMS_API_CLIENT_ID")
        ];
        // return $Data;
        $response = Http::post('https://userapi.helomobile.co.ke/api/v2/SendBulkSMS', $Data);
        return  $response->body();
    }
}
