<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        body {
            font-family: helvetica;
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
                            <h3 class="section-subtitle"><b>EDIT PROFILE</b></h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('admin/edit');?>" data-redirect="<?php echo site_url('admin/admin');?>">
                                <input type="hidden" name="id" value="<?php echo $row[0]->admin_id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="admin_name" name="admin_name" value="<?php echo $row[0]->admin_name;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Admin Group<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_admin->SelectAdminGroup($row[0]->admin_group_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Password<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="password" name="admin_password" class="form-control" id="password" data-required="false"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Email<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="email" name="admin_email" class="form-control" value="<?php echo $row[0]->admin_email;?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Photo</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control" name="admin_photo" id="admin_photo_val" value="<?php echo $row[0]->admin_photo;?>" />
                                            <input type="text" id="admin_photo" class="form-control" data-required="false" value="<?php echo $row[0]->admin_photo;?>" />
                                            <span class="input-group-btn">
                                            <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=admin_photo" >Browse</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <?php
                                        if(!empty($row[0]->admin_photo)){
                                            ?>
                                            <div id="box-content_admin_photo">
                                                <img id="admin_photo_prev" src="<?php echo base_url().$row[0]->admin_photo;?>" class="img-responsive" />
                                            </div>
                                            <?php
                                        }else {
                                            ?>
                                            <div id="box-content_admin_photo">
                                                <img id="admin_photo_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php $this->general->SelectStatus();?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <center><label for="name" class="control-label">APPROVAL</label></center>
                                </div>

                                <?php
                                $id = $row[0]->admin_id;
                                $approval = $this->db->select('*')
                                ->from('tbl_admin')
                                ->where("admin_id = $id ")
                                ->get()->row_array();
                                $menu_admin =  $approval['menu_admin'];
                                $approval_penawaran_pembelian =  $approval['approval_penawaran_pembelian'];
                                $delete_penawaran_pembelian =  $approval['delete_penawaran_pembelian'];
                                $approval_permintaan_bahan_alat =  $approval['approval_permintaan_bahan_alat'];
                                $delete_permintaan_bahan_alat =  $approval['delete_permintaan_bahan_alat'];
                                $approval_po =  $approval['approval_po'];
                                $delete_po =  $approval['delete_po'];
                                $surat_jalan_pembelian =  $approval['surat_jalan_pembelian'];
                                $verifikasi =  $approval['verifikasi'];
                                $delete_tagihan_pembelian =  $approval['delete_tagihan_pembelian'];
                                $approval_penawaran_penjualan =  $approval['approval_penawaran_penjualan'];
                                $delete_penawaran_penjualan =  $approval['delete_penawaran_penjualan'];
                                $approval_so_penjualan =  $approval['approval_so_penjualan'];
                                $delete_so_penjualan =  $approval['delete_so_penjualan'];
                                $surat_jalan_penjualan =  $approval['surat_jalan_penjualan'];
                                ?>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Menu Admin<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="menu_admin" id="menu_admin" value="1"<?= (isset($menu_admin) && $menu_admin == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Approval Penawaran Pembelian<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="approval_penawaran_pembelian" id="approval_penawaran_pembelian" value="1"<?= (isset($approval_penawaran_pembelian) && $approval_penawaran_pembelian == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Delete Penawaran Pembelian<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_penawaran_pembelian" id="delete_penawaran_pembelian" value="1"<?= (isset($delete_penawaran_pembelian) && $delete_penawaran_pembelian == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Approval Permintaan Bahan & Alat<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="approval_permintaan_bahan_alat" id="approval_permintaan_bahan_alat" value="1"<?= (isset($approval_permintaan_bahan_alat) && $approval_permintaan_bahan_alat == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Delete Permintaan Bahan & Alat<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_permintaan_bahan_alat" id="delete_permintaan_bahan_alat" value="1"<?= (isset($delete_permintaan_bahan_alat) && $delete_permintaan_bahan_alat == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Approval PO Pembelian<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="approval_po" id="approval_po" value="1"<?= (isset($approval_po) && $approval_po == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Delete PO Pembelian<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_po" id="delete_po" value="1"<?= (isset($delete_po) && $delete_po == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Hapus Surat Jalan Pembelian<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="surat_jalan_pembelian" id="surat_jalan_pembelian" value="1"<?= (isset($surat_jalan_pembelian) && $surat_jalan_pembelian == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Verifikasi Tagihan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="verifikasi" id="verifikasi" value="1"<?= (isset($verifikasi) && $verifikasi == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Hapus Tagihan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_tagihan_pembelian" id="delete_tagihan_pembelian" value="1"<?= (isset($delete_tagihan_pembelian) && $delete_tagihan_pembelian == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Approval Penawaran Penjualan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="approval_penawaran_penjualan" id="approval_penawaran_penjualan" value="1"<?= (isset($approval_penawaran_penjualan) && $approval_penawaran_penjualan == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Delete Penawaran Penjualan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_penawaran_penjualan" id="delete_penawaran_penjualan" value="1"<?= (isset($delete_penawaran_penjualan) && $delete_penawaran_penjualan == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Approval SO Penjualan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="approval_so_penjualan" id="approval_so_penjualan" value="1"<?= (isset($approval_so_penjualan) && $approval_so_penjualan == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Delete SO Penjualan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="delete_so_penjualan" id="delete_so_penjualan" value="1"<?= (isset($delete_so_penjualan) && $delete_so_penjualan == 1) ? 'checked' : '' ;?> />
                                    </div>
                                    <label for="name" class="col-sm-2 control-label">Hapus Surat Jalan Penjualan<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="surat_jalan_penjualan" id="surat_jalan_penjualan" value="1"<?= (isset($surat_jalan_penjualan) && $surat_jalan_penjualan == 1) ? 'checked' : '' ;?> />
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div>
                                        <br />
                                        <br />
                                        <a href="<?php echo site_url('admin/admin'); ?>" class="btn btn-danger" style="width:5%; font-weight:bold; border-radius:10px; margin-top: 10px;"> KEMBALI</a>
                                        <button type="submit" name="submit" class="btn btn-success" id="btn-submit" data-loading-text="please wait.." style="width:5%; font-weight:bold; border-radius:10px;">KIRIM</button>
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

	<?php echo $this->Templates->Footer();?>


    
    <script type="text/javascript">
        $(document).ready(function() {
            load_table("<?php echo site_url($row[0]->menu_alias.'/table');?>");
        });
    </script>

</body>
</html>
