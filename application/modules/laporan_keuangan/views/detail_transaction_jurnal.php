<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
    </style>
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
                                    <button style="color:white; background-color:#5bc0de; border:1px solid black; border-radius:10px; line-height:30px;"><b>KEMBALI KE LAPORAN KEUANGAN</b></button></a>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr style='background-color:#cccccc; font-weight:bold;'>
                                                <th class="text-center">Klik Untuk Melihat Transaksi</th>
                                            </tr>
                                            
                                            <tr>
                                                <?php
                                                foreach ($row_biaya as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pmm/biaya/detail_biaya/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_jurnal as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pmm/jurnal_umum/detailJurnal/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_production as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("penjualan/detail_surat_jalan/".$x['id']) ?>"><?php echo $x['no_production'];?></a></td>
                                                <?php
                                                }
                                                ?>     

                                                <?php
                                                foreach ($row_tagihan as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("penjualan/detailPenagihan/".$x['id']) ?>"><?php echo $x['nomor_invoice'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_pembayaran as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("penjualan/view_pembayaran/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_receipt as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pembelian/detail_surat_jalan/".$x['id']) ?>"><?php echo $x['surat_jalan'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_tagihan_pembelian as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pembelian/penagihan_pembelian_detail/".$x['id']) ?>"><?php echo $x['nomor_invoice'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_pembayaran_pembelian as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pembelian/view_pembayaran_pembelian/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_terima as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pmm/finance/detailTerima/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                foreach ($row_transfer as $x) {
                                                ?>
                                                <td class="text-center"><a target="_blank" href="<?= base_url("pmm/finance/detailTransfer/".$x['id']) ?>"><?php echo $x['nomor_transaksi'];?></a></td>
                                                <?php
                                                }
                                                ?>
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
