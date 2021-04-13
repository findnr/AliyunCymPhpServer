# AliyunCymPhpServer
this is Aliyun server api
# 阿里云身份证识别
```shell
//安装
composer require findnr/aliyun-cym-php-server
```
```php
<?php
use AliyunCymPhpServer\orc\Id;
//php调用
    public function getIdInfo(){
        //阿里云调用接口appcode
        $req_data['appcode']=Config::get('aliyun.id.AppCode');
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 上传到本地服务器,获取到图处地址
        $req_data['img_path'] = \think\facade\Filesystem::putFile( 'topic', $file);
        $req_data['img_path'] = runtime_path().'storage/'.$req_data['img_path'];
        //初始化数据
        $obj =  new Id($req_data);
        //获取身份证上的信息
        $data = $obj->getFaceInfo();
        //打印
        var_dump($data);
        // $ttt=http_build_query($_FILES);
        // var_dump($_FILES);
    }
```
# 阿里云短信发送
```shell
//安装
composer require findnr/aliyun-cym-php-server
```
```php
<?php
use AliyunCymPhpServer\\message\Send;
        $init=[];
        $init['accessKeyId']=Config::get('aliyun.message.AccessKeyId');
        $init['accessKeySecret']=Config::get('aliyun.message.AccessKeySecret');
        //初始化
        $sendObj = new Send($init);
        //短信签名信息
        $data['signName']="XXX公司";
        //阿里云短信模板编号
        $data['template_code']="SMS_00000000";
        //发送的手机号
        $data['phone']="18000000000";
        //替换模板的变量
        $data['content']=['code'=>123456];
        //执行发送
        $sendObj->sendOne($data);
```