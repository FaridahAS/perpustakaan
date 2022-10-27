<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BukuModel extends CI_Model {

	private $table      = 'buku';
	private $primaryKey = 'id_buku';

	private function _setBuilder()
	{
		$columnOrder	= [$this->primaryKey];
		$columnSearch	= $this->db->list_fields($this->table);
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->from($this->table);
		$this->include->setDataTables($columnOrder, $columnSearch, $orderBy);
	}

	public function getDataTable()
	{
		return array(
			'dataTable' 		=> $this->include->getResult($this->_setBuilder()),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered'	=> $this->db->get($this->_setBuilder())->num_rows(),
		);
	}

	public function findBukuTersedia($buku_id)
	{
		$this->db->where('buku_id', $buku_id);
		$this->db->where('tgl_pengembalian', null);
		$query = $this->db->get('peminjaman');
		return $query->result();
	}

}

/* End of file BukuModel.php */
/* Location: ./application/models/BukuModel.php */