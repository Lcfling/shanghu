<?php



class UsermenuModel extends CommonModel {

    protected $pk = 'menu_id';
    protected $tableName = 'usermenu';
    protected $token = 'bao_usermenu';
    protected $orderby = array('orderby'=>'asc');

    public function checkAuth($auth) {
        $data = $this->fetchAll();
        foreach ($data as $row) {
            if ($auth == $row['menu_action']) {
                return true;
            }
        }
        return false;
    }
}