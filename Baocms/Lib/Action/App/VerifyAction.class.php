<?php

class  VerifyAction extends CommonAction{
    
    public function index(){
        import('ORG.Util.Image');
        Image::buildImageVerify(4,2,'png',60,30);
    }
    
    public function creatqrcode(){
        $url=getSiteUrl()."/pchome/index/jsb?order=".$_GET["order"];
        $errorCorrectionLevel = 'L';   //容错级别
        $matrixPointSize = 10;//生成图片大小
        QRcode::png($url,false,$errorCorrectionLevel, $matrixPointSize,2);
    }
    public function qrcode(){
        $url=$_GET["url"];
        $errorCorrectionLevel = 'L';   //容错级别
        $matrixPointSize = 10;//生成图片大小
        QRcode::png($url,false,$errorCorrectionLevel, $matrixPointSize,2);
    }

    public function urlqrcode(){
        $url=getSiteUrl()."/app/auth/index";
        $errorCorrectionLevel = 'L';   //容错级别
        $matrixPointSize = 10;//生成图片大小
        QRcode::png($url,false,$errorCorrectionLevel, $matrixPointSize,2);
    }
}