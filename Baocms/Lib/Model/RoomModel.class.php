<?php
class RoomModel extends CommonModel
{
    protected $pk = 'id';
    protected $tableName = 'room';


    //获取房间信息
    public function getroom($roomid)
    {
        $data=unserialize(Cac()->get('room_'.$roomid));
        if(empty($data)){
            $data=$this->where(array('room_id'=>$roomid))->find();
            Cac()->set('room_'.$roomid,serialize($data));
        }
        return $data;
    }

    //获取房间信息
    public function getRoomData($roomid)
    {
        $data=unserialize(Cac()->get('room_'.$roomid));
        if(empty($data)){
            $data=$this->where(array('room_id'=>$roomid))->find();
            Cac()->set('room_'.$roomid,serialize($data));
        }
        return $data;
    }

    /*获取房间列表 全部或者某一个类型
     *@param $type
     *
     */
    public function getroomlist($type=''){
        $data=unserialize(Cac()->get('roomlist_'.$type));
        if(empty($data)){
            $data=$this->where(array('game'=>$type,'is_show'=>1))->select();
            Cac()->set('roomlist_'.$type,serialize($data));
        }
        return $data;
    }
    //保存信息房间
    public function saveroom($data = '', $options = array())
    {

    }
    /**更改房间信息 房间号
     * @param $id
     */
    public function updateroom($id){
        Cac()->delete('room_'.$id);
    }

    public function clearCache()
    {
        //清除类型列表
        $roomlist=$this->where("1")->select();
        print_r($roomlist);
        foreach ($roomlist as $value){
            $this->updateroom($value['room_id']);

            //
            Cac()->delete('roomlist_'.$value['game']);
        }
    }

    /**更新房间列表的缓存
     *
     */
    protected function updateroomlist(){

    }
}