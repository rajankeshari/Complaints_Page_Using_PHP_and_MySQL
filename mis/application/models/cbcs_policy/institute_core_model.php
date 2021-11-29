
<?php

class Institute_core_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function insert($data)
    {
        if($this->db->insert('cbcs_institute_core',$data))
            return TRUE;
        else
            return FALSE;
    }

       function insert_master($data)
    {
        if($this->db->insert('cbcs_institute_master',$data))
            return TRUE;
        else
            return FALSE;
    }





    function get_list(){


        $q = "select a.*,concat(concat_ws(' ',b.first_name,b.middle_name,b.last_name),' [',ucase(dept_id),']')as usernm from cbcs_institute_core a
inner join user_details b on b.id=a.user_id";

        $qu = $this->db->query($q);

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->result();
        }else{
            return false;
        }

    }

     function get_list_master(){


        $q = "select * from cbcs_institute_master";

        $qu = $this->db->query($q);

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->result();
        }else{
            return false;
        }

    }

    

    function check_already($syear){
        $q = "select * from cbcs_institute_core where session_year= ? ";

        $qu = $this->db->query($q,array($syear));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }

    }

       function check_already_master($id){
        $q = "select * from cbcs_institute_master where core_policy_id= ? ";

        $qu = $this->db->query($q,array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }

    }

    



      function edit_institute_percentage($id){
        $sql = "select * from cbcs_institute_core where id=?";
        $query = $this->db->query($sql,array($id));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return FALSE;

    }
    }

    function move_to_temp_institute_per($id){
        $sql = "insert into cbcs_institute_core_temp  select * from cbcs_institute_core where id=?";
        $query = $this->db->query($sql, array($id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_institute_percentage($id,$per,$uid){
        $sql = "update cbcs_institute_core set credit_point=".$per.",user_id=".$uid." where id=".$id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }









}

?>
