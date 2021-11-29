<?php
class Reg_open_model extends CI_Model
{
  private $reg_spe_tbl = 'reg_fee_relaxation';
  
  function addRegSpec($data){
     
          $q=$this->db->insert($this->reg_spe_tbl, $data);
         // throw new Exception($ere);
          return $q;
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
  //@ declear getFeeRelax 
 
  function getFeeRelax($stu,$sess,$sy){
      
      ($sess == 'W')?$sess="Winter":$sess="Monsoon";
     $q= $this->db->get_where($this->reg_spe_tbl,array('admn_no'=>$stu,'session'=>$sess,'session_year'=>$sy));
       if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
  }
  
  
}
?>