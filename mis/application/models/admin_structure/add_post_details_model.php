<?php

class Add_post_details_model extends CI_model
{   
  function index()
  {
      parent::__construct();
  }

  function get_departments()
  {
    $stmt="select id,name from departments";
    $query=$this->db->query($stmt);
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
  function get_admin_post()
  {

    $stmt="select admin_id,post from admin_structure";
    $query=$this->db->query($stmt);
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
   
  function admin_struct_details_save($adpostId,$depart,$domain,$adby)
  {  
    $data=array();
    if($domain=='')
    {
      $domain='';
    }

    $sql="INSERT INTO `admin_struct_details` (`admin_id`,`dept_id`,`domain`,`added_by`) VALUES ('$adpostId','$depart','$domain','$adby');";
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

  function admin_struct_details_fetch_data()
  {
    $smt="SELECT asd.asd_id,a.admin_id,asd.dept_id,a.post,a.post_category,d.name,asd.domain,asd.`status`
    FROM
    admin_structure a
    JOIN admin_struct_details AS asd ON asd.admin_id=a.admin_id left JOIN departments AS d ON d.id=asd.dept_id order by a.post asc";
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
  

  // function admin_struct_details_update($data)
  function admin_struct_details_update($usd_id,$uadminpostid,$useldepartment,$udomain,$ustatus)
  { 


    // if($usd_id==''||$uadminpostid==''||$useldepartment==''||$udomain==''||$ustatus=='')
    // {
    //   return 'error';
    // }
    // else
    // {
        $smt="UPDATE `admin_struct_details` SET `admin_id`='$uadminpostid',`dept_id`='$useldepartment',`domain`='$udomain',`status`='$ustatus' WHERE `asd_id`='$usd_id'";

        if($this->db->query($smt))
        {
        return 'update';
        }
        else
        {
        return 'error';
        }

    // }
    

  }

  function admin_struct_details_backlog($lasd_id,$ladmin_id,$ldept_id,$l_domain,$l_status,$adby)
  {
    
    $sql="INSERT INTO `admin_struct_details_log` (`asd_id`, `admin_id`, `dept_id`, `domain`, `status`, `add_by`) VALUES ('$lasd_id', '$ladmin_id', '$ldept_id', '$l_domain', '$l_status', '$adby');";
    
    $insert=$this->db->query($sql);
    // $this->db->last_query();
    // exit;
    if($insert)
    {

    return 'insert';
    }
    else
    {
    return 'error';
    }

  }
}
?>