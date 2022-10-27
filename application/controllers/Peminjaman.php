<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

	}

	public function index()
	{
		$data = array(
			'title' 	=> 'Peminjaman',
			'buku' 		=> $this->db->get('buku')->result(),
			'siswa' 	=> $this->db->get('siswa')->result(),
		);

		$this->include->layout('index_peminjaman', $data);

	}

	public function list_peminjaman()
	{
		$this->load->model('PeminjamanModel', 'peminjaman');
		$bulider = $this->peminjaman->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$nama_buku 		= $field->kode_buku .' - '. $field->nama_buku;
			$nama_siswa 	= $field->nis .' - '. $field->nama_siswa;
			$tgl_pinjam 	= $this->_findTanggal($field->tgl_peminjaman);
			$tgl_kembali 	= $field->tgl_pengembalian ? $this->_findTanggal($field->tgl_pengembalian) : '<small class="badge badge-secondary">BUKU SEDANG DIPINJAM</small>';

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<button type="button" onclick="edit_data('. $field->id_peminjaman .')" class="btn btn-success"><i class="fas fa-edit"></i></button>';
            $aksi .= '<button type="button" onclick="delete_data('. "'". base64_encode($field->id_peminjaman) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
            $aksi .= '<input type="hidden" name="buku_id_'. $field->id_peminjaman .'" value="'. $field->buku_id .'">';
            $aksi .= '<input type="hidden" name="siswa_id_'. $field->id_peminjaman .'" value="'. $field->siswa_id .'">';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $nama_buku .'</div>';
			$row[]  = '<div style="text-align: left;">'. $nama_siswa .'</div>';
			$row[]  = '<div style="text-align: left;">'. $tgl_pinjam .'</div>';
			if ($this->input->post('pengembalian')) {

				$pengembalian = '<div class="form-group clearfix">';
				$pengembalian .= '<div class="icheck-success d-inline">';
				$pengembalian .= '<input type="checkbox" id="pengembalian_'. $field->id_peminjaman .'" value="'. $field->id_peminjaman .'" onclick="kembalikan_buku('. $field->id_peminjaman .');">';
				$pengembalian .= '<label for="pengembalian_'. $field->id_peminjaman .'" style="font-weight: normal;">Kembalikan Buku</label>';
				$pengembalian .= '</div>';
				$pengembalian .= '</div>';

				$row[]  = '<div style="text-align: left;">'. $pengembalian .'</div>';
			} else {
				$row[]  = '<div style="text-align: left;">'. $tgl_kembali .'</div>';
				$row[]  = '<div style="text-align: center;">'. $aksi .'</div>';
			}

			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
		);

		echo json_encode($output);
	}

	private function _findTanggal($date)
	{
	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 	= substr($date, 0, 4);
	    $moth 	= substr($date, 5, 2);
	    $date 	= substr($date, 8, 2);

	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result = $substr . " " . $moths[(int) $moth - 1] . " " . $year;
	    return ($result);
	}

	public function update_pengembalian($id_peminjaman = null)
	{
		$query = $this->db->get_where('peminjaman ', ['id_peminjaman' => $id_peminjaman])->row();

		if (empty($query->id_peminjaman)) {
			show_404();
		}

		$this->db->update('peminjaman', ['tgl_pengembalian' => date('Y-m-d')], ['id_peminjaman' => $query->id_peminjaman]);
		$this->session->set_flashdata('success', 'Mengembalikan Buku Berhasil!');
		echo json_encode(['status' => true]);
	}

	public function save_peminjaman()
	{
		$query = $this->db->get_where('peminjaman', ['id_peminjaman' => $this->input->post('id_peminjaman')])->row();

		$this->load->library('form_validation');

		$list_fields = array(
			'buku_id' 	=> ['Nama Buku'  => 'trim|required|numeric'],
			'siswa_id' 	=> ['Nama Siswa' => 'trim|required|numeric'],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('numeric', '{field} hanya boleh berisi angka.');

		if ($this->form_validation->run() == FALSE) {

			$list_errors = array();
			foreach ($list_fields as $key => $value) {
				$list_errors[$key] = form_error($key);
			}

			$output = array(
				'status'	=> false,
				'errors'	=> $list_errors,
			);

		} else {

			$check_peminjaman = $this->db->where([
				'buku_id' 			=> $this->input->post('buku_id'),
				'siswa_id' 			=> $this->input->post('siswa_id'),
				'tgl_pengembalian' 	=> null,
			])->get('peminjaman')->num_rows();

			if ($check_peminjaman > 0 && empty($query->id_peminjaman)) {
				$output = array(
					'status' 	=> true,
					'type'		=> 'secondary',
					'message'	=> 'Buku Sedang Dipinjam!',
				);
			} else {
				$field_names = array_keys($list_fields);
				$data = array();
				for ($i=0; $i < count($field_names); $i++) { 
					$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
				}

				if (count($data) > 0) {
					if (isset($query->id_peminjaman)) {
						$this->db->update('peminjaman', $data, ['id_peminjaman' => $query->id_peminjaman]);
						$message = 'Mengubah Peminjaman Berhasil!';
					} else {
						$data['tgl_peminjaman'] = date('Y-m-d');
						$this->db->insert('peminjaman', $data);
						$message = 'Menambah Peminjaman Berhasil!';
					}
				}

				$output = array(
					'status' 	=> true,
					'type'		=> 'success',
					'message'	=> $message,
				);
			}
		}

		echo json_encode($output);
	}

	public function delete_peminjaman($id_peminjaman = null)
	{
		$query = $this->db->get_where('peminjaman', ['id_peminjaman' => base64_decode($id_peminjaman)])->row();
		if (empty($query->id_peminjaman)) {
			show_404();
		}
		$this->db->delete('peminjaman', ['id_peminjaman' => $query->id_peminjaman]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Peminjaman Berhasil!'
		]);
	}
}

/* End of file Peminjaman.php */
/* Location: ./application/controllers/Peminjaman.php */
