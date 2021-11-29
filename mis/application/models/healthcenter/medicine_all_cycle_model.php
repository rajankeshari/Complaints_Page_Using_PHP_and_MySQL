<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Medicine_all_cycle_model extends CI_Model
{
    public function __construct() 
    { 
        parent::__construct(); 
    }

    function getMedcineIdByName($mname) 
    {
        $this->db->select('*');
        $this->db->from('hc_medicine a');
        $this->db->where('LOWER(a.m_name)', $mname);
        $p = $this->db->get();

        if ($p->num_rows > 0) {

            return $p->result();

        }
    }

    function get_all_data_med($m_id)
    {
        $sql = "SELECT hc_mainstore_stock.ms_qty AS mainstock_qty,hc_medi_receive.* FROM hc_medi_receive left join hc_mainstore_stock
        ON hc_medi_receive.m_id = hc_mainstore_stock.m_id
        WHERE hc_medi_receive.m_id = '$m_id'";
        $hc_medi_receive = $this->db->query($sql);
        if($hc_medi_receive->num_rows() > 0)
        {
            $data['received_in_mainstock'] = $hc_medi_receive->result_array();
        }

        $sql = "SELECT hc_counter_master.cs_qty AS counterstock_qty,hc_counter_stock.* FROM hc_counter_stock left join hc_counter_master
        ON hc_counter_stock.m_id = hc_counter_master.m_id
        WHERE hc_counter_stock.m_id = '$m_id' AND hc_counter_stock.status = 'accepted'";
        $hc_counter_stock = $this->db->query($sql);
        if($hc_counter_stock->num_rows() > 0)
        {
            $data['received_in_counterstock'] = $hc_counter_stock->result_array();
        }

        $sql = "SELECT * FROM hc_patient c WHERE c.mid = '$m_id' AND c.m_status = 'Issued'";
        $hc_patient = $this->db->query($sql);
        if($hc_patient->num_rows() > 0)
        {
            $data['issue_to_patient'] = $hc_patient->result_array();
        }

        return $data;
        
    }

}