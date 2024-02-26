<!DOCTYPE html>
<html>
	<head>
	  <title>ALAT</title>
	  
	  <?php
		$search = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
		);
		
		$replace = array(
		'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
		);
		
		$subject = "$filter_date";

		echo str_replace($search, $replace, $subject);

	  ?>
	  
	  <style type="text/css">
		 body {
			font-family: helvetica;
		}

		table.table-border-judul1, th.table-border-judul1, td.table-border-judul1 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul2, th.table-border-judul2, td.table-border-judul2 {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul3, th.table-border-judul3, td.table-border-judul3 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-right: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul4, th.table-border-judul4, td.table-border-judul4 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul5, th.table-border-judul5, td.table-border-judul5 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-right: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul6, th.table-border-judul6, td.table-border-judul6 {
			border-left: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul7, th.table-border-judul7, td.table-border-judul7 {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul8, th.table-border-judul8, td.table-border-judul8 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul9, th.table-border-judul9, td.table-border-judul9 {
			border-top: 1px solid black;
			border-left: 1px solid black;
			border-right: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul10, th.table-border-judul10, td.table-border-judul10 {
			border-left: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-judul11, th.table-border-judul11, td.table-border-judul11 {
			border-left: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 7px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="left" style="display: block;font-weight: bold;font-size: 11px;">BIAYA ALAT</div>
		<div align="left" style="display: block;font-weight: bold;font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
		<div align="left" style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
		<br />
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table width="98%" border="0" cellpadding="3" border="0">
		
			<?php
			
			$pembelian_bp = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '1'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_pembelian_bp = 0;
			$total_vol_pembelian_bp = 0;

			foreach ($pembelian_bp as $x){
				$total_pembelian_bp += $x['price'];
				$total_vol_pembelian_bp += $x['volume'];
			}

			$pembelian_tm = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '2'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_pembelian_tm = 0;
			$total_vol_pembelian_tm = 0;

			foreach ($pembelian_tm as $x){
				$total_pembelian_tm += $x['price'];
				$total_vol_pembelian_tm += $x['volume'];
			}

			$pembelian_wl = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '3'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_pembelian_wl = 0;
			$total_vol_pembelian_wl = 0;

			foreach ($pembelian_wl as $x){
				$total_pembelian_wl += $x['price'];
				$total_vol_pembelian_wl += $x['volume'];
			}

			$pembelian_tf = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '4'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.material_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_pembelian_tf = 0;
			$total_vol_pembelian_tf = 0;

			foreach ($pembelian_tf as $x){
				$total_pembelian_tf += $x['price'];
				$total_vol_pembelian_tf += $x['volume'];
			}

			$akumulasi_bbm = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar_2) as total_nilai_keluar_2')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();

			$total_nilai_bbm = 0;
			$total_nilai_bbm = $akumulasi_bbm['total_nilai_keluar_2'];

			$insentif_tm = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_tm = 0;

			foreach ($insentif_tm as $y){
				$total_insentif_tm += $y['total'];
			}

			$insentif_wl = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_wl = 0;

			foreach ($insentif_wl as $y){
				$total_insentif_wl += $y['total'];
			}

			$total_insentif_all = $total_insentif_tm + $total_insentif_wl;

			$produk_exc = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc = 0;
			$total_vol_pembelian_produk_exc = 0;
			foreach ($produk_exc as $x){
				$total_price_exc += $x['price'];
				$total_vol_pembelian_produk_exc += $x['volume'];
			}

			$produk_dmp_4m3 = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '6'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3 = 0;
			$total_vol_pembelian_produk_dmp_4m3 = 0;
			foreach ($produk_dmp_4m3 as $x){
				$total_price_dmp_4m3 += $x['price'];
				$total_vol_pembelian_produk_dmp_4m3 += $x['volume'];
			}

			$produk_dmp_10m3 = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '7'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3 = 0;
			$total_vol_pembelian_produk_dmp_10m3 = 0;
			foreach ($produk_dmp_10m3 as $x){
				$total_price_dmp_10m3 += $x['price'];
				$total_vol_pembelian_produk_dmp_10m3 += $x['volume'];
			}

			$produk_sc = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '8'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc = 0;
			$total_vol_pembelian_produk_sc = 0;
			foreach ($produk_sc as $x){
				$total_price_sc += $x['price'];
				$total_vol_pembelian_produk_sc += $x['volume'];
			}

			$produk_gns = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '9'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns = 0;
			$total_vol_pembelian_produk_gns = 0;
			foreach ($produk_gns as $x){
				$total_price_gns += $x['price'];
				$total_vol_pembelian_produk_gns += $x['volume'];
			}

			$produk_wl_sc = $this->db->select('
			pn.nama, po.no_po, p.nama_produk, prm.measure, SUM(prm.volume) as volume, prm.harga_satuan, SUM(prm.price) as price')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->join('penerima pn', 'po.supplier_id = pn.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("p.kategori_alat = '10'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->group_by('prm.purchase_order_id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc = 0;
			$total_vol_pembelian_produk_wl_sc = 0;
			foreach ($produk_wl_sc as $x){
				$total_price_wl_sc += $x['price'];
				$total_vol_pembelian_produk_wl_sc += $x['volume'];
			}

			$total_volume_all = $total_vol_pembelian_bp + $total_vol_pembelian_tm + $total_vol_pembelian_wl + $total_vol_pembelian_tf + ($total_vol_pembelian_produk_exc + $total_vol_pembelian_produk_dmp_4m3 + $total_vol_pembelian_produk_dmp_10m3 + $total_vol_pembelian_produk_sc + $total_vol_pembelian_produk_gns + $total_vol_pembelian_produk_wl_sc);
			$total_nilai_all = $total_pembelian_bp + $total_pembelian_tm + $total_pembelian_wl + $total_pembelian_tf + $total_nilai_bbm + $total_insentif_all + ($total_price_exc + $total_price_dmp_4m3 + $total_price_dmp_10m3 + $total_price_sc + $total_price_gns + $total_price_wl_sc);
			?>
			
			<tr class="table-judul">
				<th width="5%" align="center"  rowspan="2" class="table-border-judul1"></th>
				<th width="37%" align="center" rowspan="2" class="table-border-judul2">&nbsp; <br />URAIAN</th>
				<th width="8%" align="center" rowspan="2" class="table-border-judul2">&nbsp; <br />SATUAN</th>
				<th width="50%" align="center" class="table-border-judul3" colspan="3"><div style="text-transform: uppercase;"><?php echo str_replace($search, $replace, $subject);?></div></th>
	        </tr>
			<tr class="table-judul">
				<th align="center" class="table-border-judul8">VOLUME</th>
				<th align="center" class="table-border-judul8">HARGA SATUAN</th>
				<th align="center" class="table-border-judul9">TOTAL</th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">BIAYA PERALATAN</th>
				<th align="left" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="left" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="left" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#e69500;">A. GRUP BP</th>
				<th align="left" class="table-border-judul4" style="background-color:#e69500;"></th>
				<th align="left" class="table-border-judul4" style="background-color:#e69500;"></th>
				<th align="left" class="table-border-judul5" style="background-color:#e69500;"></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;1. BATCHING PLANT</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($pembelian_bp as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">BATCHING PLANT</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_bp,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_pembelian_bp,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;2. TRUCK MIXER</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($pembelian_tm as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">TRUCK MIXER</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_tm,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_pembelian_tm,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;3. WHEEL LOADER</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($pembelian_wl as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">WHEEL LOADER</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_wl,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_pembelian_wl,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;4. TRANSFER SEMEN</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($pembelian_tf as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">TRANSFER SEMEN</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_tf,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_pembelian_tf,2,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;5. BBM SOLAR</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($total_nilai_bbm,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;6. INSENTIF OPERATOR</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($total_insentif_all,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;"></th>
				<th align="left" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="left" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="left" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#e69500;">B. GRUP SC</th>
				<th align="left" class="table-border-judul4" style="background-color:#e69500;"></th>
				<th align="left" class="table-border-judul4" style="background-color:#e69500;"></th>
				<th align="left" class="table-border-judul5" style="background-color:#e69500;"></th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;1. EXCAVATOR</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_exc as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">EXCAVATOR</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_exc,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_exc,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;2. DUMP TRUCK 4M3</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_dmp_4m3 as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">DUMP TRUCK 4M3</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_dmp_4m3,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_dmp_4m3,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;3. DUMP TRUCK 10M3</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_dmp_10m3 as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">DUMP TRUCK 10M3</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_dmp_10m3,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_dmp_10m3,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;4. STONE CRUSHER</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_sc as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">STONE CRUSHER</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_sc,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_sc,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;5. GENSET</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_gns as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">GENSET</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_gns,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_gns,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-judul4" colspan="3" style="background-color:#ffffff;">&nbsp;&nbsp;6. WHEEL LOADER</th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"></th>
	        </tr>
			<?php foreach ($produk_wl_sc as $x): ?>
			<tr class="table-baris1">
				<th align="right" class="table-border-judul4" colspan="2" style="background-color:#ffffff;"><?= $x['nama'] ?></th>
				<th align="center" class="table-border-judul7" style="background-color:#ffffff;"> /<?= $x['measure'] ?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="right" class="table-border-judul4" style="background-color:#ffffff;"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-judul5" style="background-color:#ffffff;"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="right" class="table-border-judul4" colspan="3" style="background-color:#FFFF00;">WHEEL LOADER</th>
				<th align="right" class="table-border-judul4" style="background-color:#FFFF00;"><?php echo number_format($total_vol_pembelian_produk_wl_sc,2,',','.');?></th>
				<th align="center" class="table-border-judul4" style="background-color:#FFFF00;"></th>
				<th align="right" class="table-border-judul5" style="background-color:#FFFF00;"><?php echo number_format($total_price_wl_sc,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-judul10" colspan="3" style="background-color:#e69500;">TOTAL (A+B)</th>
				<th align="right" class="table-border-judul10" style="background-color:#e69500;"><?php echo number_format($total_volume_all,2,',','.');?></th>
				<th align="left" class="table-border-judul10" style="background-color:#e69500;"></th>
				<th align="right" class="table-border-judul11" style="background-color:#e69500;"><?php echo number_format($total_nilai_all,0,',','.');?></th>
	        </tr>
	    </table>
		<br /><br /><br />
		<table width="98%">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Disetujui Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>	
						</tr>
						<tr class="">
							<?php
								$create = $this->db->select('id, unit_head, logistik, admin')
								->from('akumulasi')
								->where("(date_akumulasi between '$start_date' and '$end_date')")
								->order_by('id','desc')->limit(1)
								->get()->row_array();

                                $this->db->select('g.admin_group_name, a.admin_ttd');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$create['unit_head']);
                                $unit_head = $this->db->get('tbl_admin a')->row_array();

								$this->db->select('g.admin_group_name, a.admin_ttd');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$create['logistik']);
                                $logistik = $this->db->get('tbl_admin a')->row_array();

								$this->db->select('g.admin_group_name, a.admin_ttd');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$create['admin']);
                                $admin = $this->db->get('tbl_admin a')->row_array();
                            ?>
							<td align="center" height="55px">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $logistik['admin_ttd']?>" width="70px">
								<img src="<?= $admin['admin_ttd']?>" width="20px">
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['unit_head']),'admin_name');?></u><br />
								<?= $unit_head['admin_group_name']?></b>
							</td>
							<td align="center">
							<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['unit_head']),'admin_name');?></u><br />
								M. Teknik</b>
							</td>
							<td align="center" >
								<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['logistik']),'admin_name');?></u><br />
								<?= $logistik['admin_group_name']?></b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>