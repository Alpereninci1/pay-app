<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\HashGeneratorHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Mapper\Common\GetTokenMapper;
use App\Requests\Common\GetTokenRequest;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    private GetTokenRequest $getTokenRequest;
    public function __construct(GetTokenRequest $getTokenRequest)
    {
        $this->getTokenRequest = $getTokenRequest;
    }
    public function getToken()
    {
        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/token';

        $app_id = Config::get('app.app_id');
        $app_secret = Config::get('app.app_secret');

        $this->getTokenRequest->setAppId($app_id);
        $this->getTokenRequest->setAppSecret($app_secret);

        $body = $this->getTokenRequest->getTokenData();
        $client = new Client();
        if (Session::has('token') ) {
            return Session::get('token');
        }
        else{
            try {
                $response = $client->post($apiUrl, [
                    'body' => $body,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);
                $data = GetTokenMapper::map($response);
                $token = $data->getData()->getToken();
                $dataArray = GetTokenMapper::map($response)->toArray();
                $status_code = $data->getStatusCode();
                if ($status_code === 100) {
                    $expiration = Carbon::now()->addHours(5); // Token süresi 2 saat
                    Log::channel('info')->info('Token alındı.',['Token' => $token]);
                    Session::put('token',$token);
                    Session::put('token_expiration', $expiration);
                    Session::save();
                    return $dataArray;
                } else {
                    Log::channel('error')->error('Token alırken bir hata oluştu. Hata kodu: ',[$status_code]);
                    return response()->json(['message' => 'Token alırken bir hata oluştu. Hata kodu: ' . $status_code]);
                }
            }catch (\Exception $e){
                Log::channel('error')->error('Token alırken bir hata oluştu. Hata kodu: ',[$e->getMessage()]);
                return response()->json(['message' => 'Token alırken bir hata oluştu: ' . $e->getMessage()]);
            }
        }
    }

    public function processPayment3d(PaymentRequest $request)
    {

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart3D';

        $validatedData = $request->validated();
        $total = (float)$request->input('total');
        $installmentsNumber = $request->input('installments_number');
        $currencyCode = Config::get('app.currency_code');
        $invoiceDescription = Config::get('app.invoice_description');
        $merchantKey = Config::get('app.merchant_key');
        $name = Config::get('app._name');
        $surname = Config::get('app.surname');
        $hashKey = HashGeneratorHelper::hashGenerator($total,$installmentsNumber);
        $invoiceId = Session::get('invoice_id');
        $returnUrl = Config::get('app.return_url');
        $cancelUrl = Config::get('app.cancel_url');

        $ccHolderName = $validatedData['cc_holder_name'];
        $ccNo = $validatedData['cc_no'];
        $expiryMonth = $validatedData['expiry_month'];
        $expiryYear = $validatedData['expiry_year'];


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
                    'Authorization' => 'Bearer ' . Session::get('token')
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                Log::channel('info')->info('3D Başarılı.');
                return $response->getBody();
            } else {
                Log::channel('error')->error('Ödeme işlemi başarısız.');
                return response()->json(['message' => 'Ödeme işlemi başarısız.']);
            }
        } catch (\Exception $e) {
            // İstek sırasında bir hata oluşursa
            Log::channel('error')->error('Ödeme işlemi sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function processPayment2d(PaymentRequest $request)
    {

        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/paySmart2D';

        $filePath = "products.json";

        $json = Storage::get($filePath);

        $items = json_decode($json, true);

        $products = [];

        $validatedData = $request->validated();
        $total = (float)$validatedData['total'];
        $installmentsNumber = $validatedData['installments_number'];

        foreach ($items['products'] as $item) {
            $obj = new \stdClass();
            $obj->price = $total;
            $obj->name = $item['name'];
            $obj->description = $item['description'];
            $obj->quantity = $item['quantity'];

            $products[] = $obj;
        }

        $validatedData = $request->validated();

        $ccHolderName = $validatedData['cc_holder_name'];
        $ccNo = $validatedData['cc_no'];
        $expiryMonth = $validatedData['expiry_month'];
        $expiryYear = $validatedData['expiry_year'];
        $cvv = $validatedData['cvv'];

        $client = new Client();
        try {
            $response = $client->post($apiUrl, [
                'json' => [
                    'cc_holder_name' => $ccHolderName,
                    'cc_no' => $ccNo,
                    'expiry_month' => $expiryMonth,
                    'expiry_year' => $expiryYear,
                    'cvv' => $cvv,
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
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);
            if($response->getStatusCode() === 200) {
                Log::channel('info')->info('2D Başarılı.');
                return redirect()->route('success');
            } else {
                Log::channel('error')->error('2D Başarısız.');
                return redirect()->route('error');
            }
        } catch (\Exception $e) {
            // İstek sırasında bir hata oluşursa
            Log::channel('error')->error('Ödeme işlemi sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function getInstallment()
    {
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
                    'Authorization' => 'Bearer ' . Session::get('token'),
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
        $this->getToken();

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
                Log::channel('info')->info('Get Pos Başarılı:',[$responseData]);
                return $responseData;
            }else {
                Log::channel('error')->error('İşlem başarısız.');
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            Log::channel('error')->error('İşlem sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

//    public function payByCardTokenNonSecure(Request $request)
//    {
//        $tokenValue = Session::get('token');
//        $apiUrl = 'https://test.vepara.com.tr/ccpayment/api/payByCardTokenNonSecure';
//        $filePath = "products.json";
//
//        $json = Storage::get($filePath);
//
//        $items = json_decode($json, true);
//        $products = '';
//        foreach ($items as $item){
//            $products = $item;
//        }
//
//        $client = new Client();
//        $rawData = $request->getContent();
//        $dataArray = json_decode($rawData, true);
//        $merchant_key = Config::get('app.merchant_key');
//        $dataArray['merchant_key'] = $merchant_key;
//        $dataArray['hash_key'] = HashGeneratorHelper::hashGenerator();
//        $dataArray['invoice_id'] = Session::get('invoice_id');
//        $dataArray['items'] = $products;
//        $jsonData = json_encode($dataArray);
//
//        try {
//            $response = $client->post($apiUrl,[
//                'body' => $jsonData,
//                'headers' => [
//                    'Content-Type' => 'application/json',
//                    'Authorization' => 'Bearer ' . $tokenValue,
//                ]
//            ]);
//            if($response->getStatusCode() === 200) {
//                $responseData = json_decode($response->getBody(),true);
//                return $responseData;
//            }else {
//                return response()->json(['message' => 'İşlem başarısız.']);
//            }
//        }catch (\Exception $e){
//            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
//        }
//    }

    public function processPayment(PaymentRequest $request)
    {
        Session::put('is_visit',false);
        $validatedData = $request->validated();
        $amount = $validatedData['amount'];
        $name = $validatedData['name'];
        $phone = $validatedData['phone'];
        $tckn = $validatedData['tckn'];
        $ccHolderName = $validatedData['cc_holder_name'];
        $ccNo = $validatedData['cc_no'];
        $expiryMonth = $validatedData['expiry_month'];
        $expiryYear = $validatedData['expiry_year'];
        $cvv = $validatedData['cvv'];
        $installmentNumbers = $validatedData['installments_number'];

        Log::channel('info')->info('Girilen Veriler:', ['Tutar: ' => $amount, 'Telefon Numarası: ' => $phone, 'İsim: ' => $name, 'TC Kimlik Numarası: ' => $tckn]);

        Log::channel('info')->info('Kart Bilgiler:', ['Kart Üzerindeki İsim: ' => $ccHolderName, 'Kart Numarası: ' => $ccNo, 'Ay: ' => $expiryMonth, 'Yıl: ' => $expiryYear , 'CVV' => $cvv, 'Taksit Sayısı' => $installmentNumbers]);

        $is3D = $request->has('3d_checkbox');
        if ($is3D) {
            Log::channel('info')->info('3D Seçildi.');
            return $this->processPayment3d($request);
        } else {
            Log::channel('info')->info('2D Seçildi.');
            return $this->processPayment2d($request);
        }
    }

    public function error(Request $request)
    {
        if(Session::get('is_visit') === false){
            Session::put('is_visit',true);
            return view('error');
        }
        else{
            return redirect()->route('payment.index');
        }
    }

    public function success(Request $request)
    {
        if(Session::get('is_visit') === false){
            Session::put('is_visit',true);
            return view('success');
        }
        else{
            return redirect()->route('payment.index');
        }

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
