<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('encode_string')){
    function encode_string($string){
        $encrypt_method = "AES-256-CBC"; 
        $secret_key = 'mdiluap'; 
        $secret_iv = 'jpma181'; 
        $key = hash('sha256', $secret_key); 
        $iv = substr(hash('sha256', $secret_iv), 0, 16); 
        return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv)); 
    }
}

if ( ! function_exists('decode_string')){
    function decode_string($string){
        $encrypt_method = "AES-256-CBC"; 
        $secret_key = 'mdiluap'; 
        $secret_iv = 'jpma181'; 
        $key = hash('sha256', $secret_key); 
        $iv = substr(hash('sha256', $secret_iv), 0, 16); 
        return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
}


