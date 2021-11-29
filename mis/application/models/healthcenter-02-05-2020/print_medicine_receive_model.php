<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Print_medicine_receive_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_all_medicine_received_list() {
        $myquery = "select b.m_name,
a.mrec_qty,
DATE_FORMAT(a.exp_date,'%d-%b-%Y') as exp_date,
a.batch_no,
DATE_FORMAT(a.supp_date,'%d-%b-%Y') as supp_date,
a.mrp,
a.rate_of_pur,
a.amount,
a.invoice_no 
from hc_medi_receive a
inner join hc_medicine b on b.m_id=a.m_id
order by b.m_name";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

}

?>