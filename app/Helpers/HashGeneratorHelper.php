<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class HashGeneratorHelper{

    public static function hashGenerator()
    {
        $total = 600;
        $installment = 1;
        $currency_code = 'TRY';
        $merchant_key = '$2y$10$w/ODdbTmfubcbUCUq/ia3OoJFMUmkM1UVNBiIQIuLfUlPmaLUT1he';
        $invoice_id = rand(0,10000);
        $app_secret = '217071ea9f3f2e9b695d8f0039024e64';
        $data = $total . '|' . $installment . '|' . $currency_code . '|' . $merchant_key . '|' . $invoice_id;

        Session::put('invoice_id',$invoice_id);

        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($app_secret);

        $salt = substr(sha1(mt_rand()), 0, 4);
        $saltWithPassword = hash('sha256', $password . $salt);

        $encrypted = openssl_encrypt(
            $data, 'aes-256-cbc', $saltWithPassword, 0, $iv
        );
        $msg_encrypted_bundle = $iv . ':' . $salt . ':' . $encrypted;
        $msg_encrypted_bundle = str_replace('/', '__', $msg_encrypted_bundle);
        return $msg_encrypted_bundle;
    }

}
