<div class="container">
	<?php echo form_open_multipart('relawan/proses_edit_simpan/'.$info[0]->id_kel_sungai); ?>
	<div class="row">
		<div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div> 
		<br>
		<div class="row">

			<div class="col-sm-2">
			</div>
			<div class="col-sm-8">
				<h1 class="jdl-relawan">Edit Data</h1>
				<br>
				<div class="form-group">
					<label for="usr">Penanda: <span style="color: red">*</span></label>
					<input type="text" class="form-control" name="tanda" value="<?= $info[0]->penanda ?>" required="">
				</div>
				<br>
				<div class="checkbox">
					<label><input style="top:-3.5px;" id="manual" type="checkbox" onclick="cek()">Apakah Anda ingin edit titik koordinat secara manual ?</label>
				</div>
				<br>
				<div class="form-group">
					<div class="col-sm-6" style="padding-left: 0px">
						<label>Latitude:</label>
						<input type="text" id="lat_m" class="form-control" name="lat_m" value="<?= $info[0]->titik_lat ?>" placeholder="Titik Latitude" readonly>
					</div>
					<div class="col-sm-6" style="padding-left: 0px">
						<label>Longitude:</label>
						<input type="text" id="lng_m" class="form-control" name="lng_m" value="<?= $info[0]->titik_lng ?>" placeholder="Titik Longitude" readonly>
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
				<div class="form-group">
					<label>Keterangan:</label>
					<input type="text" class="form-control" name="ket" value="<?= $info[0]->keterangan ?>" placeholder="Keterangan">
				</div>
				<br>
				<div class="form-group">
					<label>Lampiran:</label>
					<br>
					<?php if($info[0]->lampiran != NULL){ ?>
						<img src="<?= base_url('assets/upload/'.$info[0]->lampiran) ?>" style="width: 300px">
						<br>
						<br>
					<?php } ?>
					
					<input type="file" name="lampiran">
				</div>
				<br> 
				<div class="form-group">
					<label>Foto:</label>
					<br>
					<?php if($info[0]->foto != NULL){ ?>
						<img src="<?= base_url('assets/upload/'.$info[0]->foto) ?>" style="width: 300px">
						<br>
						<br>
					<?php } ?>
					
					<input type="file" name="foto">
				</div> 
				<br>
				<div class="form-group">
					<label>Banyak Percobaan:</label>
					<input id="banyak" type="number" name="banyak" placeholder="Input Banyak Percobaan" value="<?= $info[0]->banyak ?>" class="form-control" readonly>

					<div class="form-group">

					</div> 
				</div> 
				<br>
				<br>
				<?php $jml = $info[0]->banyak;$j=1;for($i=0;$i<$jml;$i++){ ?>
					<input type="hidden" class="form-control" name="id_titik<?= $i ?>" value="<?= $info[$i]->id_titik_sungai ?>">
					<h3 style="color: #67B1B7;font-family: myf2;font-weight:bold;margin-top:35px">Percobaan <?= $j ?></h3>
					<hr style="border-color: #67B1B7">
					<br>
					<h3 style="color: #A1A3B7;font-family: myf2;margin-top:0px">Secara Fisika</h3>
					<hr>
					<div class="form-group">
						<label>Temperatur ( celcius ):</label>
						<input type="text" class="form-control" name="temp<?= $i ?>" value="<?= $info[$i]->temperatur ?>">
					</div>
					<br>
					<div class="form-group">
						<label>EC / DHL ( mhos/cm ):</label>
						<input type="text" class="form-control" name="ec<?= $i ?>" value="<?= $info[$i]->ec ?>">
					</div> 
					<br>
					<div class="form-group">
						<label>TDS ( mg/l ):</label>
						<input type="text" class="form-control" name="tds<?= $i ?>" value="<?= $info[$i]->tds ?>">
					</div> 
					<h3 style="color: #A1A3B7;font-family: myf2;margin-top:30px">Secara Kimia</h3>
					<hr>
					<div class="form-group">
						<label>pH ( mg/l ):</label>
						<input type="text" class="form-control" name="ph<?= $i ?>" value="<?= $info[$i]->ph ?>">
					</div>
					<h3 style="color: #A1A3B7;font-family: myf2;margin-top:30px">Secara Biologi</h3>
					<hr>
					<div class="form-group">
						<label>DO ( mg/l ):</label>
						<input type="text" class="form-control" name="do<?= $i ?>" value="<?= $info[$i]->do ?>">
					</div>
					<br>
					<div class="form-group">
						<label>BOD ( mg/l ):</label>
						<input type="text" class="form-control" name="bod<?= $i ?>" value="<?= $info[$i]->bod ?>">
					</div>
					<br>
					<div class="form-group">
						<label>E Coli ( Jml/100 ml ):</label>
						<input type="text" class="form-control" name="ecoli<?= $i ?>" value="<?= $info[$i]->ecoli ?>">
					</div><br>
				<?php $j++; } ?>
				<div id="bungkus" class="bungkus" style="overflow: auto;display: block">
					
				</div>
			</div>
			<div class="col-sm-2">
			</div>
		</div>
		<br>

		<div class="col-sm-2">
		</div>
		<div class="col-sm-8" style="padding: 0px;margin-top: 40px">
			<hr style="border-color: #67B1B7">
			<div class="form-group" style="float: left;margin-right:15px;">
				<button class="btn btn-cari" name="kirim"><span class="glyphicon glyphicon-send" style="margin-right: 5px;"></span> Kirim</button>
			</div> 
			<div class="form-group">
				<button class="btn btn-cari" name="cancel" style="background-color: red"><span class="glyphicon glyphicon-remove" style="margin-right: 5px"></span> Batal</button>
			</div> 
		</div>
		<div class="col-sm-2">
		</div>
	</div>
	<input type="hidden" name="lat" id="lat">
	<input type="hidden" name="lng" id="lng">

	<?php echo form_close(); ?>
</div>
<script type="text/javascript">
	var a = document.getElementsByClassName("status");
	var b = document.getElementById("isi");
	for(var i = 0; i<a.length;i++){
		if(a[i].value == b.value){
			a[i].style.display = "none";
		}
	}
	function cek(){
			var man = document.getElementById("manual");
			var lat_m = document.getElementById("lat_m");
			var lng_m = document.getElementById("lng_m");
			if(man.checked){
				lat_m.readOnly = false;
				lng_m.readOnly = false;
			}else{
				lat_m.readOnly = true;
				lng_m.readOnly = true;
			}
		}
</script>
