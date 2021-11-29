<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Enter_terms_conditions_model extends CI_Model{


    public function insert_terms_conditions($data)
    {

        $this->db->insert('terms_and_conditions',$data);


    }


    public function get_terms_and_conditions()
    {
        $query = $this->db->select('*')
                            ->from('terms_and_conditions')
                            ->get();


        return $query->result_array();
    }


    public function get_terms_and_conditions_details($id)
    {
        $query = $this->db->select('*')
                            ->from('terms_and_conditions')
                            ->where('id',$id)
                            ->get();


        return $query->result_array();
    }

    public function update_terms_and_conditions($data,$id)
    {
        $this->db->where('id',$id)
                 ->update('terms_and_conditions',$data);

    }

    public function delete_terms_and_conds($id)
    {
        $this->db->where('id',$id)->delete('terms_and_conditions');

    }



}


