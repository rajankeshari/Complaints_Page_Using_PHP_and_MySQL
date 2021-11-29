<?php
class Admin_structure_model extends CI_model{

  function index()
  {
    parent::__construct();
  }

  function admin_struct_save($adcat,$admpost,$order,$adby)
  {  
   $data=array();
   $sql="select * from admin_structure where tel_order=$order";
   $telinsert= $this->db->query($sql);
   if($telinsert->num_rows()>0)
    {
          
      return 'match';
          
    }
    else
    {
        
      $sql="INSERT INTO `admin_structure`(`post`, `post_category`,`tel_order`,`added_by`) VALUES ('$admpost','$adcat', '$order','$adby');";
      $insert= $this->db->query($sql);
      if($insert)
      {
      $data['success']='ok';
      return $data;
      }
      else
      {
      return false;
      }
    }
  }

    function admin_struct_fetch_data()
    {
      $smt="select * from admin_structure order by post asc";
      $query=$this->db->query($smt);
       if($query)
       {
        $result=$query->result_array();
        return $result;
       }
       else
       {
       return false;
       }
    }
    function admin_struct_update($admId,$adminPost,$status,$ordtel,$hortel)
    { 
      
      if($ordtel==$hortel or $ordtel=='')
      {
        
        $smt="UPDATE `admin_structure` SET `post`='$adminPost',`status`='$status' WHERE `admin_id`=$admId";
        if($query=$this->db->query($smt))
        {
        return "update";
        }
        else
        {
        return "erros";
        }
      }
      else
      {
        $sql="select * from admin_structure where tel_order=$ordtel";
        $telinsert= $this->db->query($sql);
        if($telinsert->num_rows()>0)
        {
        return 'match';
        }
        else
        {
        $smt="UPDATE `admin_structure` SET `post`='$adminPost',`status`='$status',`tel_order`=$ordtel WHERE `admin_id`=$admId";
        if($query=$this->db->query($smt))
        {
        return "update";
        }
        else
        {
        return "erros";
        }

        }
      }
    }
    function admin_struct_backlog($admid,$post,$admcat,$stats,$tel,$adby)
    {

      $sql="INSERT INTO `admin_structure_log` (`admin_id`, `post`, `post_category`, `status`, `tel_order`, `add_by`) VALUES ('$admid', '$post', '$admcat', '$stats', '$tel','$adby');";
      $insert= $this->db->query($sql);
      if($insert)
      {
      $data['success']='ok';
      return $data;
      }
      else
      {
      return false;
      }

    }
    public function manageOrder($adcat,$admpost,$order,$adby)
    {

      $data=array();
      $sql="select * from admin_structure where tel_order=$order";
      $telinsert= $this->db->query($sql);
      if($telinsert->num_rows()>0)
      {         
        return 'match';
      }
      else
      {       
        $sql="INSERT INTO `admin_structure`(`post`, `post_category`,`tel_order`,`added_by`) VALUES ('$admpost','$adcat', '$order','$adby');";
        $insert= $this->db->query($sql);
        if($insert)
        {
        $data['success']='ok';
        return $data;
        }
        else
        {
        return false; 
        }
      }
    }

    public function telOrdervalue()
    {
      $stmt="select admin_id,tel_order,post,post_category,`status` from admin_structure order by tel_order desc";
      $get=$this->db->query($stmt);
      if($get->num_rows() >0)
      {
        return $get->result_array();
      }
      else
      {
        return false;
      }
    }
    public function updateingroup($val)
    {
      $p=$this->telOrdervalue();
      $l=$val[selectedorder];
      $admincat=$val[oadmincat];
      $post=$val[oadminpost];
      $addby=$val[addby];
      $i=0;
      $m=0;
      $n=0;
      foreach ($p as $key => $value)
      {
        $admin_idup[]=$value['admin_id'];
        $tel_orderup[]=$value['tel_order'];
        if($l==$value['tel_order'])
        {  
         $postionin=$i;
         break;
        } 
        $i++;    
      }

      for($j=0; $j<=$postionin; $j++)
      {
         
       $telorder=$tel_orderup[$j] + 1;
      
       $smt="UPDATE `admin_structure` SET `tel_order`='$telorder' WHERE `admin_id`='$admin_idup[$j]'";
        $query=$this->db->query($smt);
        if($query)
        {
          $m++;
          $data['update']='ok'+$m;
        }
        else
        {
          $n++;
        }
      }
      
      if($m<0)
      {
        return "not allow";
      }
      else
      {
         $sql="INSERT INTO `admin_structure`(`post`, `post_category`,`tel_order`,`added_by`) VALUES ('$post','$admincat','$l','$addby')";
        $insert= $this->db->query($sql);
        $this->db->last_query();
        
        if($insert)
        {
        $data['insert']="sucess";
        return $data;
        }
        else
        {
        return false;
        }
      }    
    }
  }


?>