<body>
<style>
	table, form { font-size: 10pt; }
</style>
<!-- Header -->
<?php $this->getView('crud', 'main', 'header', $header); ?>
<!-- Modal -->
<?php $this->getView('crud', 'main', 'modal', 'form'); ?>
<!-- Content -->
<main role="main">
    <div class="container">
        <div class="py-5">
            <div class="err_message"></div>
            <div id="crud_project" class="card">
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
                <div class="table-content">
                    <?php $this->template('tabel'); ?>
                </div>
                <div class="card-body">
                    <div class="query" style="font-size:10pt;"></div>
                    <div class="text-center">
                        <div class="spinner-border table-loader" role="status"><span class="sr-only">Loading...</span></div>
                    </div><br>
                    <div class="jumbotron jumbotron-fluid text-center table-empty">
                        <div class="container">
                            <h5>Data tidak ditemukan</h5>
                            <p class="lead">Kata kunci yang Anda masukan tidak ditemukan dalam database</p>
                        </div>
                    </div>
                </div>
                <div class="form-content">
                    <?php $this->template('form'); ?>
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