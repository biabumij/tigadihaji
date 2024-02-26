<form action="<?php echo site_url('produksi/cetak_stock_opname'); ?>" target="_blank">
    <div class="col-sm-4">
        <input type="text" id="filter_date" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
    </div>
    <div class="col-sm-2">
        <button type="submit" class="btn btn-default" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i> Print</button>
    </div>
    <div class="col-sm-2">
        <a href="javascript:void(0);" onclick="OpenForm()" class="btn btn-info" style="border-radius:10px; font-weight:bold;"><i class="fa fa-plus"></i> Buat Stock Opname</a>
    </div>
</form>
<br />
<br />
<div class="table-responsive">
    <table class="table table-striped table-hover table-center" id="on-site-table" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Satuan</th>
                <th>Volume</th>
                <th>Catatan</th>
                <th>Lampiran</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>


<div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="Dokumen">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Buat Stock Opname</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" style="padding: 0 10px 0 20px;">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" id="date" name="date" class="form-control dtpicker" value="<?php echo date('d-m-Y'); ?>" required="">
                    </div>
                    <div class="form-group">
                        <label>Produk</label>
                        <select id="material_id" name="material_id" class="form-control" required="">
                            <option value="">Pilih Produk</option>
                            <?php
                             $this->db->where('status', 'PUBLISH');
                             $materials = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
                            foreach ($materials as $mat) {
                            ?>
                                <option value="<?php echo $mat['id']; ?>"><?php echo $mat['nama_produk']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Volume SO *</label>
                        <input type="text" id="volume" name="volume" class="form-control numberformat" required="" autocomplete="off" required="" />
                    </div>
                    <div class="form-group">
                        <label>Satuan SO *</label>
                        <select id="measure" name="measure" class="form-control" required="">
                            <option value="">Pilih Satuan</option>
                            <?php
                            $this->db->where('status', 'PUBLISH');
                            $measures = $this->db->get('pmm_measures')->result_array();
                            foreach ($measures as $mes) {
                            ?>
                                <option value="<?php echo $mes['id']; ?>"><?php echo $mes['measure_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="select_operation" name="select_operation" value="*">
                        <label>Konversi *</label>
                        <input type="text" id="convert" name="convert" class="form-control numberformat" value="1" required="" autocomplete="off" required="" />
                    </div>
                    <div class="form-group">
                        <label>Volume Konversi *</label>
                        <input type="text" id="display_volume" name="display_volume" class="form-control numberformat" required="" autocomplete="off" required="" />
                    </div>
                    <div class="form-group">
                        <label>Satuan Konversi *</label>
                        <select id="display_measure" name="display_measure" class="form-control" required="">
                            <option value="">Pilih Satuan</option>
                            <?php
                            $this->db->where('status', 'PUBLISH');
                            $measures = $this->db->get('pmm_measures')->result_array();
                            foreach ($measures as $mes) {
                            ?>
                                <option value="<?php echo $mes['id']; ?>"><?php echo $mes['measure_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea id="notes" name="notes" class="form-control" autocomplete="off" rows="5" data-required="false"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Uploads Document</label>
                        <input type="file" id="file" name="file" class="form-control" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="btn-form" style="border-radius:10px; font-weight:bold;"><i class="fa fa-send"></i> Kirim</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:10px; font-weight:bold;">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="modalDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Detail</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="modalDocSuratJalan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Upload Lampiran</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" style="padding: 0 10px 0 20px;">
                    <input type="hidden" name="id" id="id_doc_surat_jalan">
                    <div class="form-group">
                        <label>Upload Lampiran</label>
                        <input type="file" id="file" name="file" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="btn-form-doc-surat-jalan"><i class="fa fa-send"></i> Kirim</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>