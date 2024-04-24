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

		table.table-border-pojok-kiri, th.table-border-pojok-kiri, td.table-border-pojok-kiri {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
			border-left: 1px solid black;
		}

		table.table-border-pojok-tengah, th.table-border-pojok-tengah, td.table-border-pojok-tengah {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
		}

		table.table-border-pojok-kanan, th.table-border-pojok-kanan, td.table-border-pojok-kanan {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial, th.table-border-spesial, td.table-border-spesial {
			border-left: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial-kiri, th.table-border-spesial-kiri, td.table-border-spesial-kiri {
			border-left: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-tengah, th.table-border-spesial-tengah, td.table-border-spesial-tengah {
			border-left: 1px solid #cccccc;
			border-right: 1px solid #cccccc;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-kanan, th.table-border-spesial-kanan, td.table-border-spesial-kanan {
			border-left: 1px solid #cccccc;
			border-right: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
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
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">BIAYA ALAT</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
		<br /><br /><br />
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
				<th width="5%" align="center" class="table-border-pojok-kiri">NO.</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">URAIAN</th>
				<th width="32%" align="center" class="table-border-pojok-tengah">REKANAN</th>
				<th width="10%" align="center" class="table-border-pojok-tengah">VOLUME</th>
				<th width="8%" align="center" class="table-border-pojok-tengah">SATUAN</th>
				<th width="10%" align="center" class="table-border-pojok-tengah">HARGA SATUAN</th>
				<th width="15%" align="center" class="table-border-pojok-kanan">TOTAL</th>
	        </tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-spesial" colspan="7">A. GROUP BP</th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">1.</th>	
				<th align="left" class="table-border-pojok-tengah">Batching Plant</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($pembelian_bp as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Batching Plant</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_pembelian_bp,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_pembelian_bp,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">2.</th>	
				<th align="left" class="table-border-pojok-tengah">Truck Mixer</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($pembelian_tm as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Truck Mixer</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_pembelian_tm,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_pembelian_tm,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">3.</th>	
				<th align="left" class="table-border-pojok-tengah">Wheel Loader</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($pembelian_wl as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Wheel Loader</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_pembelian_wl,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_pembelian_wl,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">4.</th>	
				<th align="left" class="table-border-pojok-tengah">Transfer Semen</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($pembelian_tf as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Transfer Semen</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_pembelian_tf,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_pembelian_tf,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">5.</th>
				<th align="left" class="table-border-pojok-tengah">BBM Solar</th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_nilai_bbm,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">6.</th>
				<th align="left" class="table-border-pojok-tengah">Insentif Operator</th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_insentif_all,0,',','.');?></th>
			</tr>
			<tr class="table-baris1-bold">
				<th align="left" class="table-border-spesial" colspan="7">B. GROUP SC</th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">1.</th>	
				<th align="left" class="table-border-pojok-tengah">Excavator</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_exc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Excavator</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_exc,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_exc,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">2.</th>	
				<th align="left" class="table-border-pojok-tengah">Dump Truck 4m3</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_dmp_4m3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Dump Truck 4m3</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_dmp_4m3,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_dmp_4m3,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">3.</th>	
				<th align="left" class="table-border-pojok-tengah">Dump Truck 10m3</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_dmp_10m3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Dump Truck 10m3</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_dmp_10m3,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_dmp_10m3,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">4.</th>	
				<th align="left" class="table-border-pojok-tengah">Stone Crusher</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_sc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Stone Crusher</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_sc,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_sc,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">5.</th>	
				<th align="left" class="table-border-pojok-tengah">Genset</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_gns as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Genset</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_gns,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_gns,0,',','.');?></th>
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">6.</th>	
				<th align="left" class="table-border-pojok-tengah">Wheel Loader</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_wl_sc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="left" class="table-border-pojok-tengah"><?= $x['nama'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['harga_satuan'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['price'],0,',','.');?></th>
			</tr>
			<?php endforeach; ?>
			<tr class="table-baris1-bold">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah">Total Wheel Loader</th>
				<th align="left" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_vol_wl_sc,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_price_wl_sc,0,',','.');?></th>
			</tr>
			<tr class="table-total2">	
				<th align="right" colspan="6" class="table-border-spesial-kiri">TOTAL BIAYA PEMAKAIAN ALAT</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($total_nilai_all,0,',','.');?></th>
	        </tr>
	    </table>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
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
								$create = $this->db->select('*')
								->from('akumulasi')
								->where("(date_akumulasi = '$end_date')")
								->order_by('id','desc')->limit(1)
								->get()->row_array();

                                $this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
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
								<!--<img src="<?= $admin['admin_ttd']?>" width="20px">-->
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u><?= $unit_head['admin_name']?></u><br />
								Ka. Plant</b>
							</td>
							<td align="center">
								<b><u>Agustinus P</u><br />
								M. Teknik</b>
							</td>
							<td align="center" >
								<b><u>Agustinus P</u><br />
								Pj. Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>