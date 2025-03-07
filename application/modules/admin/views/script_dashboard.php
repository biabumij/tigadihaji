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
$date_juni24_awal = date('2024-06-01');
$date_juni24_akhir = date('2024-06-30');
$date_juli24_awal = date('2024-07-01');
$date_juli24_akhir = date('2024-07-31');
$date_agustus24_awal = date('2024-08-01');
$date_agustus24_akhir = date('2024-08-31');
$date_september24_awal = date('2024-09-01');
$date_september24_akhir = date('2024-09-30');
$date_oktober24_awal = date('2024-10-01');
$date_oktober24_akhir = date('2024-10-31');
$date_november24_awal = date('2024-11-01');
$date_november24_akhir = date('2024-11-30');
$date_desember24_awal = date('2024-12-01');
$date_desember24_akhir = date('2024-12-31');
$date_akumulasi_awal = date('2024-01-01');
$date_akumulasi_akhir = date('2024-12-31');
$date_januari25_awal = date('2025-01-01');
$date_januari25_akhir = date('2025-01-31');
$date_februari25_awal = date('2025-02-01');
$date_februari25_akhir = date('2025-02-28');


//REALISASI PRODUKSI
//JUNI24
$rak_juni24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir'")
->get()->row_array();
$rencana_produksi_juni24 = $rak_juni24['total_produksi'];

$penjualan_juni24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juni24_awal' and '$date_juni24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_juni24 = 0;
foreach ($penjualan_juni24 as $x){
    $total_volume_penjualan_juni24 += $x['volume'];
}
$realisasi_produksi_juni24 = $total_volume_penjualan_juni24;

//JULI24
$rak_juli24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir'")
->get()->row_array();
$rencana_produksi_juli24 = $rak_juli24['total_produksi'];

$penjualan_juli24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juli24_awal' and '$date_juli24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_juli24 = 0;
foreach ($penjualan_juli24 as $x){
    $total_volume_penjualan_juli24 += $x['volume'];
}
$realisasi_produksi_juli24 = $total_volume_penjualan_juli24;

//AGUSTUS24
$rak_agustus24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir'")
->get()->row_array();
$rencana_produksi_agustus24 = $rak_agustus24['total_produksi'];

$penjualan_agustus24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_agustus24_awal' and '$date_agustus24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_agustus24 = 0;
foreach ($penjualan_agustus24 as $x){
    $total_volume_penjualan_agustus24 += $x['volume'];
}
$realisasi_produksi_agustus24 = $total_volume_penjualan_agustus24;

//SEPTEMBER24
$rak_september24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir'")
->get()->row_array();
$rencana_produksi_september24 = $rak_september24['total_produksi'];

$penjualan_september24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_september24_awal' and '$date_september24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_september24 = 0;
foreach ($penjualan_september24 as $x){
    $total_volume_penjualan_september24 += $x['volume'];
}
$realisasi_produksi_september24 = $total_volume_penjualan_september24;

//OKTOBER24
$rak_oktober24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir'")
->get()->row_array();
$rencana_produksi_oktober24 = $rak_oktober24['total_produksi'];

$penjualan_oktober24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_oktober24_awal' and '$date_oktober24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_oktober24 = 0;
foreach ($penjualan_oktober24 as $x){
    $total_volume_penjualan_oktober24 += $x['volume'];
}
$realisasi_produksi_oktober24 = $total_volume_penjualan_oktober24;

//NOVEMBER24
$rak_november24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir'")
->get()->row_array();
$rencana_produksi_november24 = $rak_november24['total_produksi'];

$penjualan_november24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_november24_awal' and '$date_november24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_november24 = 0;
foreach ($penjualan_november24 as $x){
    $total_volume_penjualan_november24 += $x['volume'];
}
$realisasi_produksi_november24 = $total_volume_penjualan_november24;

//DESEMBER24
$rak_desember24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir'")
->get()->row_array();
$rencana_produksi_desember24 = $rak_desember24['total_produksi'];

$penjualan_desember24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_desember24_awal' and '$date_desember24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_desember24 = 0;
foreach ($penjualan_desember24 as $x){
    $total_volume_penjualan_desember24 += $x['volume'];
}
$realisasi_produksi_desember24 = $total_volume_penjualan_desember24;

//JANUARI25
$rak_januari25 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_e + r.vol_produk_f) as total_produksi')
->from('rak r')
->where("r.tanggal_rencana_kerja between '$date_januari25_awal' and '$date_januari25_akhir'")
->get()->row_array();
$rencana_produksi_januari25 = $rak_januari25['total_produksi'];

$penjualan_januari25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_januari25_awal' and '$date_januari25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_volume_penjualan_januari25 = 0;
foreach ($penjualan_januari25 as $x){
    $total_volume_penjualan_januari25 += $x['volume'];
}
$realisasi_produksi_januari25 = $total_volume_penjualan_januari25;

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

//LABA RUGI
//JUNI24
$rak_juni24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_juni24_awal' and '$date_juni24_akhir')")
->get()->row_array();
$nilai_produk_a_juni24 = $rak_juni24['vol_produk_a'] * $rak_juni24['price_a'];
$nilai_produk_b_juni24 = $rak_juni24['vol_produk_b'] * $rak_juni24['price_b'];
$nilai_produk_c_juni24 = $rak_juni24['vol_produk_c'] * $rak_juni24['price_c'];
$nilai_produk_d_juni24 = $rak_juni24['vol_produk_d'] * $rak_juni24['price_d'];
$nilai_produk_e_juni24 = $rak_juni24['vol_produk_e'] * $rak_juni24['price_e'];
$nilai_produk_f_juni24 = $rak_juni24['vol_produk_f'] * $rak_juni24['price_f'];
$nilai_rak_penjualan_juni24 = $nilai_produk_a_juni24 + $nilai_produk_b_juni24 + $nilai_produk_c_juni24 + $nilai_produk_d_juni24 + $nilai_produk_e_juni24 + $nilai_produk_f_juni24;

$total_niai_komposisi_bahan_juni24 = $this->pmm_model->getKomposisiBahan($date_juni24_awal,$date_juni24_akhir);
$total_niai_komposisi_alat_juni24 = $this->pmm_model->getKomposisiAlat($date_juni24_awal,$date_juni24_akhir);
$total_niai_komposisi_bua_juni24 = $this->pmm_model->getKomposisiBUA($date_juni24_awal,$date_juni24_akhir);
$total_rak_juni24 = $total_niai_komposisi_bahan_juni24 + $total_niai_komposisi_alat_juni24 + $total_niai_komposisi_bua_juni24;
$total_presentase_rak_juni24 = $total_rak_juni24 / $nilai_rak_penjualan_juni24;
$persentase_rak_juni24 = round($total_presentase_rak_juni24,2);

$penjualan_juni24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juni24_awal' and '$date_juni24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_juni24 = 0;
foreach ($penjualan_juni24 as $x){
    $total_penjualan_juni24 += $x['price'];
}

$date1 = $date_juni24_awal;
$date2 = $date_juni24_akhir;
$bahan_juni24 = $this->pmm_model->getBahan($date_juni24_awal,$date_juni24_akhir);
$alat_juni24 = $this->pmm_model->getAlat($date_juni24_awal,$date_juni24_akhir);
$overhead_juni24 = $this->pmm_model->getOverheadLabaRugi($date_juni24_awal,$date_juni24_akhir);
$diskonto_juni24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juni24_awal' and '$date_juni24_akhir')")
->get()->row_array();
$diskonto_juni24 = $diskonto_juni24['total'];
$laba_rugi_juni24 = $total_penjualan_juni24 - ($bahan_juni24 + $alat_juni24 + $overhead_juni24 + $diskonto_juni24);
$total_laba_rugi_juni24 = ($total_penjualan_juni24!=0)?($laba_rugi_juni24 / $total_penjualan_juni24) * 100:0;
$persentase_laba_rugi_juni24 = round($total_laba_rugi_juni24,2);

//JULI24
$rak_juli24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_juli24_awal' and '$date_juli24_akhir')")
->get()->row_array();
$nilai_produk_a_juli24 = $rak_juli24['vol_produk_a'] * $rak_juli24['price_a'];
$nilai_produk_b_juli24 = $rak_juli24['vol_produk_b'] * $rak_juli24['price_b'];
$nilai_produk_c_juli24 = $rak_juli24['vol_produk_c'] * $rak_juli24['price_c'];
$nilai_produk_d_juli24 = $rak_juli24['vol_produk_d'] * $rak_juli24['price_d'];
$nilai_produk_e_juli24 = $rak_juli24['vol_produk_e'] * $rak_juli24['price_e'];
$nilai_produk_f_juli24 = $rak_juli24['vol_produk_f'] * $rak_juli24['price_f'];
$nilai_rak_penjualan_juli24 = $nilai_produk_a_juli24 + $nilai_produk_b_juli24 + $nilai_produk_c_juli24 + $nilai_produk_d_juli24 + $nilai_produk_e_juli24 + $nilai_produk_f_juli24;

$total_niai_komposisi_bahan_juli24 = $this->pmm_model->getKomposisiBahan($date_juli24_awal,$date_juli24_akhir);
$total_niai_komposisi_alat_juli24 = $this->pmm_model->getKomposisiAlat($date_juli24_awal,$date_juli24_akhir);
$total_niai_komposisi_bua_juli24 = $this->pmm_model->getKomposisiBUA($date_juli24_awal,$date_juli24_akhir);
$total_rak_juli24 = $total_niai_komposisi_bahan_juli24 + $total_niai_komposisi_alat_juli24 + $total_niai_komposisi_bua_juli24;
$total_presentase_rak_juli24 = $total_rak_juli24 / $nilai_rak_penjualan_juli24;
$persentase_rak_juli24 = round($total_presentase_rak_juli24,2);

$penjualan_juli24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_juli24_awal' and '$date_juli24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_juli24 = 0;
foreach ($penjualan_juli24 as $x){
    $total_penjualan_juli24 += $x['price'];
}

$date1 = $date_juli24_awal;
$date2 = $date_juli24_akhir;
$bahan_juli24 = $this->pmm_model->getBahan($date_juli24_awal,$date_juli24_akhir);
$alat_juli24 = $this->pmm_model->getAlat($date_juli24_awal,$date_juli24_akhir);
$overhead_juli24 = $this->pmm_model->getOverheadLabaRugi($date_juli24_awal,$date_juli24_akhir);
$diskonto_juli24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juli24_awal' and '$date_juli24_akhir')")
->get()->row_array();
$diskonto_juli24 = $diskonto_juli24['total'];
$laba_rugi_juli24 = $total_penjualan_juli24 - ($bahan_juli24 + $alat_juli24 + $overhead_juli24 + $diskonto_juli24);
$total_laba_rugi_juli24 = ($total_penjualan_juli24!=0)?($laba_rugi_juli24 / $total_penjualan_juli24) * 100:0;
$persentase_laba_rugi_juli24 = round($total_laba_rugi_juli24,2);

//AGUSTUS24
$rak_agustus24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_agustus24_awal' and '$date_agustus24_akhir')")
->get()->row_array();
$nilai_produk_a_agustus24 = $rak_agustus24['vol_produk_a'] * $rak_agustus24['price_a'];
$nilai_produk_b_agustus24 = $rak_agustus24['vol_produk_b'] * $rak_agustus24['price_b'];
$nilai_produk_c_agustus24 = $rak_agustus24['vol_produk_c'] * $rak_agustus24['price_c'];
$nilai_produk_d_agustus24 = $rak_agustus24['vol_produk_d'] * $rak_agustus24['price_d'];
$nilai_produk_e_agustus24 = $rak_agustus24['vol_produk_e'] * $rak_agustus24['price_e'];
$nilai_produk_f_agustus24 = $rak_agustus24['vol_produk_f'] * $rak_agustus24['price_f'];
$nilai_rak_penjualan_agustus24 = $nilai_produk_a_agustus24 + $nilai_produk_b_agustus24 + $nilai_produk_c_agustus24 + $nilai_produk_d_agustus24 + $nilai_produk_e_agustus24 + $nilai_produk_f_agustus24;

$total_niai_komposisi_bahan_agustus24 = $this->pmm_model->getKomposisiBahan($date_agustus24_awal,$date_agustus24_akhir);
$total_niai_komposisi_alat_agustus24 = $this->pmm_model->getKomposisiAlat($date_agustus24_awal,$date_agustus24_akhir);
$total_niai_komposisi_bua_agustus24 = $this->pmm_model->getKomposisiBUA($date_agustus24_awal,$date_agustus24_akhir);
$total_rak_agustus24 = $total_niai_komposisi_bahan_agustus24 + $total_niai_komposisi_alat_agustus24 + $total_niai_komposisi_bua_agustus24;
$total_presentase_rak_agustus24 = $total_rak_agustus24 / $nilai_rak_penjualan_agustus24;
$persentase_rak_agustus24 = round($total_presentase_rak_agustus24,2);

$penjualan_agustus24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_agustus24_awal' and '$date_agustus24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_agustus24 = 0;
foreach ($penjualan_agustus24 as $x){
    $total_penjualan_agustus24 += $x['price'];
}

$date1 = $date_agustus24_awal;
$date2 = $date_agustus24_akhir;
$bahan_agustus24 = $this->pmm_model->getBahan($date_agustus24_awal,$date_agustus24_akhir);
$alat_agustus24 = $this->pmm_model->getAlat($date_agustus24_awal,$date_agustus24_akhir);
$overhead_agustus24 = $this->pmm_model->getOverheadLabaRugi($date_agustus24_awal,$date_agustus24_akhir);
$diskonto_agustus24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_agustus24_awal' and '$date_agustus24_akhir')")
->get()->row_array();
$diskonto_agustus24 = $diskonto_agustus24['total'];
$laba_rugi_agustus24 = $total_penjualan_agustus24 - ($bahan_agustus24 + $alat_agustus24 + $overhead_agustus24 + $diskonto_agustus24);
$total_laba_rugi_agustus24 = ($total_penjualan_agustus24!=0)?($laba_rugi_agustus24 / $total_penjualan_agustus24) * 100:0;
$persentase_laba_rugi_agustus24 = round($total_laba_rugi_agustus24,2);

//SEPTEMBER24
$rak_september24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_september24_awal' and '$date_september24_akhir')")
->get()->row_array();
$nilai_produk_a_september24 = $rak_september24['vol_produk_a'] * $rak_september24['price_a'];
$nilai_produk_b_september24 = $rak_september24['vol_produk_b'] * $rak_september24['price_b'];
$nilai_produk_c_september24 = $rak_september24['vol_produk_c'] * $rak_september24['price_c'];
$nilai_produk_d_september24 = $rak_september24['vol_produk_d'] * $rak_september24['price_d'];
$nilai_produk_e_september24 = $rak_september24['vol_produk_e'] * $rak_september24['price_e'];
$nilai_produk_f_september24 = $rak_september24['vol_produk_f'] * $rak_september24['price_f'];
$nilai_rak_penjualan_september24 = $nilai_produk_a_september24 + $nilai_produk_b_september24 + $nilai_produk_c_september24 + $nilai_produk_d_september24 + $nilai_produk_e_september24 + $nilai_produk_f_september24;

$total_niai_komposisi_bahan_september24 = $this->pmm_model->getKomposisiBahan($date_september24_awal,$date_september24_akhir);
$total_niai_komposisi_alat_september24 = $this->pmm_model->getKomposisiAlat($date_september24_awal,$date_september24_akhir);
$total_niai_komposisi_bua_september24 = $this->pmm_model->getKomposisiBUA($date_september24_awal,$date_september24_akhir);
$total_rak_september24 = $total_niai_komposisi_bahan_september24 + $total_niai_komposisi_alat_september24 + $total_niai_komposisi_bua_september24;
$total_presentase_rak_september24 = $total_rak_september24 / $nilai_rak_penjualan_september24;
$persentase_rak_september24 = round($total_presentase_rak_september24,2);

$penjualan_september24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_september24_awal' and '$date_september24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_september24 = 0;
foreach ($penjualan_september24 as $x){
    $total_penjualan_september24 += $x['price'];
}

$date1 = $date_september24_awal;
$date2 = $date_september24_akhir;
$bahan_september24 = $this->pmm_model->getBahan($date_september24_awal,$date_september24_akhir);
$alat_september24 = $this->pmm_model->getAlat($date_september24_awal,$date_september24_akhir);
$overhead_september24 = $this->pmm_model->getOverheadLabaRugi($date_september24_awal,$date_september24_akhir);
$diskonto_september24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_september24_awal' and '$date_september24_akhir')")
->get()->row_array();
$diskonto_september24 = $diskonto_september24['total'];
$laba_rugi_september24 = $total_penjualan_september24 - ($bahan_september24 + $alat_september24 + $overhead_september24 + $diskonto_september24);
$total_laba_rugi_september24 = ($total_penjualan_september24!=0)?($laba_rugi_september24 / $total_penjualan_september24) * 100:0;
$persentase_laba_rugi_september24 = round($total_laba_rugi_september24,2);

//OKTOBER24
$rak_oktober24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_oktober24_awal' and '$date_oktober24_akhir')")
->get()->row_array();
$nilai_produk_a_oktober24 = $rak_oktober24['vol_produk_a'] * $rak_oktober24['price_a'];
$nilai_produk_b_oktober24 = $rak_oktober24['vol_produk_b'] * $rak_oktober24['price_b'];
$nilai_produk_c_oktober24 = $rak_oktober24['vol_produk_c'] * $rak_oktober24['price_c'];
$nilai_produk_d_oktober24 = $rak_oktober24['vol_produk_d'] * $rak_oktober24['price_d'];
$nilai_produk_e_oktober24 = $rak_oktober24['vol_produk_e'] * $rak_oktober24['price_e'];
$nilai_produk_f_oktober24 = $rak_oktober24['vol_produk_f'] * $rak_oktober24['price_f'];
$nilai_rak_penjualan_oktober24 = $nilai_produk_a_oktober24 + $nilai_produk_b_oktober24 + $nilai_produk_c_oktober24 + $nilai_produk_d_oktober24 + $nilai_produk_e_oktober24 + $nilai_produk_f_oktober24;

$total_niai_komposisi_bahan_oktober24 = $this->pmm_model->getKomposisiBahan($date_oktober24_awal,$date_oktober24_akhir);
$total_niai_komposisi_alat_oktober24 = $this->pmm_model->getKomposisiAlat($date_oktober24_awal,$date_oktober24_akhir);
$total_niai_komposisi_bua_oktober24 = $this->pmm_model->getKomposisiBUA($date_oktober24_awal,$date_oktober24_akhir);
$total_rak_oktober24 = $total_niai_komposisi_bahan_oktober24 + $total_niai_komposisi_alat_oktober24 + $total_niai_komposisi_bua_oktober24;
$total_presentase_rak_oktober24 = $total_rak_oktober24 / $nilai_rak_penjualan_oktober24;
$persentase_rak_oktober24 = round($total_presentase_rak_oktober24,2);

$penjualan_oktober24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_oktober24_awal' and '$date_oktober24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_oktober24 = 0;
foreach ($penjualan_oktober24 as $x){
    $total_penjualan_oktober24 += $x['price'];
}

$date1 = $date_oktober24_awal;
$date2 = $date_oktober24_akhir;
$bahan_oktober24 = $this->pmm_model->getBahan($date_oktober24_awal,$date_oktober24_akhir);
$alat_oktober24 = $this->pmm_model->getAlat($date_oktober24_awal,$date_oktober24_akhir);
$overhead_oktober24 = $this->pmm_model->getOverheadLabaRugi($date_oktober24_awal,$date_oktober24_akhir);
$diskonto_oktober24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_oktober24_awal' and '$date_oktober24_akhir')")
->get()->row_array();
$diskonto_oktober24 = $diskonto_oktober24['total'];
$laba_rugi_oktober24 = $total_penjualan_oktober24 - ($bahan_oktober24 + $alat_oktober24 + $overhead_oktober24 + $diskonto_oktober24);
$total_laba_rugi_oktober24 = ($total_penjualan_oktober24!=0)?($laba_rugi_oktober24 / $total_penjualan_oktober24) * 100:0;
$persentase_laba_rugi_oktober24 = round($total_laba_rugi_oktober24,2);

//NOVEMBER24
$rak_november24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_november24_awal' and '$date_november24_akhir')")
->get()->row_array();
$nilai_produk_a_november24 = $rak_november24['vol_produk_a'] * $rak_november24['price_a'];
$nilai_produk_b_november24 = $rak_november24['vol_produk_b'] * $rak_november24['price_b'];
$nilai_produk_c_november24 = $rak_november24['vol_produk_c'] * $rak_november24['price_c'];
$nilai_produk_d_november24 = $rak_november24['vol_produk_d'] * $rak_november24['price_d'];
$nilai_produk_e_november24 = $rak_november24['vol_produk_e'] * $rak_november24['price_e'];
$nilai_produk_f_november24 = $rak_november24['vol_produk_f'] * $rak_november24['price_f'];
$nilai_rak_penjualan_november24 = $nilai_produk_a_november24 + $nilai_produk_b_november24 + $nilai_produk_c_november24 + $nilai_produk_d_november24 + $nilai_produk_e_november24 + $nilai_produk_f_november24;

$total_niai_komposisi_bahan_november24 = $this->pmm_model->getKomposisiBahan($date_november24_awal,$date_november24_akhir);
$total_niai_komposisi_alat_november24 = $this->pmm_model->getKomposisiAlat($date_november24_awal,$date_november24_akhir);
$total_niai_komposisi_bua_november24 = $this->pmm_model->getKomposisiBUA($date_november24_awal,$date_november24_akhir);
$total_rak_november24 = $total_niai_komposisi_bahan_november24 + $total_niai_komposisi_alat_november24 + $total_niai_komposisi_bua_november24;
$total_presentase_rak_november24 = $total_rak_november24 / $nilai_rak_penjualan_november24;
$persentase_rak_november24 = round($total_presentase_rak_november24,2);

$penjualan_november24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_november24_awal' and '$date_november24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_november24 = 0;
foreach ($penjualan_november24 as $x){
    $total_penjualan_november24 += $x['price'];
}

$date1 = $date_november24_awal;
$date2 = $date_november24_akhir;
$bahan_november24 = $this->pmm_model->getBahan($date_november24_awal,$date_november24_akhir);
$alat_november24 = $this->pmm_model->getAlat($date_november24_awal,$date_november24_akhir);
$overhead_november24 = $this->pmm_model->getOverheadLabaRugi($date_november24_awal,$date_november24_akhir);
$diskonto_november24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_november24_awal' and '$date_november24_akhir')")
->get()->row_array();
$diskonto_november24 = $diskonto_november24['total'];
$laba_rugi_november24 = $total_penjualan_november24 - ($bahan_november24 + $alat_november24 + $overhead_november24 + $diskonto_november24);
$total_laba_rugi_november24 = ($total_penjualan_november24!=0)?($laba_rugi_november24 / $total_penjualan_november24) * 100:0;
$persentase_laba_rugi_november24 = round($total_laba_rugi_november24,2);

//DESEMBER24
$rak_desember24 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_desember24_awal' and '$date_desember24_akhir')")
->get()->row_array();
$nilai_produk_a_desember24 = $rak_desember24['vol_produk_a'] * $rak_desember24['price_a'];
$nilai_produk_b_desember24 = $rak_desember24['vol_produk_b'] * $rak_desember24['price_b'];
$nilai_produk_c_desember24 = $rak_desember24['vol_produk_c'] * $rak_desember24['price_c'];
$nilai_produk_d_desember24 = $rak_desember24['vol_produk_d'] * $rak_desember24['price_d'];
$nilai_produk_e_desember24 = $rak_desember24['vol_produk_e'] * $rak_desember24['price_e'];
$nilai_produk_f_desember24 = $rak_desember24['vol_produk_f'] * $rak_desember24['price_f'];
$nilai_rak_penjualan_desember24 = $nilai_produk_a_desember24 + $nilai_produk_b_desember24 + $nilai_produk_c_desember24 + $nilai_produk_d_desember24 + $nilai_produk_e_desember24 + $nilai_produk_f_desember24;

$total_niai_komposisi_bahan_desember24 = $this->pmm_model->getKomposisiBahan($date_desember24_awal,$date_desember24_akhir);
$total_niai_komposisi_alat_desember24 = $this->pmm_model->getKomposisiAlat($date_desember24_awal,$date_desember24_akhir);
$total_niai_komposisi_bua_desember24 = $this->pmm_model->getKomposisiBUA($date_desember24_awal,$date_desember24_akhir);
$total_rak_desember24 = $total_niai_komposisi_bahan_desember24 + $total_niai_komposisi_alat_desember24 + $total_niai_komposisi_bua_desember24;
$total_presentase_rak_desember24 = $total_rak_desember24 / $nilai_rak_penjualan_desember24;
$persentase_rak_desember24 = round($total_presentase_rak_desember24,2);

$penjualan_desember24 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_desember24_awal' and '$date_desember24_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_desember24 = 0;
foreach ($penjualan_desember24 as $x){
    $total_penjualan_desember24 += $x['price'];
}

$date1 = $date_desember24_awal;
$date2 = $date_desember24_akhir;
$bahan_desember24 = $this->pmm_model->getBahan($date_desember24_awal,$date_desember24_akhir);
$alat_desember24 = $this->pmm_model->getAlat($date_desember24_awal,$date_desember24_akhir);
$overhead_desember24 = $this->pmm_model->getOverheadLabaRugi($date_desember24_awal,$date_desember24_akhir);
$diskonto_desember24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_desember24_awal' and '$date_desember24_akhir')")
->get()->row_array();
$diskonto_desember24 = $diskonto_desember24['total'];
$laba_rugi_desember24 = $total_penjualan_desember24 - ($bahan_desember24 + $alat_desember24 + $overhead_desember24 + $diskonto_desember24);
$total_laba_rugi_desember24 = ($total_penjualan_desember24!=0)?($laba_rugi_desember24 / $total_penjualan_desember24) * 100:0;
$persentase_laba_rugi_desember24 = round($total_laba_rugi_desember24,2);

//JANUARI25
$rak_januari25 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_januari25_awal' and '$date_januari25_akhir')")
->get()->row_array();
$nilai_produk_a_januari25 = $rak_januari25['vol_produk_a'] * $rak_januari25['price_a'];
$nilai_produk_b_januari25 = $rak_januari25['vol_produk_b'] * $rak_januari25['price_b'];
$nilai_produk_c_januari25 = $rak_januari25['vol_produk_c'] * $rak_januari25['price_c'];
$nilai_produk_d_januari25 = $rak_januari25['vol_produk_d'] * $rak_januari25['price_d'];
$nilai_produk_e_januari25 = $rak_januari25['vol_produk_e'] * $rak_januari25['price_e'];
$nilai_produk_f_januari25 = $rak_januari25['vol_produk_f'] * $rak_januari25['price_f'];
$nilai_rak_penjualan_januari25 = $nilai_produk_a_januari25 + $nilai_produk_b_januari25 + $nilai_produk_c_januari25 + $nilai_produk_d_januari25 + $nilai_produk_e_januari25 + $nilai_produk_f_januari25;

$total_niai_komposisi_bahan_januari25 = $this->pmm_model->getKomposisiBahan($date_januari25_awal,$date_januari25_akhir);
$total_niai_komposisi_alat_januari25 = $this->pmm_model->getKomposisiAlat($date_januari25_awal,$date_januari25_akhir);
$total_niai_komposisi_bua_januari25 = $this->pmm_model->getKomposisiBUA($date_januari25_awal,$date_januari25_akhir);
$total_rak_januari25 = $total_niai_komposisi_bahan_januari25 + $total_niai_komposisi_alat_januari25 + $total_niai_komposisi_bua_januari25;
$total_presentase_rak_januari25 = $total_rak_januari25 / $nilai_rak_penjualan_januari25;
$persentase_rak_januari25 = round($total_presentase_rak_januari25,2);

$penjualan_januari25 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
->from('pmm_productions pp')
->join('penerima p', 'pp.client_id = p.id','left')
->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
->where("pp.date_production between '$date_januari25_awal' and '$date_januari25_akhir'")
->where("pp.status = 'PUBLISH'")
->where("ppo.status in ('OPEN','CLOSED')")
->group_by("pp.client_id")
->get()->result_array();
$total_penjualan_januari25 = 0;
foreach ($penjualan_januari25 as $x){
    $total_penjualan_januari25 += $x['price'];
}

$date1 = $date_januari25_awal;
$date2 = $date_januari25_akhir;
$bahan_januari25 = $this->pmm_model->getBahan($date_januari25_awal,$date_januari25_akhir);
$alat_januari25 = $this->pmm_model->getAlat($date_januari25_awal,$date_januari25_akhir);
$overhead_januari25 = $this->pmm_model->getOverheadLabaRugi($date_januari25_awal,$date_januari25_akhir);
$diskonto_januari25 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_januari25_awal' and '$date_januari25_akhir')")
->get()->row_array();
$diskonto_januari25 = $diskonto_januari25['total'];
$laba_rugi_januari25 = $total_penjualan_januari25 - ($bahan_januari25 + $alat_januari25 + $overhead_januari25 + $diskonto_januari25);
$total_laba_rugi_januari25 = ($total_penjualan_januari25!=0)?($laba_rugi_januari25 / $total_penjualan_januari25) * 100:0;
$persentase_laba_rugi_januari25 = round($total_laba_rugi_januari25,2);

//FEBRUARI25
$rak_februari25 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_februari25_awal' and '$date_februari25_akhir')")
->get()->row_array();
$nilai_produk_a_februari25 = $rak_februari25['vol_produk_a'] * $rak_februari25['price_a'];
$nilai_produk_b_februari25 = $rak_februari25['vol_produk_b'] * $rak_februari25['price_b'];
$nilai_produk_c_februari25 = $rak_februari25['vol_produk_c'] * $rak_februari25['price_c'];
$nilai_produk_d_februari25 = $rak_februari25['vol_produk_d'] * $rak_februari25['price_d'];
$nilai_produk_e_februari25 = $rak_februari25['vol_produk_e'] * $rak_februari25['price_e'];
$nilai_produk_f_februari25 = $rak_februari25['vol_produk_f'] * $rak_februari25['price_f'];
$nilai_rak_penjualan_februari25 = $nilai_produk_a_februari25 + $nilai_produk_b_februari25 + $nilai_produk_c_februari25 + $nilai_produk_d_februari25 + $nilai_produk_e_februari25 + $nilai_produk_f_februari25;

$total_niai_komposisi_bahan_februari25 = $this->pmm_model->getKomposisiBahan($date_februari25_awal,$date_februari25_akhir);
$total_niai_komposisi_alat_februari25 = $this->pmm_model->getKomposisiAlat($date_februari25_awal,$date_februari25_akhir);
$total_niai_komposisi_bua_februari25 = $this->pmm_model->getKomposisiBUA($date_februari25_awal,$date_februari25_akhir);
$total_rak_februari25 = $total_niai_komposisi_bahan_februari25 + $total_niai_komposisi_alat_februari25 + $total_niai_komposisi_bua_februari25;
$total_presentase_rak_februari25 = $total_rak_februari25 / $nilai_rak_penjualan_februari25;
$persentase_rak_februari25 = round($total_presentase_rak_februari25,2);

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
$rak_maret25 = $this->db->select('*')
->from('rak')
->where("(tanggal_rencana_kerja between '$date_maret25_awal' and '$date_maret25_akhir')")
->get()->row_array();
$nilai_produk_a_maret25 = $rak_maret25['vol_produk_a'] * $rak_maret25['price_a'];
$nilai_produk_b_maret25 = $rak_maret25['vol_produk_b'] * $rak_maret25['price_b'];
$nilai_produk_c_maret25 = $rak_maret25['vol_produk_c'] * $rak_maret25['price_c'];
$nilai_produk_d_maret25 = $rak_maret25['vol_produk_d'] * $rak_maret25['price_d'];
$nilai_produk_e_maret25 = $rak_maret25['vol_produk_e'] * $rak_maret25['price_e'];
$nilai_produk_f_maret25 = $rak_maret25['vol_produk_f'] * $rak_maret25['price_f'];
$nilai_rak_penjualan_maret25 = $nilai_produk_a_maret25 + $nilai_produk_b_maret25 + $nilai_produk_c_maret25 + $nilai_produk_d_maret25 + $nilai_produk_e_maret25 + $nilai_produk_f_maret25;

$total_niai_komposisi_bahan_maret25 = $this->pmm_model->getKomposisiBahan($date_maret25_awal,$date_maret25_akhir);
$total_niai_komposisi_alat_maret25 = $this->pmm_model->getKomposisiAlat($date_maret25_awal,$date_maret25_akhir);
$total_niai_komposisi_bua_maret25 = $this->pmm_model->getKomposisiBUA($date_maret25_awal,$date_maret25_akhir);
$total_rak_maret25 = $total_niai_komposisi_bahan_maret25 + $total_niai_komposisi_alat_maret25 + $total_niai_komposisi_bua_maret25;
$total_presentase_rak_maret25 = $total_rak_maret25 / $nilai_rak_penjualan_maret25;
$persentase_rak_maret25 = round($total_presentase_rak_maret25,2);

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
    