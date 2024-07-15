<!DOCTYPE html>
<html>
	<head>
	  <title>EVALUASI BIAYA PRODUKSI</title>
	  
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
			font-size: 7px;
		}

	  	table tr.table-active{
            background-color: #e69500;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-active2{
			font-size: 7px;
		}
			
		table tr.table-active3{
			font-size: 7px;
		}
			
		table tr.table-active4{
			background-color: #D0D0D0;
			font-weight: bold;
			font-size: 7px;
		}
		tr.border-bottom td {
        	border-bottom: 1pt solid #ff000d;
      }
	  </style>

	</head>
	<body>
	<br />
		<br />
		<table width="98%" cellpadding="3">
			<tr>
				<td align="center"  width="100%">
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN EVALUASI BIAYA PRODUKSI<br/>
					<div style="text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2023-08-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>

		<?php
		$komposisi = $this->db->select('pp.date_production, (pp.display_volume) * pk.presentase_a as volume_a, (pp.display_volume) * pk.presentase_b as volume_b, (pp.display_volume) * pk.presentase_c as volume_c, (pp.display_volume) * pk.presentase_d as volume_d, (pp.display_volume) * pk.presentase_e as volume_e, (pp.display_volume * pk.presentase_a) * pk.price_a as nilai_a, (pp.display_volume * pk.presentase_b) * pk.price_b as nilai_b, (pp.display_volume * pk.presentase_c) * pk.price_c as nilai_c, (pp.display_volume * pk.presentase_d) * pk.price_d as nilai_d, (pp.display_volume * pk.presentase_e) * pk.price_e as nilai_e')
		->from('pmm_productions pp')
		->join('pmm_agregat pk', 'pp.komposisi_id = pk.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
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
		
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_semen_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_semen_lalu = $stock_opname_semen_ago['volume'];
		$stok_nilai_semen_lalu = $stock_opname_semen_ago['nilai'];
		$stok_harsat_semen_lalu = (round($stok_volume_semen_lalu,2)!=0)?$stok_nilai_semen_lalu / round($stok_volume_semen_lalu,2) * 1:0;

		$pembelian_semen = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 1")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_semen = $pembelian_semen['volume'];
		$pembelian_nilai_semen = $pembelian_semen['nilai'];
		$pembelian_harga_semen = (round($pembelian_volume_semen,2)!=0)?$pembelian_nilai_semen / round($pembelian_volume_semen,2) * 1:0;

		$total_stok_volume_semen = $stok_volume_semen_lalu + $pembelian_volume_semen;
		$total_stok_nilai_semen = $stok_nilai_semen_lalu + $pembelian_nilai_semen;

		$stock_opname_semen_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_semen_now = $stock_opname_semen_now['volume'];
		$nilai_stock_opname_semen_now = $stock_opname_semen_now['nilai'];

		$vol_pemakaian_semen_now = ($stok_volume_semen_lalu + $pembelian_volume_semen) - $volume_stock_opname_semen_now;
		$nilai_pemakaian_semen_now = $stock_opname_semen_now['nilai'];

		$pemakaian_volume_semen = $vol_pemakaian_semen_now;
		$pemakaian_nilai_semen = (($total_stok_nilai_semen - $nilai_stock_opname_semen_now) * $stock_opname_semen_now['reset']) + ($stock_opname_semen_now['pemakaian_custom'] * $stock_opname_semen_now['reset_pemakaian']);
		$pemakaian_harsat_semen = $pemakaian_nilai_semen / $pemakaian_volume_semen;
		
		$stock_opname_pasir_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 2")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_pasir_lalu = $stock_opname_pasir_ago['volume'];
		$stok_nilai_pasir_lalu = $stock_opname_pasir_ago['nilai'];
		$stok_harsat_pasir_lalu = (round($stok_volume_pasir_lalu,2)!=0)?$stok_nilai_pasir_lalu / round($stok_volume_pasir_lalu,2) * 1:0;

		$pembelian_pasir = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 2")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_pasir = $pembelian_pasir['volume'];
		$pembelian_nilai_pasir = $pembelian_pasir['nilai'];
		$pembelian_harga_pasir = (round($pembelian_volume_pasir,2)!=0)?$pembelian_nilai_pasir / round($pembelian_volume_pasir,2) * 1:0;

		$total_stok_volume_pasir = $stok_volume_pasir_lalu + $pembelian_volume_pasir;
		$total_stok_nilai_pasir = $stok_nilai_pasir_lalu + $pembelian_nilai_pasir;

		$stock_opname_pasir_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 2")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_pasir_now = $stock_opname_pasir_now['volume'];
		$nilai_stock_opname_pasir_now = $stock_opname_pasir_now['nilai'];

		$vol_pemakaian_pasir_now = ($stok_volume_pasir_lalu + $pembelian_volume_pasir) - $volume_stock_opname_pasir_now;
		$nilai_pemakaian_pasir_now = $stock_opname_pasir_now['nilai'];

		$pemakaian_volume_pasir = $vol_pemakaian_pasir_now;
		$pemakaian_nilai_pasir = (($total_stok_nilai_pasir - $nilai_stock_opname_pasir_now) * $stock_opname_pasir_now['reset']) + ($stock_opname_pasir_now['pemakaian_custom'] * $stock_opname_pasir_now['reset_pemakaian']);
		$pemakaian_harsat_pasir = $pemakaian_nilai_pasir / $pemakaian_volume_pasir;

		$stock_opname_1020_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_1020_lalu = $stock_opname_1020_ago['volume'];
		$stok_nilai_1020_lalu = $stock_opname_1020_ago['nilai'];
		$stok_harsat_1020_lalu = (round($stok_volume_1020_lalu,2)!=0)?$stok_nilai_1020_lalu / round($stok_volume_1020_lalu,2) * 1:0;

		$pembelian_1020 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 3")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_1020 = $pembelian_1020['volume'];
		$pembelian_nilai_1020 = $pembelian_1020['nilai'];
		$pembelian_harga_1020 = (round($pembelian_volume_1020,2)!=0)?$pembelian_nilai_1020 / round($pembelian_volume_1020,2) * 1:0;

		$total_stok_volume_1020 = $stok_volume_1020_lalu + $pembelian_volume_1020;
		$total_stok_nilai_1020 = $stok_nilai_1020_lalu + $pembelian_nilai_1020;

		$stock_opname_1020_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_1020_now = $stock_opname_1020_now['volume'];
		$nilai_stock_opname_1020_now = $stock_opname_1020_now['nilai'];

		$vol_pemakaian_1020_now = ($stok_volume_1020_lalu + $pembelian_volume_1020) - $volume_stock_opname_1020_now;
		$nilai_pemakaian_1020_now = $stock_opname_1020_now['nilai'];

		$pemakaian_volume_1020 = $vol_pemakaian_1020_now;
		$pemakaian_nilai_1020 = (($total_stok_nilai_1020 - $nilai_stock_opname_1020_now) * $stock_opname_1020_now['reset']) + ($stock_opname_1020_now['pemakaian_custom'] * $stock_opname_1020_now['reset_pemakaian']);
		$pemakaian_harsat_1020 = $pemakaian_nilai_1020 / $pemakaian_volume_1020;

		$stock_opname_2030_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_2030_lalu = $stock_opname_2030_ago['volume'];
		$stok_nilai_2030_lalu = $stock_opname_2030_ago['nilai'];
		$stok_harsat_2030_lalu = (round($stok_volume_2030_lalu,2)!=0)?$stok_nilai_2030_lalu / round($stok_volume_2030_lalu,2) * 1:0;

		$pembelian_2030 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 4")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_2030 = $pembelian_2030['volume'];
		$pembelian_nilai_2030 = $pembelian_2030['nilai'];
		$pembelian_harga_2030 = (round($pembelian_volume_2030,2)!=0)?$pembelian_nilai_2030 / round($pembelian_volume_2030,2) * 1:0;

		$total_stok_volume_2030 = $stok_volume_2030_lalu + $pembelian_volume_2030;
		$total_stok_nilai_2030 = $stok_nilai_2030_lalu + $pembelian_nilai_2030;

		$stock_opname_2030_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_2030_now = $stock_opname_2030_now['volume'];
		$nilai_stock_opname_2030_now = $stock_opname_2030_now['nilai'];

		$vol_pemakaian_2030_now = ($stok_volume_2030_lalu + $pembelian_volume_2030) - $volume_stock_opname_2030_now;
		$nilai_pemakaian_2030_now = $stock_opname_2030_now['nilai'];

		$pemakaian_volume_2030 = $vol_pemakaian_2030_now;
		$pemakaian_nilai_2030 = (($total_stok_nilai_2030 - $nilai_stock_opname_2030_now) * $stock_opname_2030_now['reset']) + ($stock_opname_2030_now['pemakaian_custom'] * $stock_opname_2030_now['reset_pemakaian']);
		$pemakaian_harsat_2030 = $pemakaian_nilai_2030 / $pemakaian_volume_2030;

		$stock_opname_additive_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 19")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_additive_lalu = $stock_opname_additive_ago['volume'];
		$stok_nilai_additive_lalu = $stock_opname_additive_ago['nilai'];
		$stok_harsat_additive_lalu = (round($stok_volume_additive_lalu,2)!=0)?$stok_nilai_additive_lalu / round($stok_volume_additive_lalu,2) * 1:0;

		$pembelian_additive = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 6")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_additive = $pembelian_additive['volume'];
		$pembelian_nilai_additive = $pembelian_additive['nilai'];
		$pembelian_harga_additive = (round($pembelian_volume_additive,2)!=0)?$pembelian_nilai_additive / round($pembelian_volume_additive,2) * 1:0;

		$total_stok_volume_additive = $stok_volume_additive_lalu + $pembelian_volume_additive;
		$total_stok_nilai_additive = $stok_nilai_additive_lalu + $pembelian_nilai_additive;

		$stock_opname_additive_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 19")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_additive_now = $stock_opname_additive_now['volume'];
		$nilai_stock_opname_additive_now = $stock_opname_additive_now['nilai'];

		$vol_pemakaian_additive_now = ($stok_volume_additive_lalu + $pembelian_volume_additive) - $volume_stock_opname_additive_now;
		$nilai_pemakaian_additive_now = $stock_opname_additive_now['nilai'];

		$pemakaian_volume_additive = $vol_pemakaian_additive_now;
		$pemakaian_nilai_additive = (($total_stok_nilai_additive - $nilai_stock_opname_additive_now) * $stock_opname_additive_now['reset']) + ($stock_opname_additive_now['pemakaian_custom'] * $stock_opname_additive_now['reset_pemakaian']);
		$pemakaian_harsat_additive = $pemakaian_nilai_additive / $pemakaian_volume_additive;

		$total_volume_realisasi = $pemakaian_volume_semen + $pemakaian_volume_pasir + $pemakaian_volume_1020 + $pemakaian_volume_2030 +  $pemakaian_volume_additive;
		$total_nilai_realisasi = $pemakaian_nilai_semen + $pemakaian_nilai_pasir + $pemakaian_nilai_1020 + $pemakaian_nilai_2030 + $pemakaian_nilai_additive;
		
		$evaluasi_volume_a = round($volume_a - $pemakaian_volume_semen,2);
		$evaluasi_volume_b = round($volume_b - $pemakaian_volume_pasir,2);
		$evaluasi_volume_c = round($volume_c - $pemakaian_volume_1020,2);
		$evaluasi_volume_d = round($volume_d - $pemakaian_volume_2030,2);
		$evaluasi_volume_e = round($volume_e - $pemakaian_volume_additive,2);

		$evaluasi_nilai_a = round($nilai_a - $pemakaian_nilai_semen,0);
		$evaluasi_nilai_b = round($nilai_b - $pemakaian_nilai_pasir,0);
		$evaluasi_nilai_c = round($nilai_c - $pemakaian_nilai_1020,0);
		$evaluasi_nilai_d = round($nilai_d - $pemakaian_nilai_2030,0);
		$evaluasi_nilai_e = round($nilai_e - $pemakaian_nilai_additive,0);

		$total_volume_evaluasi = round($total_volume_komposisi - $total_volume_realisasi,2);
		$total_nilai_evaluasi = round($evaluasi_nilai_a + $evaluasi_nilai_b + $evaluasi_nilai_c + $evaluasi_nilai_d + $evaluasi_nilai_e,0);
		?>

		<?php
		$pembelian_batching_plant = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '1'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_batching_plant = 0;
		foreach ($pembelian_batching_plant as $x){
			$total_nilai_batching_plant += $x['price'];
		}

		$pemeliharaan_batching_plant_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 138")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pemeliharaan_batching_plant_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 138")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$total_nilai_pemeliharaan_batching_plant = $pemeliharaan_batching_plant_biaya['total'] + $pemeliharaan_batching_plant_jurnal['total'];

		$penyusutan_batching_plant_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 137")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$penyusutan_batching_plant_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 137")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$total_nilai_penyusutan_batching_plant = $penyusutan_batching_plant_biaya['total'] + $penyusutan_batching_plant_jurnal['total'];
		$total_nilai_batching_plant = $total_nilai_batching_plant + $total_nilai_pemeliharaan_batching_plant + $total_nilai_penyusutan_batching_plant;
		
		$pembelian_truck_mixer = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '2'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_truck_mixer = 0;
		foreach ($pembelian_truck_mixer as $x){
			$total_nilai_truck_mixer += $x['price'];
		}

		$pembelian_wheel_loader = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '3'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_wheel_loader = 0;
		foreach ($pembelian_wheel_loader as $x){
			$total_nilai_wheel_loader += $x['price'];
		}

		$pemeliharaan_wheel_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 140")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pemeliharaan_wheel_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 140")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$total_nilai_pemeliharaan_wheel_loader = $pemeliharaan_wheel_loader_biaya['total'] + $pemeliharaan_wheel_loader_jurnal['total'];

		$penyusutan_wheel_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 139")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$penyusutan_wheel_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 136")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$total_nilai_penyusutan_wheel_loader = $penyusutan_wheel_loader_biaya['total'] + $penyusutan_wheel_loader_jurnal['total'];
		$total_nilai_wheel_loader = $total_nilai_wheel_loader + $total_nilai_pemeliharaan_wheel_loader + $total_nilai_penyusutan_wheel_loader;

		$pembelian_truck_mixer = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '2'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_truck_mixer = 0;
		$total_vol_truck_mixer = 0;
		foreach ($pembelian_truck_mixer as $x){
			$total_nilai_truck_mixer += $x['price'];
			$total_vol_truck_mixer += $x['volume'];
		}

		$pembelian_transfer_semen = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '4'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_transfer_semen = 0;
		foreach ($pembelian_transfer_semen as $x){
			$total_nilai_transfer_semen += $x['price'];
		}

		$pembelian_excavator = $this->db->select('
		pn.nama, po.no_po, po.subject, prm.measure, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as price')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->join('penerima pn', 'po.supplier_id = pn.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_alat = '5'")
		->where("po.status in ('PUBLISH','CLOSED')")
		->group_by('prm.harga_satuan')
		->order_by('pn.nama','asc')
		->get()->result_array();

		$total_nilai_excavator = 0;
		foreach ($pembelian_excavator as $x){
			$total_nilai_excavator += $x['price'];
		}

		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_solar_ago = $this->db->select('cat.volume as volume, cat.total as nilai')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$tanggal_opening_balance')")
		->where("cat.material_id = 5")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_solar_lalu = $stock_opname_solar_ago['volume'];
		$stok_nilai_solar_lalu = $stock_opname_solar_ago['nilai'];
		$stok_harsat_solar_lalu = (round($stok_volume_solar_lalu,2)!=0)?$stok_nilai_solar_lalu / round($stok_volume_solar_lalu,2) * 1:0;

		$pembelian_solar = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("p.kategori_bahan = 5")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$pembelian_volume_solar = $pembelian_solar['volume'];
		$pembelian_nilai_solar = $pembelian_solar['nilai'];
		$pembelian_harga_solar = (round($pembelian_volume_solar,2)!=0)?$pembelian_nilai_solar / round($pembelian_volume_solar,2) * 1:0;

		$total_stok_volume_solar = $stok_volume_solar_lalu + $pembelian_volume_solar;
		$total_stok_nilai_solar = $stok_nilai_solar_lalu + $pembelian_nilai_solar;

		$stock_opname_solar_now = $this->db->select('cat.volume as volume, cat.total as nilai, cat.pemakaian_custom, cat.reset, cat.reset_pemakaian')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date <= '$date2')")
		->where("cat.material_id = 5")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_now = $stock_opname_solar_now['volume'];
		$nilai_stock_opname_solar_now = $stock_opname_solar_now['nilai'];

		$vol_pemakaian_solar_now = ($stok_volume_solar_lalu + $pembelian_volume_solar) - $volume_stock_opname_solar_now;
		$nilai_pemakaian_solar_now = $stock_opname_solar_now['nilai'];

		$pemakaian_volume_solar = $vol_pemakaian_solar_now;
		$pemakaian_nilai_solar = (($total_stok_nilai_solar - $nilai_stock_opname_solar_now) * $stock_opname_solar_now['reset']) + ($stock_opname_solar_now['pemakaian_custom'] * $stock_opname_solar_now['reset_pemakaian']);
		$pemakaian_harsat_solar = $pemakaian_nilai_solar / $pemakaian_volume_solar;	

		$total_vol_excavator = $pembelian_excavator['volume'];
		$total_vol_transfer_semen = $pembelian_transfer_semen['volume'];

		$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
		->from('pmm_productions pp')
		->join('penerima p', 'pp.client_id = p.id','left')
		->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.status = 'PUBLISH'")
		->where("ppo.status in ('OPEN','CLOSED')")
		->group_by("pp.client_id")
		->get()->result_array();
		
		$total_volume = 0;
		foreach ($penjualan as $x){
			$total_volume += $x['volume'];
		}

		$rap_alat = $this->db->select('rap.*')
		->from('rap_alat rap')
		->where("rap.tanggal_rap_alat <= '$date2'")
		->where('rap.status','PUBLISH')
		->get()->result_array();

		foreach ($rap_alat as $x){
			$vol_rap_batching_plant = $x['vol_batching_plant'];
			$vol_rap_pemeliharaan_batching_plant = $x['vol_pemeliharaan_batching_plant'];
			$vol_rap_wheel_loader = $x['vol_wheel_loader'];
			$vol_rap_pemeliharaan_wheel_loader = $x['vol_pemeliharaan_wheel_loader'];
			$vol_rap_truck_mixer = $x['vol_truck_mixer'];
			$vol_rap_excavator = $x['vol_excavator'];
			$vol_rap_transfer_semen = $x['vol_transfer_semen'];
			$vol_rap_bbm_solar = $x['vol_bbm_solar'];
			$harsat_batching_plant = $x['batching_plant'];
			$harsat_pemeliharaan_batching_plant = $x['pemeliharaan_batching_plant'];
			$harsat_penyusutan_batching_plant = $x['batching_plant'] - $x['pemeliharaan_batching_plant'];
			$harsat_pemeliharaan_wheel_loader = $x['pemeliharaan_wheel_loader'];
			$harsat_penyusutan_wheel_loader = $x['wheel_loader'] - $x['pemeliharaan_wheel_loader'];
			$harsat_wheel_loader = $x['wheel_loader'];
			$harsat_truck_mixer = $x['truck_mixer'];
			$harsat_excavator = $x['excavator'];
			$harsat_transfer_semen = $x['transfer_semen'];
			$harsat_bbm_solar = $x['vol_bbm_solar'] * $x['harsat_bbm_solar'];
			
		}

		$vol_batching_plant = $total_volume;
		$vol_pemeliharaan_batching_plant = $total_volume;
		$vol_penyusutan_batching_plant = $total_volume;
		$vol_wheel_loader = $total_volume;
		$vol_pemeliharaan_wheel_loader = $total_volume;
		$vol_penyusutan_wheel_loader = $total_volume;
		$vol_truck_mixer = $total_volume;
		$vol_excavator = $total_volume;
		$vol_transfer_semen = $total_volume;
		$vol_bbm_solar = $total_volume;

		$batching_plant = $harsat_batching_plant * $total_volume;
		$pemeliharaan_batching_plant = $harsat_pemeliharaan_batching_plant * $total_volume;
		$penyusutan_batching_plant = $batching_plant - $pemeliharaan_batching_plant;
		$wheel_loader = ($harsat_wheel_loader * $vol_wheel_loader) + $total_nilai_pemeliharaan_wheel_loader;
		$pemeliharaan_wheel_loader = $harsat_pemeliharaan_wheel_loader * $vol_pemeliharaan_wheel_loader;
		$penyusutan_wheel_loader = $wheel_loader - $pemeliharaan_wheel_loader;
		$truck_mixer = $harsat_truck_mixer * $total_volume;
		$excavator = $harsat_excavator * $total_volume;
		$transfer_semen = $harsat_transfer_semen * $total_volume;
		$bbm_solar = $harsat_bbm_solar * $vol_bbm_solar;

		$harsat_batching_plant = ($vol_batching_plant!=0)?$batching_plant / $vol_batching_plant * 1:0;
		$harsat_truck_mixer = ($vol_truck_mixer!=0)?$truck_mixer / $vol_truck_mixer * 1:0;
		$harsat_wheel_loader = ($wheel_loader!=0)?$wheel_loader / $vol_wheel_loader * 1:0;
		$harsat_excavator = ($excavator!=0)?$excavator / $vol_excavator * 1:0;
		$harsat_transfer_semen = ($transfer_semen!=0)?$transfer_semen / $vol_transfer_semen * 1:0;
		$harsat_bbm_solar = ($vol_bbm_solar!=0)?$bbm_solar / $vol_bbm_solar * 1:0;
		$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;

		$pemakaian_vol_batching_plant = 0;
		$pemakaian_vol_pemeliharaan_batching_plant = 0;
		$pemakaian_vol_penyusutan_batching_plant = $total_volume;
		$pemakaian_vol_truck_mixer = $total_vol_truck_mixer;
		$pemakaian_vol_wheel_loader = 0;
		$pemakaian_vol_pemeliharaan_wheel_loader = 0;
		$pemakaian_vol_penyusutan_wheel_loader = $pemakaian_vol_pemeliharaan_wheel_loader;
		$pemakaian_vol_excavator = $total_vol_excavator;
		$pemakaian_vol_transfer_semen = $total_vol_transfer_semen;
		$pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;
		
		//SPESIAL//
		$total_pemakaian_pemeliharaan_batching_plant = $total_nilai_pemeliharaan_batching_plant;
		$total_pemakaian_penyusutan_batching_plant = $penyusutan_batching_plant;
		$total_pemakaian_batching_plant = $total_nilai_batching_plant + $total_pemakaian_penyusutan_batching_plant;
		$total_pemakaian_truck_mixer = $total_nilai_truck_mixer;
		$total_pemakaian_pemeliharaan_wheel_loader = $total_nilai_pemeliharaan_wheel_loader;
		$total_pemakaian_penyusutan_wheel_loader = $penyusutan_wheel_loader;
		$total_pemakaian_wheel_loader = $total_nilai_wheel_loader + $total_pemakaian_penyusutan_wheel_loader;
		$total_pemakaian_excavator = $total_nilai_excavator;
		$total_pemakaian_transfer_semen = $total_nilai_transfer_semen;
		$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
		//SPESIAL//

		$total_vol_evaluasi_batching_plant = ($pemakaian_vol_batching_plant!=0)?$vol_batching_plant - $pemakaian_vol_batching_plant * 1:0;
		$total_nilai_evaluasi_batching_plant = ($total_pemakaian_batching_plant!=0)?$batching_plant - $total_pemakaian_batching_plant * 1:0;
		$total_vol_evaluasi_pemeliharaan_batching_plant = ($pemakaian_vol_pemeliharaan_batching_plant!=0)?$vol_pemeliharaan_batching_plant - $pemakaian_vol_pemeliharaan_batching_plant * 1:0;
		$total_nilai_evaluasi_pemeliharaan_batching_plant = ($total_pemakaian_pemeliharaan_batching_plant!=0)?$pemeliharaan_batching_plant - $total_pemakaian_pemeliharaan_batching_plant * 1:0;
		$total_vol_evaluasi_penyusutan_batching_plant = ($pemakaian_vol_penyusutan_batching_plant!=0)?$vol_penyusutan_batching_plant - $pemakaian_vol_penyusutan_batching_plant * 1:0;
		$total_nilai_evaluasi_penyusutan_batching_plant = ($total_pemakaian_penyusutan_batching_plant!=0)?$penyusutan_batching_plant - $total_pemakaian_penyusutan_batching_plant * 1:0;
		$total_vol_evaluasi_truck_mixer = ($pemakaian_vol_truck_mixer!=0)?$vol_truck_mixer - $pemakaian_vol_truck_mixer * 1:0;
		$total_nilai_evaluasi_truck_mixer = ($total_pemakaian_truck_mixer!=0)?$truck_mixer - $total_pemakaian_truck_mixer * 1:0;
		$total_vol_evaluasi_wheel_loader = ($pemakaian_vol_wheel_loader!=0)?$vol_wheel_loader - $pemakaian_vol_wheel_loader * 1:0;
		$total_nilai_evaluasi_wheel_loader = ($total_pemakaian_wheel_loader!=0)?$wheel_loader - $total_pemakaian_wheel_loader * 1:0;
		$total_vol_evaluasi_pemeliharaan_wheel_loader = ($pemakaian_vol_pemeliharaan_wheel_loader!=0)?$vol_pemeliharaan_wheel_loader - $pemakaian_vol_pemeliharaan_wheel_loader * 1:0;
		$total_nilai_evaluasi_pemeliharaan_wheel_loader = ($total_pemakaian_pemeliharaan_wheel_loader!=0)?$pemeliharaan_wheel_loader - $total_pemakaian_pemeliharaan_wheel_loader * 1:0;
		$total_vol_evaluasi_penyusutan_wheel_loader = ($pemakaian_vol_penyusutan_wheel_loader!=0)?$vol_penyusutan_wheel_loader - $pemakaian_vol_penyusutan_wheel_loader * 1:0;
		$total_nilai_evaluasi_penyusutan_wheel_loader = ($total_pemakaian_penyusutan_wheel_loader!=0)?$penyusutan_wheel_loader - $total_pemakaian_penyusutan_wheel_loader * 1:0;
		$total_vol_evaluasi_excavator = ($pemakaian_vol_excavator!=0)?$vol_excavator - $pemakaian_vol_excavator * 1:0;
		$total_nilai_evaluasi_excavator = ($total_pemakaian_excavator!=0)?$excavator - $total_pemakaian_excavator * 1:0;
		$total_vol_evaluasi_transfer_semen = ($pemakaian_vol_transfer_semen!=0)?$vol_transfer_semen - $pemakaian_vol_transfer_semen * 1:0;
		$total_nilai_evaluasi_transfer_semen = ($total_pemakaian_transfer_semen!=0)?$transfer_semen - $total_pemakaian_transfer_semen * 1:0;
		$total_vol_evaluasi_bbm_solar = ($pemakaian_volume_solar!=0)?($vol_rap_bbm_solar * $total_volume) - $pemakaian_volume_solar * 1:0;
		$total_nilai_evaluasi_bbm_solar = ($pemakaian_nilai_solar!=0)?$bbm_solar - $pemakaian_nilai_solar * 1:0;

		$total_vol_rap_alat = $total_volume;
		$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;
		$total_vol_realisasi_alat = $pemakaian_vol_batching_plant + $pemakaian_vol_truck_mixer + $pemakaian_vol_wheel_loader + $pemakaian_vol_excavator + $pemakaian_vol_transfer_semen + $pemakaian_volume_solar;
		$total_nilai_realisasi_alat = $total_pemakaian_batching_plant + $total_pemakaian_truck_mixer + $total_pemakaian_wheel_loader + $total_pemakaian_excavator + $total_nilai_transfer_semen + $pemakaian_nilai_solar;
		$total_vol_evaluasi_alat = $total_vol_evaluasi_batching_plant + $total_vol_evaluasi_truck_mixer + $total_vol_evaluasi_wheel_loader + $total_vol_evaluasi_excavator + $total_vol_evaluasi_transfer_semen + $total_vol_evaluasi_bbm_solar;
		$total_nilai_evaluasi_alat = $total_nilai_evaluasi_batching_plant + $total_nilai_evaluasi_truck_mixer + $total_nilai_evaluasi_wheel_loader + $total_nilai_evaluasi_excavator + $total_nilai_evaluasi_transfer_semen + $total_nilai_evaluasi_bbm_solar;
		?>

		<?php
		$rap_gaji_upah = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa in ('114','115')")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_konsumsi = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 116")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_biaya_sewa_mess = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 119")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_listrik_internet = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 118")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_pengujian_material_laboratorium = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 120")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_keamanan_kebersihan = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 97")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_pengobatan = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 70")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_donasi = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 76")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_bensin_tol_parkir = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 78")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_perjalanan_dinas_penjualan = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 62")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_pakaian_dinas = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 87")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_alat_tulis_kantor = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 96")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_perlengkapan_kantor = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 98")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_beban_kirim = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 93")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_beban_lain_lain = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 94")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_biaya_sewa_kendaraan = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 100")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_thr_bonus = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 117")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();

		$rap_biaya_admin_bank = $this->db->select('rap.*,sum(det.jumlah) as total')
		->from('rap_bua rap')
		->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
		->where("rap.status = 'PUBLISH'")
		->where("det.coa = 91")
		->where("rap.tanggal_rap_bua < '$date2'")
		->order_by('rap.tanggal_rap_bua','asc')->limit(1)
		->get()->row_array();
		
		//REALISASI
		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun in ('114','115')")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun in ('114','115')")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];

		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 116")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 116")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];

		$biaya_sewa_mess_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 119")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$biaya_sewa_mess_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 119")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$biaya_sewa_mess = $biaya_sewa_mess_biaya['total'] + $biaya_sewa_mess_jurnal['total'];

		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 118")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 118")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$pengujian_material_laboratorium_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 120")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_material_laboratorium_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 120")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$pengujian_material_laboratorium = $pengujian_material_laboratorium_biaya['total'] + $pengujian_material_laboratorium_jurnal['total'];

		$keamanan_kebersihan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 97")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$keamanan_kebersihan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 97")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$keamanan_kebersihan = $keamanan_kebersihan_biaya['total'] + $keamanan_kebersihan_jurnal['total'];

		$pengobatan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 70")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengobatan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 70")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$pengobatan = $pengobatan_biaya['total'] + $pengobatan_jurnal['total'];

		$donasi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 76")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$donasi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 76")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$donasi = $donasi_biaya['total'] + $donasi_jurnal['total'];

		$bensin_tol_parkir_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 78")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$bensin_tol_parkir_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 78")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$bensin_tol_parkir = $bensin_tol_parkir_biaya['total'] + $bensin_tol_parkir_jurnal['total'];

		$perjalanan_dinas_penjualan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 62")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perjalanan_dinas_penjualan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 62")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$perjalanan_dinas_penjualan = $perjalanan_dinas_penjualan_biaya['total'] + $perjalanan_dinas_penjualan_jurnal['total'];

		$pakaian_dinas_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 87")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pakaian_dinas_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 87")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$pakaian_dinas = $pakaian_dinas_biaya['total'] + $pakaian_dinas_jurnal['total'];

		$alat_tulis_kantor_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 96")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$alat_tulis_kantor_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 96")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$alat_tulis_kantor = $alat_tulis_kantor_biaya['total'] + $alat_tulis_kantor_jurnal['total'];

		$perlengkapan_kantor_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 98")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perlengkapan_kantor_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 98")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$perlengkapan_kantor = $perlengkapan_kantor_biaya['total'] + $perlengkapan_kantor_jurnal['total'];

		$beban_kirim_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 93")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$beban_kirim_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 93")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$beban_kirim = $beban_kirim_biaya['total'] + $beban_kirim_jurnal['total'];

		$beban_lain_lain_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 94")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$beban_lain_lain_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 94")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$beban_lain_lain = $beban_lain_lain_biaya['total'] + $beban_lain_lain_jurnal['total'];

		$biaya_sewa_kendaraan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 100")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$biaya_sewa_kendaraan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 100")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$biaya_sewa_kendaraan = $biaya_sewa_kendaraan_biaya['total'] + $biaya_sewa_kendaraan_jurnal['total'];

		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 117")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 117")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];

		$biaya_admin_bank_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 91")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$biaya_admin_bank_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 91")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$biaya_admin_bank = $biaya_admin_bank_biaya['total'] + $biaya_admin_bank_jurnal['total'];

		$biaya_persiapan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 131")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$biaya_persiapan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 131")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		$biaya_persiapan = $biaya_persiapan_biaya['total'] + $biaya_persiapan_jurnal['total'];

		$total_volume_rap_bua = $total_volume;
		$total_nilai_rap_bua = ($rap_gaji_upah['total'] + $rap_konsumsi['total'] + $rap_biaya_sewa_mess['total'] + $rap_listrik_internet['total'] + $rap_pengujian_material_laboratorium['total'] + $rap_keamanan_kebersihan['total'] + $rap_pengobatan['total'] + $rap_donasi['total'] + $rap_bensin_tol_parkir['total'] + $rap_perjalanan_dinas_penjualan['total'] + $rap_pakaian_dinas['total'] + $rap_alat_tulis_kantor['total'] + $rap_perlengkapan_kantor['total'] + $rap_beban_kirim['total'] + $rap_beban_lain_lain['total'] + $rap_biaya_sewa_kendaraan['total'] + $rap_thr_bonus['total'] + $rap_biaya_admin_bank['total']) / 24;
		$total_harsat_rap_bua = $total_nilai_rap_bua / $total_volume_rap_bua;
		
		$total_volume_realisasi_bua = $total_volume;
		$total_nilai_realisasi_bua = $gaji_upah + $konsumsi + $biaya_sewa_mess + $listrik_internet + $pengujian_material_laboratorium + $keamanan_kebersihan + $pengobatan + $donasi + $bensin_tol_parkir + $perjalanan_dinas_penjualan + $pakaian_dinas + $alat_tulis_kantor + $perlengkapan_kantor + $beban_kirim + $beban_lain_lain + $biaya_sewa_kendaraan + $thr_bonus + $biaya_admin_bank + $biaya_persiapan;
		$total_harsat_realisasi_bua = $total_nilai_realisasi_bua / $total_volume_realisasi_bua;

		$total_volume_evaluasi_bua = $total_volume_rap_bua - $total_volume_realisasi_bua;
		$total_nilai_evaluasi_bua = $total_nilai_rap_bua - $total_nilai_realisasi_bua;
		?>

		<table width="98%" border="0" cellpadding="3">
			<tr class="table-active">
	            <th width="5%" align="center" rowspan="2">&nbsp; <br />NO.</th>
				<th width="15%" align="center" rowspan="2">&nbsp; <br />URAIAN</th>
				<th width="30%" align="center" colspan="3">RAP</th>
				<th width="30%" align="center" colspan="3">REALISASI</th>
				<th width="20%" align="center" colspan="2">DEVIASI</th>
	        </tr>
			<tr class="table-active">
	            <th align="right">VOL.</th>
				<th align="right">HARSAT</th>
				<th align="right">NILAI</th>
				<th align="right">VOL.</th>
				<th align="right">HARSAT</th>
				<th align="right">NILAI</th>
				<th align="right">VOL.</th>
				<th align="right">NILAI</th>
	        </tr>
			<tr class="table-active3">
	            <th align="center"><b>1</b></th>
				<th align="left"><b>BAHAN</b></th>
				<th align="right"><?php echo number_format($total_volume_komposisi,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_komposisi / $total_volume_komposisi,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_komposisi,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_realisasi,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_realisasi / $total_volume_realisasi,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_realisasi,0,',','.');?></th>
				<?php
				$styleColor = $total_volume_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_volume_evaluasi < 0 ? "(".number_format(-$total_volume_evaluasi,2,',','.').")" : number_format($total_volume_evaluasi,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi < 0 ? "(".number_format(-$total_nilai_evaluasi,0,',','.').")" : number_format($total_nilai_evaluasi,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th align="center"><b>2</b></th>
				<th align="left"><b>ALAT</b></th>
				<th align="right"><?php echo number_format($total_vol_rap_alat,2,',','.');?></th>
				<?php
				$total_harsat_rap_alat = (round($total_vol_rap_alat,2)!=0)?($total_nilai_rap_alat / $total_vol_rap_alat) * 1:0;
				?>
				<th align="right"><?php echo number_format($total_harsat_rap_alat ,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_rap_alat,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_vol_realisasi_alat,2,',','.');?></th>
				<?php
				$total_harsat_realisasi_alat = (round($total_vol_realisasi_alat,2)!=0)?($total_nilai_realisasi_alat / $total_vol_realisasi_alat) * 1:0;
				?>
				<th align="right"><?php echo number_format($total_harsat_realisasi_alat,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_realisasi_alat,0,',','.');?></th>
				<?php
				$styleColor = $total_vol_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_vol_evaluasi_alat < 0 ? "(".number_format(-$total_vol_evaluasi_alat,0,',','.').")" : number_format($total_vol_evaluasi_alat,0,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_alat < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi_alat < 0 ? "(".number_format(-$total_nilai_evaluasi_alat,0,',','.').")" : number_format($total_nilai_evaluasi_alat,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th align="center">3</th>
				<th align="left"><b>BUA</b></th>
				<th align="right"><?php echo number_format($total_volume_rap_bua,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_harsat_rap_bua,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_rap_bua,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_volume_realisasi_bua,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_harsat_realisasi_bua,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_realisasi_bua,0,',','.');?></th>
				<?php
				$styleColor = $total_volume_evaluasi_bua < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo number_format($total_volume_evaluasi_bua,2,',','.');?></th>
				<?php
				$styleColor = $total_nilai_evaluasi_bua < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi_bua < 0 ? "(".number_format(-$total_nilai_evaluasi_bua,0,',','.').")" : number_format($total_nilai_evaluasi_bua,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
				<th align="right" colspan="2">TOTAL</th>
				<th align="right"></th>
				<th align="right"></th>
				<?php
				$total_nilai_rap = $total_nilai_komposisi + $total_nilai_rap_alat;
				?>
				<th align="right"><?php echo number_format($total_nilai_rap,0,',','.');?></th>
				<th align="right"></th>
				<th align="right"></th>
				<?php
				$total_nilai_realisasi = $total_nilai_realisasi + $total_nilai_realisasi_alat + $total_nilai_realisasi_bua;
				?>
				<th align="right"><?php echo number_format($total_nilai_realisasi,0,',','.');?></th>
				<th align="right"></th>
				<?php
				$total_nilai_evaluasi = $total_nilai_evaluasi + $total_nilai_evaluasi_alat + $total_nilai_evaluasi_bua;
				$styleColor = $total_nilai_evaluasi < 0 ? 'color:red' : 'color:black';
				?>
				<th align="right" style="<?php echo $styleColor ?>"><?php echo $total_nilai_evaluasi < 0 ? "(".number_format(-$total_nilai_evaluasi,0,',','.').")" : number_format($total_nilai_evaluasi,0,',','.');?></th>
			</tr>
		</table>
		
	</body>
</html>