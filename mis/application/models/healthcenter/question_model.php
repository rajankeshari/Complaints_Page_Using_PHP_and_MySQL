<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Question_model extends CI_Model {
    
    var $q = 'out_question';
    var $a ='out_ans';
    
    public function __construct() {
        parent::__construct();
    }
    
    function insertQuestion($data){
        $this->db->insert($this->q,$data);
        return $this->db->insert_id();
    }
    
    function insertAnswer($data){
        $this->db->insert($this->a,$data);
    }   

}

?>