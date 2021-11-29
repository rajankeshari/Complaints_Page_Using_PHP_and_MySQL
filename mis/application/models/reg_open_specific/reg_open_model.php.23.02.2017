<?php
class Reg_open_model extends CI_Model
{
  private $reg_spe_tbl = 'reg_open_specific';
  
  function addRegSpec($data){
      $this->db->insert($this->reg_spe_tbl, $data);
  }
  
  function delRegSpec($id){
  $this->db->delete($this->reg_spe_tbl, array('id'=>$id));
  }
  
  function getRegSpec($con=''){
      if($con){
        $q=$this->db->get_where($this->reg_spe_tbl,$con);
      }else{
          $q=$this->db->get($this->reg_spe_tbl);
      }
        if($q->num_rows() > 0){
            return $q->result();
        }else{
            return false;
        }
  }  
  function updateRegSpec($data,$con){
      $this->db->update($this->reg_spe_tbl, $data,$con);
  }
  
}
?>