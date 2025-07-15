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
                            <h4 class="section-subtitle"><b>EDIT ADMIN ACCESS</b></h4>
                            <div class="panel-actions">
                            </div>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('admin_access/edit');?>" data-redirect="<?php echo site_url('admin/admin_access');?>">
                                <input type="hidden" name="id" value="<?php echo $row[0]->admin_group_id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Admin Group<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="admin_group_name" name="admin_group_name" value="<?php echo $row[0]->admin_group_name;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Access<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_admin_access->SelectAccess($row[0]->admin_group_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php $this->general->SelectStatus($row[0]->status);?>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div>
                                        <br />
                                        <br />
                                        <a href="<?php echo site_url('admin/admin_access'); ?>" class="btn btn-danger" style="width:5%; font-weight:bold; border-radius:5px; margin-top: 10px;"> KEMBALI</a>
                                        <button type="submit" name="submit" class="btn btn-success" id="btn-submit" data-loading-text="please wait.." style="width:5%; font-weight:bold; border-radius:5px;">KIRIM</button>
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

</body>
</html>
