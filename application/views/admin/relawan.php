<div class="container" style="padding-bottom: 40px">
	<div class="row">
		<div class="col-sm-6">
			<h1 class="jdl-relawan">Data Relawan</h1> <h4 style="color: #A1A3B7;font-family: myf2;font-weight: bold"><?= $banyak->total ?> Relawan Sungai</h4>
		</div>
		<div class="col-sm-6" style="text-align: right;padding-top: 40px">
			<button id="btn-cari" class="btn" name="kirim" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus" style="margin-right: 5px"></span> Tambah Relawan</button>
		</div>
		<div class="col-sm-12" style="padding-top: 20px">
			<h4 class="jdl-relawan">Relawan Aktif</h4>
			<table id="relawan" class="table table-responsive table-striped display">
				<thead>
					<tr>
						<th>No</th>
						<th>User</th>
						<th>Nama</th>
						<th>Banyak Kontribusi</th>
						<th>Simpan Sementara</th>
						<th>Tunggu Verifikasi</th>
						<th>Data Terkirim</th>
						<th>Diterima</th>
						<th>Ditolak</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; foreach ($info as $k) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $k->id_relawan_sub ?></td>
							<td style="text-transform: capitalize;"><?= $k->nm_relawan ?></td>
							<td><?= $k->banyak ?></td>
							<td><?= $k->simpan ?></td>
							<td><?= $k->verif ?></td>
							<td><?= $k->kirim ?></td>
							<td><?= $k->terima ?></td>
							<td><?= $k->tolak ?></td>
						</tr>
						<?php $i++; }  ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-12" style="padding-top: 20px">
			<h4 class="jdl-relawan">List Semua Relawan</h4>
			<table id="relawan2" class="table table-responsive table-striped display">
				<thead>
					<tr>
						<th>No</th>
						<th>User</th>
						<th>Nama Relawan</th>
						<th>No Handphone</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; foreach ($list as $b) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $b->id_relawan ?></td>
							<td style="text-transform: capitalize;"><?= $b->nm_relawan ?></td>
							<td><?= $b->hp ?></td>
							<td>
								<button class="btn btn-info" data-toggle="modal" data-target="#editModal" onclick="edit('<?= $b->id_relawan ?>')">EDIT</button>

								<a href="<?= base_url('admin/hapus_relawan/').$b->id_relawan ?>">

									<button class="btn btn-danger" onclick="return Confirm();">HAPUS</button>
								</a>
							</td>
						</tr>
						<?php $i++; }  ?>
					</tbody>
				</table>
			</div>
		</div>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Tambah Relawan</h4>
					</div>
					<?php echo form_open('Auth/register_relawan'); ?>
					<div class="modal-body">
						<div class="form-group">
							<label for="usr">Username: <span style="color: red">Maksimal 20 karakter</span></label>
							<input type="text" class="form-control" name="user" placeholder="Input Username" required="">
						</div>
						<br>
						<div class="form-group">
							<label for="usr">Nama: <span style="color: red">*</span></label>
							<input type="text" class="form-control" name="nama" placeholder="Input Nama" required="">
						</div>
						<br>
						<div class="form-group">
							<label for="usr">Password: <span style="color: red">*</span></label>
							<input type="password" class="form-control" name="pass" placeholder="Input Password" required="">
						</div>
						<br>
						<div class="form-group">
							<label for="usr">No Handphone: </label>
							<input type="text" class="form-control" name="hp" placeholder="Input No Handphone">
						</div>
						<br>
						<br>
						<div class="form-group" style="text-align: center;">
							<button id="btn-cari" class="btn" name="tambah" style="background-color: #1de149;"><span class="glyphicon glyphicon-plus" style="margin-right: 5px"></span> Tambah</button>
						</div> 
					</div>
					<?php echo form_close(); ?>
					<div class="modal-footer">
					</div>
				</div>

			</div>
		</div>
		<div id="editModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Relawan</h4>
					</div>
					<div class="modal-body edit-body">
						
					</div>
					<div class="modal-footer">
					</div>
				</div>

			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#relawan').DataTable();
			} );
			$(document).ready(function() {
				$('#relawan2').DataTable();
			} );
			function Confirm() {
				var x=confirm("Apakah anda yakin akan menghapus data ini?")
				if (x) {
					return true;
				} else {
					return false;
				}
			}
		</script>
		<script type="text/javascript">
			function edit(id){
				$.ajax({
					url : "<?php echo base_url('admin/tampil_edit_relawan/')?>" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{	
						var info = '<form action="<?= base_url('auth/edit_relawan/') ?>'+data.id_relawan+'" method="post" accept-charset="utf-8">'+
						'<div class="form-group">'+
							'<label for="usr">Username: <span style="color: red">Maksimal 20 karakter</span></label>'+
							'<input type="text" class="form-control" name="user" value="'+data.id_relawan+'" required="">'+
						'</div>'+
						'<br>'+
						'<div class="form-group">'+
							'<label for="usr">Nama: <span style="color: red">*</span></label>'+
							'<input type="text" class="form-control" name="nama" value="'+data.nm_relawan+'" required="">'+
						'</div>'+
						'<br>'+
						'<div class="form-group">'+
							'<label for="usr">Password ( Jika password tidak ingin diganti, harap dikosongi ):</label>'+
							'<input type="password" class="form-control" name="pass">'+
						'</div>'+
						'<br>'+
						'<div class="form-group">'+
							'<label for="usr">No Handphone: </label>'+
							'<input type="text" class="form-control" name="hp" value="'+data.hp+'">'+
						'</div>'+
						'<br>'+
						'<br>'+
						'<div class="form-group" style="text-align: center;">'+
							'<button id="btn-cari" class="btn" name="update" style="background-color: #1de149;"><span class="glyphicon glyphicon-pencil" style="margin-right: 5px"></span> Update</button>'+
						'</div> '+
						'<?php echo form_close(); ?>';
						$( ".edit-body" ).html(info);

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('erors');
					}
				});
			}
		</script>
