<!DOCTYPE html>
<html>
	<head>
	  <title>OVERHEAD</title>

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
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 1px solid #000000;
		  /*padding: 10px 4px;*/
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		}
		table tr.table-active{
            background-color: #e69500;
        }
        table tr.table-active2{
            background-color: #b5b5b5;
        }
        table tr.table-active3{
            background-color: #eee;
        }
		table tr.table-active4{
            font-weight: bold;
        }
		hr{
			margin-top:0;
			margin-bottom:30px;
		}
		h3{
			margin-top:0;
		}
		.table-lap tr td, .table-lap tr th{
			border-bottom: 1px solid #000;
		}
	  </style>

	</head>
	<body>
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">Laporan Biaya Umum & Administratif</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px;">Proyek Bendungan Tiga Dihaji</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px;">PT. Bia Bumi Jayendra</div>
					<div align="center" style="display: block;font-weight:bold; font-size: 11px; ">Periode : <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<table class="table-lap" width="98%" border="0" cellpadding="3">
			<?php
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

			$gaji_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun in ('114','115')")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun in ('114','115')")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$gaji = $gaji_biaya['total'] + $gaji_jurnal['total'];

			$akomodasi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 143")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akomodasi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 143")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$akomodasi = $akomodasi_biaya['total'] + $akomodasi_jurnal['total'];

			$biaya_maintenance_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 141")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_maintenance_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 141")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_maintenance = $biaya_maintenance_biaya['total'] + $biaya_maintenance_jurnal['total'];

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

			$biaya_pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 178")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 178")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_pengujian = $biaya_pengujian_biaya['total'] + $biaya_pengujian_jurnal['total'];

			$biaya_donasi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 179")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_donasi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 179")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_donasi = $biaya_donasi_biaya['total'] + $biaya_donasi_jurnal['total'];

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

			$biaya_kirim_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 180")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_kirim_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 180")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_kirim = $biaya_kirim_biaya['total'] + $biaya_kirim_jurnal['total'];

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

			$perjalanan_dinas_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 62")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$perjalanan_dinas_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 62")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$perjalanan_dinas = $perjalanan_dinas_biaya['total'] + $perjalanan_dinas_jurnal['total'];

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

			$sewa_mess_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 181")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$sewa_mess_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 181")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$sewa_mess = $sewa_mess_biaya['total'] + $sewa_mess_jurnal['total'];

			$biaya_lain_lain_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 94")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lain_lain_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 94")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_lain_lain = $biaya_lain_lain_biaya['total'] + $biaya_lain_lain_jurnal['total'];
			
			$biaya_adm_bank_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 182")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_adm_bank_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 182")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_adm_bank = $biaya_adm_bank_biaya['total'] + $biaya_adm_bank_jurnal['total'];

			$biaya_jamsostek_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 183")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_jamsostek_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 183")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_jamsostek = $biaya_jamsostek_biaya['total'] + $biaya_jamsostek_jurnal['total'];

			$biaya_iuran_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 184")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_iuran_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 184")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			$biaya_iuran = $biaya_iuran_biaya['total'] + $biaya_iuran_jurnal['total'];

			$total_realisasi = $konsumsi + $listrik_internet + $gaji + $akomodasi + $biaya_maintenance + $thr_bonus + $biaya_pengujian + $biaya_donasi + $biaya_sewa_kendaraan + $bensin_tol_parkir + $biaya_kirim + $pakaian_dinas + $perjalanan_dinas + $perlengkapan_kantor + $pengobatan + $alat_tulis_kantor + $keamanan_kebersihan + $sewa_mess + $biaya_lain_lain + $biaya_adm_bank + $biaya_jamsostek + $biaya_iuran;
			?>
			<tr class="table-active">
				<th align="center" width="20%"><b>Kode Akun</b></th>
				<th align="center" width="50%"><b>Nama Akun</b></th>
				<th align="center" width="30%" align="right"><b>Jumlah</b></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50301</th>
				<th align="left" width="50%">Konsumsi</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_konsumsi?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($konsumsi,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50302</th>
				<th align="left" width="50%">Listrik & Internet</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_listrik_internet?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($listrik_internet,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50303</th>
				<th align="left" width="50%">Biaya Upah</th>
				<th align="center" width="30%" align="right">0</th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50304</th>
				<th align="left" width="50%">Gaji</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_gaji?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($gaji,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50305</th>
				<th align="left" width="50%">Akomodasi Tamu </th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_akomodasi?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($akomodasi,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50306</th>
				<th align="left" width="50%">Biaya Perbaikan & Pemeliharaan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_maintenance?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_maintenance,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50307</th>
				<th align="left" width="50%">THR & Bonus</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_thr_bonus?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($thr_bonus,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50308</th>
				<th align="left" width="50%">Biaya Pengujian Material & Laboratorium</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_pengujian?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_pengujian,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50309</th>
				<th align="left" width="50%">Biaya Donasi</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_donasi?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_donasi,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50310</th>
				<th align="left" width="50%">Biaya Sewa Kendaraan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_sewa_kendaraan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_sewa_kendaraan,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50311</th>
				<th align="left" width="50%">Bensin, Tol dan Parkir</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_bensin_tol_parkir?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($bensin_tol_parkir,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50312</th>
				<th align="left" width="50%">Biaya Kirim</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_kirim?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_kirim,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50313</th>
				<th align="left" width="50%">Pakaian Dinas & K3</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_pakaian_dinas?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($pakaian_dinas,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50314</th>
				<th align="left" width="50%">Perjalanan Dinas Umum</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_perjalanan_dinas?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($perjalanan_dinas,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50315</th>
				<th align="left" width="50%">Perlengkapan Kantor</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_perlengkapan_kantor?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($perlengkapan_kantor,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50316</th>
				<th align="left" width="50%">Biaya Pengobatan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_pengobatan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($pengobatan,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50317</th>
				<th align="left" width="50%">Biaya Alat Tulis Kantor & Printing</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_alat_tulis_kantor?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($alat_tulis_kantor,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50318</th>
				<th align="left" width="50%">Biaya Keamanan dan Kebersihan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_keamanan_kebersihan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($keamanan_kebersihan,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50319</th>
				<th align="left" width="50%">Biaya Sewa - Mess / Bangunan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_sewa_mess?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($sewa_mess,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50320</th>
				<th align="left" width="50%">Biaya Adm Bank</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_beban_adm?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($beban_adm,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50321</th>
				<th align="left" width="50%">Biaya Lain-Lain</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_lain_lain?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_lain_lain,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50322</th>
				<th align="left" width="50%">Biaya Jamsostek</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_jamsostek?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_jamsostek,0,',','.');?></a></th>
			</tr>
			<tr>
				<th align="center" width="20%">5-50323</th>
				<th align="left" width="50%">Biaya Iuran & Langganan</th>
				<th align="center" width="30%" align="right"><a target="_blank" href="<?= base_url("laporan/cetak_biaya_iuran_langganan?filter_date=".$filter_date = date('d-m-Y',strtotime($date1)).' - '.date('d-m-Y',strtotime($date2))) ?>"><?php echo number_format($biaya_iuran,0,',','.');?></a></th>
			</tr>
			<tr class="table-active2">
				<td width="80%" style="padding-left:20px;"><b>Total Biaya Operasional Produksi</b></td>
				<td width="20%" align="right"><b><?php echo number_format($total_realisasi,0,',','.');?></b></td>
			</tr>
		</table>
		<br />
		<br />
		<table width="98%">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Diperiksa Oleh & Disetujui Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>	
						</tr>
						<tr>
							<td align="center" height="55px">
								<img src="uploads/ttd_novel.png" width="50px">
							</td>
							<td align="center">
								<img src="uploads/ttd_avriani.png" width="50px">
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b><u>Novel Joko Tri Laksono</u><br />
								Ka. Plant</b>
							</td>
							<td align="center" >
								<b><u>Avriani Delia Ika Putri</u><br />
								Keuangan Proyek</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>