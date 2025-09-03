<form action="<?php echo site_url('kunci/cetak_kunci'); ?>" target="_blank">
    <div class="col-sm-3">
        <input type="text" id="filter_date_pemakaian" name="filter_date" class="form-control dtpickerangepemakaian" autocomplete="off" placeholder="Filter By Date">
    </div>
    <!--<div class="col-sm-1">
        <button type="submit" class="btn btn-default" style="border-radius:5px; font-weight:bold;">PRINT</button>
    </div>-->
</form>
<?php
$admin_id = $this->session->userdata('admin_id');
$approval = $this->db->select('*')
->from('tbl_admin')
->where("admin_id = $admin_id ")
->get()->row_array();
$kunci_rakor =  $approval['kunci_rakor'];
?>
<?php if($kunci_rakor == 1){?>
    <div class="col-sm-5">
    <button style="background-color:#88b93c; border-radius:5px; line-height:30px;"><a href="<?php echo site_url('kunci/form_rakor'); ?>"><b style="color:white;">BUAT KUNCI</b></a></button>
    </div>
<?php
}
?>
<br />
<br />
<div class="table-responsive">
    <table class="table table-striped table-hover table-center" id="table-rakor" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>