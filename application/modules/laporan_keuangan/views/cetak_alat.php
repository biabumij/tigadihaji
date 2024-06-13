<!DOCTYPE html>
<html>
	<head>
	  <title>BIAYA ALAT</title>
	  
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

		table tr.table-judul{
			background-color: #e69500;
			font-weight:bold; 
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
			font-weight:bold; 
		}
			
		table tr.table-total{
			font-weight:bold; 
			font-size: 7px;
			color: black;
		}

		table tr.table-total2{
			background-color: #cccccc;
			font-weight:bold; 
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight:bold; font-size: 11px;">BIAYA ALAT</div>
		<div align="center" style="display: block;font-weight:bold; font-size: 11px;">PROYEK BENDUNGAN TIGA DIHAJI</div>
		<div align="center" style="display: block;font-weight:bold; font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
		<div align="center" style="display: block;font-weight:bold; font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
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

			$insentif_tm = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 123")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_tm = 0;
			foreach ($insentif_tm as $y){
				$total_insentif_tm += $y['total'];
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

			$insentif_wl = $this->db->select('pb.memo as memo, sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 125")
			->where("pb.status = 'PAID'")
			->where("pb.memo <> 'SC' ")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->group_by('pdb.id')
			->get()->result_array();

			$total_insentif_wl = 0;
			foreach ($insentif_wl as $y){
				$total_insentif_wl += $y['total'];
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

			$total_vol_batching_plant = $total_volume;
			$total_vol_truck_mixer = $total_volume;
			$total_vol_wheel_loader = $total_volume;
			$total_vol_excavator = $pembelian_excavator['volume'];
			$total_vol_transfer_semen = $pembelian_transfer_semen['volume'];
			$total_vol_bbm_solar = $total_volume;

			$total_pemakaian_vol_batching_plant = $total_vol_batching_plant;
			$total_pemakaian_vol_truck_mixer = $total_vol_truck_mixer;
			$total_pemakaian_vol_wheel_loader = $total_vol_wheel_loader;
			$total_pemakaian_vol_excavator = $total_vol_excavator;
			$total_pemakaian_vol_transfer_semen = $total_vol_transfer_semen;
			$total_pemakaian_vol_bbm_solar = $total_volume_pemakaian_solar;

			$total_pemakaian_batching_plant = $total_nilai_batching_plant;
			$total_pemakaian_truck_mixer = $total_nilai_truck_mixer + $total_insentif_tm;
			$total_pemakaian_wheel_loader = $total_nilai_wheel_loader + $total_insentif_wl;
			$total_pemakaian_excavator = $total_nilai_excavator;
			$total_pemakaian_transfer_semen = $total_nilai_transfer_semen;
			$total_pemakaian_bbm_solar = $total_akumulasi_bbm;
			
			$rap_alat = $this->db->select('rap.*')
			->from('rap_alat rap')
			->where("rap.tanggal_rap_alat <= '$date2'")
			->where('rap.status','PUBLISH')
			->get()->result_array();

			$total_vol_rap_batching_plant = 0;
			$total_vol_rap_truck_mixer = 0;
			$total_vol_rap_wheel_loader = 0;
			$total_vol_rap_excavator = 0;
			$total_vol_rap_transfer_semen = 0;
			$total_vol_rap_bbm_solar = 0;

			$total_batching_plant = 0;
			$total_truck_mixer = 0;
			$total_wheel_loader = 0;
			$total_excavator = 0;
			$total_transfer_semen = 0;
			$total_bbm_solar = 0;

			foreach ($rap_alat as $x){
				$total_vol_rap_batching_plant += $x['vol_batching_plant'];
				$total_vol_rap_truck_mixer += $x['vol_truck_mixer'];
				$total_vol_rap_wheel_loader += $x['vol_wheel_loader'];
				$total_vol_rap_excavator += $x['vol_excavator'];
				$total_vol_rap_transfer_semen += $x['vol_transfer_semen'];
				$total_vol_rap_bbm_solar += $x['vol_bbm_solar'];
				$total_batching_plant = $x['harsat_batching_plant'];
				$total_truck_mixer = $x['harsat_truck_mixer'];
				$total_wheel_loader = $x['harsat_wheel_loader'];
				$total_excavator = $x['harsat_excavator'];
				$total_transfer_semen = $x['harsat_transfer_semen'];
				$total_bbm_solar = $x['harsat_bbm_solar'];
				
			}

			$vol_batching_plant = $total_vol_rap_batching_plant * $total_pemakaian_vol_batching_plant;
			$vol_truck_mixer = $total_vol_rap_truck_mixer * $total_pemakaian_vol_truck_mixer;
			$vol_wheel_loader = $total_vol_rap_wheel_loader * $total_pemakaian_vol_wheel_loader;
			$vol_excavator = $total_vol_rap_excavator * $total_pemakaian_vol_excavator;
			$vol_transfer_semen = $total_vol_rap_transfer_semen * $total_pemakaian_vol_transfer_semen;
			$vol_bbm_solar = $total_vol_rap_bbm_solar * $total_vol_bbm_solar;

			$batching_plant = $total_batching_plant * $vol_batching_plant;
			$truck_mixer = $total_truck_mixer * $vol_truck_mixer;
			$wheel_loader = $total_wheel_loader * $vol_wheel_loader;
			$excavator = $total_excavator * $vol_excavator;
			$transfer_semen = $total_transfer_semen * $vol_transfer_semen;
			$bbm_solar = $total_bbm_solar * $vol_bbm_solar;

			$harsat_batching_plant = ($vol_batching_plant!=0)?$batching_plant / $vol_batching_plant * 1:0;
			$harsat_truck_mixer = ($vol_truck_mixer!=0)?$truck_mixer / $vol_truck_mixer * 1:0;
			$harsat_wheel_loader = ($wheel_loader!=0)?$wheel_loader / $vol_wheel_loader * 1:0;
			$harsat_excavator = ($excavator!=0)?$excavator / $vol_excavator * 1:0;
			$harsat_transfer_semen = ($transfer_semen!=0)?$transfer_semen / $vol_transfer_semen * 1:0;
			$harsat_bbm_solar = ($vol_bbm_solar!=0)?$bbm_solar / $vol_bbm_solar * 1:0;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;

			$total_vol_evaluasi_batching_plant = ($total_pemakaian_vol_batching_plant!=0)?$vol_batching_plant - $total_pemakaian_vol_batching_plant * 1:0;
			$total_nilai_evaluasi_batching_plant = ($total_pemakaian_batching_plant!=0)?$batching_plant - $total_pemakaian_batching_plant * 1:0;
			$total_vol_evaluasi_truck_mixer = ($total_pemakaian_vol_truck_mixer!=0)?$vol_truck_mixer - $total_pemakaian_vol_truck_mixer * 1:0;
			$total_nilai_evaluasi_truck_mixer = ($total_pemakaian_truck_mixer!=0)?$truck_mixer - $total_pemakaian_truck_mixer * 1:0;
			$total_vol_evaluasi_wheel_loader = ($total_pemakaian_vol_wheel_loader!=0)?$vol_wheel_loader - $total_pemakaian_vol_wheel_loader * 1:0;
			$total_nilai_evaluasi_wheel_loader = ($total_pemakaian_wheel_loader!=0)?$wheel_loader - $total_pemakaian_wheel_loader * 1:0;
			$total_vol_evaluasi_excavator = ($total_pemakaian_vol_excavator!=0)?$vol_excavator - $total_pemakaian_vol_excavator * 1:0;
			$total_nilai_evaluasi_excavator = ($total_pemakaian_excavator!=0)?$excavator - $total_pemakaian_excavator * 1:0;
			$total_vol_evaluasi_transfer_semen = ($total_pemakaian_vol_transfer_semen!=0)?$vol_transfer_semen - $total_pemakaian_vol_transfer_semen * 1:0;
			$total_nilai_evaluasi_transfer_semen = ($total_pemakaian_transfer_semen!=0)?$transfer_semen - $total_pemakaian_transfer_semen * 1:0;
			$total_vol_evaluasi_bbm_solar = ($pemakaian_volume_solar!=0)?$vol_bbm_solar - $pemakaian_volume_solar * 1:0;
			$total_nilai_evaluasi_bbm_solar = ($pemakaian_nilai_solar!=0)?$bbm_solar - $pemakaian_nilai_solar * 1:0;

			$total_vol_rap_alat = $vol_batching_plant + $vol_truck_mixer + $vol_wheel_loader + $vol_excavator + $vol_transfer_semen + $vol_bbm_solar;
			$total_nilai_rap_alat = $batching_plant + $truck_mixer + $wheel_loader + $excavator + $transfer_semen + $bbm_solar;
			$total_vol_realisasi_alat = $total_pemakaian_vol_batching_plant + $total_pemakaian_vol_truck_mixer + $total_pemakaian_vol_wheel_loader + $total_pemakaian_vol_excavator + $total_pemakaian_vol_transfer_semen + $pemakaian_volume_solar;
			$total_nilai_realisasi_alat = $total_pemakaian_batching_plant + $total_pemakaian_truck_mixer + $total_pemakaian_wheel_loader + $total_pemakaian_excavator + $total_nilai_transfer_semen + $pemakaian_nilai_solar;
			$total_vol_evaluasi_alat = $total_vol_evaluasi_batching_plant + $total_vol_evaluasi_truck_mixer + $total_vol_evaluasi_wheel_loader + $total_vol_evaluasi_excavator + $total_vol_evaluasi_transfer_semen + $total_vol_evaluasi_bbm_solar;
			$total_nilai_evaluasi_alat = $total_nilai_evaluasi_batching_plant + $total_nilai_evaluasi_truck_mixer + $total_nilai_evaluasi_wheel_loader + $total_nilai_evaluasi_excavator + $total_nilai_evaluasi_transfer_semen + $total_nilai_evaluasi_bbm_solar;
			?>
			<tr class="table-judul">
				<th width="5%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-left:1px solid black; border-bottom:1px solid black;">&nbsp;<br>NO.</th>
				<th width="30%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>URAIAN</th>
				<th width="10%" align="center" rowspan="2" style="background-color:#e69500; border-top:1px solid black; border-bottom:1px solid black;">&nbsp;<br>SATUAN</th>
				<th width="55%" align="center" colspan="3" style="background-color:#e69500; border:1px solid black;">REALISASI</th>
	        </tr>
			<tr class="table-judul">
				<th width="15%" align="right" style="border-left:1px solid black; border-bottom:1px solid black;">VOLUME</th>
				<th width="15%" align="right" style="border-bottom:1px solid black;">HARSAT</th>
				<th width="25%" align="right" style="border-bottom:1px solid black; border-right:1px solid black;">NILAI</th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">1.</th>			
				<th align="left">Batching Plant</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_batching_plant,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_batching_plant / $total_pemakaian_vol_batching_plant,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($total_pemakaian_batching_plant,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">2.</th>			
				<th align="left">Truck Mixer + Insentif</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_truck_mixer,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_truck_mixer / $total_pemakaian_vol_truck_mixer,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($total_pemakaian_truck_mixer,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">3.</th>			
				<th align="left">Wheel Loader + Insentif</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_wheel_loader,2,',','.');?></th>
				<th align="right"><?php echo number_format($total_pemakaian_wheel_loader / $total_pemakaian_vol_wheel_loader,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($total_pemakaian_wheel_loader,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">4.</th>			
				<th align="left">Excavator</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_excavator,2,',','.');?></th>
				<?php
				$total_harsat_pemakaian_excavator = ($total_pemakaian_vol_excavator!=0)?$total_pemakaian_excavator / $total_pemakaian_vol_excavator * 1:0;
				?>
				<th align="right"><?php echo number_format($total_harsat_pemakaian_excavator,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($total_pemakaian_excavator,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">5.</th>			
				<th align="left">Transfer Semen</th>
				<th align="center" style="border-right:1px solid black;">M3</th>
				<th align="right"><?php echo number_format($total_pemakaian_vol_transfer_semen,2,',','.');?></th>
				<?php
				$total_harsat_pemakaian_transfer_semen = ($total_pemakaian_vol_transfer_semen!=0)?$total_pemakaian_transfer_semen / $total_pemakaian_vol_transfer_semen * 1:0;
				?>
				<th align="right"><?php echo number_format($total_harsat_pemakaian_transfer_semen,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($total_pemakaian_transfer_semen,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" style="border-left:1px solid black;">6.</th>			
				<th align="left">BBM Solar</th>
				<th align="center" style="border-right:1px solid black;">Liter</th>
				<th align="right"><?php echo number_format($pemakaian_volume_solar,2,',','.');?></th>
				<th align="right"><?php echo number_format($pemakaian_harsat_solar,0,',','.');?></th>
				<th align="right" style="border-right:1px solid black;"><?php echo number_format($pemakaian_nilai_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-total">		
				<th align="right" colspan="3" style="background-color:#FFFF00; border:1px solid black;">TOTAL</th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black;"></th>
				<th align="right" style="background-color:#FFFF00; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?php echo number_format($total_nilai_realisasi_alat,0,',','.');?></th>
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
							<td align="center" >
								Dibuat Oleh
							</td>	
						</tr>
						<tr class="">
							<?php
								$create = $this->db->select('id, unit_head, logistik, admin')
								->from('kunci_bahan_baku')
								->where("(date between '$start_date' and '$end_date')")
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
								<!--<img src="<?= $unit_head['admin_ttd']?>" width="70px">-->
							</td>
							<td align="center">
								<!--<img src="<?= $logistik['admin_ttd']?>" width="70px">-->
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b><u><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$create['unit_head']),'admin_name');?></u><br />
								<?= $unit_head['admin_group_name']?></b>
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