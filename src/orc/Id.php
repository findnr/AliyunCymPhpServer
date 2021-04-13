<?php

namespace AliyunCymPhpServer\orc;

class Id
{
    private $url;
    private $appcode;
    private $img_path;
    private $method;
    public function __construct($data = [])
    {
        $this->method = 'POST';
        $this->img_path = $data['img_path'];
        $this->url = 'http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
        $this->appcode = $data['appcode'];
    }
    public function getFaceInfo()
    {
        $config_data = ['side' => 'face'];
        return $this->_sed_info($config_data);
    }
    public function getBackInfo()
    {
        $config_data = ['side' => 'back'];
        return $this->_sed_info($config_data);
    }
    private function _sed_info($config_data = [])
    {
        $headers = [];
        array_push($headers, "Authorization:APPCODE " . $this->appcode);
        array_push($headers, "Content-Type" . ":" . "application/json; charset=UTF-8");
        $config = $config_data;
        $img_data = $this->_img_base64();
        $request = ["image" => $img_data];
        if (count($config) > 0) {
            $request["configure"] = json_encode($config);
        }
        $body = json_encode($request);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$" . $this->url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($curl);
        
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $rheader = substr($result, 0, $header_size);
        $rbody = substr($result, $header_size);

        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        if($httpCode == 200){
            $result_str = $rbody;
            printf("result is :\n %s\n", $result_str);
        }else{
            printf("Http error code: %d\n", $httpCode);
            printf("Error msg in body: %s\n", $rbody);
            printf("header: %s\n", $rheader);
        }
        curl_close($curl);
        return $result_str;
    }
    private function _img_base64()
    {
        $img_data = '';
        if (substr($this->img_path, 0, strlen('http')) == 'http') {
            $img_data = $this->img_path;
        } else {
            if ($fp = fopen($this->img_path, 'rb', 0)) {
                $binary = fread($fp, filesize($this->img_path));
                fclose($fp);
                $img_data = base64_encode($binary);
            } else {
                printf("%s 图片不存在", $this->img_path);
            }
        }
        return $img_data;
    }
}
