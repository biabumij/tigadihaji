<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN EVALUASI BUA</title>
	  
	  <?php
		function tanggal_indo($tanggal)
		{
			$bulan = array (1 =>   'Januari',
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
			$split = explode('-', $tanggal);
			return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
		}
	  ?>
	  
	  <style type="text/css">
		 body {
			font-family: helvetica;
			font-size: 8px;
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
			font-size: 8px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 8px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 8px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Evaluasi Biaya BUA</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Proyek Bendungan Tiga Dihaji</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">Periode <?php echo tanggal_indo($date1,true);?> - <?php echo tanggal_indo($date2,true);?></div>
		<br /><br /><br />
		
		
		<table class="table table-bordered" width="98%"  cellpadding="3">
			
			<?php
			$rap_bua_id = $row['id'];
			$rap_konsumsi = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 116")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_listrik_internet = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 118")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_gaji = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa in ('114','115')")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_akomodasi = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 143")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_maintenance = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 141")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_thr_bonus = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 117")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_pengujian = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 178")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_donasi = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 179")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_sewa_kendaraan = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 100")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_bensin_tol_parkir = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 78")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_kirim = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 180")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_pakaian_dinas = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 87")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_perjalanan_dinas = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 62")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_perlengkapan_kantor = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 98")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();
			
			$rap_pengobatan = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 70")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_alat_tulis_kantor = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 96")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_keamanan_kebersihan = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 97")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_sewa_mess = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 181")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_lain_lain = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 94")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();
			
			$rap_biaya_adm_bank = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 182")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_jamsostek = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 183")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			$rap_biaya_iuran = $this->db->select('rap.*,sum(det.harga_satuan) as total')
			->from('rap_bua rap')
			->join('rap_bua_detail det','rap.id = det.rap_bua_id','left')
			->where("rap.id = $rap_bua_id")
			->where("rap.status = 'PUBLISH'")
			->where("det.coa = 184")
			->order_by('rap.tanggal_rap_bua','asc')->limit(1)
			->get()->row_array();

			//REALISASI
			$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];
			$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];
			$gaji = $gaji_biaya['total'] + $gaji_jurnal['total'];
			$akomodasi = $akomodasi_biaya['total'] + $akomodasi_jurnal['total'];
			$biaya_maintenance = $biaya_maintenance_biaya['total'] + $biaya_maintenance_jurnal['total'];
			$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];
			$biaya_pengujian = $biaya_pengujian_biaya['total'] + $biaya_pengujian_jurnal['total'];
			$biaya_donasi = $biaya_donasi_biaya['total'] + $biaya_donasi_jurnal['total'];
			$biaya_sewa_kendaraan = $biaya_sewa_kendaraan_biaya['total'] + $biaya_sewa_kendaraan_jurnal['total'];
			$bensin_tol_parkir = $bensin_tol_parkir_biaya['total'] + $bensin_tol_parkir_jurnal['total'];
			$biaya_kirim = $biaya_kirim_biaya['total'] + $biaya_kirim_jurnal['total'];
			$pakaian_dinas = $pakaian_dinas_biaya['total'] + $pakaian_dinas_jurnal['total'];
			$perjalanan_dinas = $perjalanan_dinas_biaya['total'] + $perjalanan_dinas_jurnal['total'];
			$perlengkapan_kantor = $perlengkapan_kantor_biaya['total'] + $perlengkapan_kantor_jurnal['total'];
			$pengobatan = $pengobatan_biaya['total'] + $pengobatan_jurnal['total'];
			$alat_tulis_kantor = $alat_tulis_kantor_biaya['total'] + $alat_tulis_kantor_jurnal['total'];
			$keamanan_kebersihan = $keamanan_kebersihan_biaya['total'] + $keamanan_kebersihan_jurnal['total'];
			$sewa_mess = $sewa_mess_biaya['total'] + $sewa_mess_jurnal['total'];
			$biaya_lain_lain = $biaya_lain_lain_biaya['total'] + $biaya_lain_lain_jurnal['total'];
			$biaya_adm_bank = $biaya_adm_bank_biaya['total'] + $biaya_adm_bank_jurnal['total'];
			$biaya_jamsostek = $biaya_jamsostek_biaya['total'] + $biaya_jamsostek_jurnal['total'];
			$biaya_iuran = $biaya_iuran_biaya['total'] + $biaya_iuran_jurnal['total'];
			?>

			<?php
			$evaluasi_konsumsi = $rap_konsumsi['total'] - $konsumsi;
			$evaluasi_listrik_internet = $rap_listrik_internet['total'] - $listrik_internet;
			$evaluasi_gaji = $rap_gaji['total'] - $gaji;
			$evaluasi_akomodasi = $rap_akomodasi['total'] - $akomodasi;
			$evaluasi_biaya_maintenance = $rap_biaya_maintenance['total'] - $biaya_maintenance;
			$evaluasi_thr_bonus = $rap_thr_bonus['total'] - $thr_bonus;
			$evaluasi_biaya_pengujian = $rap_biaya_pengujian['total'] - $biaya_pengujian;
			$evaluasi_biaya_donasi = $rap_biaya_donasi['total'] - $biaya_donasi;
			$evaluasi_biaya_sewa_kendaraan = $rap_biaya_sewa_kendaraan['total'] - $biaya_sewa_kendaraan;
			$evaluasi_bensin_tol_parkir = $rap_bensin_tol_parkir['total'] - $bensin_tol_parkir;
			$evaluasi_biaya_kirim = $rap_biaya_kirim['total'] - $biaya_kirim;
			$evaluasi_pakaian_dinas = $rap_pakaian_dinas['total'] - $pakaian_dinas;
			$evaluasi_perjalanan_dinas = $rap_perjalanan_dinas['total'] - $perjalanan_dinas;
			$evaluasi_perlengkapan_kantor = $rap_perlengkapan_kantor['total'] - $perlengkapan_kantor;
			$evaluasi_pengobatan = $rap_pengobatan['total'] - $pengobatan;
			$evaluasi_alat_tulis_kantor = $rap_alat_tulis_kantor['total'] -	$alat_tulis_kantor;
			$evaluasi_keamanan_kebersihan = $rap_keamanan_kebersihan['total'] - $keamanan_kebersihan;
			$evaluasi_sewa_mess = $rap_sewa_mess['total'] - $sewa_mess;
			$evaluasi_biaya_lain_lain = $rap_biaya_lain_lain['total'] - $biaya_lain_lain;
			$evaluasi_biaya_adm_bank = $rap_biaya_adm_bank['total'] - $biaya_adm_bank;
			$evaluasi_biaya_jamsostek = $rap_biaya_jamsostek['total'] - $biaya_jamsostek;
			$evaluasi_biaya_iuran = $rap_biaya_iuran['total'] - $biaya_iuran;
			
			$total_rap = $rap_konsumsi['total'] + $rap_listrik_internet['total'] + $rap_gaji['total'] + $rap_akomodasi['total'] + $rap_biaya_maintenance['total'] + $rap_thr_bonus['total'] + $rap_biaya_pengujian['total'] + $rap_biaya_donasi['total'] + $rap_biaya_sewa_kendaraan['total'] + $rap_bensin_tol_parkir['total'] + $rap_biaya_kirim['total'] + $rap_pakaian_dinas['total'] + $rap_perjalanan_dinas['total'] + $rap_perlengkapan_kantor['total'] + $rap_pengobatan['total'] + $rap_alat_tulis_kantor['total']  + $rap_keamanan_kebersihan['total'] + $rap_sewa_mess['total'] + $rap_biaya_lain_lain['total'] + $rap_biaya_adm_bank['total' + $rap_biaya_jamsostek['total']] + $rap_biaya_iuran['total'];
			$total_realisasi = $konsumsi + $listrik_internet + $gaji + $akomodasi + $biaya_maintenance + $thr_bonus + $biaya_pengujian + $biaya_donasi + $biaya_sewa_kendaraan + $bensin_tol_parkir + $biaya_kirim + $pakaian_dinas + $perjalanan_dinas + $perlengkapan_kantor + $pengobatan + $alat_tulis_kantor + $keamanan_kebersihan + $sewa_mess + $biaya_lain_lain + $biaya_adm_bank + $biaya_jamsostek + $biaya_iuran;
			$total_evaluasi = $total_rap - $total_realisasi;
			?>
			
			<tr class="table-judul">
				<th width="5%" align="center" class="table-border-pojok-kiri">NO.</th>
				<th width="35%" align="center" class="table-border-pojok-tengah">URAIAN</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">RAP</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">REALISASI</th>
				<th width="20%" align="center" class="table-border-pojok-kanan">DEVIASI</th>
	        </tr>
			<?php
				$styleColorA = $evaluasi_konsumsi < 0 ? 'color:red' : 'color:black';
				$styleColorB = $evaluasi_listrik_internet < 0 ? 'color:red' : 'color:black';
				$styleColorC = $evaluasi_gaji < 0 ? 'color:red' : 'color:black';
				$styleColorD = $evaluasi_akomodasi < 0 ? 'color:red' : 'color:black';
				$styleColorE = $evaluasi_biaya_maintenance < 0 ? 'color:red' : 'color:black';
				$styleColorF = $evaluasi_thr_bonus < 0 ? 'color:red' : 'color:black';
				$styleColorG = $evaluasi_thr_pengujian < 0 ? 'color:red' : 'color:black';
				$styleColorH = $evaluasi_donasi < 0 ? 'color:red' : 'color:black';
				$styleColorI = $evaluasi_biaya_sewa_kendaraan < 0 ? 'color:red' : 'color:black';
				$styleColorJ = $evaluasi_bensin_tol_parkir < 0 ? 'color:red' : 'color:black';
				$styleColorK = $evaluasi_biaya_kirim < 0 ? 'color:red' : 'color:black';
				$styleColorL = $evaluasi_pakaian_dinas < 0 ? 'color:red' : 'color:black';
				$styleColorM = $evaluasi_perjalanan_dinas < 0 ? 'color:red' : 'color:black';
				$styleColorN = $evaluasi_perlengkapan_kantor < 0 ? 'color:red' : 'color:black';
				$styleColorO = $evaluasi_pengobatan < 0 ? 'color:red' : 'color:black';
				$styleColorP = $evaluasi_alat_tulis_kantor < 0 ? 'color:red' : 'color:black';
				$styleColorQ = $evaluasi_keamanan_kebersihan < 0 ? 'color:red' : 'color:black';
				$styleColorR = $evaluasi_sewa_mess < 0 ? 'color:red' : 'color:black';
				$styleColorS = $evaluasi_biaya_lain_lain < 0 ? 'color:red' : 'color:black';
				$styleColorT = $evaluasi_biaya_adm_bank < 0 ? 'color:red' : 'color:black';
				$styleColorU = $evaluasi_biaya_jamsostek < 0 ? 'color:red' : 'color:black';
				$styleColorv = $evaluasi_biaya_iuran < 0 ? 'color:red' : 'color:black';
				
				$styleColorW = $total_evaluasi < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">1</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50301) - Biaya Konsumsi</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_konsumsi['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($konsumsi,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorA ?>"><?php echo $evaluasi_konsumsi < 0 ? "(".number_format(-$evaluasi_konsumsi,0,',','.').")" : number_format($evaluasi_konsumsi,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">2</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50302) - Biaya Listrik & Internet</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_listrik_internet['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($listrik_internet,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorB ?>"><?php echo $evaluasi_listrik_internet < 0 ? "(".number_format(-$evaluasi_listrik_internet,0,',','.').")" : number_format($evaluasi_listrik_internet,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">3</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50303) - Biaya Upah</th>
				<th align="right" class="table-border-pojok-tengah">0</th>
				<th align="right" class="table-border-pojok-tengah">0</th>
				<th align="right" class="table-border-pojok-kanan">0</th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">4</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50304) - Biaya Gaji</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_gaji['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($gaji,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorC ?>"><?php echo $evaluasi_gaji < 0 ? "(".number_format(-$evaluasi_gaji,0,',','.').")" : number_format($evaluasi_gaji,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">5</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50305) - Biaya Akomodasi Tamu</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_akomodasi['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($akomodasi,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorD ?>"><?php echo $evaluasi_akomodasi < 0 ? "(".number_format(-$evaluasi_akomodasi,0,',','.').")" : number_format($evaluasi_akomodasi,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">6</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50306) - Biaya Perbaikan & Pemeliharaan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_maintenance['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_maintenance,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorE ?>"><?php echo $evaluasi_biaya_maintenance < 0 ? "(".number_format(-$evaluasi_biaya_maintenance,0,',','.').")" : number_format($evaluasi_biaya_maintenance,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">7</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50307) - Biaya THR & Bonus</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_thr_bonus['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($thr_bonus,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorF ?>"><?php echo $evaluasi_thr_bonus < 0 ? "(".number_format(-$evaluasi_thr_bonus,0,',','.').")" : number_format($evaluasi_thr_bonus,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">8</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50308) - Biaya Pengujian Material & Laboratorium</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_pengujian['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_pengujian,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorG ?>"><?php echo $evaluasi_biaya_pengujian < 0 ? "(".number_format(-$evaluasi_biaya_pengujian,0,',','.').")" : number_format($evaluasi_biaya_pengujian,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">9</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50309) - Biaya Donasi</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_donasi['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_donasi,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorH ?>"><?php echo $evaluasi_biaya_donasi < 0 ? "(".number_format(-$evaluasi_biaya_donasi,0,',','.').")" : number_format($evaluasi_biaya_donasi,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">10</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50310) - Biaya Sewa Kendaraan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_sewa_kendaraan['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_sewa_kendaraan,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorI ?>"><?php echo $evaluasi_biaya_sewa_kendaraan < 0 ? "(".number_format(-$evaluasi_biaya_sewa_kendaraan,0,',','.').")" : number_format($evaluasi_biaya_sewa_kendaraan,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">11</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50311) - Bensin, Tol dan Parkir</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_bensin_tol_parkir['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($bensin_tol_parkir,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorJ ?>"><?php echo $evaluasi_bensin_tol_parkir < 0 ? "(".number_format(-$evaluasi_bensin_tol_parkir,0,',','.').")" : number_format($evaluasi_bensin_tol_parkir,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">12</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50312) - Biaya Kirim</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_kirim['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_kirim,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorK ?>"><?php echo $evaluasi_biaya_kirim < 0 ? "(".number_format(-$evaluasi_biaya_kirim,0,',','.').")" : number_format($evaluasi_biaya_kirim,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">13</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50313) - Pakaian Dinas & K3</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_pakaian_dinas['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($pakaian_dinas,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorL ?>"><?php echo $evaluasi_pakaian_dinas < 0 ? "(".number_format(-$evaluasi_pakaian_dinas,0,',','.').")" : number_format($evaluasi_pakaian_dinas,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">14</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50314) - Perjalanan Dinas Umum</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_perjalanan_dinas['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($perjalanan_dinas,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorM ?>"><?php echo $evaluasi_perjalanan_dinas < 0 ? "(".number_format(-$evaluasi_perjalanan_dinas,0,',','.').")" : number_format($evaluasi_perjalanan_dinas,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">15</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50315) - Perlengkapan Kantor</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_perlengkapan_kantor['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($perlengkapan_kantor,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorN ?>"><?php echo $evaluasi_perlengkapan_kantor < 0 ? "(".number_format(-$evaluasi_perlengkapan_kantor,0,',','.').")" : number_format($evaluasi_perlengkapan_kantor,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">16</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50316) - Biaya Pengobatan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_pengobatan['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($pengobatan,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorO ?>"><?php echo $evaluasi_pengobatan < 0 ? "(".number_format(-$evaluasi_pengobatan,0,',','.').")" : number_format($evaluasi_pengobatan,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">17</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50317) - Biaya Alat Tulis Kantor & Printing</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_alat_tulis_kantor['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($alat_tulis_kantor,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorP ?>"><?php echo $evaluasi_alat_tulis_kantor < 0 ? "(".number_format(-$evaluasi_alat_tulis_kantor,0,',','.').")" : number_format($evaluasi_alat_tulis_kantor,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">18</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50318) - Biaya Keamanan & Kebersihan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_keamanan_kebersihan['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($keamanan_kebersihan,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorQ ?>"><?php echo $evaluasi_keamanan_kebersihan < 0 ? "(".number_format(-$evaluasi_keamanan_kebersihan,0,',','.').")" : number_format($evaluasi_keamanan_kebersihan,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">19</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50319) - Biaya Sewa - Mess / Bangunan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_sewa_mess['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($sewa_mess,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorR ?>"><?php echo $evaluasi_sewa_mess < 0 ? "(".number_format(-$evaluasi_sewa_mess,0,',','.').")" : number_format($evaluasi_sewa_mess,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">20</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50320) - Biaya Lain-Lain</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_lain_lain['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_lain_lain,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorS ?>"><?php echo $evaluasi_biaya_lain_lain < 0 ? "(".number_format(-$evaluasi_biaya_lain_lain,0,',','.').")" : number_format($evaluasi_biaya_lain_lain,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">21</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50321) - Biaya Adm Bank</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_adm_bank['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_adm_bank,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorT ?>"><?php echo $evaluasi_biaya_adm_bank < 0 ? "(".number_format(-$evaluasi_biaya_adm_bank,0,',','.').")" : number_format($evaluasi_biaya_adm_bank,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">22</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50322) - Biaya Jamsostek</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_jamsostek['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_jamsostek,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorU ?>"><?php echo $evaluasi_biaya_jamsostek < 0 ? "(".number_format(-$evaluasi_biaya_jamsostek,0,',','.').")" : number_format($evaluasi_biaya_jamsostek,0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">23</th>			
				<th align="left" class="table-border-pojok-tengah">(5-50323) - Biaya Iuran & Langganan</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rap_biaya_iuran['total'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($biaya_iuran,0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan" style="<?php echo $styleColorV ?>"><?php echo $evaluasi_biaya_iuran < 0 ? "(".number_format(-$evaluasi_biaya_iuran,0,',','.').")" : number_format($evaluasi_biaya_iuran,0,',','.');?></th>
	        </tr>
			<tr class="table-total2">
				<th align="center" colspan="2" class="table-border-spesial-kiri">TOTAL</th>
				<th align="right" class="table-border-spesial-tengah"><?php echo number_format($total_rap,0,',','.');?></th>
				<th align="right" class="table-border-spesial-tengah"><?php echo number_format($total_realisasi,0,',','.');?></th>
				<th align="right" class="table-border-spesial-kanan" style="<?php echo $styleColorW ?>"><?php echo $total_evaluasi < 0 ? "(".number_format(-$total_evaluasi,0,',','.').")" : number_format($total_evaluasi,0,',','.');?></th>
	        </tr>
	    </table>
		<br /><br /><br /><br /><br /><br /><br />
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center">
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="55px">
							
							</td>
							<td align="center">
								<img src="uploads/ttd_novel.png" width="50px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Deddy Sarwobiso</u><br />
								Direktur Utama</b>
							</td>
							<td align="center">
								<b><u>Novel Joko Tri Laksono</u><br />
								Ka. Plant</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>