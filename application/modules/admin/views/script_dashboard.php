<?php
function tgl_indo($date_minggu_1_awal){
	$bulan = array (
		1 =>   'Januari',
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
	$pecahkan = explode('-', $date_minggu_1_awal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$date_now = date('Y-m-d');

$date_januari25_awal = date('2025-01-01');
$date_januari25_akhir = date('2025-01-31');
$date_februari25_awal = date('2025-02-01');
$date_februari25_akhir = date('2025-02-28');
$date_maret25_awal = date('2025-03-01');
$date_maret25_akhir = date('2025-03-31');
$date_april25_awal = date('2025-04-01');
$date_april25_akhir = date('2025-04-30');
$date_mei25_awal = date('2025-05-01');
$date_mei25_akhir = date('2025-05-31');
$date_juni25_awal = date('2025-06-01');
$date_juni25_akhir = date('2025-06-30');
$date_juli25_awal = date('2025-07-01');
$date_juli25_akhir = date('2025-07-31');
$date_agustus25_awal = date('2025-08-01');
$date_agustus25_akhir = date('2025-08-31');
$date_september25_awal = date('2025-09-01');
$date_september25_akhir = date('2025-09-30');
$date_oktober25_awal = date('2025-10-01');
$date_oktober25_akhir = date('2025-10-31');
$date_november25_awal = date('2025-11-01');
$date_november25_akhir = date('2025-11-30');
$date_desember25_awal = date('2025-12-01');
$date_desember25_akhir = date('2025-12-31');


//REALISASI PRODUKSI
//FEBRUARI25
$rak_februari25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_februari25_awal' and '$date_februari25_akhir'")
->get()->row_array();
$rencana_produksi_februari25 = $rak_februari25['total_produksi'];

$penjualan_februari25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_februari25_awal' and '$date_februari25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_februari25 = 0;
foreach ($penjualan_februari25 as $x){
    $total_volume_penjualan_februari25 += $x['volume'];
}
$realisasi_produksi_februari25 = $total_volume_penjualan_februari25;

//MARET25
$rak_maret25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_maret25_awal' and '$date_maret25_akhir'")
->get()->row_array();
$rencana_produksi_maret25 = $rak_maret25['total_produksi'];

$penjualan_maret25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_maret25_awal' and '$date_maret25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_maret25 = 0;
foreach ($penjualan_maret25 as $x){
    $total_volume_penjualan_maret25 += $x['volume'];
}
$realisasi_produksi_maret25 = $total_volume_penjualan_maret25;

//APRIL25
$rak_april25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_april25_awal' and '$date_april25_akhir'")
->get()->row_array();
$rencana_produksi_april25 = $rak_april25['total_produksi'];

$penjualan_april25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_april25_awal' and '$date_april25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_april25 = 0;
foreach ($penjualan_april25 as $x){
    $total_volume_penjualan_april25 += $x['volume'];
}
$realisasi_produksi_april25 = $total_volume_penjualan_april25;

//MEI25
$rak_mei25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_mei25_awal' and '$date_mei25_akhir'")
->get()->row_array();
$rencana_produksi_mei25 = $rak_mei25['total_produksi'];

$penjualan_mei25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_mei25_awal' and '$date_mei25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_mei25 = 0;
foreach ($penjualan_mei25 as $x){
    $total_volume_penjualan_mei25 += $x['volume'];
}
$realisasi_produksi_mei25 = $total_volume_penjualan_mei25;

//JUNI25
$rak_juni25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_juni25_awal' and '$date_juni25_akhir'")
->get()->row_array();
$rencana_produksi_juni25 = $rak_juni25['total_produksi'];

$penjualan_juni25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juni25_awal' and '$date_juni25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_juni25 = 0;
foreach ($penjualan_juni25 as $x){
    $total_volume_penjualan_juni25 += $x['volume'];
}
$realisasi_produksi_juni25 = $total_volume_penjualan_juni25;

//JULI25
$rak_juli25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_juli25_awal' and '$date_juli25_akhir'")
->get()->row_array();
$rencana_produksi_juli25 = $rak_juli25['total_produksi'];

$penjualan_juli25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juli25_awal' and '$date_juli25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_juli25 = 0;
foreach ($penjualan_juli25 as $x){
    $total_volume_penjualan_juli25 += $x['volume'];
}
$realisasi_produksi_juli25 = $total_volume_penjualan_juli25;

//AGUSTUS25
$rak_agustus25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_agustus25_awal' and '$date_agustus25_akhir'")
->get()->row_array();
$rencana_produksi_agustus25 = $rak_agustus25['total_produksi'];

$penjualan_agustus25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_agustus25_awal' and '$date_agustus25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_agustus25 = 0;
foreach ($penjualan_agustus25 as $x){
    $total_volume_penjualan_agustus25 += $x['volume'];
}
$realisasi_produksi_agustus25 = $total_volume_penjualan_agustus25;

//SEPTEMBER25
$rak_september25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_september25_awal' and '$date_september25_akhir'")
->get()->row_array();
$rencana_produksi_september25 = $rak_september25['total_produksi'];

$penjualan_september25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_september25_awal' and '$date_september25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_september25 = 0;
foreach ($penjualan_september25 as $x){
    $total_volume_penjualan_september25 += $x['volume'];
}
$realisasi_produksi_september25 = $total_volume_penjualan_september25;

//OKTOBER25
$rak_oktober25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_oktober25_awal' and '$date_oktober25_akhir'")
->get()->row_array();
$rencana_produksi_oktober25 = $rak_oktober25['total_produksi'];

$penjualan_oktober25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_oktober25_awal' and '$date_oktober25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_oktober25 = 0;
foreach ($penjualan_oktober25 as $x){
    $total_volume_penjualan_oktober25 += $x['volume'];
}
$realisasi_produksi_oktober25 = $total_volume_penjualan_oktober25;

//NOVEMBER25
$rak_november25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_november25_awal' and '$date_november25_akhir'")
->get()->row_array();
$rencana_produksi_november25 = $rak_november25['total_produksi'];

$penjualan_november25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_november25_awal' and '$date_november25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_november25 = 0;
foreach ($penjualan_november25 as $x){
    $total_volume_penjualan_november25 += $x['volume'];
}
$realisasi_produksi_november25 = $total_volume_penjualan_november25;

//DESEMBER25
$rak_desember25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_desember25_awal' and '$date_desember25_akhir'")
->get()->row_array();
$rencana_produksi_desember25 = $rak_desember25['total_produksi'];

$penjualan_desember25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_desember25_awal' and '$date_desember25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_desember25 = 0;
foreach ($penjualan_desember25 as $x){
    $total_volume_penjualan_desember25 += $x['volume'];
}
$realisasi_produksi_desember25 = $total_volume_penjualan_desember25;

//LABA RUGI
//FEBRUARI25
$total_niai_komposisi_bahan_februari25 = $this->pmm_model->getKomposisiBahan($date_februari25_awal,$date_februari25_akhir);
$total_niai_komposisi_alat_februari25 = $this->pmm_model->getKomposisiAlat($date_februari25_awal,$date_februari25_akhir);
$total_niai_komposisi_bua_februari25 = $this->pmm_model->getKomposisiBUA($date_februari25_awal,$date_februari25_akhir);
$total_rak_februari25 = $total_niai_komposisi_bahan_februari25 + $total_niai_komposisi_alat_februari25 + $total_niai_komposisi_bua_februari25;

$penjualan_februari25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_februari25_awal' and '$date_februari25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_februari25 = 0;
foreach ($penjualan_februari25 as $x){
    $total_penjualan_februari25 += $x['price'];
}

$rak_laba_rugi_februari25 = $total_penjualan_februari25 - $total_rak_februari25;
$total_presentase_rak_februari25 = ($total_penjualan_februari25!=0)?($rak_laba_rugi_februari25 / $total_penjualan_februari25) * 100:0;
$persentase_rak_februari25 = round($total_presentase_rak_februari25,2);

$date1 = $date_februari25_awal;
$date2 = $date_februari25_akhir;
$bahan_februari25 = $this->pmm_model->getBahan($date_februari25_awal,$date_februari25_akhir);
$alat_februari25 = $this->pmm_model->getAlat($date_februari25_awal,$date_februari25_akhir);
$overhead_februari25 = $this->pmm_model->getOverheadLabaRugi($date_februari25_awal,$date_februari25_akhir);
$diskonto_februari25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_februari25_awal' and '$date_februari25_akhir')")
->get()->row_array();
$diskonto_februari25 = $diskonto_februari25['total'];
$laba_rugi_februari25 = $total_penjualan_februari25 - ($bahan_februari25 + $alat_februari25 + $overhead_februari25 + $diskonto_februari25);
$total_laba_rugi_februari25 = ($total_penjualan_februari25!=0)?($laba_rugi_februari25 / $total_penjualan_februari25) * 100:0;
$persentase_laba_rugi_februari25 = round($total_laba_rugi_februari25,2);

//MARET25
$total_niai_komposisi_bahan_maret25 = $this->pmm_model->getKomposisiBahan($date_maret25_awal,$date_maret25_akhir);
$total_niai_komposisi_alat_maret25 = $this->pmm_model->getKomposisiAlat($date_maret25_awal,$date_maret25_akhir);
$total_niai_komposisi_bua_maret25 = $this->pmm_model->getKomposisiBUA($date_maret25_awal,$date_maret25_akhir);
$total_rak_maret25 = $total_niai_komposisi_bahan_maret25 + $total_niai_komposisi_alat_maret25 + $total_niai_komposisi_bua_maret25;

$penjualan_maret25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_maret25_awal' and '$date_maret25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_maret25 = 0;
foreach ($penjualan_maret25 as $x){
    $total_penjualan_maret25 += $x['price'];
}

$rak_laba_rugi_maret25 = $total_penjualan_maret25 - $total_rak_maret25;
$total_presentase_rak_maret25 = ($total_penjualan_maret25!=0)?($rak_laba_rugi_maret25 / $total_penjualan_maret25) * 100:0;
$persentase_rak_maret25 = round($total_presentase_rak_maret25,2);

$date1 = $date_maret25_awal;
$date2 = $date_maret25_akhir;
$bahan_maret25 = $this->pmm_model->getBahan($date_maret25_awal,$date_maret25_akhir);
$alat_maret25 = $this->pmm_model->getAlat($date_maret25_awal,$date_maret25_akhir);
$overhead_maret25 = $this->pmm_model->getOverheadLabaRugi($date_maret25_awal,$date_maret25_akhir);
$diskonto_maret25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_maret25_awal' and '$date_maret25_akhir')")
->get()->row_array();
$diskonto_maret25 = $diskonto_maret25['total'];
$laba_rugi_maret25 = $total_penjualan_maret25 - ($bahan_maret25 + $alat_maret25 + $overhead_maret25 + $diskonto_maret25);
$total_laba_rugi_maret25 = ($total_penjualan_maret25!=0)?($laba_rugi_maret25 / $total_penjualan_maret25) * 100:0;
$persentase_laba_rugi_maret25 = round($total_laba_rugi_maret25,2);

//APRIL25
$total_niai_komposisi_bahan_april25 = $this->pmm_model->getKomposisiBahan($date_april25_awal,$date_april25_akhir);
$total_niai_komposisi_alat_april25 = $this->pmm_model->getKomposisiAlat($date_april25_awal,$date_april25_akhir);
$total_niai_komposisi_bua_april25 = $this->pmm_model->getKomposisiBUA($date_april25_awal,$date_april25_akhir);
$total_rak_april25 = $total_niai_komposisi_bahan_april25 + $total_niai_komposisi_alat_april25 + $total_niai_komposisi_bua_april25;

$penjualan_april25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_april25_awal' and '$date_april25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_april25 = 0;
foreach ($penjualan_april25 as $x){
    $total_penjualan_april25 += $x['price'];
}

$rak_laba_rugi_april25 = $total_penjualan_april25 - $total_rak_april25;
$total_presentase_rak_april25 = ($total_penjualan_april25!=0)?($rak_laba_rugi_april25 / $total_penjualan_april25) * 100:0;
$persentase_rak_april25 = round($total_presentase_rak_april25,2);

$date1 = $date_april25_awal;
$date2 = $date_april25_akhir;
$bahan_april25 = $this->pmm_model->getBahan($date_april25_awal,$date_april25_akhir);
$alat_april25 = $this->pmm_model->getAlat($date_april25_awal,$date_april25_akhir);
$overhead_april25 = $this->pmm_model->getOverheadLabaRugi($date_april25_awal,$date_april25_akhir);
$diskonto_april25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_april25_awal' and '$date_april25_akhir')")
->get()->row_array();
$diskonto_april25 = $diskonto_april25['total'];
$laba_rugi_april25 = $total_penjualan_april25 - ($bahan_april25 + $alat_april25 + $overhead_april25 + $diskonto_april25);
$total_laba_rugi_april25 = ($total_penjualan_april25!=0)?($laba_rugi_april25 / $total_penjualan_april25) * 100:0;
$persentase_laba_rugi_april25 = round($total_laba_rugi_april25,2);

//MEI25
$total_niai_komposisi_bahan_mei25 = $this->pmm_model->getKomposisiBahan($date_mei25_awal,$date_mei25_akhir);
$total_niai_komposisi_alat_mei25 = $this->pmm_model->getKomposisiAlat($date_mei25_awal,$date_mei25_akhir);
$total_niai_komposisi_bua_mei25 = $this->pmm_model->getKomposisiBUA($date_mei25_awal,$date_mei25_akhir);
$total_rak_mei25 = $total_niai_komposisi_bahan_mei25 + $total_niai_komposisi_alat_mei25 + $total_niai_komposisi_bua_mei25;

$penjualan_mei25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_mei25_awal' and '$date_mei25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_mei25 = 0;
foreach ($penjualan_mei25 as $x){
    $total_penjualan_mei25 += $x['price'];
}

$rak_laba_rugi_mei25 = $total_penjualan_mei25 - $total_rak_mei25;
$total_presentase_rak_mei25 = ($total_penjualan_mei25!=0)?($rak_laba_rugi_mei25 / $total_penjualan_mei25) * 100:0;
$persentase_rak_mei25 = round($total_presentase_rak_mei25,2);

$date1 = $date_mei25_awal;
$date2 = $date_mei25_akhir;
$bahan_mei25 = $this->pmm_model->getBahan($date_mei25_awal,$date_mei25_akhir);
$alat_mei25 = $this->pmm_model->getAlat($date_mei25_awal,$date_mei25_akhir);
$overhead_mei25 = $this->pmm_model->getOverheadLabaRugi($date_mei25_awal,$date_mei25_akhir);
$diskonto_mei25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_mei25_awal' and '$date_mei25_akhir')")
->get()->row_array();
$diskonto_mei25 = $diskonto_mei25['total'];
$laba_rugi_mei25 = $total_penjualan_mei25 - ($bahan_mei25 + $alat_mei25 + $overhead_mei25 + $diskonto_mei25);
$total_laba_rugi_mei25 = ($total_penjualan_mei25!=0)?($laba_rugi_mei25 / $total_penjualan_mei25) * 100:0;
$persentase_laba_rugi_mei25 = round($total_laba_rugi_mei25,2);

//JUNI25
$total_niai_komposisi_bahan_juni25 = $this->pmm_model->getKomposisiBahan($date_juni25_awal,$date_juni25_akhir);
$total_niai_komposisi_alat_juni25 = $this->pmm_model->getKomposisiAlat($date_juni25_awal,$date_juni25_akhir);
$total_niai_komposisi_bua_juni25 = $this->pmm_model->getKomposisiBUA($date_juni25_awal,$date_juni25_akhir);
$total_rak_juni25 = $total_niai_komposisi_bahan_juni25 + $total_niai_komposisi_alat_juni25 + $total_niai_komposisi_bua_juni25;

$penjualan_juni25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juni25_awal' and '$date_juni25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_juni25 = 0;
foreach ($penjualan_juni25 as $x){
    $total_penjualan_juni25 += $x['price'];
}

$rak_laba_rugi_juni25 = $total_penjualan_juni25 - $total_rak_juni25;
$total_presentase_rak_juni25 = ($total_penjualan_juni25!=0)?($rak_laba_rugi_juni25 / $total_penjualan_juni25) * 100:0;
$persentase_rak_juni25 = round($total_presentase_rak_juni25,2);

$date1 = $date_juni25_awal;
$date2 = $date_juni25_akhir;
$bahan_juni25 = $this->pmm_model->getBahan($date_juni25_awal,$date_juni25_akhir);
$alat_juni25 = $this->pmm_model->getAlat($date_juni25_awal,$date_juni25_akhir);
$overhead_juni25 = $this->pmm_model->getOverheadLabaRugi($date_juni25_awal,$date_juni25_akhir);
$diskonto_juni25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juni25_awal' and '$date_juni25_akhir')")
->get()->row_array();
$diskonto_juni25 = $diskonto_juni25['total'];
$laba_rugi_juni25 = $total_penjualan_juni25 - ($bahan_juni25 + $alat_juni25 + $overhead_juni25 + $diskonto_juni25);
$total_laba_rugi_juni25 = ($total_penjualan_juni25!=0)?($laba_rugi_juni25 / $total_penjualan_juni25) * 100:0;
$persentase_laba_rugi_juni25 = round($total_laba_rugi_juni25,2);

//JULI25
$total_niai_komposisi_bahan_juli25 = $this->pmm_model->getKomposisiBahan($date_juli25_awal,$date_juli25_akhir);
$total_niai_komposisi_alat_juli25 = $this->pmm_model->getKomposisiAlat($date_juli25_awal,$date_juli25_akhir);
$total_niai_komposisi_bua_juli25 = $this->pmm_model->getKomposisiBUA($date_juli25_awal,$date_juli25_akhir);
$total_rak_juli25 = $total_niai_komposisi_bahan_juli25 + $total_niai_komposisi_alat_juli25 + $total_niai_komposisi_bua_juli25;

$penjualan_juli25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juli25_awal' and '$date_juli25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_juli25 = 0;
foreach ($penjualan_juli25 as $x){
    $total_penjualan_juli25 += $x['price'];
}

$rak_laba_rugi_juli25 = $total_penjualan_juli25 - $total_rak_juli25;
$total_presentase_rak_juli25 = ($total_penjualan_juli25!=0)?($rak_laba_rugi_juli25 / $total_penjualan_juli25) * 100:0;
$persentase_rak_juli25 = round($total_presentase_rak_juli25,2);

$date1 = $date_juli25_awal;
$date2 = $date_juli25_akhir;
$bahan_juli25 = $this->pmm_model->getBahan($date_juli25_awal,$date_juli25_akhir);
$alat_juli25 = $this->pmm_model->getAlat($date_juli25_awal,$date_juli25_akhir);
$overhead_juli25 = $this->pmm_model->getOverheadLabaRugi($date_juli25_awal,$date_juli25_akhir);
$diskonto_juli25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juli25_awal' and '$date_juli25_akhir')")
->get()->row_array();
$diskonto_juli25 = $diskonto_juli25['total'];
$laba_rugi_juli25 = $total_penjualan_juli25 - ($bahan_juli25 + $alat_juli25 + $overhead_juli25 + $diskonto_juli25);
$total_laba_rugi_juli25 = ($total_penjualan_juli25!=0)?($laba_rugi_juli25 / $total_penjualan_juli25) * 100:0;
$persentase_laba_rugi_juli25 = round($total_laba_rugi_juli25,2);

//AGUSTUS25
$total_niai_komposisi_bahan_agustus25 = $this->pmm_model->getKomposisiBahan($date_agustus25_awal,$date_agustus25_akhir);
$total_niai_komposisi_alat_agustus25 = $this->pmm_model->getKomposisiAlat($date_agustus25_awal,$date_agustus25_akhir);
$total_niai_komposisi_bua_agustus25 = $this->pmm_model->getKomposisiBUA($date_agustus25_awal,$date_agustus25_akhir);
$total_rak_agustus25 = $total_niai_komposisi_bahan_agustus25 + $total_niai_komposisi_alat_agustus25 + $total_niai_komposisi_bua_agustus25;

$penjualan_agustus25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_agustus25_awal' and '$date_agustus25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_agustus25 = 0;
foreach ($penjualan_agustus25 as $x){
    $total_penjualan_agustus25 += $x['price'];
}

$rak_laba_rugi_agustus25 = $total_penjualan_agustus25 - $total_rak_agustus25;
$total_presentase_rak_agustus25 = ($total_penjualan_agustus25!=0)?($rak_laba_rugi_agustus25 / $total_penjualan_agustus25) * 100:0;
$persentase_rak_agustus25 = round($total_presentase_rak_agustus25,2);

$date1 = $date_agustus25_awal;
$date2 = $date_agustus25_akhir;
$bahan_agustus25 = $this->pmm_model->getBahan($date_agustus25_awal,$date_agustus25_akhir);
$alat_agustus25 = $this->pmm_model->getAlat($date_agustus25_awal,$date_agustus25_akhir);
$overhead_agustus25 = $this->pmm_model->getOverheadLabaRugi($date_agustus25_awal,$date_agustus25_akhir);
$diskonto_agustus25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_agustus25_awal' and '$date_agustus25_akhir')")
->get()->row_array();
$diskonto_agustus25 = $diskonto_agustus25['total'];
$laba_rugi_agustus25 = $total_penjualan_agustus25 - ($bahan_agustus25 + $alat_agustus25 + $overhead_agustus25 + $diskonto_agustus25);
$total_laba_rugi_agustus25 = ($total_penjualan_agustus25!=0)?($laba_rugi_agustus25 / $total_penjualan_agustus25) * 100:0;
$persentase_laba_rugi_agustus25 = round($total_laba_rugi_agustus25,2);

//SEPTEMBER25
$total_niai_komposisi_bahan_september25 = $this->pmm_model->getKomposisiBahan($date_september25_awal,$date_september25_akhir);
$total_niai_komposisi_alat_september25 = $this->pmm_model->getKomposisiAlat($date_september25_awal,$date_september25_akhir);
$total_niai_komposisi_bua_september25 = $this->pmm_model->getKomposisiBUA($date_september25_awal,$date_september25_akhir);
$total_rak_september25 = $total_niai_komposisi_bahan_september25 + $total_niai_komposisi_alat_september25 + $total_niai_komposisi_bua_september25;

$penjualan_september25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_september25_awal' and '$date_september25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_september25 = 0;
foreach ($penjualan_september25 as $x){
    $total_penjualan_september25 += $x['price'];
}

$rak_laba_rugi_september25 = $total_penjualan_september25 - $total_rak_september25;
$total_presentase_rak_september25 = ($total_penjualan_september25!=0)?($rak_laba_rugi_september25 / $total_penjualan_september25) * 100:0;
$persentase_rak_september25 = round($total_presentase_rak_september25,2);

$date1 = $date_september25_awal;
$date2 = $date_september25_akhir;
$bahan_september25 = $this->pmm_model->getBahan($date_september25_awal,$date_september25_akhir);
$alat_september25 = $this->pmm_model->getAlat($date_september25_awal,$date_september25_akhir);
$overhead_september25 = $this->pmm_model->getOverheadLabaRugi($date_september25_awal,$date_september25_akhir);
$diskonto_september25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_september25_awal' and '$date_september25_akhir')")
->get()->row_array();
$diskonto_september25 = $diskonto_september25['total'];
$laba_rugi_september25 = $total_penjualan_september25 - ($bahan_september25 + $alat_september25 + $overhead_september25 + $diskonto_september25);
$total_laba_rugi_september25 = ($total_penjualan_september25!=0)?($laba_rugi_september25 / $total_penjualan_september25) * 100:0;
$persentase_laba_rugi_september25 = round($total_laba_rugi_september25,2);

//OKTOBER25
$total_niai_komposisi_bahan_oktober25 = $this->pmm_model->getKomposisiBahan($date_oktober25_awal,$date_oktober25_akhir);
$total_niai_komposisi_alat_oktober25 = $this->pmm_model->getKomposisiAlat($date_oktober25_awal,$date_oktober25_akhir);
$total_niai_komposisi_bua_oktober25 = $this->pmm_model->getKomposisiBUA($date_oktober25_awal,$date_oktober25_akhir);
$total_rak_oktober25 = $total_niai_komposisi_bahan_oktober25 + $total_niai_komposisi_alat_oktober25 + $total_niai_komposisi_bua_oktober25;

$penjualan_oktober25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_oktober25_awal' and '$date_oktober25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_oktober25 = 0;
foreach ($penjualan_oktober25 as $x){
    $total_penjualan_oktober25 += $x['price'];
}

$rak_laba_rugi_oktober25 = $total_penjualan_oktober25 - $total_rak_oktober25;
$total_presentase_rak_oktober25 = ($total_penjualan_oktober25!=0)?($rak_laba_rugi_oktober25 / $total_penjualan_oktober25) * 100:0;
$persentase_rak_oktober25 = round($total_presentase_rak_oktober25,2);

$date1 = $date_oktober25_awal;
$date2 = $date_oktober25_akhir;
$bahan_oktober25 = $this->pmm_model->getBahan($date_oktober25_awal,$date_oktober25_akhir);
$alat_oktober25 = $this->pmm_model->getAlat($date_oktober25_awal,$date_oktober25_akhir);
$overhead_oktober25 = $this->pmm_model->getOverheadLabaRugi($date_oktober25_awal,$date_oktober25_akhir);
$diskonto_oktober25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_oktober25_awal' and '$date_oktober25_akhir')")
->get()->row_array();
$diskonto_oktober25 = $diskonto_oktober25['total'];
$laba_rugi_oktober25 = $total_penjualan_oktober25 - ($bahan_oktober25 + $alat_oktober25 + $overhead_oktober25 + $diskonto_oktober25);
$total_laba_rugi_oktober25 = ($total_penjualan_oktober25!=0)?($laba_rugi_oktober25 / $total_penjualan_oktober25) * 100:0;
$persentase_laba_rugi_oktober25 = round($total_laba_rugi_oktober25,2);

//NOVEMBER25
$total_niai_komposisi_bahan_november25 = $this->pmm_model->getKomposisiBahan($date_november25_awal,$date_november25_akhir);
$total_niai_komposisi_alat_november25 = $this->pmm_model->getKomposisiAlat($date_november25_awal,$date_november25_akhir);
$total_niai_komposisi_bua_november25 = $this->pmm_model->getKomposisiBUA($date_november25_awal,$date_november25_akhir);
$total_rak_november25 = $total_niai_komposisi_bahan_november25 + $total_niai_komposisi_alat_november25 + $total_niai_komposisi_bua_november25;

$penjualan_november25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_november25_awal' and '$date_november25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_november25 = 0;
foreach ($penjualan_november25 as $x){
    $total_penjualan_november25 += $x['price'];
}

$rak_laba_rugi_november25 = $total_penjualan_november25 - $total_rak_november25;
$total_presentase_rak_november25 = ($total_penjualan_november25!=0)?($rak_laba_rugi_november25 / $total_penjualan_november25) * 100:0;
$persentase_rak_november25 = round($total_presentase_rak_november25,2);

$date1 = $date_november25_awal;
$date2 = $date_november25_akhir;
$bahan_november25 = $this->pmm_model->getBahan($date_november25_awal,$date_november25_akhir);
$alat_november25 = $this->pmm_model->getAlat($date_november25_awal,$date_november25_akhir);
$overhead_november25 = $this->pmm_model->getOverheadLabaRugi($date_november25_awal,$date_november25_akhir);
$diskonto_november25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_november25_awal' and '$date_november25_akhir')")
->get()->row_array();
$diskonto_november25 = $diskonto_november25['total'];
$laba_rugi_november25 = $total_penjualan_november25 - ($bahan_november25 + $alat_november25 + $overhead_november25 + $diskonto_november25);
$total_laba_rugi_november25 = ($total_penjualan_november25!=0)?($laba_rugi_november25 / $total_penjualan_november25) * 100:0;
$persentase_laba_rugi_november25 = round($total_laba_rugi_november25,2);

//DESEMBER25
$total_niai_komposisi_bahan_desember25 = $this->pmm_model->getKomposisiBahan($date_desember25_awal,$date_desember25_akhir);
$total_niai_komposisi_alat_desember25 = $this->pmm_model->getKomposisiAlat($date_desember25_awal,$date_desember25_akhir);
$total_niai_komposisi_bua_desember25 = $this->pmm_model->getKomposisiBUA($date_desember25_awal,$date_desember25_akhir);
$total_rak_desember25 = $total_niai_komposisi_bahan_desember25 + $total_niai_komposisi_alat_desember25 + $total_niai_komposisi_bua_desember25;

$penjualan_desember25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_desember25_awal' and '$date_desember25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_desember25 = 0;
foreach ($penjualan_desember25 as $x){
    $total_penjualan_desember25 += $x['price'];
}

$rak_laba_rugi_desember25 = $total_penjualan_desember25 - $total_rak_desember25;
$total_presentase_rak_desember25 = ($total_penjualan_desember25!=0)?($rak_laba_rugi_desember25 / $total_penjualan_desember25) * 100:0;
$persentase_rak_desember25 = round($total_presentase_rak_desember25,2);

$date1 = $date_desember25_awal;
$date2 = $date_desember25_akhir;
$bahan_desember25 = $this->pmm_model->getBahan($date_desember25_awal,$date_desember25_akhir);
$alat_desember25 = $this->pmm_model->getAlat($date_desember25_awal,$date_desember25_akhir);
$overhead_desember25 = $this->pmm_model->getOverheadLabaRugi($date_desember25_awal,$date_desember25_akhir);
$diskonto_desember25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_desember25_awal' and '$date_desember25_akhir')")
->get()->row_array();
$diskonto_desember25 = $diskonto_desember25['total'];
$laba_rugi_desember25 = $total_penjualan_desember25 - ($bahan_desember25 + $alat_desember25 + $overhead_desember25 + $diskonto_desember25);
$total_laba_rugi_desember25 = ($total_penjualan_desember25!=0)?($laba_rugi_desember25 / $total_penjualan_desember25) * 100:0;
$persentase_laba_rugi_desember25 = round($total_laba_rugi_desember25,2);

//REALISASI PER MINGGU
$rencana_kerja_now = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja <= '$date_now'")
->order_by('r.id','desc')->limit(1)
->get()->row_array();

$date_01_month = date('Y-m-01', strtotime($date_now));

$date_dashboard_1 = $this->db->select('d.*')
->from('date_dashboard d')
->where("d.date >= '$date_01_month'")
->order_by('d.id','asc')->limit(1)
->get()->row_array();

$date_dashboard_2 = $this->db->select('d.*')
->from('date_dashboard d')
->where("d.date >= '$date_01_month'")
->order_by('d.id','asc')->limit(1,1)
->get()->row_array();

$date_dashboard_3 = $this->db->select('d.*')
->from('date_dashboard d')
->where("d.date >= '$date_01_month'")
->order_by('d.id','asc')->limit(1,2)
->get()->row_array();

$date_dashboard_4 = $this->db->select('d.*')
->from('date_dashboard d')
->where("d.date >= '$date_01_month'")
->order_by('d.id','asc')->limit(1,3)
->get()->row_array();

$rencana_kerja_perminggu = $rencana_kerja_now['total_produksi'] / 4;
$rencana_kerja_perminggu_fix = round($rencana_kerja_perminggu,2);

$date_minggu_1_awal = date('Y-m-01', strtotime($date_now));
$date_minggu_1_akhir = date('Y-m-d', strtotime($date_dashboard_1['date']));

$penjualan_minggu_1 = $this->db->select('SUM(pp.display_price) as total, SUM(pp.display_volume) as volume')
->from('pmm_productions pp')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_minggu_1_awal' and '$date_minggu_1_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->row_array();

$penjualan_minggu_1 = $penjualan_minggu_1['volume'];
$penjualan_minggu_1_fix = round($penjualan_minggu_1,2);

$date_minggu_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_minggu_1_akhir)));
$date_minggu_2_akhir = date('Y-m-d', strtotime($date_dashboard_2['date']));

$penjualan_minggu_2 = $this->db->select('SUM(pp.display_price) as total, SUM(pp.display_volume) as volume')
->from('pmm_productions pp')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_minggu_2_awal' and '$date_minggu_2_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->row_array();

$penjualan_minggu_2 = $penjualan_minggu_2['volume'];
$penjualan_minggu_2_fix = round($penjualan_minggu_2,2);

$date_minggu_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_minggu_2_akhir)));
$date_minggu_3_akhir = date('Y-m-d', strtotime($date_dashboard_3['date']));

$penjualan_minggu_3 = $this->db->select('SUM(pp.display_price) as total, SUM(pp.display_volume) as volume')
->from('pmm_productions pp')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_minggu_3_awal' and '$date_minggu_3_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->row_array();

$penjualan_minggu_3 = $penjualan_minggu_3['volume'];
$penjualan_minggu_3_fix = round($penjualan_minggu_3,2);

$date_minggu_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_minggu_3_akhir)));
$date_minggu_4_akhir = date('Y-m-d', strtotime($date_dashboard_4['date']));

$penjualan_minggu_4 = $this->db->select('SUM(pp.display_price) as total, SUM(pp.display_volume) as volume')
->from('pmm_productions pp')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_minggu_4_awal' and '$date_minggu_4_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->row_array();

$penjualan_minggu_4 = $penjualan_minggu_4['volume'];
$penjualan_minggu_4_fix = round($penjualan_minggu_4,2);
?>
    