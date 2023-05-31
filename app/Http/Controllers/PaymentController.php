<?php

namespace App\Http\Controllers;

use App\Helpers\HashGeneratorHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    public function getToken(Request $request)
    {
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/token';

        $app_id = Config::get('app.app_id');
        $app_secret = Config::get('app.app_secret');
        $client = new Client();

        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'app_id' => $app_id,
                    'app_secret' => $app_secret
                ]
            ]);
            $responseData = json_decode($response->getBody(), true);
            $token = $responseData['data']['token'];
            if ($responseData['status_code'] === 100) {
                $expiration = Carbon::now()->addHours(2); // Token süresi 2 saat
                Session::put('token', $token);
                Session::put('token_expiration', $expiration);
                return redirect()->route('payment.intermediate');
            } else {
                return response()->json(['message' => 'Token alırken bir hata oluştu. Hata kodu: ' . $responseData['error_code']]);
            }
        }catch (\Exception $e){

            return response()->json(['message' => 'Token alırken bir hata oluştu: ' . $e->getMessage()]);
        }

    }

    public function processPayment3d(Request $request)
    {
        $tokenValue = Session::get('token');

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
        $invoiceDescription = $request->input('invoice_description');
        $total = $request->input('total');
        $merchantKey = Config::get('app.merchant_key');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $hashKey = HashGeneratorHelper::hashGenerator();
        $invoiceId = Session::get('invoice_id');
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
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $view = View::make('payment_result')->with('responseHtml', $response->getBody());
                return $view;
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
        $tokenValue = Session::get('token');

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
        $merchant_key = Config::get('app.merchant_key');
        $dataArray['merchant_key'] = $merchant_key;
        $dataArray['hash_key'] = HashGeneratorHelper::hashGenerator();
        $dataArray['invoice_id'] = Session::get('invoice_id');
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
                return response()->json($responseData);
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
        $tokenValue = Session::get('token');

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/installments';
        $client = new Client();
        $merchant_key = Config::get('app.merchant_key');
        try {
            $response = $client->post($apiUrl,[
                'json' => [
                    'merchant_key' => $merchant_key
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return view('installment',['response_data' => $response->getBody()]);
            }else {
                return response()->json(['message' => 'İşlemi başarısız.']);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function getPos(Request $request)
    {

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/getpos';
        $client = new Client();

        $credit_card = $request->input('credit_card');
        $amount = $request->input('amount');
        $currency_code = 'TRY';
        $is_2d = $request->input('is_2d');
        $merchant_key = Config::get('app.merchant_key');

        try {
            $response = $client->post($apiUrl,[
                'json' => [
                    'credit_card' => $credit_card,
                    'amount' => $amount,
                    'currency_code' => $currency_code,
                    'merchant_key' => $merchant_key,
                    'is_2d' => $is_2d
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);
            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(),true);
                return view('get-pos-result',['response_data' => $response->getBody()]);
            }else {
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function payByCardTokenNonSecure(Request $request)
    {
        $tokenValue = Session::get('token');
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/payByCardTokenNonSecure';
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
        $merchant_key = Config::get('app.merchant_key');
        $dataArray['merchant_key'] = $merchant_key;
        $dataArray['hash_key'] = HashGeneratorHelper::hashGenerator();
        $dataArray['invoice_id'] = Session::get('invoice_id');
        $dataArray['items'] = $products;
        $jsonData = json_encode($dataArray);

        try {
            $response = $client->post($apiUrl,[
                'body' => $jsonData,
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

    public function getPosView()
    {
        return view('get-pos-view');
    }

    public function payment3DView()
    {
        return view('payment3d-view');
    }

    public function payment2DView()
    {
        return view('payment2d-view');
    }

    public function mainPage()
    {
        return view('main');
    }
    public function intermediate(Request $request)
    {
        return view('intermediate',['token' => Session::get('token')]);
    }

    public function payByCardTokenView()
    {
        return view('paybycardtoken-view');
    }
}
