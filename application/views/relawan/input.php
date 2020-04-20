<div class="container">
	<?php echo form_open_multipart('relawan/input'); ?>
	<div class="row" style="padding-bottom: 30px;padding-left:20px;padding-right:20px">
		<div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div> 
		<br>
		<div class="row">

			<div class="col-sm-2">
			</div>
			<div class="col-sm-8">
				<h1 class="jdl-relawan">Input Data</h1>
				<br>
				<h2 class="jgn">Jangan Lupa Hidupkan GPS / Lokasi di HP</h2>
				<div class="form-group">
					<label for="usr">Penanda Lokasi: <span style="color: red">*</span></label>
					<input type="text" class="form-control" name="tanda" placeholder="Penanda" required="">
				</div>

				<br>
				<div class="checkbox">
					<label><input style="top:-3.5px;" id="manual" type="checkbox" onclick="cek()">Apakah Anda ingin menginputkan titik koordinat secara manual ?</label>
				</div>
				<br>
				<div class="form-group">
					<div class="col-sm-6" style="padding-left: 0px">
						<label>Latitude:</label>
						<input type="text" id="lat_m" class="form-control" name="lat_m" placeholder="Titik Latitude" disabled="">
					</div>
					<div class="col-sm-6" style="padding-left: 0px">
						<label>Longitude:</label>
						<input type="text" id="lng_m" class="form-control" name="lng_m" placeholder="Titik Longitude" disabled="">
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
				<label class="jgn">Apakah Titik Latitude dan Longitude Anda Tidak Muncul ? <a href="https://play.google.com/store/apps/details?id=com.digrasoft.mygpslocation">Download Disini</a></label>
				
				<div class="form-group" style="margin-top:30px">
					<label>Keterangan:</label>
					<input type="text" class="form-control" name="ket" placeholder="Keterangan">
				</div>
				<br>
				<div class="form-group">
					<label>Lampiran ( Foto Hasil Uji Lab | .JPG / .JPEG):</label>
					<input type="file" name="lampiran">
				</div>
				<br> 
				<div class="form-group">
					<label>Foto Lokasi ( Foto Lokasi Sungai | .JPG / .JPEG):</label>
					<input type="file" name="foto">
				</div> 
				<br>
				<div class="form-group">
					<label>Input Jumlah Ulangan Pengambilan Sampel Air Sungai:<span style="color: red">*</span></label>
					<input id="banyak" type="number" name="banyak" placeholder="Input Jumlah Ulangan Pengambilan Sampel Air Sungai" class="form-control" required>

					<div class="form-group">

					</div> 
				</div> 
				<div id="btn-banyak" class="btn btn-cari" name="banyak" onclick="banyaks()" style="background-color:#2b74bc"><span class="glyphicon glyphicon-plus" style="margin-right: 5px"></span>Tambah Jumlah Ulangan Pengambilan Sampel Air Sungai</div>
				<br>
				<br>
				<div id="bungkus" class="bungkus" style="overflow: auto;display: block">
					
				</div>
			</div>
			<div class="col-sm-2">
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-2">
			</div>
			<div class="col-sm-8" style="padding: 0px;margin-top: 40px">
				<hr style="border-color: #67B1B7">
				<div class="form-group" style="float: left;margin-right:15px;">
					<button class="btn btn-cari" name="kirim"><span class="glyphicon glyphicon-send" style="margin-right: 5px;"></span> Kirim</button>
				</div> 
				<div class="form-group">
					<button class="btn btn-cari" name="simpan" style="background-color: #1de149"><span class="glyphicon glyphicon-save" style="margin-right: 5px"></span> Simpan Sementara</button>
				</div> 
			</div>
			<div class="col-sm-2">
			</div>
		</div>
	</div>
	<input type="hidden" name="lat" id="lat">
	<input type="hidden" name="lng" id="lng">
	<?php echo form_close(); ?>
	
</div>


<script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyBhyxxSEIZC8-jOAeDAEyHQRVyeOJx0S5w&sensor=false"></script>
<script type="text/javascript">
	// var map;
	// function initMap() {
	// 	var mapLayer = document.getElementById("map-layer");
	// 	var centerCoordinates = new google.maps.LatLng(37.6, -95.665);
	// 	var defaultOptions = { center: centerCoordinates, zoom: 4 }

	// 	map = new google.maps.Map(mapLayer, defaultOptions);
	// }

	$( document ).ready(function() {	
		var lat = document.getElementById("lat");
		var lng = document.getElementById("lng");
		var lat_m = document.getElementById("lat_m");
		var lng_m = document.getElementById("lng_m");
		if ("geolocation" in navigator){
			navigator.geolocation.getCurrentPosition(function(position){ 
				var currentLatitude = position.coords.latitude;
				var currentLongitude = position.coords.longitude;

				lat.value = currentLatitude;
				lng.value = currentLongitude;
				lat_m.value = currentLatitude;
				lng_m.value = currentLongitude;
			});
		}
		
	});
	function cek(){
		var man = document.getElementById("manual");
		var lat_m = document.getElementById("lat_m");
		var lng_m = document.getElementById("lng_m");
		if(man.checked){
			lat_m.disabled = false;
			lng_m.disabled = false;
		}else{
			lat_m.disabled = true;
			lng_m.disabled = true;
			lat_m.value = null;
			lng_m.value = null;
		}
	}
</script>
<script type="text/javascript">
	function banyaks(){
		var jml = document.getElementById('banyak');
		var tot = jml.value;
		var bungkus = document.getElementById('bungkus');
		var htmls = '<div class="bungkus2" style="overflow: auto;display: block"><div><br>';
		var j=1;
		var a;
		if(tot >0 ){

		
		for(var i=0;i<tot;i++){
			// a ='<div class="bungkus2'+i+'" style="overflow: auto;display: block">';
			
			
			a = '<h3 style="color: #67B1B7;font-family: myf2;font-weight:bold;margin-top:35px">Pengukuran '+j+'</h3>'+
			'<hr style="border-color: #67B1B7">'+
			'<br>'+
			'<h3 style="color: #A1A3B7;font-family: myf2;margin-top:0px">Secara Fisika</h3>'+
			'<hr>'+
			'<div class="form-group">'+
			'<label>Temperatur / Suhu ( celcius ) :</label>'+
			'<input type="text" class="form-control" name="temp'+i+'" placeholder="Temperatur ( celcius )">'+
			'</div>'+
			'<br>'+
			'<div class="form-group">'+
			'<label>EC / DHL / Daya Hantar Listrik ( mhos/cm ) :</label>'+
			'<input type="text" class="form-control" name="ec'+i+'" placeholder="EC / DHL ( mhos/cm )">'+
			'</div> '+
			'<br>'+
			'<div class="form-group">'+
			'<label>TDS / Partikel Terlarut ( mg/l ) :</label>'+
			'<input type="text" class="form-control" name="tds'+i+'" placeholder="TDS ( mg/l )">'+
			'</div> '+
			'<h3 style="color: #A1A3B7;font-family: myf2;margin-top:30px">Secara Kimia</h3>'+
			'<hr>'+
			'<div class="form-group">'+
			'<label>pH / Derajat Keasaman ( mg/l ) :</label>'+
			'<input type="text" class="form-control" name="ph'+i+'" placeholder="pH ( mg/l )">'+
			'</div>'+
			'<h3 style="color: #A1A3B7;font-family: myf2;margin-top:30px">Secara Biologi</h3>'+
			'<hr>'+
			'<div class="form-group">'+
			'<label>DO / Oksigen Terlarut ( mg/l ) :</label>'+
			'<input type="text" class="form-control" name="do'+i+'" placeholder="DO ( mg/l )">'+
			'</div>'+
			'<br>'+
			'<div class="form-group">'+
			'<label>BOD ( mg/l ) :</label>'+
			'<input type="text" class="form-control" name="bod'+i+'" placeholder="BOD ( mg/l )">'+
			'</div> '+
			'<br>'+
			'<div class="form-group">'+
			'<label>E Coli ( MPN/100 ml ) :</label>'+
			'<input type="text" class="form-control" name="ecoli'+i+'" placeholder="E Coli ( MPN/100 ml )">';
			b ='</div><br>';
			htmls += (a+b);
			j++;
		}
		$( ".bungkus").html(htmls);
	}else{
		htmls = "<br>";
		$( ".bungkus").html(htmls);
		alert("Input Jumlah Ulangan Pengambilan Sampel Air Sungai Harus Lebih Dari 0")
	}
	}
</script>