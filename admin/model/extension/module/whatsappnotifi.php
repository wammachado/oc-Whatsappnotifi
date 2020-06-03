<?php
class ModelExtensionModuleWhatsappnotifi extends Model
{

    public function save($store_id = null, $data){

        $modulo = "module_whatsappnotifi";
        
        $sqlinsert = "INSERT INTO " . DB_PREFIX . "setting SET 
                        store_id='".$data['store_id']."', 
                        code='".$data['code']."', 
                        `key`='".$data['key']."', 
                        value='".$data['value']."', 
                        serialized='".$data['serialized']."';"; 

        $this->db->query($sqlinsert);
        
    }
    public function status($data){
        $modulo = "module_whatsappnotifi";
        
        $sqldelete = "DELETE FROM `" . DB_PREFIX . "setting` WHERE code='".$data['code']."' AND `key`='".$data['key']."'";
        
        $this->db->query($sqldelete);  
        
        $sqlinsert ="INSERT INTO " . DB_PREFIX . "setting SET
                    store_id=0,
                    code='".$data['code']."',
                    `key`='".$data['key']."',
                    value='".$data['value']."'";
        $this->db->query($sqlinsert);
        
    }
    public function clear(){

        $modulo = "module_whatsappnotifi";
        
        $sqldelete = "DELETE FROM `" . DB_PREFIX . "setting` WHERE code = '$modulo';";
        
        $this->db->query($sqldelete);  
        
        
    }
    
    public function select($store_id, $item = null){
        
        $modulo = "module_whatsappnotifi";

        $sqlselect = "SELECT * FROM `" . DB_PREFIX . "setting` 
                        WHERE store_id =  ".(int)$store_id." 
                        AND code = '$modulo'
                        AND `key` = '$item'";
        
        // var_dump($sqlselect);die();

        $query =  $this->db->query($sqlselect);
        //var_dump($query->row);die();

        return $query->row ? $query->row : false;

    }

}