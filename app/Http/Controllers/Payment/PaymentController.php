<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\HashGeneratorHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Mapper\Common\GetTokenMapper;
use App\Requests\Common\GetTokenRequest;
use App\Requests\Payment\ItemRequest;
use App\Requests\Payment\Payment2dRequest;
use App\Requests\Payment\Payment3dRequest;
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
    private Payment2dRequest $payment2dRequest;
    private Payment3dRequest $payment3dRequest;
    private ItemRequest $itemRequest;

    public function __construct(GetTokenRequest $getTokenRequest,Payment2dRequest $payment2dRequest,Payment3dRequest $payment3dRequest,ItemRequest $itemRequest)
    {
        $this->getTokenRequest = $getTokenRequest;
        $this->payment2dRequest = $payment2dRequest;
        $this->payment3dRequest = $payment3dRequest;
        $this->itemRequest = $itemRequest;
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
        $ccHolderName = $validatedData['cc_holder_name'];
        $ccNo = $validatedData['cc_no'];
        $expiryMonth = $validatedData['expiry_month'];
        $expiryYear = $validatedData['expiry_year'];
        $total = (float)$validatedData['total'];
        $installmentsNumber = $validatedData['installments_number'];
        $description = "asdasd";


        $this->payment3dRequest->setCcHolderName($ccHolderName);
        $this->payment3dRequest->setCcNo($ccNo);
        $this->payment3dRequest->setExpiryMonth($expiryMonth);
        $this->payment3dRequest->setExpiryYear($expiryYear);
        $this->payment3dRequest->setMerchantKey(Config::get('app.merchant_key'));
        $this->payment3dRequest->setCurrencyCode(Config::get('app.currency_code'));
        $this->payment3dRequest->setInvoiceDescription(Config::get('app.invoice_description'));
        $this->payment3dRequest->setTotal($total);
        $this->payment3dRequest->setInstallmentsNumber($installmentsNumber);
        $this->payment3dRequest->setName(Config::get('app._name'));
        $this->payment3dRequest->setSurname(Config::get('app.surname'));
        $this->payment3dRequest->setHashKey(HashGeneratorHelper::hashGenerator($total,$installmentsNumber));
        $this->payment3dRequest->setInvoiceId(Session::get('invoice_id'));
        $this->payment3dRequest->setReturnUrl(Config::get('app.return_url'));
        $this->payment3dRequest->setCancelUrl(Config::get('app.cancel_url'));

        $items = [
            [
                'name' => 'item 1',
                'price' => $total,
                'quantity' => 1,
                'description' => 'asfasfasfas'
            ]
        ];

        $itemRequestData = [];

        foreach ($items as $item) {
            $itemRequest = new ItemRequest();
            $itemRequest->setName($item['name']);
            $itemRequest->setPrice($item['price']);
            $itemRequest->setQuantity($item['quantity']);
            $itemRequest->setDescription($item['description']);

            $itemRequestData[] = $itemRequest;
        }
        $this->payment3dRequest->setItems($itemRequestData);


        $client = new Client();
        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'cc_holder_name' => $this->payment3dRequest->getCcHolderName(),
                    'cc_no' => $this->payment3dRequest->getCcNo(),
                    'expiry_month' => $this->payment3dRequest->getExpiryMonth(),
                    'expiry_year' => $this->payment3dRequest->getExpiryYear(),
                    'currency_code' => $this->payment3dRequest->getCurrencyCode(),
                    'installments_number' => $this->payment3dRequest->getInstallmentsNumber(),
                    'invoice_id' => $this->payment3dRequest->getInvoiceId(),
                    'invoice_description' => $this->payment3dRequest->getInvoiceDescription(),
                    'total' => $this->payment3dRequest->getTotal(),
                    'merchant_key' => $this->payment3dRequest->getMerchantKey(),
                    'name' => $this->payment3dRequest->getName(),
                    'surname' => $this->payment3dRequest->getSurname(),
                    'hash_key' => $this->payment3dRequest->getHashKey(),
                    'return_url' => $this->payment3dRequest->getReturnUrl(),
                    'cancel_url' => $this->payment3dRequest->getCancelUrl(),
                    'items' => $this->payment3dRequest->getItems(),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . Session::get('token')
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                Log::channel('info')->info('3D Başarılı.');
                return $response->getBody();
            } else {
                Log::channel('error')->error('3D Ödeme işlemi başarısız.');
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

        $ccHolderName = $validatedData['cc_holder_name'];
        $ccNo = $validatedData['cc_no'];
        $expiryMonth = $validatedData['expiry_month'];
        $expiryYear = $validatedData['expiry_year'];
        $cvv = $validatedData['cvv'];

        $this->payment2dRequest->setCcHolderName($ccHolderName);
        $this->payment2dRequest->setCcNo($ccNo);
        $this->payment2dRequest->setExpiryMonth($expiryMonth);
        $this->payment2dRequest->setExpiryYear($expiryYear);
        $this->payment2dRequest->setCvv($cvv);
        $this->payment2dRequest->setMerchantKey(Config::get('app.merchant_key'));
        $this->payment2dRequest->setCurrencyCode(Config::get('app.currency_code'));
        $this->payment2dRequest->setInvoiceDescription(Config::get('app.invoice_description'));
        $this->payment2dRequest->setTotal($total);
        $this->payment2dRequest->setInstallmentsNumber($installmentsNumber);
        $this->payment2dRequest->setName(Config::get('app._name'));
        $this->payment2dRequest->setSurname(Config::get('app.surname'));
        $this->payment2dRequest->setHashKey(HashGeneratorHelper::hashGenerator($total,$installmentsNumber));
        $this->payment2dRequest->setInvoiceId(Session::get('invoice_id'));
        $this->payment2dRequest->setItems($products);

        $body = $this->payment2dRequest->getData();

        $client = new Client();
        try {
            $response = $client->post($apiUrl, [
                'body' => $body,
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
        $tokenValue = Session::get('token');
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
                    'Authorization' => 'Bearer ' . $tokenValue,
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

    public function error()
    {
        if(Session::get('is_visit') === false){
            Session::put('is_visit',true);
            return view('error');
        }
        else{
            return redirect()->route('payment.index');
        }
    }

    public function success()
    {
        if(Session::get('is_visit') === false){
            Session::put('is_visit',true);
            return view('success');
        }
        else{
            return redirect()->route('payment.index');
        }

    }


    public function index()
    {
        return view('index');
    }
}
