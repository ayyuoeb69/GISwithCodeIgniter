<div class="container" style="padding-bottom: 50px">
	<div class="row">
		<h1 class="jdl-relawan">Lihat Data</h1> <h4 style="color: #A1A3B7;font-family: myf2;font-weight: bold"><?= $d_laporan->total ?> Laporan ( <?= $d_simpan->total ?> Disimpan Sementara, <?= $d_kirim->total ?> Terkirim, <?= $d_proses->total ?> Proses Verifikasi Admin, <?= $d_terima->total ?> Diterima, <?= $d_tolak->total ?> Ditolak )</h4>

		<div class="col-sm-12">
			<h3 style="color: #A1A3B7;font-family: myf2;margin-top:53px;margin-bottom: 30px">Data Simpan Sementara</h3>
			<hr>
			<table id="simpan" class="table table-responsive table-striped">
				<thead>
					<tr>
						<th>No</th>
						<th>Penanda</th>
						<th>ID Titik</th>
						<th>Waktu Simpan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; foreach ($simpan as $s) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $s->penanda ?></td>
							<td><?= $s->id_kel_sungai ?></td>
							<td><?= $s->waktu_tambah ?></td>
							<td>
								<a href="<?= base_url('relawan/edit_simpan/').$s->id_kel_sungai ?>">
									<button class="btn btn-info">EDIT</button>
								</a>
								<a href="<?= base_url('relawan/hapus_simpan/').$s->id_kel_sungai ?>">
									<button class="btn btn-danger" onclick="return Confirm();">HAPUS</button>
								</a>
							</td>
						</tr>
						<?php $i++; }  ?>
					</tbody>
				</table>
			</div>
			<div class="col-sm-12">
				<h3 style="color: #A1A3B7;font-family: myf2;margin-top:43px">Data Terkirim ( Verifikasi Admin )</h3>
				<hr>
				<table id="kirim" class="table table-responsive table-striped nowrap">
					<thead>
						<tr>
							<th>No</th>
							<th>Penanda Lokasi</th>
							<th>ID Titik</th>
							<th>Status Titik</th>
							<th>Waktu Kirim / Edit</th>
							<th>Status Laporan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; foreach ($kirim as $k) {
							?>
							<tr>
								<td><?= $i ?></td>
								<td><?= $k->penanda ?></td>
								<td><?= $k->id_kel_sungai ?></td>
								<td>
									<?php if($k->status_titik == 'Biru'){?>
										<span class="label label-primary">Baik</span>
									<?php }else if($k->status_titik == 'Kuning'){ ?>
										<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>
									<?php }else if($k->status_titik == 'Orange'){ ?>
										<span class="label label-warning">Cemar Sedang</span>
									<?php }else if($k->status_titik == 'Merah'){ ?>
										<span class="label label-danger">Cemar Berat</span>
									<?php } ?>

								</td>
								<td><?= $k->waktu_tambah ?></td>
								<td><?php if($k->status_setuju == 0){
									echo "Proses Verifikasi Admin";
								}else if($k->status_setuju == 1){
									echo "Diterima";
								}elseif ($k->status_setuju == 2) {
									echo "Ditolak";
								} ?></td>
								<td>
									<a href="<?= base_url('relawan/edit_simpan/').$k->id_kel_sungai ?>">
										<button class="btn btn-info">EDIT</button>
									</a>
									<button class="btn btn-warning" data-toggle="modal" data-target="#myModal" onclick="modal('<?= $k->id_kel_sungai ?>')">DETAIL</button>

									<a href="<?= base_url('relawan/hapus_kirim/').$k->id_kel_sungai ?>">

										<button class="btn btn-danger" onclick="return Confirm();">HAPUS</button>
									</a>
								</td>
							</tr>
							<?php $i++; }  ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-12">
					<h3 style="color: #A1A3B7;font-family: myf2;margin-top:43px;float: left">Data Terkirim ( Data Diterima )</h3>
					<br>
					<a href="<?= base_url('relawan/cetak') ?>" style="margin-bottom: :40px">
						<button id="btn-profile" data-toggle="modal" data-target="#modalInfo" class="btn" style="margin-top:35px;background-color: #115a68;float: right;"><span class="glyphicon glyphicon-print" style="margin-right: 5px;"></span> Print PDF</button>
					</a>
					<br>
					<br>
					<br>
					<table id="terima" class="table table-responsive table-striped nowrap" style="float: none">
						<thead>
							<tr>
								<th>No</th>
								<th>Penanda Lokasi</th>
								<th>ID Titik</th>
								<th>Status Titik</th>
								<th>Waktu Kirim / Edit</th>
								<th>Status Laporan</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; foreach ($terima as $k) {
								?>
								<tr>
									<td><?= $i ?></td>
									<td><?= $k->penanda ?></td>
									<td><?= $k->id_kel_sungai ?></td>
									<td>
										<?php if($k->status_titik == 'Biru'){?>
											<span class="label label-primary">Baik</span>
										<?php }else if($k->status_titik == 'Kuning'){ ?>
											<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>
										<?php }else if($k->status_titik == 'Orange'){ ?>
											<span class="label label-warning">Cemar Sedang</span>
										<?php }else if($k->status_titik == 'Merah'){ ?>
											<span class="label label-danger">Cemar Berat</span>
										<?php } ?>

									</td>
									<td><?= $k->waktu_tambah ?></td>
									<td><?php if($k->status_setuju == 0){
										echo "Proses Verifikasi Admin";
									}else if($k->status_setuju == 1){
										echo "Diterima";
									}elseif ($k->status_setuju == 2) {
										echo "Ditolak";
									} ?></td>
									<td>
										<a href="<?= base_url('relawan/edit_simpan/').$k->id_kel_sungai ?>">
											<button class="btn btn-info">EDIT</button>
										</a>
										<button class="btn btn-warning" data-toggle="modal" data-target="#myModal" onclick="modal('<?= $k->id_kel_sungai ?>')">DETAIL</button>

										<a href="<?= base_url('relawan/hapus_kirim/').$k->id_kel_sungai ?>">

											<button class="btn btn-danger" onclick="return Confirm();">HAPUS</button>
										</a>
									</td>
								</tr>
								<?php $i++; }  ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Detail</h4>
						</div>
						<div class="modal-body">

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>

				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function() {
					$('#simpan').DataTable({});
					$('#kirim').DataTable({"scrollX": true});
					$('#terima').DataTable({"scrollX": true});
				} );
			</script>
			<script type="text/javascript">
				function modal(id){
					var  a;
					var i;
					var b;
					var c;
					var d ='<br>';
					var j =1;
					var e;
					var status_titik;
					$.ajax({
						url : "<?php echo base_url('relawan/tampil_modal/')?>/" + id,
						type: "GET",
						dataType: "JSON",
						success: function(data)
						{
							if(data[0].status_setuju == 0){ a = "Proses Verifikasi Admin"; }else if(data[0].status_setuju == 1){a = "Diterima"; }else if(data[0].status_setuju == 2){ a="Ditolak"; }
							if(data[0].waktu_setuju == null){e="Belum Disetujui";}else{e=data[0].waktu_setuju;}
							for(i=0;i<data.length;i++){
								if(data[0].status_titik == 'Biru'){
									status_titik = '<span class="label label-primary">Baik</span>'
								}else if(data[0].status_titik == 'Kuning'){
									status_titik = '<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>'
								}else if(data[0].status_titik == 'Orange'){
									status_titik = '<span class="label label-warning">Cemar Sedang</span>'
								}else if(data[0].status_titik == 'Merah'){
									status_titik = '<span class="label label-danger">Cemar Berat</span>'
								}
								if(data[0].foto != null){
									foto = '<img class="img-responsive" src="<?= base_url('assets/upload/') ?>'+data[0].foto+'">';
								}else{
									foto = 'Tidak Ada Foto';
								}
								if(data[0].lampiran != null){
									lampiran = '<img class="img-responsive" src="<?= base_url('assets/upload/') ?>'+data[0].lampiran+'">';
								}else{
									lampiran = 'Tidak Ada Lampiran';
								}
								var b = '<div class="row">'+							
								'<div class="col-sm-5"><label><b>Temperatur / Suhu Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].temperatur+' celcius </div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>EC / DHL / Daya Hantar Listrik Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].ec+' mhos/cm </div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+							
								'<div class="col-sm-5"><label><b>TDS / Partikel Terlarut Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].tds+' mg/l </div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>pH / Derajat Keasaman Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].ph+' mg/l</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>DO / Oksigen Terlarut Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].do+' mg/l </div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>BOD Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].bod+' mg/l </div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+							
								'<div class="col-sm-5"><label><b>E-Coli Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].ecoli+' MPN/100 ml </div>';

								var c = '</div>'+
								'<br>'+
								'<hr style="border-color: #67B1B7"><br>';
								d += (b + c);
								j++;
							}
							$( ".modal-body" )
							.html('<div class="row">'+
								'<div class="col-sm-5"><label><b>Penanda Lokasi: </b></label></div><div class="col-sm-7">'+data[0].penanda+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+							
								'<div class="col-sm-5"><label><b>ID Titik Lokasi: </b></label></div><div class="col-sm-7">'+data[0].id_kel_sungai+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>Status Sungai : </b></label></div><div class="col-sm-7">'+status_titik+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+							
								'<div class="col-sm-5"><label><b>Waktu Kirim / Edit : </b></label></div><div class="col-sm-7">'+data[0].waktu_tambah+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>Status Laporan : </b></label></div><div class="col-sm-7">'+a+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>Foto Lampiran Uji Lab: </b></label></div><div class="col-sm-7">'+lampiran+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>Foto Sungai : </b></label></div><div class="col-sm-7">'+foto+'</div>'+
								'</div>'+
								'<br>'+
								d+
								'<div class="row">'+
								'<div class="col-sm-5"><label><b>Keterangan Sungai : </b></label></div><div class="col-sm-7">'+data[0].keterangan+'</div>'+
								'</div>'+
								'<br>'+
								'<div class="row">'+							
								'<div class="col-sm-5"><label><b>Waktu Setuju: </b></label></div><div class="col-sm-7">'+e+'</div>'+
								'</div>');


						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error');
						}
					});
}
function Confirm() {
	var x=confirm("Apakah anda yakin akan menghapus data ini?")
	if (x) {
		return true;
	} else {
		return false;
	}
}
</script>
