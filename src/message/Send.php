<?php

namespace AliyunCymPhpServer\message;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Send{

    public function __construct($data=[])
    {
        AlibabaCloud::accessKeyClient($data['accessKeyId'], $data['accessKeySecret'])->regionId('cn-hangzhou')->asDefaultClient();
    }
    public function sendOne($data=[]){
        $result = AlibabaCloud::rpc()

                ->product('Dysmsapi')

                // ->scheme('https') // https | http

                ->version('2017-05-25')

                ->action('SendSms')

                ->method('POST')

                ->host('dysmsapi.aliyuncs.com')

                ->options([

                    'query' => [

                        'RegionId' =>'cn-hangzhou',

                        'SignName' => $data['signName'],

                        'PhoneNumbers'  =>  $data['phone'],

                        'TemplateCode'  =>  $data['template_code'],

                        'TemplateParam' =>  json_encode($data['content']),

                    ],

                ])->request();
    }
}