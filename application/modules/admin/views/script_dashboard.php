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

//REALISASI PRODUKSI
//JUNI24
$rak_juni24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_juli24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_agustus24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_september24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_oktober24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_november24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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
$rak_desember24 = $this->db->select('(r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d + r.vol_produk_f) as total_produksi')
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

//LABA RUGI
//JUNI24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_juni24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_juni24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juni24_awal' and '$date_juni24_akhir')")
->get()->row_array();
$diskonto_juni24 = $diskonto_juni24['total'];
$laba_rugi_juni24 = $total_penjualan_juni24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_juni24 + $diskonto_juni24);
$total_laba_rugi_juni24 = ($total_penjualan_juni24!=0)?($laba_rugi_juni24 / $total_penjualan_juni24) * 100:0;
$persentase_laba_rugi_juni24 = round($total_laba_rugi_juni24,2);

//JULI24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_juli24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_juli24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_juli24_awal' and '$date_juli24_akhir')")
->get()->row_array();
$diskonto_juli24 = $diskonto_juli24['total'];
$laba_rugi_juli24 = $total_penjualan_juli24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_juli24 + $diskonto_juli24);
$total_laba_rugi_juli24 = ($total_penjualan_juli24!=0)?($laba_rugi_juli24 / $total_penjualan_juli24) * 100:0;
$persentase_laba_rugi_juli24 = round($total_laba_rugi_juli24,2);

//AGUSTUS24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_agustus24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_agustus24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_agustus24_awal' and '$date_agustus24_akhir')")
->get()->row_array();
$diskonto_agustus24 = $diskonto_agustus24['total'];
$laba_rugi_agustus24 = $total_penjualan_agustus24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_agustus24 + $diskonto_agustus24);
$total_laba_rugi_agustus24 = ($total_penjualan_agustus24!=0)?($laba_rugi_agustus24 / $total_penjualan_agustus24) * 100:0;
$persentase_laba_rugi_agustus24 = round($total_laba_rugi_agustus24,2);

//SEPTEMBER24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_september24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_september24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_september24_awal' and '$date_september24_akhir')")
->get()->row_array();
$diskonto_september24 = $diskonto_september24['total'];
$laba_rugi_september24 = $total_penjualan_september24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_september24 + $diskonto_september24);
$total_laba_rugi_september24 = ($total_penjualan_september24!=0)?($laba_rugi_september24 / $total_penjualan_september24) * 100:0;
$persentase_laba_rugi_september24 = round($total_laba_rugi_september24,2);

//OKTOBER24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_oktober24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_oktober24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_oktober24_awal' and '$date_oktober24_akhir')")
->get()->row_array();
$diskonto_oktober24 = $diskonto_oktober24['total'];
$laba_rugi_oktober24 = $total_penjualan_oktober24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_oktober24 + $diskonto_oktober24);
$total_laba_rugi_oktober24 = ($total_penjualan_oktober24!=0)?($laba_rugi_oktober24 / $total_penjualan_oktober24) * 100:0;
$persentase_laba_rugi_oktober24 = round($total_laba_rugi_oktober24,2);

//NOVEMBER24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_november24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_november24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_november24_awal' and '$date_november24_akhir')")
->get()->row_array();
$diskonto_november24 = $diskonto_november24['total'];
$laba_rugi_november24 = $total_penjualan_november24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_november24 + $diskonto_november24);
$total_laba_rugi_november24 = ($total_penjualan_november24!=0)?($laba_rugi_november24 / $total_penjualan_november24) * 100:0;
$persentase_laba_rugi_november24 = round($total_laba_rugi_november24,2);

//DESEMBER24
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
$bahan_juni_24 = $this->pmm_model->getBahan($date1,$date2);
$alat_juni_24 = $this->pmm_model->getAlat($date1,$date2);
$overhead_desember24 = $this->pmm_model->getOverheadLabaRugi($date1,$date2);
$diskonto_desember24 = $this->db->select('sum(pdb.jumlah) as total')
->from('pmm_biaya pb ')
->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
->join('pmm_coa c','pdb.akun = c.id','left')
->where("pdb.akun = 110")
->where("pb.status = 'PAID'")
->where("(pb.tanggal_transaksi between '$date_desember24_awal' and '$date_desember24_akhir')")
->get()->row_array();
$diskonto_desember24 = $diskonto_desember24['total'];
$laba_rugi_desember24 = $total_penjualan_desember24 - ($bahan_juni_24 + $alat_juni_24 + $overhead_desember24 + $diskonto_desember24);
$total_laba_rugi_desember24 = ($total_penjualan_desember24!=0)?($laba_rugi_desember24 / $total_penjualan_desember24) * 100:0;
$persentase_laba_rugi_desember24 = round($total_laba_rugi_desember24,2);

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
    