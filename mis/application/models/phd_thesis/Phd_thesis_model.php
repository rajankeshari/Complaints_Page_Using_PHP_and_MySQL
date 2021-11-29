<?php
class Phd_thesis_model extends CI_Model 
{

    function __construct() 
    {        
        parent::__construct(array('adpg'));
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation'); 
    }


    
}
?>
