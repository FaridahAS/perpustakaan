<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PeminjamanModel extends CI_Model {

	private $table      = 'peminjaman';
	private $primaryKey = 'id_peminjaman';

	private function _setBuilder()
	{
		$columnOrder	= [$this->primaryKey];

		$columnSearch	= array_merge(
			$this->db->list_fields($this->table),
			$this->db->list_fields('buku'), 
			$this->db->list_fields('siswa'), 
			$this->db->list_fields('kelas')
		);
		
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->join('buku', 'buku.id_buku = peminjaman.buku_id', 'left');
		$this->db->join('siswa', 'siswa.id_siswa = peminjaman.siswa_id', 'left');
		$this->db->join('kelas', 'kelas.id_kelas = siswa.kelas_id', 'left');
		if ($this->input->post('pengembalian')) {
			$this->db->where('peminjaman.tgl_pengembalian', null);
		}
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

}

/* End of file PeminjamanModel.php */
/* Location: ./application/models/PeminjamanModel.php */