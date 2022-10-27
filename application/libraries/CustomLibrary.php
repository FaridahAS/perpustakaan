<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomLibrary
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function layout($content, $data = NULL)
    {
        $section = array('content' => $this->ci->load->view('section/' . $content, $data, TRUE));
        return $this->ci->load->view('layout/index_page', $section);
    }

    # DataTables

    public function setDataTables($col_order, $col_search, $order_by)
    {
        $i = 0;
        foreach ($col_search as $row) {
            if (@$_POST['search']['value']) {

                if ($i === 0) {
                    $this->ci->db->group_start();
                    $this->ci->db->like($row, $_POST['search']['value']);
                } else {
                    $this->ci->db->or_like($row, $_POST['search']['value']);
                }

                if (count($col_search) - 1 == $i)
                    $this->ci->db->group_end();
            }
            $i++;
        }
        if (@$_POST['order']) {
            $this->ci->db->order_by($col_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (@$order_by) {
            $this->ci->db->order_by(key($order_by), $order_by[key($order_by)]);
        }
    }

    private function _getPaging()
    {
        if ($this->ci->input->post('length') != -1)
            $this->ci->db->limit($this->ci->input->post('length'), $this->ci->input->post('start'));
    }

    private $resultSet;

    public function getResult($bulider)
    {
        $this->_getPaging();
        $this->resultSet = $bulider;
        return $this->ci->db->get()->result();
    }

    #End DataTables

}

/* End of file CustomLibrary.php */
/* Location: ./application/libraries/CustomLibrary.php */