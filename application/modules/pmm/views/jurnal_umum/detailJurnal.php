<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        body{
			font-family: helvetica;
	  	}
        .table-center th, .table-center td{
            text-align:center;
        }
    </style>
</head>

<body>
    <div class="wrap">
        
        <?php echo $this->Templates->PageHeader();?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar();?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                            <li>
                                <a href="<?php echo site_url('admin/jurnal_umum');?>"> Jurnal Umum</a></li>
                            <li><a>Detail Jurnal</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <h3 >Detail Jurnal</h3>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr>
                                                <th width="30%">Nomor Transaksi</th>
                                                <th width="2%">:</th>
                                                <td width="68%"> <?= $detail["nomor_transaksi"] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Transaksi</th>
                                                <th>:</th>
                                                <td> <?= date('d F Y',strtotime($detail["tanggal_transaksi"])) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dibuat Oleh</th>
                                                <th>:</th>
                                                <td><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$detail['created_by']),'admin_name');?></td>
                                            </tr>
                                            <tr>
                                                <th>Dibuat Tanggal</th>
                                                <th>:</th>
                                                <td><?= date('d/m/Y H:i:s',strtotime($detail['created_on']));?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Akun Biaya</th>
                                                <th>Deskripsi</th>
                                                <th>Debit</th>
                                                <th>Kredit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no=1; 
                                            $debit = 0;
                                            $kredit = 0;
                                            ?>
                                            <?php foreach($detailBiaya as $d) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td class="text-left">
                                                <?= $d["coa"]; ?>
                                                </td>
                                                <td class="text-left">
                                                <?= $d["deskripsi"]; ?>
                                                </td>
                                                <td class="text-right">
                                                <?= $this->filter->Rupiah($d['debit']);?>
                                                </td>
                                                <td class="text-right">
                                                <?= $this->filter->Rupiah($d['kredit']);?>
                                                </td>
                                            </tr>
                                            <?php
                                            $debit += $d['debit'];
                                            $kredit += $d['kredit']; 
                                            endforeach;
                                             ?>
                                        </tbody>
                                        <tfoot style="text-align: right;">
                                            <tr>
                                                <th colspan="3" align="right">TOTAL</th>
                                                <th ><?= $this->filter->Rupiah($debit);?></th>
                                                <th ><?= $this->filter->Rupiah($kredit);?></th>
                                            </tr>
                                        </tfoot>
                                    </table>    
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table  table-condensed">
                                            <tr>
                                                <th width="30%">Memo</th>
                                                <th width="2%">:</th>
                                                <td> <?= $detail['memo'];?></td>
                                            </tr>
                                            <tr>
                                                <th>Lampiran</th>
                                                <th>:</th>
                                                <td> 
                                                    <?php
                                                    $lampiran = $this->db->get_where('pmm_lampiran_jurnal',array('jurnal_id'=>$detail['id']))->result_array();
                                                    if(!empty($lampiran)){
                                                        foreach ($lampiran as $key => $lam) {
                                                            ?>
                                                            <a href="<?= base_url().'uploads/jurnal_umum/'.$lam['lampiran'];?>" target="_blank"><?= $lam['lampiran'];?></a><br />
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <a href="<?= base_url('admin/jurnal_umum') ?>" class="btn btn-info" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 15){
                                            ?>
                                            <?php if($detail["status"] === "UNPAID") : ?>
                                                <a href="<?= base_url("pmm/Jurnal_umum/approvalJurnal/".$detail["id"]) ?>" class="btn btn-success"><i class="fa fa-check" style="width:10%; font-weight:bold; border-radius:10px;"></i> Approve</a>
                                                <a href="<?= base_url("pmm/Jurnal_umum/rejectedJurnal/".$detail["id"]) ?>"class="btn btn-primary"><i class="fa fa-close" style="width:10%; font-weight:bold; border-radius:10px;"></i> Reject</a>
                                            <?php endif; ?>
                                            <?php
                                            }
                                            ?>

                                            <?php if($detail["status"] === "PAID") : ?>
                                                <a target="_blank" href="<?= base_url('pmm/jurnal_umum/cetakJurnal/'.$detail["id"]) ?>" class="btn btn-default" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-print"></i> Print</a>
                                                <?php
                                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 16){
                                                ?>
                                                <a  href="<?= base_url('pmm/jurnal_umum/form/'.$detail['id']) ?>" class="btn btn-primary" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-edit"></i> Edit</a>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 16){
                                                ?>
                                                <a class="btn btn-danger" style="width:10%; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('pmm/jurnal_umum/delete/'.$detail['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                                <?php
                                                }
                                                ?>
                                            <?php endif; ?>
                                            
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
    
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">
        
        function DeleteData(href)
        {
            bootbox.confirm({
                message: "Apakah anda yakin untuk proses data ini ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        window.location.href = href;
                    }
                    
                }
            });
        }
    </script>


</body>
</html>
