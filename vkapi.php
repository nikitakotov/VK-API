<?php

class VKAPI {

    private $token;
    private $cb;
    private $secret;
    private $apiurl = 'https://api.vk.com/method/';

    public function __construct($token, $cb, $secret) {
        $this->token = $token;
        $this->cb = $cb;
        $this->secret = $secret;
    }

    public function request($method, $params) {
        $params['access_token'] = $this->token;
        if(!$params['v']) {
            $params['v'] = '5.92';
        }

        if($method == 'messages.send' and !$params['random_id']) {
            $params['random_id'] = 0;
        }
        $url = $this->apiurl.$method."?".http_build_query($params);
        return file_get_contents($url);
    }

    public function listener() {
        $data = json_decode(file_get_contents("php://input"));
        if($data->secret == $this->secret) {
            if($data->type == 'confirmation') {
                die($this->cb);
            } else {
                return $data;
            }
        } else {
            return 0;
        }
    }

    public function ok() {
        die("ok");
    }

}
