<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Print_counter_stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_all_medicine_received_list() {
        /*$myquery = "select b.m_name,
DATE_FORMAT(a.cs_exp_date,'%d-%b-%Y') as exp_date,
a.cs_batchno,
a.cs_qty
from hc_counter_stock a
inner join hc_medicine b on b.m_id=a.m_id
order by b.m_name";*/

$myquery="SELECT a.*,DATE_FORMAT(b.cs_exp_date,'%d-%b-%Y') as exp_date,b.cs_batchno,c.m_name FROM hc_counter_master a
INNER JOIN hc_counter_stock b ON b.m_id=a.m_id
inner join hc_medicine c ON c.m_id=a.m_id GROUP BY a.m_id,b.cs_exp_date,b.cs_batchno ORDER BY trim(c.m_name)";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
	
	function get_main_stock_medicine() {
        $myquery = "SELECT a.*,b.m_name,c.* FROM hc_mainstore_stock a
INNER JOIN  hc_medicine b ON b.m_id=a.m_id
INNER JOIN hc_medi_expdate c ON c.m_id=a.m_id
ORDER BY trim(b.m_name)";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

}

?>