<?php
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
file_put_contents("D:\\test.txt", $this->db->last_query());


$total_penjualan_juni24 = 0;

foreach ($penjualan_juni24 as $x){
    $total_penjualan_juni24 += $x['price'];
}

$laba_rugi_juni24 = $total_penjualan_juni24;


?>
    