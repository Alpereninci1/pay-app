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
    public function getToken()
    {
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/token';

        $app_id = Config::get('app.app_id');
        $app_secret = Config::get('app.app_secret');
        $client = new Client();

        try {
            $response = $client->post($apiUrl, [
                'json' => [
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
                return $responseData;
            } else {
                return response()->json(['message' => 'Token alırken bir hata oluştu. Hata kodu: ' . $responseData['error_code']]);
            }
        }catch (\Exception $e){

            return response()->json(['message' => 'Token alırken bir hata oluştu: ' . $e->getMessage()]);
        }

    }

    public function processPayment3d(Request $request)
    {
        if (Session::has('token')) {
            $tokenValue = Session::get('token');
        } else {
            $this->getToken();
        }

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart3D';

        $total = (float)$request->input('total');
        $ccHolderName = $request->input('cc_holder_name');
        $ccNo = $request->input('cc_no');
        $expiryMonth = $request->input('expiry_month');
        $expiryYear = $request->input('expiry_year');
        $currencyCode = Config::get('app.currency_code');
        $installmentsNumber = $request->input('installments_number');
        $invoiceDescription = Config::get('app.invoice_description');
        $merchantKey = Config::get('app.merchant_key');
        $name = Config::get('app._name');
        $surname = Config::get('app.surname');
        $hashKey = HashGeneratorHelper::hashGenerator($total,$installmentsNumber);
        $invoiceId = Session::get('invoice_id');
        $returnUrl = Config::get('app.return_url');
        $cancelUrl = Config::get('app.cancel_url');

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
                    'items' => json_encode([
                        [
                            'name' => 'item 1',
                            'price' => $total,
                            'quantity' => 1,
                            'description' => 'asfasfasfas'
                        ]
                    ]),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenValue
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                return $response->getBody();
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
        if (Session::has('token')) {
            $tokenValue = Session::get('token');
        } else {
            $this->getToken();
        }

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart2D';

        $filePath = "products.json";

        $json = Storage::get($filePath);

        $items = json_decode($json, true);

        $products = [];

        $total = (float)$request->input('total');
        $installmentsNumber = $request->input('installments_number');

        foreach ($items['products'] as $item) {
            $obj = new \stdClass();
            $obj->price = (float)$request->input('total');
            $obj->name = $item['name'];
            $obj->description = $item['description'];
            $obj->quantity = $item['quantity'];

            $products[] = $obj;
        }

        $client = new Client();
        try {
            $response = $client->post($apiUrl, [
                'json' => [
                    'cc_holder_name' => $request->input('cc_holder_name'),
                    'cc_no' => $request->input('cc_no'),
                    'expiry_month' => $request->input('expiry_month'),
                    'expiry_year' => $request->input('expiry_year'),
                    'merchant_key' => Config::get('app.merchant_key'),
                    'currency_code' => Config::get('app.currency_code'),
                    'invoice_description' => Config::get('app.invoice_description'),
                    'total' => $total,
                    'installments_number' => $installmentsNumber,
                    'name' => Config::get('app.name'),
                    'surname' => Config::get('app.surname'),
                    'hash_key' => HashGeneratorHelper::hashGenerator($total,$installmentsNumber),
                    'invoice_id' => Session::get('invoice_id'),
                    'items' => $products
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);
            if($response->getStatusCode() === 200) {
                return view('success',[$response->getBody()]);
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
        if (Session::has('token')) {
            $tokenValue = Session::get('token');
        } else {
            $this->getToken();
        }

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
        $this->getToken(); // token aldığımız ilk yer
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/getpos';
        $client = new Client();

        $credit_card = $request->input('credit_card');
        $amount = $request->input('amount');
        $currency_code = Config::get('app.currency_code');
        $is_2d = '0';
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

    public function processPayment(Request $request)
    {
        $is3D = $request->has('3d_checkbox');
        if ($is3D) {
            return $this->processPayment3d($request);
        } else {
            return $this->processPayment2d($request);
        }
    }

    public function error(Request $request)
    {
        $data = $request->get('status_description');
        return view('error', compact('data'));
    }

    public function getPosView()
    {
        return view('get-pos-view');
    }

    public function index()
    {
        return view('index');
    }
}
