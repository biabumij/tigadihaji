<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
    </style>
    
    <?php
    // FUNGSI
    function tanggal_indo($tanggal, $cetak_hari = false)
    {
        $hari = array ( 1 =>    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                );
                
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
        //$split 	  = explode('-', $tanggal);
        //$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

        $split   = explode(' ', $tanggal);
        $tanggal = $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . '' . $tgl_indo;
        }
        return $tgl_indo;
    }
    ?>
</head>

<body>
<div class="wrap">
    <?php echo $this->Templates->PageHeader();?>
        <div class="page-body">
            <div class="content">
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                               
                                <h3>Detail Transaksi</h3>
                                <div class="text-left">
                                    <a href="<?php echo site_url('admin/laporan_keuangan');?>">
                                    <button style="color:white; background-color:#5bc0de; border:1px solid black; border-radius:5px; line-height:30px;"><b>KEMBALI KE LAPORAN KEUANGAN</b></button></a>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr style='background-color:#cccccc; font-weight:bold;'>
                                                <th class="text-center">Rekanan</th>
                                                <th class="text-center">Dokumen</th>
                                                <th class="text-center">No Surat Jalan</th>
                                                <th class="text-center">Hari</th>
                                                <th class="text-center">Tanggal</th>
                                                <th class="text-center">Produk</th>
                                                <th class="text-center">No Kendaraan</th>
                                                <th class="text-center">Volume</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-center">Nilai</th>
                                            </tr>
                                            <tr>
                                                <?php
                                                $supplier_id = $this->db->select('ppo.supplier_id as supplier_id')
                                                ->from('pmm_receipt_material prm')
                                                ->join('pmm_purchase_order ppo', 'prm.purchase_order_id = ppo.id','left')
                                                ->where('prm.id',$row['id'])
                                                ->get()->row_array();
                                                ?>
                                                <td class="text-left"><?= $this->crud_global->GetField('penerima',array('id'=>$supplier_id['supplier_id']),'nama');?></td>
                                                <td class="text-left"><a target="_blank" href="<?= base_url("uploads/surat_jalan_penerimaan/".$row['surat_jalan_file']) ?>"><?php echo $row['surat_jalan_file'];?></a></td>
                                                <td class="text-center"><?php echo $row['surat_jalan'];?></td>
                                                <?php
                                                $tanggal = date('Y-m-d', strtotime($row['date_receipt']));
                                                ?>
                                                <td class="text-center"><?php echo tanggal_indo($tanggal,true);?></td>
                                                <td class="text-center"><?php echo date('d-m-Y',strtotime($row['date_receipt']));?></td>
                                                <td class="text-center"><?= $this->crud_global->GetField('produk',array('id'=>$row['material_id']),'nama_produk');?></td>
                                                <td class="text-center"><?php echo $row['no_kendaraan'];?></td>
                                                <td class="text-right"><?php echo number_format($row['volume'],2,',','.');?></td>
                                                <td class="text-center"><?php echo $row['measure'];?></td>
                                                <td class="text-right"><?php echo number_format($row['price'],0,',','.');?></td>
                                            </tr>
                                            
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer();?>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    
</body>
</html>
