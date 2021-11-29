<?php

class Check_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function index() 
    {

        
        

        $this->db->trans_begin();

        $this->db->query('AN SQL QUERY...');
        $this->db->query('ANOTHER QUERY...');
        $this->db->query('AND YET ANOTHER QUERY...');

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
                $this->db->trans_commit();
        }

    }



    ?>
