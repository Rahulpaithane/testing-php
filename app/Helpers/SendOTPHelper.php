<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class SendOTPHelper{
    public static function otpGenerator($sms_text, $mobile){

            $fields = array(
                "sender_id" => "TXTIND",
                "message" => $sms_text,
                "language" => "english",
                "route" => "v3",
                "numbers" =>  $mobile,
                "flash" => "0",
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization:hWC75ubPJSHY3b7lY7rnuMq88KO6oNHM4boCV5RW6akZom0FZ202k42joTFu",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

        return true;
    }
}
?>
