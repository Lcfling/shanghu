<?php

//

class  AuthAction extends CommonAction{

    public $url="https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=2018071760720352&scope=auth_user&redirect_uri=";
    private $geteway="https://openapi.alipay.com/gateway.do";

    private $privateKey="MIIEpQIBAAKCAQEAvvAX3TK6ivMBW6eP2E5e8qYEPDL/bm8Ni9u1dz7XJTL6uWrCZIK2YOejPy7xiUI1yEmx8bnU4AK+82sxJZ6t/ccdQbsIEyo067cxr223h81tvEbLFNPKZv1ilhMhyj9lhwry6D74wXmpvDphb+2DXLx9HaDT0uzX1ClmSpU5XW3ginNiUos5EJg7WYUV/sJrYq6utRGeOVeJx6aTl6n49EoFDZOGI8pcBMTEVKBTm+IJDFwu4s/9Ys9RypJrR0QFD6iR5LHGHV4kz8Sd3tb55ooZ+FGYzvVGlit5iZ3mHpynOMG+ttvvHVWcXiIbmW22f//f+cr+Sahu8dXh6zNdmwIDAQABAoIBAQCYyVKvRBqVeWKKzwiB1CSSElqtOTnASskSMxuAch6Cu8p5eH5ZI2eBH5o3wv6wEPUschwcC9HV9xyJeCr5qYIc4qn8oQcTHuD+BbeSYz9LQg0fbZ6v6dG6m+O5p1GQny5E43QAd/NiDlLONgEmKW7GVGXhwYH+9Fq4gPBDmikvIoyxjEKJWLNLoog8V+lyXDbBE236aqVW6GSofksrNWG2ZqfwNGQDB+qk3sFa3CPX/0HQ0/MeLOVajTpul7o84dHzNEAOw1Wfljnv3+uPOA8oYmJuUMIsrWxhzxQU27tA41y28WOkpmgugSByZLDeSHjGMj/4Gl03x5gGMrq+eRShAoGBAOzZLO+d4JMaBk/Y9zpT+AuYDhqxEoahDvji/chPdmjAk2ZJmirxCpAWylAq5exoZ7fL7iB9rZKfVpbQiKaf00HK6yLAvFv2EqUqiu+7j6A4U2jra7o4M2KHPOE6pA4xHDRxl+rW80CTHfCifBCL1udf4JQ4CS1WTVT9RqQibnfJAoGBAM5gjvVtO7TPsaQej31TB/Rt71KfZ0uu++/f7ei/7Kie6UAUAEl7HTjQehsnci559mX8/LJE6nn1LsyDIbuSQ44kYkTwaX8wSZtc0AanpkuXEUFxt2J49lBa6V9IOwMWcgo0Ba54xURIbUaWxH+DXYr+ravfnvmLj0bWbT3MQ+RDAoGBAJKNr2h1YEilidJOhmvpGUrTQ6bwem4jqogGrNINmU8oGgzo7iQ0Ej0mXPlOEgc+cy8fWLFyErzvKz0Wu/eeXbIQRX6zk9mzYcYhlObSQAT71WDOi1InBGSrGdij2G+Po8wvnbkKWGpUneoQIIR8gvWYNfaA1ezfweoc7mERjRlBAoGBALf9pfZw2FYvBLTI+ixFJkZo7IDA1JrI+pu1DKoA0cDTTChwrIDWXgsU8ofa9xUwbtgmYDOe9VydloeGMUDE+a10CphjrepS5oy4hk4okT27BQtPkqscJJ0nMABeqR3rmOg30QjnaxehaqsQJ0d3mqIcxEEDMPxRy/7flK0Pu0d5AoGAaVVy4kpGrwEbOdnToGs4z261qOgDIheI98QuD6woWit2wIYWFXMs5mFqmng/nOOuWhSMWy2fbfawtUCZgro0eMPPfvD1gSKgxsf8EIL422B0CkLchqGV365GnuVmZozjBdQj/WTIiArLkuEmN7hmmtUs5k+ks8w7Z528tCfWWcg=";

    private $publicKey="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAioBMXQG1mb6Ls22vvBydN2qj3juDu6WkgoQXlFl1StMz/pZSYyMXf2xBMShZ0CZ9ZXcahoudnYO2BX8slGLmPs2tXtYtP5O47DlkzjQREwpF03WrGQhnzvJpqhGGPqARYZkcgHX0mdqbvJ2zfkkkPmnr1q7RLFrGzRrflH40GGF1sqJBVTWmmwu4TrLqwb4m13176L8PNRBDPjpAJRmtxlVwNUeQpGGhrBNLNF3jqoZwWR/l0fuXhKKs+3jl6iqr3FQUsQX15fWIrVodrBYx4DMfBRMgfqoIG1d24Tj5g+fc6Mz4XTUXP5IZ9jgjMCJ46D74A78QJTKFu4dG4VyPFwIDAQAB";

    private $appid="2018071760720352";

    public function index(){


        //die($this->url);
        header("Location:".$this->url."");
        $callbackurl=getSiteUrl()."/app/auth/callback";
        header("Location:".$this->url.urlencode($callbackurl));
        echo $callbackurl;
    }
    public function callback(){
        //print_r($_GET);
        require_once LIB_PATH."Payment/alipay/aop/AopClient.php";
        require_once LIB_PATH."Payment/alipay/aop/request/AlipaySystemOauthTokenRequest.php";
        $auth_code=$_GET['auth_code'];
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey = $this->privateKey;
        $aop->alipayrsaPublicKey=$this->publicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($auth_code);
        //$request->setRefreshToken("201208134b203fe6c11548bcabd8da5bb087a83b");
        $result = $aop->execute ( $request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->user_id;
        if(!empty($resultCode)){
            echo "双击复制User_id";
            echo "------\n";
            echo $resultCode;
        } else {
            print_r($result);
            echo "------\n";
            print_r($result->$responseNode);
            echo "失败";
        }
    }
    public function test(){
        print_r(get_defined_constants(true)['user']);
    }
}