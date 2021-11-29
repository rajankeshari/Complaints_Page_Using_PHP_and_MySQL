<?php

class send_indiviadual_mail_model extends CI_Model
{
    
   
    function __construct() {
        parent::__construct();
    }


    public function get_applied_post()
    {

        $sql = "select * from `mail_posts`";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function get_email($post_applied)
    {

        $sql = "select * , a.id as `email_id` from `mail_f_emp_applied_for` a inner join `mail_posts` b on a.post_name = b.name where b.name = '".$post_applied."' and a.app_no != ''"; 
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); //die();
        // echo '<pre>';
        // print_r($query->result_array()); 
        // echo '</pre>';
        // exit;
        return $query->result_array();
    }


    public function capture_mail_response($email_response)
    {

        //print_r($email_response);

        $this->db->insert('capture_email_data', $email_response);
        //echo $this->db->last_query();
        //$insert_id = $this->db->insert_id();
        //return $insert_id;

    }


    public function store_logs_values($store_email_logs)
    {

        //print_r($email_response);

        $this->db->insert('store_email_logs', $store_email_logs);

        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        return $insert_id;

    }

    public function get_jee_advanced_email() 
    {

        $query = $this->db->select('*')
                          ->from('send_multiple_mail_jee') 
                          ->get();

        return $query->result_array();

    }


    public function capture_mail_response_advance($email_response,$application_id)
    {

        $query = $this->db->where('id',$application_id);
                 $this->db->update('send_multiple_mail_jee',$email_response);



    }



    
    
    
}
?>

