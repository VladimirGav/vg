<?php

namespace core;


class ReCaptcha
{
    public $publickey = '';
    public $privatekey = _PRIVATE_KEY_;
    public $response_name = _RESPONCE_NAME_;

    static function instance(){
        return new ReCaptcha;
    }

    public function checkRecaptcha(){
        if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST[$this->response_name])){
            $secret = $this->privatekey;
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $remoteip = $_SERVER['REMOTE_ADDR'];
            $response = $_POST[$this->response_name];
            $url_data = $url.'?secret='.$secret.'&response='.$response.'&remoteip='.$remoteip;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url_data);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($curl);
            $res = json_decode($res, true);
            if($res['success']){
                return true;
            } else {
                return false;
                //return json_encode($res['error-codes']);
            }

        }
    }
}