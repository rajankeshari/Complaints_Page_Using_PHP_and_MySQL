<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medi_open_stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_all_medicine_list() {
        $myquery = "select a.* from hc_medicine a order by a.m_id";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

}

?>