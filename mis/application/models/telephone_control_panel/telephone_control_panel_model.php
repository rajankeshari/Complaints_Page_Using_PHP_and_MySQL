<?php

class Telephone_control_panel_model extends CI_model
{   
  function index()
  {
      parent::__construct();
  }

  function get_departments()
  {
    $stmt="select id,name from departments";
    $query=$this->db->query($stmt);
    if($query->num_rows()>0)
    {
      $result=$query->result_array();
      return $result;
    }
    else
    {
      return false;
    }

  }
  public function get_empname_byDeptid($deptid)
  {
        
    $stmt="select CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) as name,u.id as emp_id from users u join user_details as ud on u.id=ud.id
    join cbcs_departments as d on d.id=ud.dept_id
    where u.`status`='A' and u.auth_id='emp' and d.id='$deptid' order by name asc";
    $query=$this->db->query($stmt);
    if($query->num_rows() > 0)
    {
    $result=$query->result_array();
    return $result;
    }
    else
    {
    return false;
    }     
  }
   public function fetch_tel()
   {
     //  $smt="SELECT ass.post,t.tel_dir,t.office_ext,t.resi_ext,t.bsnl_landline,ass.post_category,ass.admin_id,asd.asd_id,asd.dept_id,asd.domain,cd.name AS dept,
     // CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS fname
     // FROM tele_dir t
     // left JOIN admin_structure ass ON t.admin_id=ass.admin_id
     // left JOIN admin_struct_details asd ON t.asd_id=asd.asd_id
     // LEFT JOIN departments cd ON asd.dept_id=cd.id
     // left JOIN user_details ud ON ud.id=t.emp_id";
      $smt="SELECT  d.name AS deptName,asd.admin_id,ass.post,t.tel_dir,td.name as postdept,t.office_ext,t.resi_ext,t.bsnl_landline,ass.post_category,
 ass.admin_id,asd.asd_id,asd.dept_id,d.name AS d,asd.domain,cd.name AS dept, CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS fname
FROM tele_dir t
      left JOIN admin_structure ass ON t.admin_id=ass.admin_id
      left JOIN admin_struct_details asd ON t.asd_id=asd.asd_id
      LEFT JOIN departments cd ON asd.dept_id=cd.id
      left JOIN user_details ud ON ud.id=t.emp_id 
      left Join departments td on td.id=t.dept_id 
      left join departments d on d.id=ud.dept_id order by ass.tel_order
      ";
      $query=$this->db->query($smt);
       if($query->num_rows() > 0)
       {
        $result=$query->result_array();
        return $result;
       }
       else
       {
       return false;
       }

   }
  public function get_post_byAdminid($admid)
  {
     
    $stmt="select a.admin_id,a.post from admin_structure a where a.post_category='$admid' order by a.post asc";
    $query=$this->db->query($stmt);
    if($query->num_rows() > 0)
    {
    $result=$query->result_array();
    return $result;
    }
    else
    {
    return false;
    }
  }
  public function get_domain_bypostid($adminid)
  {
     
    $stmt="select asd.admin_id,asd.asd_id,asd.domain from admin_struct_details asd where asd.admin_id='$adminid' order by asd.domain asc";
    $query=$this->db->query($stmt);
    if($query->num_rows() > 0)
    {
    $result=$query->result_array();
    return $result;
    }
    else
    {
    return false;
    }
  }

  function post_category()
  {

    $stmt="select distinct post_category,admin_id from admin_structure group by post_category asc";
    $query=$this->db->query($stmt);
    if($query->num_rows()> 0)
    {
    $result=$query->result_array();
    return $result;
    }
    else
    {
    return false;
    }

  }
  public function insert_web_tel_dir($k)
  { 
    if(!$k[empname]=='')
    {
    // {
      $sql="INSERT INTO `web_tel_dir` 
      (`admin_id`,`asd_id`,`emp_id`, `office_ext`, `resi_ext`,`bsnl_landline`,`added_by`)
       VALUES ('$k[post]','$k[domain]','$k[empname]','$k[officeno]','$k[residenceno]','$k[bsnlno]','$k[added]');";
      if($this->db->query($sql))
      {    
       return "inserted";
      }
      else
      {
       return "error";
      }
    }

    // else 
    // {
     
    //   $stmt="select CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) as 
    //   name,u.id as emp_id,ede.domain_name,d.name as department,d.id,uad.line1,uad.line2,uad.city,uad.state,uad.pincode,uad.country,uad.contact_no
    //   from users u join user_details as ud on u.id=ud.id
    //   left join emaildata_emp ede on ede.emp_id=u.id
    //   left join user_address uad on uad.id=ud.id
    //   LEFT JOIN departments d ON d.id=ud.dept_id
    //   where u.`status`='A' and u.auth_id='emp' and u.id='$k[empname]' group by u.id";
    //   $run=$this->db->query($stmt);

    //   if($run->num_rows()> 0)
    //   {    
    //   $l=$run->result_array();
    //   }
    //   else
    //   {
    //   return "error";
    //   }
    //   foreach($l as $val)
    //   {
    //   $email=$val['domain_name'];
    //   $contact=$val['contact_no'];
    //   $line1=$val['line1'];
    //   $line2=$val['line2'];
    //   $state=$val['state'];
    //   $city=$val['city'];
    //   $pincode=$val['pincode'];
    //   $country=$val['country'];

    //   } 
    //   $sql="INSERT INTO `web_tel_dir` 
    //   (`admin_id`,`asd_id`,`emp_id`, `office_ext`, `resi_ext`,`bsnl_landline`,`email`,`mobile_no`,`line1`,`line2`,`city`,`state`,`pincode`,`country`,`added_by`)
    //   VALUES ('$k[post]','$k[domain]','$k[empname]','$k[officeno]','$k[residenceno]','$k[bsnlno]','$email','$contact','$line1','$line2','$city','$state','$pincode','$country','$k[added]');";
    //   if($this->db->query($sql))
    //   {    
    //   return "inserted";
    //   }
    //   else
    //   {
    //   return "error";
    //   }
    // }  
  }

  public function insert_tel_dir($k)
  { 
    

 $sql="INSERT INTO `tele_dir` (`admin_id`,`asd_id`,`emp_id`,`dept_id`,`office_ext`, `resi_ext`,`bsnl_landline`, `added_by`) VALUES ('$k[post]','$k[domain]','$k[empname]','$k[department]', '$k[officeno]', '$k[residenceno]', '$k[bsnlno]', '$k[added]')"; 

     
    if($this->db->query($sql))
    {
    
    return "inserted";
    }
    else
    {
    return "error";
    }
    
  }
  
  public function search_tel_number($num)
  {
    // echo "reach m";
    // print_r($num);   
    // exit;
  $stmtoff="SELECT t.tel_dir,t.office_ext,t.resi_ext,t.bsnl_landline,ass.post_category,ass.admin_id,asd.asd_id,asd.dept_id,asd.domain,cd.name AS dept,
CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS fname
FROM tele_dir t
left JOIN admin_structure ass ON t.admin_id=ass.admin_id
left JOIN admin_struct_details asd ON t.asd_id=asd.asd_id
LEFT JOIN cbcs_departments cd ON asd.dept_id=cd.id
left JOIN user_details ud ON ud.id=t.emp_id
WHERE (t.office_ext=$num[search] or t.resi_ext=$num[search] or t.bsnl_landline=$num[search]) group by t.tel_dir";
     

    $querytel=$this->db->query($stmtoff);
    if($querytel->num_rows()> 0)
    {
    
      $result=$querytel->result_array();
      return $result;
    }
    else
    {
      return false;
    }
  }
  public function update_tel_num($num)
  {
        
    if($num[edit_office]=='')
     {
      $num[edit_office]='';
     }
      if($num[edit_office]=='')
     {
      $num[edit_office]='';
     }
     if($num[edit_bsnl]=='')
     {
      $num[edit_bsnl]='';
     }
     if($num[edit_residence]=='')
     {
      $num[edit_residence]='';
     }
     
      $stmt="UPDATE `tele_dir` SET `office_ext`='$num[edit_office]',`resi_ext`='$num[edit_residence]',`bsnl_landline`='$num[edit_bsnl]' WHERE `tel_dir` ='$num[tel_dir_id]'";    
    $query=$this->db->query($stmt);
    
    if($query)
    {

    return "update";
    }
    else
    {
    return "error";
    } 
  }

  public function delete_telephone($id)
  {
    $stmt="DELETE FROM `tele_dir` WHERE `tel_dir`='$id'";
    $query=$this->db->query($stmt);
    if($query)
    {

    return "delete";
    }
    else
    {
    return "error";
    }

  }
  public function tel_dir_log($l)
  { 
    // $stmt="select tel_dir,admin_id,asd_id,dept_id,emp_id,office_ext,resi_ext,bsnl_landline from tele_dir where tel_dir='$l[tel_dir_id]'";
    // $query=$this->db->query($stmt);
    // $m=$query->result_array();
    // echo $m[0][bsnl_landline];
    // echo "<pre>";
    // print_r($m);
    // exit;

     $stmt="INSERT INTO `tele_dir_log` (`tel_dir`, `admin_id`, `asd_id`,`emp_id`, `office_ext`, `resi_ext`, `bsnl_landline`, `added_by`) VALUES ('$l[tel_dir_id]', '$l[admin_id]','$l[asd_id]','$l[emp_id]','$l[hedit_office]', '$l[hedit_residence]', '$l[hedit_bsnl]','$l[added]')";
    
    $query=$this->db->query($stmt);
    if($query)
    {

    return "delete";
    }
    else
    {
    return "error";
    }

  }

  
}

?>
