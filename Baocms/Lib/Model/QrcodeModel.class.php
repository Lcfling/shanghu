<?php



class QrcodeModel extends CommonModel {

    protected $pk = 'id';
    protected $tableName = 'qrcode';

    //是否在队列中 Qrcode_Queue
    public function isQrcodeIn($id,$type){

        $queue=Cac()->lRange('Qrcode_Queue_'.$type,0,-1);
        if(in_array($id,$queue)){
            return true;
        }else{
            return false;
        }
    }
    //设备是否在线 ping_
    public function isQrcodeOnline($id){

        $time=Cac()->get('ping_'.$id);
        if($time<time()-10){
            return false;
        }else{
            return true;
        }
    }
    public function RpushQueue($id){
        $qrcode=D('Qrcode')->where(array('id'=>$id))->find();
        if(empty($qrcode)){
            DebugLog($id.'-没有找到'.var_export($qrcode,true),'RpushQueue');
            return false;
        }
        if(!$this->isQrcodeOnline($id)){
            DebugLog($id.'-没有在线','RpushQueue');
            return false;
        }
        if($qrcode['is_active']!=1){
            DebugLog($id.'-二维码关闭中','RpushQueue');
            return false;
        }
        if($this->isQrcodeIn($id)){
            DebugLog($id.'-已经在队列中','RpushQueue');
            return false;
        }
        Cac()->rPush('Qrcode_Queue_'.$qrcode['type'],$id);
        return true;
    }
    public function getQueueLen($type){
        return Cac()->lLen('Qrcode_Queue_'.$type);
    }
    //队列中获取一个二维码
    public function getOneQrcode($type){
        for ($i=0;$i<$this->getQueueLen($type);$i++){
            $id=Cac()->lPop('Qrcode_Queue_'.$type);
            $Qrcode=$this->where('id='.$id)->find();
            if(empty($Qrcode)){
                DebugLog($id.'-不存在','getOneqrcode');
                continue;
            }
            if($Qrcode['is_active']!=1){
                DebugLog($id.'-关闭中','getOneqrcode');
                continue;
            }
            if(!$this->isQrcodeOnline($id)){
                $this->RpushQueue($id);
                DebugLog($id.'-设备不在线','getOneqrcode');
                continue;
            }
            $this->RpushQueue($id);
            return $Qrcode;
        }
        return false;
    }
}
