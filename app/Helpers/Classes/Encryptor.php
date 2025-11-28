<?php

namespace App\Helpers\Classes;

class Encryptor
{
    private string $encrypt_method = "AES-256-CBC";
    protected string $secret_key = 'M7c3da0b1dea59e94f3e1dd715fd82d39f21113e30K';
    private string $secret_iv = 'Mf24ca194f3e3e30dd39ca3ecf3507c32d31dbd7K';
    private string $key;
    private string $iv;

    public function __construct() {
        $this->iv  = substr(hash('sha256', $this->secret_iv), 0, 16); // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $this->key = hash('sha256', $this->secret_key);
    }

    public function openSSLEncrypt($string): string {
        return base64_encode(openssl_encrypt($string, $this->encrypt_method, $this->key, 0, $this->iv));
    }

    public function openSSLDecrypt($string): string {
        return openssl_decrypt(base64_decode($string), $this->encrypt_method, $this->key, 0, $this->iv);
    }

    public function aesEncrypt($string): string {
        return encrypt($string);
    }

    public function aesDecrypt($string): string {
        return decrypt($string);
    }

    public function base64UrlEncrypt($string): string {
        return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
    }

    public function base64UrlDecrypt($string): false|string
    {
        return base64_decode(strtr($string, '-_', '+/'));
    }
}
