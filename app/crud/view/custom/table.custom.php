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
				<th width="150px">Total : <span class="count">0</span></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{no}</td>
				<td><img src="{foto_user}" class="img-responsive thumbnail" width="100px" alt=""></td>
				<td>{nama_user}</td>
				<td>{jenis_kelamin}</td>
				<td>{alamat_user}</td>
				<td>{hobby_user}</td>
				<td>
					<button data-toggle="tooltip" data-placement="top" title="Ubah Data" id="{id_user}" class="btn btn-default btn-form"><i class="fa fa-edit"></i></button>
					<button data-toggle="tooltip" data-placement="top" title="Hapus Data" id="{id_user}" class="btn btn-default btn-delete"><i class="fa fa-trash"></i></button>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="card-body">
	<div class="table-pagging" style="border-top: 1px solid #ccc; padding-top: 10px;">
		<ul class="pagination">
			<li class="pagging page-item" number-page="" style="cursor: pointer;"><span class="page-link">{page}</span></li>
		</ul>
	</div>
</div>