<?php extract($form); ?>
<div class="row">
	<div class="col-md-7">
		<label for="nama_user">Nama User</label>
		<div class="form-group">
			<div class="form-line">
				<?= comp\BOOTSTRAP::inputKey('id_user', $id_user); ?>
				<?= comp\BOOTSTRAP::inputText('nama_user', 'text', $nama_user, 'class="form-control" required'); ?>
			</div>
		</div>
		<label for="nama_user">Jenis Kelamin</label>
		<div class="form-group">
			<?= comp\BOOTSTRAP::inputRadio('jenis_kelamin', $pilihan_jenis_kelamin, $jenis_kelamin); ?>
		</div>
		<label for="nama_user">Hobby</label>
		<div class="form-group">
			<?= comp\BOOTSTRAP::inputCheckbox('hobby_user[]', $pilihan_hobby, $hobby_user); ?>
		</div>
		<label for="nama_user">Alamat</label>
		<div class="form-group">
			<div class="form-line">
				<?= comp\BOOTSTRAP::inputTextArea('alamat_user', $alamat_user, 'rows="3" class="form-control no-resize"'); ?>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<label>Foto</label>
		<div class="image-preview"><img src="<?= $foto_user; ?>" class="img-responsive thumbnail" alt="image" width="100%"></div>
		<input id="foto" class="file-image" name="foto" style="display: none;" type="file" accept="<?= $mimes_image; ?>">
		<label for="foto" class="btn btn-block btn-sm btn-dark" style="cursor: pointer; margin-top: 10px;">UPLOAD</label>
		<p class="help-block"><?= $keterangan_upload_image; ?></p>
	</div>
</div>
<script>
	$(".form-modal-title").html("<?= $form_title; ?>");
</script>