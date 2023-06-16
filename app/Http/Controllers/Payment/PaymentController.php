<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\HashGeneratorHelper;
use App\Helpers\RequestHelper;
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
    public function __construct(GetTokenRequest $getTokenRequest,Payment2dRequest $payment2dRequest,Payment3dRequest $payment3dRequest)
    {
        $this->getTokenRequest = $getTokenRequest;
        $this->payment2dRequest = $payment2dRequest;
        $this->payment3dRequest = $payment3dRequest;
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
        $total = (float)$validatedData['total'];

        RequestHelper::payment3dRequest($this->payment3dRequest,$validatedData);

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
            $itemData = [
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'description' => $item['description']
            ];

            $itemRequestData[] = $itemData;
        }

        $this->payment3dRequest->setItems($itemRequestData);

        $itemJson = json_encode($this->payment3dRequest->getItems());

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
                    'items' => $itemJson,
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

        $validatedData = $request->validated();
        $total = (float)$validatedData['total'];

        RequestHelper::payment2dRequest($this->payment2dRequest,$validatedData);

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
            $itemData = [
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'description' => $item['description']
            ];

            $itemRequestData[] = $itemData;
        }

        $this->payment2dRequest->setItems($itemRequestData);

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
