<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kunci extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','produk/m_produk'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
    }


	public function table_pemakaian()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('date <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('*');
		$this->db->order_by('date','desc');
		$query = $this->db->get('kunci_bahan_baku');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date'] = date('d F Y',strtotime($row['date']));
                $row['jumlah_bahan'] = number_format($row['nilai_semen'] + $row['nilai_pasir'] + $row['nilai_1020'] + $row['nilai_2030'] + $row['nilai_additive'],0,',','.');
				$row['jumlah_solar'] = number_format($row['nilai_solar'],0,',','.');
				$row['status'] = $row['status'];
				$row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));

				$admin_id = $this->session->userdata('admin_id');
				$approval = $this->db->select('*')
				->from('tbl_admin')
				->where("admin_id = $admin_id ")
				->get()->row_array();
				$edit_rap =  $approval['edit_rap'];

				if($edit_rap == 1){
					$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataPemakaian('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:5px;"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_pemakaian()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('kunci_bahan_baku',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}
	

	public function form_pemakaian()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('kunci/form_pemakaian', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_pemakaian()
	{
		$date = $this->input->post('date');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date' => date('Y-m-d', strtotime($date)),
			'unit_head' => 6,
			'logistik' => 10,
			'admin' => 10,
			'keu' => 9,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('kunci_bahan_baku', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>ERROR</b>');
			redirect('/kunci/pemakaian');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>SAVED</b>');
			redirect('admin/kunci');
		}
	}

	public function table_rakor()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('date <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('*');
		$this->db->order_by('date','desc');
		$query = $this->db->get('kunci_rakor');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['date'] = date('d F Y',strtotime($row['date']));
				$row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataRakor('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:5px;"><i class="fa fa-close"></i> </a>';

				/*if($this->session->userdata('admin_group_id') == 1){
					$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteDataRakor('.$row['id'].')" class="btn btn-danger" style="font-weight:bold; border-radius:5px;"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}*/
				
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_rakor()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('kunci_rakor',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function form_rakor()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$this->load->view('kunci/form_rakor', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_rakor()
	{
		$date = $this->input->post('date');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'date' => date('Y-m-d', strtotime($date)),
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s'),
		);

		$this->db->insert('kunci_rakor', $arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>ERROR</b>');
			redirect('/kunci#rakor');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>SAVED</b>');
			redirect('admin/kunci');
		}
	}

}
?>