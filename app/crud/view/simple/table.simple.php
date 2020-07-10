<?php if($count > 0): ?>
<div class="table-responsive">
	<table class="table table-default table-hover">
		<thead>
			<tr>
				<th width="50px">#</th>
				<th>Foto</th>
				<th>Nama</th>
				<th>Jenis Kelamin</th>
				<th>Alamat</th>
				<th>Hobby</th>
				<th width="150px">Total : <?= $count.' '.$label; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($table as $key => $value) {
			echo '<tr>';
			echo '<td>'.$no++.'</td>';
			echo '<td><img src="'.$value['foto_user'].'" class="img-responsive thumbnail" width="100px" alt=""></td>';
			echo '<td>'.$value['nama_user'].'</td>';
			echo '<td>'.$value['jenis_kelamin'].'</td>';
			echo '<td>'.$value['alamat_user'].'</td>';
			echo '<td>'.$value['hobby_user'].'</td>';
			echo '<td>
					<button data-toggle="tooltip" data-placement="top" title="Ubah Data" id="'.$value['id_user'].'" class="btn btn-default btn-form"><i class="fa fa-edit"></i></button>
					<button data-toggle="tooltip" data-placement="top" title="Hapus Data" id="'.$value['id_user'].'" class="btn btn-default btn-delete"><i class="fa fa-trash"></i></button>
					</td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
</div>
<div class="card-body">
<?= comp\BOOTSTRAP::pagging($page, $limit, $count); ?>
</div>
<?php else: ?>
<div class="card-body">
	<div class="jumbotron jumbotron-fluid text-center table-empty">
		<div class="container">
			<h5>Data tidak ditemukan</h5>
			<p class="lead">Kata kunci yang Anda masukan tidak ditemukan dalam database</p>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="card-body">
<?= ($query != '') ? '<div style="font-size:10pt;"><code>'.$query.'</code></div>': ''; ?>
</div>