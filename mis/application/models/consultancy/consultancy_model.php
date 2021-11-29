<?php

class Consultancy_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_many_records($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
  $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

  	   $this->db->select($str);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }


/* Added by CK on 21 June 2019 */
public function get_many_records1($selector,$val,$sdates,$edates) {

$sql="select a.*, b.emp_no, concat_ws(' ', c.first_name,c.middle_name,c.last_name) as ename,c.dept_id
from cons_master a
inner join
cons_member b on a.id=b.cons_master_id
inner join
user_details c on c.id=b.emp_no
where b.`type`='ci'";
			if ($selector=='dept')
			{
					$sql .= " AND c.dept_id='".$val."'";
					
			}
			if ($selector=='pi_id')
			{
					$sql .= " AND b.emp_no='".$val."'";
					
			}
			if ($sdates!="" and $edates=="" )
			{
					$sql .= " AND a.created_date='".$sdates."'";
					
			}
			if ($sdates!="" and $edates!="" )
			{
					$sql .= " AND a.created_date>='".$sdates."' and a.created_date<='".$edates."'";
					
			}
			
$sql.=" order by a.id desc";


$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result_array();
		else
			return false;
}

	
/* Added by CK on 25 June 2019 */

public function get_dept_records($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
  $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

  	   $this->db->select($str);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }
	



    public function get_records_from_two_join($tableOne, $tableOneColumn, $tableTwo, $tableTwoColumn, $filters_id_values, $orderBy = "", $req_data = "") {

        if ($req_data) {
            $this->db->select($req_data);
        } else {
            $this->db->select('*');
        }


        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {//filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }

        return $this->db->get()->result_array();
    }
    function getDept()
       {
         $query = $this->db->get_where('departments', array('type' => 'academic'));
         return $query->result_array();
       }
       function getFaculty($dept,$emp_id=null)
   {
           /* 20-4-19  $query=$this->db->query("
             SELECT ud.id, CONCAT_WS(' ', ud.salutation, ud.first_name, ud.middle_name, ud.last_name) as name
             FROM users AS fd
             JOIN user_details AS ud ON fd.id = ud.id
             WHERE fd.auth_id='emp' AND ud.dept_id IN (SELECT id FROM departments WHERE type='academic')
             ORDER BY name ASC ");*/

	/* 	 
             $query=$this->db->query(" SELECT ud.id, CONCAT_WS(' ', ud.salutation, ud.first_name, ud.middle_name, ud.last_name) as name
             FROM users AS fd
             JOIN user_details AS ud ON fd.id = ud.id
             WHERE fd.auth_id='emp' AND ud.dept_id IN (SELECT id FROM departments WHERE type='academic') and ud.dept_id='$dept'
             ORDER BY id desc");
	*/

	/* Modified by CK on 22 June 2019	*/
	
	if($emp_id)$str=" and  ud.id= '".$emp_id."'";
	$query=$this->db->query(" SELECT ud.id, CONCAT_WS(' ', ud.salutation, ud.first_name, ud.middle_name, ud.last_name) as name
             FROM users AS fd
             JOIN user_details AS ud ON fd.id = ud.id
             WHERE fd.auth_id='emp' AND ud.dept_id IN (SELECT id FROM departments WHERE type='academic') and ud.dept_id='$dept'  $str
             ORDER BY ud.first_name, ud.middle_name, ud.last_name");
	
	
           return $query->result_array();
       }
       public function get_many_records_filter($dept,$falcuty){
  if(!empty($dept) && empty($falcuty)){
  $query=$this->db->query("select cm.*,cmm.* from cons_master cm join cons_member cmm on cmm.cons_master_id=cm.id where cmm.department='$dept'  group by cm.id");
} else if(!empty($dept) && !empty($falcuty)){
$query=$this->db->query("select cm.*,cmm.* from cons_master cm join cons_member cmm on cmm.cons_master_id=cm.id where cmm.emp_no='$falcuty'  group by cm.id");
}else if(empty($dept) && !empty($falcuty)){
$query=$this->db->query("select cm.*,cmm.* from cons_master cm join cons_member cmm on cmm.cons_master_id=cm.id where cmm.emp_no='$falcuty'  group by cm.id");

}
else{
$query=$this->db->query("select cm.*,cmm.* from cons_master cm join cons_member cmm on cmm.cons_master_id=cm.id group by cm.id");
}
return $query->result_array();
}
    
	
	public function add_update_record($table, $data = array(), $id_name = '') {
        $this->db->set($data);
        if ($id_name) {
            $this->db->where($id_name, $data[$id_name]);
            $query = $this->db->update($table);
			//echo $this->db->last_query();die();
        } else {//adding record in table
            $query = $this->db->insert($table);
			//echo $this->db->last_query();die();
        }
    }

    public function delete_many_records($table_name, $filters_id_values = "") {
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->delete($table_name);
    }

    public function get_record_from_filter($table_name, $request_fields = "", $filters_id_values) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        $this->db->select($str);
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        $query = $this->db->get($table_name);
        return $query->row_array();
    }

    public function select_group_wise_data($table, $filters_id_values = array(), $groupWiseColumn, $returnKey, $returnValue, $order_by = "", $value = "") {
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        if ($groupWiseColumn) {
            $this->db->group_by($groupWiseColumn);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $this->db->select($returnKey, $returnValue);
        $this->db->from($table);
        $return_data = $this->db->get()->result_array();
        $opt_str = "";
        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select a Value </option>';
        } else {
            $opt_str.='<option value=""> Select a Value </option>';
        }
        if (!empty($return_data)) {
            foreach ($return_data as $return_data_value) {

                if (!empty($return_data_value[$returnKey])) {
                    $opt_str.='<option ';
                    if (($value) && ($value == $return_data_value[$returnKey])) {
                        $opt_str.='selected="selected" ';
                    }
                    $opt_str.='value="' . $return_data_value[$returnKey] . '">' . $return_data_value[$returnValue] . '</option>';
                }
            }
        }
        $opt_str.='<option value="newValue"> Choose Others</option>';
        return $opt_str;
    }

    public function get_records_groupby_from_two_join_two_column($tableOne, $tableOneColumn, $tableOneColumnTwo, $tableTwo, $tableTwoColumn, $tabletwoColumnTwo, $filters_id_values, $orderBy = "", $req_data = "", $groupWiseColumn = "") {

        if ($req_data) {
            $this->db->select($req_data);
        } else {
            $this->db->select('*');
        }


        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn and b.$tabletwoColumnTwo=a.$tableOneColumnTwo", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {//filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }
        if ($groupWiseColumn) {
            $this->db->group_by($groupWiseColumn);
        }

        return $this->db->get()->result_array();
    }
	public function update_record_from_filter($table, $data = array(), $filters_id_values = array()) {
        if ($filters_id_values) {
            $this->db->set($data);
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
            $query = $this->db->update($table);
        }
    }
	function get_co_ci($id){
		$sql="SELECT CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS empno,a.emp_no from cons_member a 
INNER JOIN user_details b ON b.id=a.emp_no
WHERE a.cons_master_id=? AND a.`TYPE`='co-ci'";
		$query = $this->db->query($sql,array($id));
		if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}

}

?>
