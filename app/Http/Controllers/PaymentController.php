<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
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

        $filePath = 'storage/app/products.json';
        $jsonData = Storage::get($filePath);

        $items = json_decode($jsonData,true);

        dd($items);

        foreach ($items['products'] as $item)
        {
            $productId = $item['id'];
            $productName = $item['name'];
            $productPrice = $item['price'];

            echo "Ürün ID: " . $productId . "<br>";
            echo "Ürün Adı: " . $productName . "<br>";
            echo "Ürün Fiyatı: " . $productPrice . "<br>";
        }

        $ccHolderName = $request->input('cc_holder_name');
        $ccNo = $request->input('cc_no');
        $expiryMonth = $request->input('expiry_month');
        $expiryYear = $request->input('expiry_year');
        $currencyCode = $request->input('currency_code');
        $installmentsNumber = $request->input('installments_number');
        $invoiceId = $request->input('invoice_id');
        $invoiceDescription = $request->input('invoice_description');
        $total = $request->input('request');
        $merchantKey = $request->input('merchant_key');
        $name = $request->input('request');
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
                    'items' => $items
                ]
            ]);

            // Banka API'sinden dönen yanıtı al
            $responseData = json_decode($response->getBody(), true);

            // Ödeme başarılıysa
            if ($responseData['status'] === 'success') {
                // Kullanıcıya ürünü ver veya işlemleri tamamlayın
                // Örneğin, kullanıcıya indirme bağlantısı gönderin veya ürünü veritabanında işaretleme yapın

                return response()->json(['message' => 'Ödeme başarıyla tamamlandı. Ürün verildi.']);
            } else {
                return response()->json(['message' => 'Ödeme işlemi başarısız. Hata kodu: ' . $responseData['error_code']]);
            }
        } catch (\Exception $e) {
            // İstek sırasında bir hata oluşursa
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}
