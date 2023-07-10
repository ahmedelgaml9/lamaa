<?php

namespace App\Classes;

use Illuminate\Http\Request;


class Payments
{

  public static function create($request, $paymentData = [])
  {

    $authUser = $paymentData['user'];
    $invoice = $paymentData['invoice'];
    $invoiceNumber = $paymentData['invoice_number'];
    $token = self::paylinkAuth();

    if(!$token){

        return response()->json(['success' => false,'status_code'=> 422, 'msg' => 'Cannot make auth', 'data' => []]);
    }

    $payload = json_encode([

        "amount" => $paymentData['amount'],
        "callBackUrl" => url('api/v3/paylink/return'),
        "clientEmail" => $authUser->email,
        "clientMobile" => $authUser->phone,
        "clientName" => $authUser->name,
        "note" => "This invoice is for VIP client.",
        "orderNumber" => $invoiceNumber,

    ]);

    $payUrl = 'https://restapi.paylink.sa/api/addInvoice';
    $ch = curl_init( $payUrl );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result);
    
    if(!isset($response->url)){

        return response()->json(['success' => false,'status_code'=> 422, 'msg' => isset($response->detail)?$response->detail:'Invoice not created', 'data' => []]);
    }

      $invoice->update(['transaction_code' => $response->transactionNo, 'paid' => 0, 'active' => 0]);

      return response()->json(['success' => true,'status_code'=> 200, 'data' => ['payment_url' => $response->url]]);
  }

  public static function paylinkAuth()
  {

      $payload = json_encode([

        "apiId" =>"APP_ID_1653166826792",
        "persistToken" => false,
        "secretKey" =>"3d11a238-8c0d-4302-a9f1-1214448b42aa",

      ]);

      $payUrl = 'https://restapi.paylink.sa/api/auth';
      $ch = curl_init( $payUrl );
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      curl_close($ch);
      $response = json_decode($result);
      if(isset($response->id_token)){

         return $response->id_token;
      }
      
      return false;
      
    }
}
