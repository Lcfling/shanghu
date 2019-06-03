<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);
use \GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
require_once ROOT_PATH."mysql-master/src/Connection.php";
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    public static function onWorkerStart($businessWorker)
    {
        $time_interval = 2.5;
        $hostUrl="http://alipay.622c7.cn/";
        //Gateway::getAllClientIdCount();
        //if($worker)
        if ($businessWorker->id == 10) {
            Timer::add(2, function()use($hostUrl)
            {
                //echo "dingshiqi running!!!";
                //file_get_contents($hostUrl."app/index/phb");
                file_get_contents($hostUrl."app/index/timercall");
            });
        }
        if ($businessWorker->id == 1) {
            Timer::add(10, function()use($hostUrl)
            {
                //echo "dingshiqi running!!!";
                //file_get_contents($hostUrl."app/index/phb");
                file_get_contents($hostUrl."app/index/unfrozen");
            });
        }
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     *
     */
    //private $Model="";

    public static function onConnect($client_id) {
        // 向当前client_id发送数据
        //echo "content success";
        //$reData="slogin";
        //Gateway::sendToClient($client_id, "Hello $client_id\n");
        // 向所有人发送
        //Gateway::sendToAll("$client_id login\n");
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message) {
        // 向所有人发送
        //数据解析
        /*$a=array(
            "m"=>"index",
            "a"=>"index",
            "uid"=>51,
            'client_id'=>$client_id
        );*/
        //$message=json_encode($a,true);
        //以上调试时候使用
        $SocketData=json_decode($message,true);
        $SocketData['client_id']=$client_id;

        //$GLOBALS['post']=$SocketData;

        //Gateway::joinGroup($client_id);
        spl_autoload_register('requireClassName');
//加载类文件函数


//单入口
        //$SocketData=$GLOBALS['post'];

        $extendEnt = 'Action';
        $m=isset($SocketData['m']) ? ucfirst(strtolower($SocketData['m'])) : 'Index';
        echo $m.$extendEnt."--------------------------------\n";
        if(!class_exists($m.$extendEnt)){ $m = 'Index'; }
        echo $m.$extendEnt."--------------------------------\n";
        eval('$action=new '.$m.$extendEnt.'();');
        eval('$action->run($SocketData);');
        //Gateway::sendToAll("$client_id said $message");
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id) {
        // 向所有人发送
        GateWay::sendToAll("$client_id logout");
    }
}
