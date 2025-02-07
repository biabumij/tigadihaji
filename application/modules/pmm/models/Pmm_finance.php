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

    function InsertTransactionsPenjualan($production_id,$date_production,$no_production,$client_id,$product_id,$price,$created_by,$created_on)
    {
        $data = array(
            'production_id' => $production_id,
            'akun' => 3,
            'debit' => $price,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan2($production_id,$date_production,$no_production,$client_id,$product_id,$price,$created_by,$created_on)
    {
        $data = array(
            'production_id' => $production_id,
            'akun' => 53,
            'kredit' => $price,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan3($production_id,$date_production,$no_production,$client_id,$product_id,$price,$komposisi_id,$created_by,$created_on)
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
            'production_id' => $production_id,
            'akun' => 149,
            'debit' => $total_nilai_komposisi,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualan4($production_id,$date_production,$no_production,$client_id,$product_id,$price,$komposisi_id,$created_by,$created_on)
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
            'production_id' => $production_id,
            'akun' => 6,
            'kredit' => $total_nilai_komposisi,
            'tanggal_transaksi' => $date_production,
            'nomor_transaksi' => $no_production,
            'penerima' => $client_id,
            'produk' => $product_id,
            'transaksi' => 'Pengiriman Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPenjualanTotal($production_id,$date_production,$no_production,$price,$komposisi_id,$created_by,$created_on)
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
            'production_id' => $production_id,
            'debit' => $total_nilai_komposisi + $price,
            'kredit' => $total_nilai_komposisi + $price,
            'tanggal_transaksi' => $date_production,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_id' => $tagihan_id,
            'akun' => 3,
            'debit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'transaksi' => 'Invoice Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan2($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_id' => $tagihan_id,
            'akun' => 53,
            'kredit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'transaksi' => 'Invoice Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan3($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_id' => $tagihan_id,
            'akun' => 149,
            'debit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'transaksi' => 'Invoice Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualan4($tagihan_id,$tanggal_invoice_id,$total,$nomor_invoice,$client_id,$created_by,$created_on)
    {

        $data = array(
            'tagihan_id' => $tagihan_id,
            'akun' => 6,
            'kredit' => $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $client_id,
            'transaksi' => 'Invoice Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPenjualanTotal($tagihan_id,$tanggal_invoice_id,$total,$client_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_id' => $tagihan_id,
            'debit' => $total + $total,
            'kredit' => $total + $total,
            'tanggal_transaksi' => $tanggal_invoice_id,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualan($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke,$created_by,$created_on)
    {
        $data = array(
            'pembayaran_id' => $pembayaran_id,
            'akun' => $setor_ke,
            'debit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $client_id,
            'transaksi' => 'Pembayaran Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualan2($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke,$created_by,$created_on)
    {
        $akun_masuk = $this->db->select('pm.*, p.akun_masuk')
		->from('pmm_pembayaran pm')
		->join('penerima p', 'pm.client_id = p.id','left')
		->where("pm.client_id = '$client_id'")
		->get()->row_array();
        $akun_masuk = $akun_masuk['akun_masuk'];
        
        $data = array(
            'pembayaran_id' => $pembayaran_id,
            'akun' => $akun_masuk,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $client_id,
            'transaksi' => 'Pembayaran Penjualan',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPenjualanTotal($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$client_id,$setor_ke,$created_by,$created_on)
    {
        $data = array(
            'pembayaran_id' => $pembayaran_id,
            'debit' => $pembayaran_pro,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembelian($no_production,$date_receipt,$surat_jalan,$purchase_order_id,$material_id,$display_price_new,$created_by,$created_on)
    {
        $supplier_id = $this->db->select('supplier_id')
		->from('pmm_purchase_order')
		->where("id = '$purchase_order_id'")
		->get()->row_array();
        $supplier_id = $supplier_id['supplier_id'];
        $data = array(
            'receipt_id' => $no_production,
            'akun' => 5,
            'debit' => $display_price_new,
            'tanggal_transaksi' => $date_receipt,
            'nomor_transaksi' => $surat_jalan,
            'penerima' => $supplier_id,
            'produk' => $material_id,
            'transaksi' => 'Penerimaan Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembelian2($no_production,$date_receipt,$surat_jalan,$purchase_order_id,$material_id,$display_price_new,$created_by,$created_on)
    {
        $supplier_id = $this->db->select('supplier_id')
		->from('pmm_purchase_order')
		->where("id = '$purchase_order_id'")
		->get()->row_array();
        $supplier_id = $supplier_id['supplier_id'];
        $data = array(
            'receipt_id' => $no_production,
            'akun' => 32,
            'debit' => NULL,
            'kredit' => $display_price_new,
            'tanggal_transaksi' => $date_receipt,
            'nomor_transaksi' => $surat_jalan,
            'penerima' => $supplier_id,
            'produk' => $material_id,
            'transaksi' => 'Penerimaan Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembelianTotal($no_production,$date_receipt,$surat_jalan,$display_price_new,$created_by,$created_on)
    {
        $supplier_id = $this->db->select('supplier_id')
		->from('pmm_purchase_order')
		->where("id = '$purchase_order_id'")
		->get()->row_array();
        $supplier_id = $supplier_id['supplier_id'];
        $data = array(
            'receipt_id' => $no_production,
            'debit' => $display_price_new,
            'kredit' => $display_price_new,
            'tanggal_transaksi' => $date_receipt,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPembelian($tagihan_id,$tanggal_invoice,$total_pro,$nomor_invoice,$supplier_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_pembelian_id' => $tagihan_id,
            'akun' => 32,
            'debit' => $total_pro,
            'tanggal_transaksi' => $tanggal_invoice,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $supplier_id,
            'transaksi' => 'Invoice Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPembelian2($tagihan_id,$tanggal_invoice,$total_pro,$nomor_invoice,$supplier_id,$created_by,$created_on)
    {

        $akun_masuk = $this->db->select('ppp.*, p.akun_masuk')
		->from('pmm_penagihan_pembelian ppp')
		->join('penerima p', 'ppp.supplier_id = p.id','left')
		->where("ppp.id = '$tagihan_id'")
		->get()->row_array();
        $akun_masuk = $akun_masuk['akun_masuk'];


        $data = array(
            'tagihan_pembelian_id' => $tagihan_id,
            'akun' => $akun_masuk,
            'kredit' => $total_pro,
            'tanggal_transaksi' => $tanggal_invoice,
            'nomor_transaksi' => $nomor_invoice,
            'penerima' => $supplier_id,
            'transaksi' => 'Invoice Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTagihanPembelianTotal($tagihan_id,$tanggal_invoice,$total_pro,$nomor_invoice,$supplier_id,$created_by,$created_on)
    {
        $data = array(
            'tagihan_pembelian_id' => $tagihan_id,
            'debit' => $total_pro,
            'kredit' => $total_pro,
            'tanggal_transaksi' => $tanggal_invoice,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPembelian($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$bayar_dari,$created_by,$created_on)
    {
        $akun_masuk = $this->db->select('p.akun_masuk')
		->from('pmm_pembayaran_penagihan_pembelian pppp')
        ->join('pmm_penagihan_pembelian ppp', 'pppp.penagihan_pembelian_id = ppp.id','left')
		->join('penerima p', 'ppp.supplier_id = p.id','left')
		->where("pppp.id = '$pembayaran_id'")
		->get()->row_array();
        $akun_masuk = $akun_masuk['akun_masuk'];

        $supplier_id = $this->db->select('ppp.supplier_id')
		->from('pmm_pembayaran_penagihan_pembelian pppp')
        ->join('pmm_penagihan_pembelian ppp', 'pppp.penagihan_pembelian_id = ppp.id','left')
		->where("pppp.id = '$pembayaran_id'")
		->get()->row_array();
        $supplier_id = $supplier_id['supplier_id'];
        
        $data = array(
            'pembayaran_pembelian_id' => $pembayaran_id,
            'akun' => $akun_masuk,
            'debit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $supplier_id,
            'transaksi' => 'Pembayaran Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPembelian2($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$nomor_transaksi,$bayar_dari,$created_by,$created_on)
    {
        $supplier_id = $this->db->select('ppp.supplier_id')
		->from('pmm_pembayaran_penagihan_pembelian pppp')
        ->join('pmm_penagihan_pembelian ppp', 'pppp.penagihan_pembelian_id = ppp.id','left')
		->where("pppp.id = '$pembayaran_id'")
		->get()->row_array();
        $supplier_id = $supplier_id['supplier_id'];

        $data = array(
            'pembayaran_pembelian_id' => $pembayaran_id,
            'akun' => $bayar_dari,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $supplier_id,
            'transaksi' => 'Pembayaran Pembelian',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsPembayaranPembelianTotal($pembayaran_id,$tanggal_pembayaran,$pembayaran_pro,$bayar_dari,$created_by,$created_on)
    {
        $data = array(
            'pembayaran_pembelian_id' => $pembayaran_id,
            'debit' => $pembayaran_pro,
            'kredit' => $pembayaran_pro,
            'tanggal_transaksi' => $tanggal_pembayaran,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsBiaya($biaya_id,$nomor_transaksi,$deskripsi,$product,$jumlah,$tanggal_transaksi,$penerima,$created_by,$created_on)
    {
        $data = array(
            'biaya_id' => $biaya_id,
            'deskripsi' => $deskripsi,
            'akun' => $product,
            'debit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'penerima' => $penerima,
            'transaksi' => 'Biaya',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsBiaya2($biaya_id,$bayar_dari,$nomor_transaksi,$jumlah_biaya,$tanggal_transaksi,$penerima,$created_by,$created_on)
    {
        $data = array(
            'biaya_id' => $biaya_id,
            'akun' => $bayar_dari,
            'kredit' => $jumlah_biaya,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Biaya',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsBiayaTotal($biaya_id,$jumlah_biaya,$tanggal_transaksi,$created_by,$created_on)
    {
        $data = array(
            'biaya_id' => $biaya_id,
            'debit' => $jumlah_biaya,
            'kredit' => $jumlah_biaya,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsJurnal($jurnal_id,$nomor_transaksi,$product,$debit,$kredit,$tanggal_transaksi,$created_by,$created_on)
    {
        $data = array(
            'jurnal_id' => $jurnal_id,
            'akun' => $product,
            'debit' => $debit,
            'kredit' => $kredit,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Jurnal Umum',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsJurnalTotal($jurnal_id,$total_debit,$total_kredit,$tanggal_transaksi,$created_by,$created_on)
    {
        $data = array(
            'jurnal_id' => $jurnal_id,
            'debit' => $total_debit,
            'kredit' => $total_kredit,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionTerima($terima_id,$nomor_transaksi,$tanggal_transaksi,$setor_ke,$jumlah,$created_by,$created_on)
    {
        $data = array(
            'terima_id' => $terima_id,
            'akun' => $setor_ke,
            'kredit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Terima Uang',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionTerima2($terima_id,$nomor_transaksi,$tanggal_transaksi,$terima_dari,$jumlah,$created_by,$created_on)
    {
        $data = array(
            'terima_id' => $terima_id,
            'akun' => $terima_dari,
            'debit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Terima Uang',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTerimaTotal($terima_id,$jumlah,$tanggal_transaksi,$created_by,$created_on)
    {
        $data = array(
            'terima_id' => $terima_id,
            'debit' => $jumlah,
            'kredit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionTransfer($transfer_id,$nomor_transaksi,$tanggal_transaksi,$setor_ke,$jumlah,$created_by,$created_on)
    {
        $data = array(
            'transfer_id' => $transfer_id,
            'akun' => $setor_ke,
            'kredit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Transfer Uang',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionTransfer2($transfer_id,$nomor_transaksi,$tanggal_transaksi,$transfer_dari,$jumlah,$created_by,$created_on)
    {
        $data = array(
            'transfer_id' => $transfer_id,
            'akun' => $transfer_dari,
            'debit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'nomor_transaksi' => $nomor_transaksi,
            'transaksi' => 'Transfer Uang',
            'created_by' => $created_by,
            'created_on' =>  $created_on
        );
        $this->db->insert('transactions',$data);
    }

    function InsertTransactionsTransferTotal($transfer_id,$jumlah,$tanggal_transaksi,$created_by,$created_on)
    {
        $data = array(
            'transfer_id' => $transfer_id,
            'debit' => $jumlah,
            'kredit' => $jumlah,
            'tanggal_transaksi' => $tanggal_transaksi,
            'transaksi' => '<div style="text-align:right; vertical-align:middle;">Total</div>',
            'created_by' => $created_by,
            'created_on' =>  $created_on
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