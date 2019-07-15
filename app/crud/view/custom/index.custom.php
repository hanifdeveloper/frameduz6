<body>
<style>
	table, form { font-size: 10pt; }
    .table-empty, .table-content { display: none; }
</style>
<!-- Header -->
<?php $this->getView('crud', 'main', 'header', $header); ?>
<!-- Content -->
<main role="main">
    <div class="container">
        <div class="py-5">
            <div id="crud-project" class="card">
                <div class="card-header bg-light">
                    <form class="frmData" onsubmit="return false;" autocomplete="off">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="cari"><small>Cari User : </small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                                    </div>
                                    <?= comp\BOOTSTRAP::inputText('cari', 'text', '', 'class="form-control" placeholder="..."'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="jenis"><small>Jenis Kelamin : </small></label>
                                <?= comp\BOOTSTRAP::inputSelect('jenis', $pilihan_jenis_kelamin, '', 'class="form-control custom-select" style="cursor: pointer;"'); ?>
                            </div>
                            <div class="col-md-4">
                                <br><button type="button" class="btn btn-dark btn-form">Tambah Data</button>
                            </div>
                        </div>
                        <?= comp\BOOTSTRAP::inputKey('page', '1'); ?>
                    </form>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="spinner-border table-loader" role="status"><span class="sr-only">Loading...</span></div>
                    </div><br>
                    <div class="table-content">
                        <?php $this->getView('crud', 'custom', 'template', 'tabel'); ?>
                    </div>
                    <div class="jumbotron jumbotron-fluid text-center table-empty">
                        <div class="container">
                            <h5>Data tidak ditemukan</h5>
                            <p class="lead">Kata kunci yang Anda masukan tidak ditemukan dalam database</p>
                        </div>
                    </div>
                </div>
                <!-- Form Modal -->
                <div class="modal fade form-modal-input" id="" tabindex="-1" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title form-modal-title"></h4>
                            </div>
                            <form class="frmInput form-horizontal" onsubmit="return false;" autocomplete="off">
                                <div class="modal-body">
                                    <div class="form-modal-content" style="padding: 10px;">
                                        <?php $this->getView('crud', 'custom', 'template', 'form'); ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="form-modal-loader">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Sedang menyimpan data ..
                                    </div>
                                    <button type="button" class="btn btn-effect-ripple btn-danger form-modal-button" data-dismiss="modal">Tutup</button>
                                    <button button="submit" class="btn btn-effect-ripple btn-primary form-modal-button"><i class="fa fa-check"></i> Simpan</button><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Script -->
<?= $jsPath; ?>
<script src="<?= $api_path.'/script'; ?>"></script>
<script src="<?= $url_path.'/script'; ?>"></script>
</body>