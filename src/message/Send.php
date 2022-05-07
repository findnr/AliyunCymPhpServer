<?php

namespace CymAliyun\message;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Send
{
    /**
     * 初始化发短信客户端
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        AlibabaCloud::accessKeyClient($data['accessKeyId'], $data['accessKeySecret'])->regionId('cn-hangzhou')->asDefaultClient();
    }
    /**
     * 发送一条短信
     *
     * @param array $data
     * @return void
     */
    public function sendOne($data = [])
    {
        // echo "<pre>";
        AlibabaCloud::rpc();
        $result = AlibabaCloud::rpc()->product('Dysmsapi')

            // ->scheme('https') // https | http

            ->version('2017-05-25')->action('SendSms')->method('POST')->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => [
                    'SignName' => $data['sign_name'],
                    'PhoneNumbers'  =>  $data['send_phone'],
                    'TemplateCode'  =>  $data['template_code'],
                    'TemplateParam' =>  json_encode($data['content']),
                ],
            ])->request();
        return $result->toArray();
    }
    /**
     * 批量发送
     *
     * @param array $data
     * @param array $phone_data
     * @return void
     */
    public function sendAll($data = [], $phone_data = [])
    {
        $send_data['TemplateCode'] = $data['template_code'];
        if (count($phone_data) > 100) {
            $phone_group_arr = $this->_data_res($phone_data, 100);
            foreach ($phone_group_arr as $k => $v) {
                $$send_data = [];
                $send_data['TemplateCode'] = $data['template_code'];
                $send_data['SignNameJson'] = [];
                $send_data['TemplateParamJson'] = [];
                $send_data['PhoneNumberJson'] = [];
                foreach ($v as $ks => $vs) {
                    array_push($send_data['SignNameJson'], $data['sign_name']);
                    array_push($send_data['PhoneNumberJson'], $vs['send_phone']);
                    unset($vs['send_phone']);
                    array_push($send_data['TemplateParamJson'], $vs);
                }
                $send_data['SignNameJson'] = json_encode($send_data['SignNameJson']);
                $send_data['TemplateParamJson'] = json_encode($send_data['TemplateParamJson']);
                $send_data['PhoneNumberJson'] = json_encode($send_data['PhoneNumberJson']);
                $res_data[] = $this->_send_all($send_data);
            }
        } else {
            $send_data['SignNameJson'] = [];
            $send_data['TemplateParamJson'] = [];
            $send_data['PhoneNumberJson'] = [];
            foreach ($phone_data as $ks => $vs) {
                array_push($send_data['SignNameJson'], $data['sign_name']);
                array_push($send_data['PhoneNumberJson'], $vs['send_phone']);
                unset($vs['send_phone']);
                array_push($send_data['TemplateParamJson'], $vs);
            }
            $send_data['SignNameJson'] = json_encode($send_data['SignNameJson']);
            $send_data['TemplateParamJson'] = json_encode($send_data['TemplateParamJson']);
            $send_data['PhoneNumberJson'] = json_encode($send_data['PhoneNumberJson']);
            $res_data = $this->_send_all($send_data);
        }
        return $res_data;
    }
    /**
     * 发送多条短信
     *
     * @param array $data
     * @return array
     */
    private function _send_all($data = []): array
    {
        $result = AlibabaCloud::rpc()->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')->action('SendBatchSms')->method('POST')->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => $data,
            ])->request();
        return $result->toArray();
    }
    /**
     * 返加数据
     *
     * @param [type] $arrobj
     * @param [type] $num
     * @return [type] array
     */
    private function _data_res($arrobj, $num): array
    {
        $data = [];
        function ttt($i, $arr, $num, &$data)
        {
            $tmp = array_slice($arr, $i, $num);
            if (count($tmp) < $num) {
                if (count($tmp) != 0) $data[] = $tmp;
                return;
            } else {
                $data[] = $tmp;
                $i = $i + $num;
                ttt($i, $arr, $num, $data);
            }
        }
        ttt($i = 0, $arrobj, $num, $data);
        return $data;
    }
}
