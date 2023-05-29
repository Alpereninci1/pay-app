<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    private $token = null;
    public function getToken()
    {
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/token';

        $app_id = 'c3d81ae3cc3011ef10dcefa31a458d65';
        $app_secret = '217071ea9f3f2e9b695d8f0039024e64';
        $client = new Client();

        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'app_id' => $app_id,
                    'app_secret' => $app_secret
                ]
            ]);
            $responseData = json_decode($response->getBody(), true);
            $this->token = $responseData['data']['token'];
            if ($responseData['status_code'] === 100) {
                return response()->json(
                    [
                        'message' => 'Token alındı.',
                        'data' => $responseData
                    ]
                );
            } else {
                return response()->json(['message' => 'Token alırken bir hata oluştu. Hata kodu: ' . $responseData['error_code']]);
            }
        }catch (\Exception $e){

            return response()->json(['message' => 'Token alırken bir hata oluştu: ' . $e->getMessage()]);
        }

    }
    public function processPayment3d(Request $request)
    {
        $this->getToken();
        $tokenValue = $this->token;

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart3D';

        $filePath = "products.json";

        $json = Storage::get($filePath);

        $items = json_decode($json, true);
        $products = '';
        foreach ($items as $item){
            $products = $item;
        }

        $ccHolderName = $request->input('cc_holder_name');
        $ccNo = $request->input('cc_no');
        $expiryMonth = $request->input('expiry_month');
        $expiryYear = $request->input('expiry_year');
        $currencyCode = $request->input('currency_code');
        $installmentsNumber = $request->input('installments_number');
        $invoiceId = $request->input('invoice_id');
        $invoiceDescription = $request->input('invoice_description');
        $total = $request->input('total');
        $merchantKey = $request->input('merchant_key');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $hashKey = $request->input('hash_key');
        $returnUrl = $request->input('return_url');
        $cancelUrl = $request->input('cancel_url');

        $client = new Client();
        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'cc_holder_name' => $ccHolderName,
                    'cc_no' => $ccNo,
                    'expiry_month' => $expiryMonth,
                    'expiry_year' => $expiryYear,
                    'currency_code' => $currencyCode,
                    'installments_number' => $installmentsNumber,
                    'invoice_id' => $invoiceId,
                    'invoice_description' => $invoiceDescription,
                    'total' => $total,
                    'merchant_key' => $merchantKey,
                    'name' => $name,
                    'surname' => $surname,
                    'hash_key' => $hashKey,
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'items' => $products,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                return view('payment_result', ['payment_result' => $response->getBody()]);
            } else {
                return response()->json(['message' => 'Ödeme işlemi başarısız.']);
            }
        } catch (\Exception $e) {
            // İstek sırasında bir hata oluşursa
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function processPayment2d(Request $request)
    {

        $this->getToken();

        $tokenValue = $this->token;

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart2D';

        $filePath = "products.json";

        $json = Storage::get($filePath);

        $items = json_decode($json, true);
        $products = '';
        foreach ($items as $item){
            $products = $item;
        }

        $client = new Client();
        $rawData = $request->getContent();
        $dataArray = json_decode($rawData, true);
        $dataArray['items'] = $products;
        $jsonData = json_encode($dataArray);
        try {
            $response = $client->post($apiUrl, [
                'body' => $jsonData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return $responseData;
            } else {
                return response()->json(['message' => 'Ödeme işlemi başarısız.']);
            }
        } catch (\Exception $e) {
            // İstek sırasında bir hata oluşursa
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function getInstallment(Request $request)
    {

        $this->getToken();
        $tokenValue = $this->token;
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/installments';
        $client = new Client();
        $rawData = $request->getContent();

        try {
            $response = $client->post($apiUrl,[
                'body' => $rawData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return $responseData;
            }else {
                return response()->json(['message' => 'İşlemi başarısız.']);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function getPos(Request $request)
    {

        $this->getToken();
        $tokenValue = $this->token;
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/getpos';
        $client = new Client();
        $rawData = $request->getContent();

        try {
            $response = $client->post($apiUrl,[
                'body' => $rawData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return $responseData;
            }else {
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function payByCardTokenNonSecure(Request $request)
    {

        $this->getToken();
        $tokenValue = $this->token;
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/payByCardTokenNonSecure';
        $client = new Client();
        $rawData = $request->getContent();

        try {
            $response = $client->post($apiUrl,[
                'body' => $rawData,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return $responseData;
            }else {
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}
