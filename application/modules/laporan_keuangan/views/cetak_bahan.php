<!DOCTYPE html>
<html>
	<head>
	  <title>BIAYA (BAHAN)</title>
	  <?= include 'lib.php'; ?>
	  
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
			font-size: 8px;
		}
		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 8px;
		}
			
		table tr.table-baris1{
			background-color: #F0F0F0;
			font-size: 8px;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 8px;
		}
	  </style>

	</head>
	<body>
		<br />
		<br />
		<table width="98%" cellpadding="3">
			<tr>
				<td align="center"  width="100%">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN BIAYA BAHAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
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
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table width="98%" cellpadding="5" border="1">
		
		<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_semen_ago = $this->db->select('pp.vol_nilai_semen as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_semen = $this->db->select('pp.nilai_semen as nilai_semen')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_semen = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 1")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_semen_lalu = $stock_opname_semen_ago['volume'];
			$stok_nilai_semen_lalu = $harga_semen['nilai_semen'];
			$stok_harsat_semen_lalu = (round($stok_volume_semen_lalu,2)!=0)?$stok_nilai_semen_lalu / round($stok_volume_semen_lalu,2) * 1:0;
		
			$pembelian_volume_semen = $pembelian_semen['volume'];
			$pembelian_nilai_semen = $pembelian_semen['nilai'];
			$pembelian_harga_semen = (round($pembelian_volume_semen,2)!=0)?$pembelian_nilai_semen / round($pembelian_volume_semen,2) * 1:0;

			$total_stok_volume_semen = $stok_volume_semen_lalu + $pembelian_volume_semen;
			$total_stok_nilai_semen = $stok_nilai_semen_lalu + $pembelian_nilai_semen;

			$stock_opname_semen_now = $this->db->select('pp.vol_nilai_semen as volume, pp.nilai_semen as nilai, pp.vol_pemakaian_semen as volume_pemakaian, pp.vol_pemakaian_semen2 as volume_pemakaian2, pp.nilai_pemakaian_semen as nilai_pemakaian, pp.nilai_pemakaian_semen2 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_semen_now = $stock_opname_semen_now['volume'];
			$nilai_stock_opname_semen_now = $stock_opname_semen_now['nilai'];
			$vol_pemakaian_semen_now = $stock_opname_semen_now['volume_pemakaian'];
			$nilai_pemakaian_semen_now = $stock_opname_semen_now['nilai_pemakaian'];
			$vol_pemakaian_semen_now2 = $stock_opname_semen_now['volume_pemakaian2'];
			$nilai_pemakaian_semen_now2 = $stock_opname_semen_now['nilai_pemakaian2'];

			$pemakaian_volume_semen = $vol_pemakaian_semen_now;
			$pemakaian_harsat_semen = $nilai_pemakaian_semen_now / $vol_pemakaian_semen_now;
			$pemakaian_nilai_semen = $nilai_pemakaian_semen_now;

			$pemakaian_volume_semen2 = $vol_pemakaian_semen_now2;
			$pemakaian_harsat_semen2 = $nilai_pemakaian_semen_now2 / $vol_pemakaian_semen_now2;
			$pemakaian_nilai_semen2 = $nilai_pemakaian_semen_now2;

			$total_pemakaian_volume_semen = $pemakaian_volume_semen + $pemakaian_volume_semen2;
			$total_pemakaian_nilai_semen = $pemakaian_nilai_semen + $pemakaian_nilai_semen2;

			$stok_akhir_volume_semen = $volume_stock_opname_semen_now;
			$stok_akhir_nilai_semen = $nilai_stock_opname_semen_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_pasir_ago = $this->db->select('pp.vol_nilai_pasir as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_pasir = $this->db->select('pp.nilai_pasir as nilai_pasir')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_pasir = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 2")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_pasir_lalu = $stock_opname_pasir_ago['volume'];
			$stok_nilai_pasir_lalu = $harga_pasir['nilai_pasir'];
			$stok_harsat_pasir_lalu = (round($stok_volume_pasir_lalu,2)!=0)?$stok_nilai_pasir_lalu / round($stok_volume_pasir_lalu,2) * 1:0;
		
			$pembelian_volume_pasir = $pembelian_pasir['volume'];
			$pembelian_nilai_pasir = $pembelian_pasir['nilai'];
			$pembelian_harga_pasir = (round($pembelian_volume_pasir,2)!=0)?$pembelian_nilai_pasir / round($pembelian_volume_pasir,2) * 1:0;

			$total_stok_volume_pasir = $stok_volume_pasir_lalu + $pembelian_volume_pasir;
			$total_stok_nilai_pasir = $stok_nilai_pasir_lalu + $pembelian_nilai_pasir;

			$stock_opname_pasir_now = $this->db->select('pp.vol_nilai_pasir as volume, pp.nilai_pasir as nilai, pp.vol_pemakaian_pasir as volume_pemakaian, pp.vol_pemakaian_pasir2 as volume_pemakaian2, pp.nilai_pemakaian_pasir as nilai_pemakaian, pp.nilai_pemakaian_pasir2 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_pasir_now = $stock_opname_pasir_now['volume'];
			$nilai_stock_opname_pasir_now = $stock_opname_pasir_now['nilai'];
			$vol_pemakaian_pasir_now = $stock_opname_pasir_now['volume_pemakaian'];
			$nilai_pemakaian_pasir_now = $stock_opname_pasir_now['nilai_pemakaian'];
			$vol_pemakaian_pasir_now2 = $stock_opname_pasir_now['volume_pemakaian2'];
			$nilai_pemakaian_pasir_now2 = $stock_opname_pasir_now['nilai_pemakaian2'];

			$pemakaian_volume_pasir = $vol_pemakaian_pasir_now;
			$pemakaian_harsat_pasir = $nilai_pemakaian_pasir_now / $vol_pemakaian_pasir_now;
			$pemakaian_nilai_pasir = $nilai_pemakaian_pasir_now;

			$pemakaian_volume_pasir2 = $vol_pemakaian_pasir_now2;
			$pemakaian_harsat_pasir2 = $nilai_pemakaian_pasir_now2 / $vol_pemakaian_pasir_now2;
			$pemakaian_nilai_pasir2 = $nilai_pemakaian_pasir_now2;

			$total_pemakaian_volume_pasir = $pemakaian_volume_pasir + $pemakaian_volume_pasir2;
			$total_pemakaian_nilai_pasir = $pemakaian_nilai_pasir + $pemakaian_nilai_pasir2;

			$stok_akhir_volume_pasir = $volume_stock_opname_pasir_now;
			$stok_akhir_nilai_pasir = $nilai_stock_opname_pasir_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_1020_ago = $this->db->select('pp.vol_nilai_1020 as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_1020 = $this->db->select('pp.nilai_1020 as nilai_1020')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_1020 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 3")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_1020_lalu = $stock_opname_1020_ago['volume'];
			$stok_nilai_1020_lalu = $harga_1020['nilai_1020'];
			$stok_harsat_1020_lalu = (round($stok_volume_1020_lalu,2)!=0)?$stok_nilai_1020_lalu / round($stok_volume_1020_lalu,2) * 1:0;
		
			$pembelian_volume_1020 = $pembelian_1020['volume'];
			$pembelian_nilai_1020 = $pembelian_1020['nilai'];
			$pembelian_harga_1020 = (round($pembelian_volume_1020,2)!=0)?$pembelian_nilai_1020 / round($pembelian_volume_1020,2) * 1:0;

			$total_stok_volume_1020 = $stok_volume_1020_lalu + $pembelian_volume_1020;
			$total_stok_nilai_1020 = $stok_nilai_1020_lalu + $pembelian_nilai_1020;

			$stock_opname_1020_now = $this->db->select('pp.vol_nilai_1020 as volume, pp.nilai_1020 as nilai, pp.vol_pemakaian_1020 as volume_pemakaian, pp.vol_pemakaian_10202 as volume_pemakaian2, pp.nilai_pemakaian_1020 as nilai_pemakaian, pp.nilai_pemakaian_10202 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_1020_now = $stock_opname_1020_now['volume'];
			$nilai_stock_opname_1020_now = $stock_opname_1020_now['nilai'];
			$vol_pemakaian_1020_now = $stock_opname_1020_now['volume_pemakaian'];
			$nilai_pemakaian_1020_now = $stock_opname_1020_now['nilai_pemakaian'];
			$vol_pemakaian_1020_now2 = $stock_opname_1020_now['volume_pemakaian2'];
			$nilai_pemakaian_1020_now2 = $stock_opname_1020_now['nilai_pemakaian2'];

			$pemakaian_volume_1020 = $vol_pemakaian_1020_now;
			$pemakaian_harsat_1020 = $nilai_pemakaian_1020_now / $vol_pemakaian_1020_now;
			$pemakaian_nilai_1020 = $nilai_pemakaian_1020_now;

			$pemakaian_volume_10202 = $vol_pemakaian_1020_now2;
			$pemakaian_harsat_10202 = $nilai_pemakaian_1020_now2 / $vol_pemakaian_1020_now2;
			$pemakaian_nilai_10202 = $nilai_pemakaian_1020_now2;

			$total_pemakaian_volume_1020 = $pemakaian_volume_1020 + $pemakaian_volume_10202;
			$total_pemakaian_nilai_1020 = $pemakaian_nilai_1020 + $pemakaian_nilai_10202;

			$stok_akhir_volume_1020 = $volume_stock_opname_1020_now;
			$stok_akhir_nilai_1020 = $nilai_stock_opname_1020_now;
			?>

			<?php
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_2030_ago = $this->db->select('pp.vol_nilai_2030 as volume')
			->from('kunci_bahan_baku pp')
			->where("(pp.date = '$tanggal_opening_balance')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			
			$harga_2030 = $this->db->select('pp.nilai_2030 as nilai_2030')
			->from('kunci_bahan_baku pp')
			->where("(pp.date between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();

			$pembelian_2030 = $this->db->select('prm.display_measure as satuan, SUM(prm.display_volume) as volume, (prm.display_price / prm.display_volume) as harga, SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 4")
			->group_by('prm.material_id')
			->get()->row_array();

			$stok_volume_2030_lalu = $stock_opname_2030_ago['volume'];
			$stok_nilai_2030_lalu = $harga_2030['nilai_2030'];
			$stok_harsat_2030_lalu = (round($stok_volume_2030_lalu,2)!=0)?$stok_nilai_2030_lalu / round($stok_volume_2030_lalu,2) * 1:0;
		
			$pembelian_volume_2030 = $pembelian_2030['volume'];
			$pembelian_nilai_2030 = $pembelian_2030['nilai'];
			$pembelian_harga_2030 = (round($pembelian_volume_2030,2)!=0)?$pembelian_nilai_2030 / round($pembelian_volume_2030,2) * 1:0;

			$total_stok_volume_2030 = $stok_volume_2030_lalu + $pembelian_volume_2030;
			$total_stok_nilai_2030 = $stok_nilai_2030_lalu + $pembelian_nilai_2030;

			$stock_opname_2030_now = $this->db->select('pp.vol_nilai_2030 as volume, pp.nilai_2030 as nilai, pp.vol_pemakaian_2030 as volume_pemakaian, pp.vol_pemakaian_20302 as volume_pemakaian2, pp.nilai_pemakaian_2030 as nilai_pemakaian, pp.nilai_pemakaian_20302 as nilai_pemakaian2')
			->from('kunci_bahan_baku pp')
			->where("(pp.date <= '$date2')")
			->order_by('pp.date','desc')->limit(1)
			->get()->row_array();
			$volume_stock_opname_2030_now = $stock_opname_2030_now['volume'];
			$nilai_stock_opname_2030_now = $stock_opname_2030_now['nilai'];
			$vol_pemakaian_2030_now = $stock_opname_2030_now['volume_pemakaian'];
			$nilai_pemakaian_2030_now = $stock_opname_2030_now['nilai_pemakaian'];
			$vol_pemakaian_2030_now2 = $stock_opname_2030_now['volume_pemakaian2'];
			$nilai_pemakaian_2030_now2 = $stock_opname_2030_now['nilai_pemakaian2'];

			$pemakaian_volume_2030 = $vol_pemakaian_2030_now;
			$pemakaian_harsat_2030 = $nilai_pemakaian_2030_now / $vol_pemakaian_2030_now;
			$pemakaian_nilai_2030 = $nilai_pemakaian_2030_now;

			$pemakaian_volume_20302 = $vol_pemakaian_2030_now2;
			$pemakaian_harsat_20302 = $nilai_pemakaian_2030_now2 / $vol_pemakaian_2030_now2;
			$pemakaian_nilai_20302 = $nilai_pemakaian_2030_now2;

			$total_pemakaian_volume_2030 = $pemakaian_volume_2030 + $pemakaian_volume_20302;
			$total_pemakaian_nilai_2030 = $pemakaian_nilai_2030 + $pemakaian_nilai_20302;

			$stok_akhir_volume_2030 = $volume_stock_opname_2030_now;
			$stok_akhir_nilai_2030 = $nilai_stock_opname_2030_now;
			?>

			<?php
			//TOTAL
			$total_volume_realisasi = $total_volume_pemakaian_semen + $total_volume_pemakaian_pasir + $total_volume_pemakaian_batu1020 + $total_volume_pemakaian_batu2030;
			$total_nilai_realisasi = $total_pemakaian_nilai_semen + $total_pemakaian_nilai_pasir + $total_pemakaian_nilai_1020 + $total_pemakaian_nilai_2030;
	        ?>
			
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2">&nbsp;<br>NO.</th>
				<th width="30%" align="center" rowspan="2">&nbsp;<br>URAIAN</th>
				<th width="15%" align="center" rowspan="2">&nbsp;<br>SATUAN</th>
				<th width="50%" align="center" colspan="3">PEMAKAIAN</th>
	        </tr>
			<tr class="table-judul">
				<th align="center" width="15%">VOLUME</th>
				<th align="center" width="15%">HARGA</th>
				<th align="center" width="20%">NILAI</th>
	        </tr>
			<tr class="table-baris1">
				<th align="center">1</th>	
				<th align="left"><b>Semen</b></th>
				<th align="center">Ton</th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_semen,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_semen / $total_pemakaian_volume_semen,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_semen,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center">2</th>
				<th align="left"><b>Pasir</b></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_pasir,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_pasir / $total_pemakaian_volume_pasir,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_pasir,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center">3</th>
				<th align="left"><b>Batu Split 10-20</b></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_1020,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_1020 / $total_pemakaian_volume_1020,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_1020,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center">4</th>
				<th align="left"><b>Batu Split 20-30</b></th>
				<th align="center">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_volume_2030,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_2030 / $total_pemakaian_volume_2030,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_nilai_2030,0,',','.');?></th>
	        </tr>
			<tr class="table-total">
	            <th align="right" colspan="5">TOTAL</th>
				<th align="right"><?php echo number_format($total_nilai_realisasi,0,',','.');?></th>
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