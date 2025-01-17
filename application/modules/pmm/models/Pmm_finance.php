<?php

class Pmm_finance extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('crud_global');
    }


    function NoInvoice()
    {
    	$no_invoice = '';
    	$get_last = $this->db->select('id')->order_by('id','desc')->get('pmm_penagihan_penjualan')->row_array();	
    	$id = 0;
    	if(!empty($get_last)){
    		$id = $get_last['id'] + 1;
    	}else {
    		$id = 1;
    	}
    	$no_invoice = str_pad($id, 3, '0', STR_PAD_LEFT).'/INV/BIABUMI-PRM/'.date('m').'/'.date('Y');
    	return $no_invoice;
    }


    function InsertTransactions($biaya_id,$bayar_dari,$jumlah,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => $biaya_id,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'akun' => $bayar_dari,
            'debit' => 0,
            'kredit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'BIAYA'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsJurnal($jurnal_id,$product,$kredit,$debit,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => $jurnal_id,
            'terima_id' => 0,
            'transfer_id' => 0,
            'akun' => $product,
            'debit' => $debit,
            'kredit' => $kredit,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'JURNAL UMUM'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTerima($terima_id,$setor_ke,$jumlah,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => $terima_id,
            'transfer_id' => 0,
            'akun' => $setor_ke,
            'debit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'TERIMA'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTransfer($transfer_id,$setor_ke,$jumlah,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => $transfer_id,
            'akun' => $setor_ke,
            'debit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'TRANSFER'
        );
        $this->db->insert('transactions',$data);
    }

    function UpdateTransactionsBiaya($form_id_biaya_main,$bayar_dari,$total,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => $form_id_biaya_main,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'akun' => $bayar_dari,
            'debit' => 0,
            'kredit' => $total,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'BIAYA'
        );
        $this->db->delete('transactions', array('biaya_id' => $form_id_biaya_main));
        $this->db->insert('transactions',$data);
    }

    function UpdateTransactionsJurnal($form_id_jurnal_main,$akun_jurnal,$total_debit,$tanggal_transaksi)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => $form_id_jurnal_main,
            'terima_id' => 0,
            'transfer_id' => 0,
            'akun' => $akun_jurnal,
            'debit' => $total_debit,
            'kredit' => 0,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => 'JURNAL UMUM'
        );
        $this->db->delete('transactions', array('jurnal_id' => $form_id_jurnal_main));
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan($production_id,$date_production,$no_production,$client_id,$product_id,$price)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => $production_id,
            'akun' => 3,
            'debit' => $price,
            'kredit' => 0,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan2($production_id,$date_production,$no_production,$client_id,$product_id,$price)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => $production_id,
            'akun' => 53,
            'debit' => 0,
            'kredit' => $price,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan3($production_id,$date_production,$no_production,$client_id,$product_id,$price,$komposisi_id)
    {
        $komposisi = $this->db->select('(pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume) * pk.presentase_e as volume_e, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d, (pp.display_volume * pk.presentase_e) * pk.price_e as nilai_e')
		->from('pmm_productions pp')
		->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
		->where("pp.id = '$production_id'")
		->get()->result_array();

		$total_volume_a = 0;
		$total_volume_b = 0;
		$total_volume_c = 0;
		$total_volume_d = 0;
		$total_volume_e = 0;

		$total_nilai_a = 0;
		$total_nilai_b = 0;
		$total_nilai_c = 0;
		$total_nilai_d = 0;
		$total_nilai_e = 0;

		foreach ($komposisi as $x){
			$total_volume_a += $x['volume_a'];
			$total_volume_b += $x['volume_b'];
			$total_volume_c += $x['volume_c'];
			$total_volume_d += $x['volume_d'];
			$total_volume_e += $x['volume_e'];
			$total_nilai_a += $x['nilai_a'];
			$total_nilai_b += $x['nilai_b'];
			$total_nilai_c += $x['nilai_c'];
			$total_nilai_d += $x['nilai_d'];
			$total_nilai_e += $x['nilai_e'];
			
		}

		$volume_a = $total_volume_a;
		$volume_b = $total_volume_b;
		$volume_c = $total_volume_c;
		$volume_d = $total_volume_d;
		$volume_e = $total_volume_e;

		$nilai_a = $total_nilai_a;
		$nilai_b = $total_nilai_b;
		$nilai_c = $total_nilai_c;
		$nilai_d = $total_nilai_d;
		$nilai_e = $total_nilai_e;

		$price_a = ($total_volume_a!=0)?$total_nilai_a / $total_volume_a * 1:0;
		$price_b = ($total_volume_b!=0)?$total_nilai_b / $total_volume_b * 1:0;
		$price_c = ($total_volume_c!=0)?$total_nilai_c / $total_volume_c * 1:0;
		$price_d = ($total_volume_d!=0)?$total_nilai_d / $total_volume_d * 1:0;
		$price_e = ($total_volume_e!=0)?$total_nilai_e / $total_volume_e * 1:0;

		$total_volume_komposisi = $volume_a + $volume_b + $volume_c + $volume_d + $volume_e;
		$total_nilai_komposisi = $nilai_a + $nilai_b + $nilai_c + $nilai_d + $nilai_e;

        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => $production_id,
            'akun' => 149,
            'debit' => $total_nilai_komposisi,
            'kredit' => 0,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan4($production_id,$date_production,$no_production,$client_id,$product_id,$price,$komposisi_id)
    {
        $komposisi = $this->db->select('(pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume) * pk.presentase_e as volume_e, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d, (pp.display_volume * pk.presentase_e) * pk.price_e as nilai_e')
		->from('pmm_productions pp')
		->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
		->where("pp.id = '$production_id'")
		->get()->result_array();

		$total_volume_a = 0;
		$total_volume_b = 0;
		$total_volume_c = 0;
		$total_volume_d = 0;
		$total_volume_e = 0;

		$total_nilai_a = 0;
		$total_nilai_b = 0;
		$total_nilai_c = 0;
		$total_nilai_d = 0;
		$total_nilai_e = 0;

		foreach ($komposisi as $x){
			$total_volume_a += $x['volume_a'];
			$total_volume_b += $x['volume_b'];
			$total_volume_c += $x['volume_c'];
			$total_volume_d += $x['volume_d'];
			$total_volume_e += $x['volume_e'];
			$total_nilai_a += $x['nilai_a'];
			$total_nilai_b += $x['nilai_b'];
			$total_nilai_c += $x['nilai_c'];
			$total_nilai_d += $x['nilai_d'];
			$total_nilai_e += $x['nilai_e'];
			
		}

		$volume_a = $total_volume_a;
		$volume_b = $total_volume_b;
		$volume_c = $total_volume_c;
		$volume_d = $total_volume_d;
		$volume_e = $total_volume_e;

		$nilai_a = $total_nilai_a;
		$nilai_b = $total_nilai_b;
		$nilai_c = $total_nilai_c;
		$nilai_d = $total_nilai_d;
		$nilai_e = $total_nilai_e;

		$price_a = ($total_volume_a!=0)?$total_nilai_a / $total_volume_a * 1:0;
		$price_b = ($total_volume_b!=0)?$total_nilai_b / $total_volume_b * 1:0;
		$price_c = ($total_volume_c!=0)?$total_nilai_c / $total_volume_c * 1:0;
		$price_d = ($total_volume_d!=0)?$total_nilai_d / $total_volume_d * 1:0;
		$price_e = ($total_volume_e!=0)?$total_nilai_e / $total_volume_e * 1:0;

		$total_volume_komposisi = $volume_a + $volume_b + $volume_c + $volume_d + $volume_e;
		$total_nilai_komposisi = $nilai_a + $nilai_b + $nilai_c + $nilai_d + $nilai_e;

        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => $production_id,
            'akun' => 6,
            'debit' => 0,
            'kredit' => $total_nilai_komposisi,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan5($production_id,$date_production,$no_production,$price,$komposisi_id)
    {
        $komposisi = $this->db->select('(pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume) * pk.presentase_e as volume_e, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d, (pp.display_volume * pk.presentase_e) * pk.price_e as nilai_e')
		->from('pmm_productions pp')
		->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
		->where("pp.id = '$production_id'")
		->get()->result_array();

		$total_volume_a = 0;
		$total_volume_b = 0;
		$total_volume_c = 0;
		$total_volume_d = 0;
		$total_volume_e = 0;

		$total_nilai_a = 0;
		$total_nilai_b = 0;
		$total_nilai_c = 0;
		$total_nilai_d = 0;
		$total_nilai_e = 0;

		foreach ($komposisi as $x){
			$total_volume_a += $x['volume_a'];
			$total_volume_b += $x['volume_b'];
			$total_volume_c += $x['volume_c'];
			$total_volume_d += $x['volume_d'];
			$total_volume_e += $x['volume_e'];
			$total_nilai_a += $x['nilai_a'];
			$total_nilai_b += $x['nilai_b'];
			$total_nilai_c += $x['nilai_c'];
			$total_nilai_d += $x['nilai_d'];
			$total_nilai_e += $x['nilai_e'];
			
		}

		$volume_a = $total_volume_a;
		$volume_b = $total_volume_b;
		$volume_c = $total_volume_c;
		$volume_d = $total_volume_d;
		$volume_e = $total_volume_e;

		$nilai_a = $total_nilai_a;
		$nilai_b = $total_nilai_b;
		$nilai_c = $total_nilai_c;
		$nilai_d = $total_nilai_d;
		$nilai_e = $total_nilai_e;

		$price_a = ($total_volume_a!=0)?$total_nilai_a / $total_volume_a * 1:0;
		$price_b = ($total_volume_b!=0)?$total_nilai_b / $total_volume_b * 1:0;
		$price_c = ($total_volume_c!=0)?$total_nilai_c / $total_volume_c * 1:0;
		$price_d = ($total_volume_d!=0)?$total_nilai_d / $total_volume_d * 1:0;
		$price_e = ($total_volume_e!=0)?$total_nilai_e / $total_volume_e * 1:0;

		$total_volume_komposisi = $volume_a + $volume_b + $volume_c + $volume_d + $volume_e;
		$total_nilai_komposisi = $nilai_a + $nilai_b + $nilai_c + $nilai_d + $nilai_e;

        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => $production_id,
            'akun' => 0,
            'debit' => $total_nilai_komposisi + $price,
            'kredit' => $total_nilai_komposisi + $price,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => NULL,
            'produk' => NULL,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => $tagihan_id,
            'akun' => 53,
            'debit' => $total,
            'kredit' => 0,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Invoice Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan2($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id)
    {

        $akun_masuk = $this->db->select('ppp.*, p.akun_masuk')
		->from('pmm_penagihan_penjualan ppp')
		->join('penerima p', 'ppp.client_id = p.id','left')
		->where("ppp.id = '$tagihan_id'")
		->get()->row_array();
        $akun_masuk = $akun_masuk['akun_masuk'];

        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => $tagihan_id,
            'akun' => $akun_masuk,
            'debit' => $total,
            'kredit' => 0,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Invoice Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan3($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => $tagihan_id,
            'akun' => 3,
            'debit' => 0,
            'kredit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Invoice Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan4($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => $tagihan_id,
            'akun' => 148,
            'debit' => 0,
            'kredit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Invoice Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan5($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => $tagihan_id,
            'akun' => 0,
            'debit' => $total + $total,
            'kredit' => $total + $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => NULL,
            'produk' => NULL,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualan($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => 0,
            'pembayaran_id' => $pembayaran_id,
            'akun' => $setor_ke,
            'debit' => $pembayaran_pro,
            'kredit' => 0,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Pembayaran Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualan2($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke)
    {
        $akun_masuk = $this->db->select('pm.*, p.akun_masuk')
		->from('pmm_pembayaran pm')
		->join('penerima p', 'pm.client_id = p.id','left')
		->where("pm.client_id = '$client_id'")
		->get()->row_array();
        $akun_masuk = $akun_masuk['akun_masuk'];
        
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => 0,
            'pembayaran_id' => $pembayaran_id,
            'akun' => $akun_masuk,
            'debit' => 0,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $client_id,
            'produk' => NULL,
            'transaksi' => 'Pembayaran Penjualan'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualan3($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke)
    {
        $data = array(
            'biaya_id' => 0,
            'jurnal_id' => 0,
            'terima_id' => 0,
            'transfer_id' => 0,
            'production_id' => 0,
            'tagihan_id' => 0,
            'pembayaran_id' => $pembayaran_id,
            'akun' => 0,
            'debit' => $pembayaran_pro,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => NULL,
            'produk' => NULL,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>'
        );
        $this->db->insert('transactions',$data);
    }

    function InsertLogs($log_type,$table_name,$table_id,$description)
    {
        $data = array(
            'log_type' => $log_type,
            'table_name' => $table_name,
            'table_id' => $table_id,
            'description' => $description,
            'created_by' => $this->session->userdata('admin_id')
        );
        $this->db->insert('logs',$data);
    }

    function getSalesPoPpn($id)
    {
        $total = 0;

        $this->db->select('SUM(tax) as tax');
        $this->db->where('sales_po_id',$id);
        //$this->db->where('tax_id',3);
        $this->db->where("tax_id in (3,6)");
        $query = $this->db->get('pmm_sales_po_detail')->row_array();
        if(!empty($query)){
            $total = $query['tax'];
        }
        return $total;
    }


    function getTotalPembayaranPenagihanPenjualan($id)
    {   
        $total = 0;

        $this->db->select('SUM(total) as total');
        $this->db->where('penagihan_id',$id);
        $this->db->where('status','Disetujui');
        $query = $this->db->get('pmm_pembayaran')->row_array();
        if(!empty($query)){
            $total = $query['total'];
        }
        return $total;
    }

    
    function getTotalPembayaranPenagihanPembelian($id)
    {   
        $total = 0;

        $this->db->select('SUM(total) as total');
        $this->db->where('penagihan_pembelian_id',$id);
        $this->db->where('status','Disetujui');
        $query = $this->db->get('pmm_pembayaran_penagihan_pembelian')->row_array();
        if(!empty($query)){
            $total = $query['total'];
        }
        return $total;
    }

    function getVerifDokumen($id)
    {
        $data = array();
        $this->db->select('pvp.*, (pvp.nilai_tagihan + pvp.ppn - pvp.pph) as total_tagihan, ps.nama as supplier_name');
        $this->db->join('pmm_penagihan_pembelian pp','pvp.penagihan_pembelian_id = pp.id','left');
        $this->db->join('penerima ps','ps.id = pp.supplier_id','left');
        $query = $this->db->get_where('pmm_verifikasi_penagihan_pembelian pvp',array('pvp.penagihan_pembelian_id'=>$id))->row_array();

        if(!empty($query)){
            $query['tanggal_po'] = date('d/m/Y',strtotime($query['tanggal_po']));
            $query['tanggal_invoice'] = date('d/m/Y',strtotime($query['tanggal_invoice']));
            $query['tanggal_diterima_office'] = date('d/m/Y',strtotime($query['tanggal_diterima_office']));
            $query['tanggal_lolos_verifikasi'] = date('d/m/Y',strtotime($query['tanggal_lolos_verifikasi']));
            $query['tanggal_diterima_proyek'] = date('d/m/Y',strtotime($query['tanggal_diterima_proyek']));
            $query['nilai_kontrak'] = number_format($query['nilai_kontrak'],0,',','.');
            $query['nilai_tagihan'] =  number_format($query['nilai_tagihan'],0,',','.');
            $query['ppn'] =  number_format($query['ppn'],0,',','.');
            $query['pph'] =  number_format($query['pph'],0,',','.');
            $query['total_tagihan'] =  number_format($query['total_tagihan'],0,',','.');
            $query['verifikator'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$query['created_by']),'admin_name');
            $data = $query;
        }

        return $data;
    }

    function getVerifDokumenById($id)
    {
        $data = array();
        $this->db->select('pvp.*, (pvp.nilai_tagihan + pvp.ppn - pvp.pph) as total_tagihan, ps.nama as supplier_name');
        $this->db->join('pmm_penagihan_pembelian pp','pvp.penagihan_pembelian_id = pp.id','left');
        $this->db->join('penerima ps','ps.id = pp.supplier_id','left');
        $query = $this->db->get_where('pmm_verifikasi_penagihan_pembelian pvp',array('pvp.id'=>$id))->row_array();
        

        if(!empty($query)){
            $query['tanggal_po'] = date('d/m/Y',strtotime($query['tanggal_po']));
            $query['tanggal_invoice'] = date('d/m/Y',strtotime($query['tanggal_invoice']));
            $query['tanggal_diterima_proyek'] = date('d/m/Y',strtotime($query['tanggal_diterima_proyek']));
            $query['tanggal_lolos_verifikasi'] = date('d/m/Y',strtotime($query['tanggal_lolos_verifikasi']));
            $query['tanggal_diterima_office'] = date('d/m/Y',strtotime($query['tanggal_diterima_office']));
            $query['nilai_kontrak'] = number_format($query['nilai_kontrak'],0,',','.');
            $query['nilai_tagihan'] = number_format($query['nilai_tagihan'],0,',','.');
            $query['ppn'] = number_format($query['ppn'],0,',','.');
            $query['pph'] = number_format($query['pph'],0,',','.');
            $query['total_tagihan'] = number_format($query['total_tagihan'],0,',','.');
            $query['verifikator'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$query['created_by']),'admin_name');
            $data = $query;
        }

        return $data;
    }

    function CheckorNo($id)
    {
        $output = 'X';

        if(!empty($id)){
            if($id == 1){
                $output = 'V';
            }
        }
        return $output;
    }

    function CheckorNoNew($id)
    {
        $output = '';

        if(!empty($id)){
            if($id >= 0){
                $output = 'V';
            }
        }
        return $output;
    }

    function CheckorNoNew2($id)
    {
        $output = '';

        if(!empty($id)){
            if($id >= 65){
                $output = 'V';
            }
        }
        return $output;
    }

    function CheckorNoNew3($id)
    {
        $output = '';

        if(!empty($id)){
            if($id >= 56 && $id <= 65){
                $output = 'V';
            }
        }
        return $output;
    }

    function CheckorNoNew4($id)
    {
        $output = '';

        if(!empty($id)){
            if($id <= 55){
                $output = 'V';
            }
        }
        return $output;
    }


    function BankCash()
    {
        $output = array();
        // Setor Bank
        $this->db->select('c.*');
        $this->db->where('c.coa_category',3);
        $this->db->where('c.status','PUBLISH');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_coa c');
        $output = $query->result_array();
        return $output;
    }

    function getAkunCoa()
    {
        $output = array();
        // Setor Bank
        $this->db->select('c.*');
        // $this->db->where('c.coa_category',3);
        $this->db->where('c.status','PUBLISH');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_coa c');
        $output = $query->result_array();
        return $output;
    }


    //function GetSaldoKasBank($id)
    //{
    //    $output = 0;

    //    $this->db->select('(SUM(debit) - SUM(credit)) as total');
    //    $this->db->where('coa_id',$id);
    //    $query = $this->db->get('transactions')->row_array();

        // print_r($query);
    //    if(!empty($query['total'])){
    //        $output = $query['total'];
    //    }
    //    return $output;
    //}

}