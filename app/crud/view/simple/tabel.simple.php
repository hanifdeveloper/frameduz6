<?= $query; ?>
<?php if($jumlah > 0){ ?>
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
				<th width="150px"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($tabel as $kol){
			echo '<tr>';
			echo '<td>'.$no++.'</td>';
			echo '<td><img src="'.$kol['foto_user'].'" class="img-responsive thumbnail" width="100px" alt=""></td>';
			echo '<td>'.$kol['nama_user'].'</td>';
			echo '<td>'.$kol['jenis_kelamin'].'</td>';
			echo '<td>'.$kol['alamat_user'].'</td>';
			echo '<td>'.$kol['hobby_user'].'</td>';
			echo '<td>
					<button data-toggle="tooltip" data-placement="top" title="Ubah Data" id="'.$kol['id_user'].'" class="btn btn-default btn-form"><i class="fa fa-edit"></i></button>
					<button data-toggle="tooltip" data-placement="top" title="Hapus Data" id="'.$kol['id_user'].'" class="btn btn-default btn-delete"><i class="fa fa-trash"></i></button>
					</td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
</div>
<?= comp\BOOTSTRAP::pagging($page, $batas, $jumlah); ?>
<?php } else{
	echo '<div class="jumbotron jumbotron-fluid text-center table-empty">
			<div class="container">
				<h5>Data tidak ditemukan</h5>
				<p class="lead">Kata kunci yang Anda masukan tidak ditemukan dalam database</p>
			</div>
		</div>';
} ?>
