<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		logged_in();
	}

	public function index()
	{
		$row = $this->_findInfo();

		$list_info = array(
			'Jumlah Siswa' 		=> ['<i class="fas fa-users" style="color: white;"></i>' => $row['jumlah_siswa']],
			'Jumlah Buku' 		=> ['<i class="fas fa-book" style="color: white;"></i>' => $row['jumlah_buku']],
			'Buku Dipinjam' 	=> ['<i class="fas fa-exchange-alt" style="color: white;"></i>' => $row['buku_dipinjam']],
			'Buku Tersedia' 	=> ['<i class="fas fa-archive" style="color: white;"></i>' => $row['buku_tersedia']],
		);

		$data = array(
			'title' 		=> 'Beranda',
			'list_info'		=> $list_info,
		);

		$this->include->layout('index_home', $data);
	}

	private function _findInfo()
	{
		$id_buku 	 = array();
		$jumlah_buku = 0;
		foreach ($this->db->get('buku')->result() as $row) {
			$id_buku[] = $row->id_buku;
			$jumlah_buku += $row->jumlah_buku;
		}

		$buku_dipinjam = $this->db->where_in('buku_id', $id_buku)->where('tgl_pengembalian', null)->get('peminjaman')->num_rows();

		return array(
			'jumlah_siswa' 	=> $this->db->count_all_results('siswa'),
			'jumlah_buku' 	=> $jumlah_buku,
			'buku_dipinjam' => $buku_dipinjam,
			'buku_tersedia'	=> $jumlah_buku - $buku_dipinjam,
		);
	}

	public function settings()
	{
		$data = array(
			'title' 		=> 'Settings',
			'petugas'		=> $this->db->get_where('petugas', ['id_petugas' => $this->session->id_petugas])->row(),
		);

		$this->include->layout('index_settings', $data);
	}

	public function list_petugas()
	{
		$this->load->model('PetugasModel', 'petugas');
		$bulider = $this->petugas->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$foto_profile = $field->foto_profile ? 'assets/img/' . $field->foto_profile : 'assets/dist/img/default-150x150.png';

			$foto = '<img class="img-fluid" src="' . base_url($foto_profile) . '" alt="" style="max-width: 100px; height: 100px;">';

			$aksi = '<div class="btn-group btn-group-sm">';
			$aksi .= '<button type="button" onclick="edit_petugas(' . $field->id_petugas . ')" class="btn btn-success"><i class="fas fa-edit"></i></button>';
			$aksi .= '<button type="button" onclick="ubah_foto(' . "'" . base64_encode($field->id_petugas) . "'" . ')" class="btn btn-primary"><i class="fas fa-image"></i></button>';
			$aksi .= '<button type="button" class="btn btn-danger" onclick="delete_petugas(' . "'" . base64_encode($field->id_petugas) . "'" . ')"><i class="fas fa-trash"></i></button>';
			$aksi .= '</div>';
			$aksi .= '<input type="hidden" name="nama_petugas_' . $field->id_petugas . '" value="' . $field->nama_petugas . '">';
			$aksi .= '<input type="hidden" name="email_' . $field->id_petugas . '" value="' . $field->email . '">';

			$row[]  = '<div style="text-align: center;">' . $no++ . '</div>';
			$row[]  = '<div style="text-align: left;">' . $field->nama_petugas . '</div>';
			$row[]  = '<div style="text-align: left;">' . $field->email . '</div>';
			$row[]  = '<div style="text-align: center;">' . $foto . '</div>';
			$row[]  = '<div style="text-align: center;">' . $aksi . '</div>';

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

	public function list_kelas()
	{
		$this->load->model('KelasModel', 'kelas');
		$bulider = $this->kelas->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$aksi = '<div class="btn-group btn-group-sm">';
			$aksi .= '<button type="button" onclick="edit_kelas(' . $field->id_kelas . ')" class="btn btn-success"><i class="fas fa-edit"></i></button>';
			$aksi .= '<button type="button" class="btn btn-danger" onclick="delete_kelas(' . "'" . base64_encode($field->id_kelas) . "'" . ')"><i class="fas fa-trash"></i></button>';
			$aksi .= '</div>';
			$aksi .= '<input type="hidden" name="nama_kelas_' . $field->id_kelas . '" value="' . $field->nama_kelas . '">';

			$row[]  = '<div style="text-align: center;">' . $no++ . '</div>';
			$row[]  = '<div style="text-align: left;">' . $field->nama_kelas . '</div>';
			$row[]  = '<div style="text-align: center;">' . $aksi . '</div>';

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

	public function update_foto_profile($id_petugas = null)
	{
		$query 	= $this->db->get_where('petugas', ['id_petugas' => base64_decode($id_petugas)])->row();

		if (empty($query->id_petugas)) {
			show_404();
		}

		$this->_do_upload();

		if ($this->upload->do_upload('foto_profile')) {
			if ($query->foto_profile) {
				unlink('./assets/img/' . $query->foto_profile);
			}

			$this->db->update('petugas', ['foto_profile' => $this->upload->data('file_name')], ['id_petugas' => $query->id_petugas]);
		}

		if ($this->input->is_ajax_request()) {
			$output = array(
				'status' 	=> true,
				'message'	=> 'Mengubah Foto Berhasil!',
			);

			echo json_encode($output);
		} else {
			$this->session->set_flashdata('success', 'Mengubah Foto Berhasil!');
			redirect($this->agent->referrer());
		}
	}

	private function _do_upload()
	{
		$config['upload_path']   = './assets/img/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|GIF|JPG|PNG|JPEG|BMP|';
		$config['max_size']    	 = 10000;
		$config['max_width']   	 = 10000;
		$config['max_height']  	 = 10000;
		$config['file_name']     = time();
		$this->upload->initialize($config);
	}

	public function delete_foto_profile($id_petugas = null)
	{
		$query 	= $this->db->get_where('petugas', ['id_petugas' => base64_decode($id_petugas)])->row();

		if (empty($query->id_petugas)) {
			show_404();
		}

		if ($query->foto_profile) {
			unlink('./assets/img/' . $query->foto_profile);
		}

		$this->db->update('petugas', ['foto_profile' => null], ['id_petugas' => $query->id_petugas]);

		if ($this->input->is_ajax_request()) {
			$output = array(
				'status' 	=> true,
				'message'	=> 'Menghapus Foto Berhasil!',
			);

			echo json_encode($output);
		} else {
			$this->session->set_flashdata('success', 'Menghapus Foto Berhasil!');
			redirect($this->agent->referrer());
		}
	}

	public function update_password_petugas($id_petugas = null)
	{
		$query = $this->db->get_where('petugas', ['id_petugas' => base64_decode($id_petugas)])->row();

		$this->load->library('form_validation');

		$list_fields = array(
			'password1' 		=> ['Password Sekarang' 	=> 'trim|required'],
			'password2' 		=> ['Password Baru' 		=> 'trim|required|min_length[6]'],
			'password3' 		=> ['Konfirmasi Password' 	=> 'trim|required|matches[password2]'],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
		$this->form_validation->set_message('matches', '{field} tidak sama dengan {param}.');

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

			if (isset($query->id_petugas)) {
				if ($query->password == sha1($this->input->post('password1'))) {
					$this->db->update('petugas', ['password' => sha1($this->input->post('password2'))], ['id_petugas' => $query->id_petugas]);
					$output = array(
						'status' 	=> true,
						'type'		=> 'success',
						'message'	=> 'Mengubah Password Berhasil!',
					);
				} else {
					$output = array(
						'status' 	=> false,
						'errors'	=> ['password1' => 'Password Sekarang salah, coba lagi.']
					);
				}
			} else {
				$output = array(
					'status' 	=> true,
					'type'		=> 'danger',
					'message'	=> 'Mengubah Password Gagal!',
				);
			}
		}

		echo json_encode($output);
	}

	public function save_petugas()
	{
		$query = $this->db->get_where('petugas', ['id_petugas' => $this->input->post('id_petugas')])->row();

		if (isset($query->id_petugas)) {
			$unique_email =  $query->email != $this->input->post('email') ? '|is_unique[petugas.email]' : '';
			$required_password =  '';
		} else {
			$unique_email = '|is_unique[petugas.email]';
			$required_password =  '|required';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'nama_petugas' 	=> ['Nama Petugas' 	=> 'trim|required'],
			'email' 		=> ['Email' 		=> 'trim|required|valid_email' . $unique_email],
			'password' 		=> ['Password' 		=> 'trim|min_length[6]' . $required_password],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');
		$this->form_validation->set_message('valid_email', '{field} tidak valid.');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');

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

			$data = array(
				'nama_petugas' 	=> ucwords(strtolower($this->input->post('nama_petugas'))),
				'email' 		=> $this->input->post('email'),
			);

			if ($this->input->post('password')) {
				$data['password'] = sha1($this->input->post('password'));
			}

			if (isset($query->id_petugas)) {
				$this->db->update('petugas', $data, ['id_petugas' => $query->id_petugas]);
				$id_petugas = $query->id_petugas;
				$message = 'Mengubah Petugas Berhasil!';
			} else {
				$this->db->insert('petugas', $data);
				$id_petugas = $this->db->insert_id();
				$message = 'Menambah Petugas Berhasil!';
			}

			if ($id_petugas == $this->session->id_petugas) {
				$this->session->set_flashdata('success', 'Mengubah Profile Berhasil!');
				$output = array(
					'status' 	 => true,
					'id_petugas' => $id_petugas
				);
			} else {
				$output = array(
					'status' 	=> true,
					'message'	=> $message,
				);
			}
		}

		echo json_encode($output);
	}

	public function delete_petugas($id_petugas = null)
	{
		$query = $this->db->get_where('petugas', ['id_petugas' => base64_decode($id_petugas)])->row();
		if (empty($query->id_petugas)) {
			show_404();
		}
		if ($query->foto_profile) {
			unlink('./assets/img/' . $query->foto_profile);
		}
		$this->db->delete('petugas', ['id_petugas' => $query->id_petugas]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Petugas Berhasil!'
		]);
	}

	public function save_kelas()
	{
		$query = $this->db->get_where('kelas', ['id_kelas' => $this->input->post('id_kelas')])->row();

		if (isset($query->id_kelas)) {
			$unique_nama_kelas =  $query->nama_kelas != $this->input->post('nama_kelas') ? '|is_unique[kelas.nama_kelas]' : '';
		} else {
			$unique_nama_kelas = '|is_unique[kelas.nama_kelas]';
		}


		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'trim|required' . $unique_nama_kelas);
		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');

		if ($this->form_validation->run() == FALSE) {

			$output = array(
				'status'	=> false,
				'errors'	=> ['nama_kelas' => form_error('nama_kelas')],
			);
		} else {

			$data = array('nama_kelas' => htmlspecialchars($this->input->post('nama_kelas')));

			if (isset($query->id_kelas)) {
				$this->db->update('kelas', $data, ['id_kelas' => $query->id_kelas]);
				$message = 'Mengubah Kelas Berhasil!';
			} else {
				$this->db->insert('kelas', $data);
				$message = 'Menambah Kelas Berhasil!';
			}

			$output = array(
				'status' 	=> true,
				'message'	=> $message,
			);
		}

		echo json_encode($output);
	}

	public function delete_kelas($id_kelas = null)
	{
		$query = $this->db->get_where('kelas', ['id_kelas' => base64_decode($id_kelas)])->row();
		if (empty($query->id_kelas)) {
			show_404();
		}
		$this->db->delete('kelas', ['id_kelas' => $query->id_kelas]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Kelas Berhasil!'
		]);
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
