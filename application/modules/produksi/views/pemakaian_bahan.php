<form action="<?php echo site_url('produksi/cetak_stock_opname'); ?>" target="_blank">
    <div class="col-sm-3">
        <input type="text" id="filter_date_pemakaian_bahan" name="filter_date" class="form-control dtpickerangepemakaianbahan" autocomplete="off" placeholder="Filter By Date">
    </div>
    <!--<div class="col-sm-1">
        <button type="submit" class="btn btn-default" style="border-radius:5px; font-weight:bold;">PRINT</button>
    </div>-->
</form>
<div class="col-sm-5">
<button style="background-color:#88b93c; border-radius:5px;"><a href="<?php echo site_url('produksi/form_pemakaian_bahan'); ?>"><b style="color:white;">BUAT PEMAKAIAN BAHAN</b></a></button>
</div>
<br />
<br />
<div class="table-responsive">
    <table class="table table-striped table-hover table-center" id="table-pemakaian-bahan" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Bahan</th>
                <th>Volume</th>
                <th>Nilai</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>