<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
    public $BusinessShortCode = "818358";
    public $LipaNaMpesaPassKey = "4f0f6693c07b6374d4c0136750671adfacad04a2523e5fa4f1fcf8b6b7641bc6";
    public $TransactionType  = "CustomerPayBillOnline";
    public $CallBackURL  = "";

    public function initiatePayment(Request $request){
        $rules = [
            'amount'=>'required|numeric',
            'party_a'=>'required|min:12|max:12',
            'account_ref'=>'required',
            'trans_desc'=>'required',
            'remarks'=>'required',
            'booking_id'=>'required'
        ];
        $val = Validator::make($request->all(),$rules);
        if($val->fails()){
            return response()->json(['errors'=>$val->errors()]);
        }
        $booking_id = $request->booking_id;

        return response()->json($this->STKPushSimulation(
            $this->BusinessShortCode,
            $this->LipaNaMpesaPassKey,
            $this->TransactionType,
            $request->amount,
            $request->party_a,
            $this->BusinessShortCode,
            $request->party_a,//phone number
            $this->CallBackURL.$booking_id,
            $request->account_ref,
            $request->trans_desc,
            $request->remarks));

    }
    public function generateAccessToken()
    {

        $consumer_key="QNNoDTsuNVexj3ljJ8G4sFUhTPFlglGX";
        $consumer_secret="bDxPGaUBcIFdE5bI";
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token=json_decode($curl_response);
        //return response()->json($access_token->access_token);
        return $access_token->access_token;
    }
    public function STKPushSimulation(
        $BusinessShortCode,
        $LipaNaMpesaPasskey,
        $TransactionType,
        $Amount,
        $PartyA,
        $PartyB,
        $PhoneNumber,
        $CallBackURL,
        $AccountReference,
        $TransactionDesc,
        $Remarks){

        $env = 'live';
        if($env == 'live'){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = $this->generateAccessToken();
        }elseif($env == 'sandbox'){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = $this->generateAccessToken();
        }else{
            return response()->json(['message'=>'invalid application status']);
            //should write to the database
        }
        $timestamp='20'.date("ymdhis");
        $password=base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));
        $curl_post_data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $TransactionType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc,
            'Remarks'=>$Remarks
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response=curl_exec($curl);
        return $curl_response;
    }
}
