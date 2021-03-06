<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Pengguna extends CI_Model {

	//UNTUK UMUM
	public function bidang_list_all()
	{
		$q=$this->db->select('*')->get('tb_bidang');
		return $q->result();
	}


	//BATAS UMUM

	//UNTUK PROFIL
	public function swap_pengguna()
	{
		$q=$this->db->select('tanggal_regis')->get('tb_pengguna');
		return $q->result();
	}
	//BATAS PROFIL

	//UNTUK KEBUTUHAN
	public function get_data_dkebutuhan($id_dkebutuhan) 
	{
		$q=$this->db->select( '	bt.type, 
														nk.nama_kebutuhan,
														db.id_dkebutuhan, db.id_kebutuhan, db.id_nkebutuhan, 
														db.id, db.tgl_pengajuan, 
														db.tgl_mulai, db.tgl_akhir, db.status')
								
				->from('tb_dkebutuhan as db')
				->join('tb_pengguna as p', 'p.id = db.id', 'LEFT')
				->join('tb_kebutuhan as bt', 'bt.id_kebutuhan = db.id_kebutuhan', 'LEFT')
				->join('tb_nkebutuhan as nk','nk.id_nkebutuhan = db.id_nkebutuhan', 'LEFT')
				->where( 'db.id_dkebutuhan', $id_dkebutuhan )
				->limit(1)->get();

		if( $q->num_rows() < 1 ) {
			redirect( base_url('/pengguna/kebutuhan') );
		}
		return $q->row();
	}

	// public function get_kebutuhan($type) 
	// {
	// 	$q=$this->db->select('id_kebutuhan')->from('tb_kebutuhan')->where('type', $type)->get();
	// 	return $q->result();
	// }
	// public function get_nkebutuhan($nama_kebutuhan) 
	// {
	// 	$q=$this->db->select('id_nkebutuhan')->from('tb_nkebutuhan')->where('nama_kebutuhan',$nama_kebutuhan)->get();
	// 	return $q->result();
	// }

	public function get_kebutuhan() 
	{
		$q=$this->db->select('*')->get('tb_kebutuhan');
		return $q->result();
	}
	public function get_nkebutuhan() 
	{
		$q=$this->db->select('*')->get('tb_nkebutuhan');
		return $q->result();
	}

	public function dkebutuhan_list_all() 
	{
		$q=$this->db->select(  'bt.type, 
								nk.nama_kebutuhan,
								db.id, db.id_dkebutuhan, db.tgl_pengajuan, db.tgl_mulai, db.tgl_akhir, db.status' )

				->from('tb_dkebutuhan as db')
				->join('tb_kebutuhan as bt', 'bt.id_kebutuhan = db.id_kebutuhan', 'LEFT')
				->join('tb_nkebutuhan as nk','nk.id_nkebutuhan = db.id_nkebutuhan', 'LEFT')
				->where('db.id', $this->session->userdata('user_id') )
				->get();
		return $q->result();
	}

	public function dkebutuhan_list_ajax($json) 
	{
		$new_arr=array();$i=1;
		foreach ($json as $key => $value) 
		{
			$value->no=$i;
			switch ($value->status) 
			{
				case 'waiting':
					$label = 'warning';
					break;
				case 'rejected':
					$label = 'danger';
					break;
				case 'approved':
					$label = 'success';
					break;
			};
			$diff  = date_diff( date_create($value->tgl_mulai), date_create($value->tgl_akhir) );
			$value->lama_izin = $diff->format('%d hari');
			$value->tgl_pengajuan = date_format ( date_create($value->tgl_pengajuan), 'd M Y');
			$value->tgl_mulai = date_format( date_create($value->tgl_mulai), 'd M Y');
			$value->tgl_akhir = date_format( date_create($value->tgl_akhir), 'd M Y');
			$value->status = '<label class="badge badge-'.$label.' text-uppercase">'.$value->status.'</label>';
			$new_arr[]=$value;
			$i++;
		}
		return $new_arr;
	}

	public function add_kebutuhan( $id_kebutuhan, $id_nkebutuhan,
	                         	   $tgl_pengajuan, $tgl_mulai , $tgl_akhir, $status ) 

	{
		$d_t_d = array( 'id'						=> $this->session->userdata('user_id'),      
										'id_kebutuhan'	=> $id_kebutuhan,
										'id_nkebutuhan'	=> $id_nkebutuhan,
										'tgl_pengajuan' => $tgl_pengajuan,
										'tgl_mulai' 		=> $tgl_mulai,
										'tgl_akhir' 		=> $tgl_akhir,
										'status' 				=> $status );

		$this->db->insert('tb_dkebutuhan', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Pengajuan Permintaan Kebutuhan berhasil ditambahkan');
	}

	public function update_kebutuhan( $id_dkebutuhan, $id_kebutuhan, $id_nkebutuhan,
																		$tgl_pengajuan, $tgl_mulai , $tgl_akhir ) 
	{
		$d_t_d = array( 'id_kebutuhan'	=> $id_kebutuhan,
										'id_nkebutuhan'	=> $id_nkebutuhan,
										'tgl_pengajuan' => $tgl_pengajuan,
										'tgl_mulai' 		=> $tgl_mulai,
										'tgl_akhir' 		=> $tgl_akhir
										// 'status' 				=> $status 
									);

		$this->db->where( 'id_dkebutuhan', $id_dkebutuhan )->update('tb_dkebutuhan', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Data Pengajuan Permintaan berhasil diubah');
	}

	public function delete_kebutuhan($id_dkebutuhan) 
	{
		$this->db->delete('tb_dkebutuhan', array('id_dkebutuhan' => $id_dkebutuhan));
	}
	//BATAS KEBUTUHAN

	//UNTUK KELUHAN
	public function get_dkeluhan($id_dkeluhan) 
	{
		$q=$this->db->select('bt.type,
													db.id_dkeluhan, db.tgl_pengajuan, db.keluhan, db.status' )

				->from ('tb_dkeluhan as db')
				// ->join ('tb_bidang as b', 'b.id_bidang = db.id_bidang', 'LEFT')
				->join ('tb_keluhan as bt', 'bt.id_keluhan = db.id_keluhan', 'LEFT')
				->where( 'db.id_dkeluhan', $id_dkeluhan )
				->limit(1)->get();

		if( $q->num_rows() < 1 ) {
			redirect( base_url('/pengguna/keluhan') );
		}
		return $q->row();
	}

	public function get_keluhan() 
	{
		$q=$this->db->select('*')->get('tb_keluhan');
		return $q->result();
	}

	public function dkeluhan_list_all() 
	{
		$q=$this->db->select(  'bt.type,
								db.id, db.id_dkeluhan, db.tgl_pengajuan, db.keluhan, db.status' )
								
				->from('tb_dkeluhan as db')
				->join('tb_keluhan as bt', 'bt.id_keluhan = db.id_keluhan', 'LEFT')
				->where('db.id', $this->session->userdata('user_id'))
				->get();
		return $q->result();
	}

	// YANG BARU
	public function dkeluhan_list_ajax($json)
	{
		$new_arr=array();$i=1;
		foreach ($json as $key => $value) 
		{
			$value->no=$i;
			switch ($value->status) 
			{
				case 'waiting':
					$label = 'warning';
					break;
				case 'rejected':
					$label = 'danger';
					break;
				case 'approved':
					$label = 'success';
					break;
			};

			$value->status = '<label class="badge badge-'.$label.' text-uppercase">'.$value->status.'</label>';
			$new_arr[]=$value;
			$i++;
		}
		return $new_arr;
	}

	public function add_keluhan( $id_keluhan, $keluhan, $tgl_pengajuan, $status ) 
	{
		$d_t_d = array( 'id'						=> $this->session->userdata('user_id'), 
										'id_keluhan'		=> $id_keluhan,
										'keluhan'				=> $keluhan,
										'tgl_pengajuan' => $tgl_pengajuan,
										'status' 				=> $status );

		$this->db->insert('tb_dkeluhan', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Pengajuan Permintaan Keluhan berhasil ditambahkan');
	}

	public function update_keluhan(	$id_dkeluhan, $id_keluhan, $keluhan, 
																	$tgl_pengajuan, $status  ) 
	{
		$d_t_d = array( 'id_keluhan'		=> $id_keluhan,
										'keluhan'				=> $keluhan,
										'tgl_pengajuan' => $tgl_pengajuan
										// 'status' 				=> $status 
									);

		$this->db->where( 'id_dkeluhan', $id_dkeluhan )->update('tb_dkeluhan', $d_t_d);
		$this->session->set_flashdata('msg_alert', 'Data Pengajuan Keluhan berhasil diubah');
	}

	public function delete_keluhan($id_dkeluhan) 
	{ 
		$this->db->delete('tb_dkeluhan', array('id_dkeluhan' => $id_dkeluhan)); 
	}
	//BATAS KELUHAN

}

/* End of file M_DataKeluhan.php */
/* Location: ./application/models/M_DataKeluhan.php */