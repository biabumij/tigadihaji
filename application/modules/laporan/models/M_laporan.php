<?php

class M_laporan extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('crud_global');
    }

    function biaya_langsung_parent($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.coa_category in ('15','17')");
        $this->db->where("c.id <> 110 "); //Biaya Diskonto Bank
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_langsung($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.coa_category in ('15','17')");
        $this->db->where("c.id <> 110 "); //Biaya Diskonto Bank
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_langsung_jurnal_parent($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.coa_category in ('15','17')");
        $this->db->where("c.id <> 110 "); //Biaya Diskonto Bank
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function biaya_langsung_jurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.coa_category in ('15','17')");
        $this->db->where("c.id <> 110 "); //Biaya Diskonto Bank
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function biaya_lainnya_parent($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.id = 110 ");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_lainnya($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.coa_category in ('15','17')");
        $this->db->where("c.id = 110 ");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_lainnya_jurnal_parent($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.id = 110 ");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function biaya_lainnya_jurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("c.id = 110 ");
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function buildTree(array $elements, $parentId = 0) 
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['coa_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['coa_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function showBiayaPemakaianDanaParent($date)
    {
        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("pdb.akun = 232");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaPemakaianDana($date)
    {
        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi, b.memo');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("pdb.akun = 232");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaPemakaianDanaJurnalParent($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("pdb.akun = 232");
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa_parent');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function showBiayaPemakaianDanaJurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi, b.memo');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where("pdb.akun = 232");
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

}
