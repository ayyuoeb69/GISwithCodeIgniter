<!-- <script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyBPU0Y6pTubcYNjKyQMslqJJDtl8Ndbf6E&sensor=false"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script>
// Initialize and add the map
$(document).on('click','#btn-cari',filter);
var map;
var poly;
function filter(){
	var data_sungai = { 'sungai': $('#sungai').val() };
	var a;
	var b;
	var polyOptions = {
		strokeColor : 'rgba(30, 63, 207, 0.5)',
		strokeOpacity: 1.0,
		strokeWeight: 6,
		clickable : true,
		zIndex : 4,
		draggable: true,
	};
	poly = new google.maps.Polyline(polyOptions);
	poly.setMap(map);
	$.ajax({
		url : '<?php echo site_url("home/getSungai") ?>',
		dataType : 'json',
		data: data_sungai,
		type : 'POST',
		success : function(data,status){
			b = 'Status <b> '+data.nm_sungai+': </b><br><br>';
			if(data.status_sungai >= 0){
				a = '<span class="label label-primary">Baik</span>';
			}else if(data.status_sungai >= -10 && data.status_sungai <= -1){ 
				a = '<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>';
			}else if(data.status_sungai >= -30 && data.status_sungai <= -11){ 
				a = '<span class="label label-warning">Cemar Sedang</span>';
			}else if(data.status_sungai <= -31){ 
				a='<span class="label label-danger">Cemar Berat</span>';
			}
			$( ".jdl-relawan" ).html(b + a);
		},
		error: function(){
			alert("ERROR");
		}
	})
	var lngs;
	var lats;
	if($('#sungai').val() == 's_gajah'){
		lats = -7.797118;
		lngs = 110.397184;
	}else if($('#sungai').val() == 's_brantas'){
		
		lats = -7.455030;
		lngs = 112.244856;
	}else if($('#sungai').val() == 's_kambaniru'){
		
		lats = -9.7223402;
		lngs = 120.267;
	}
	
	var map = new google.maps.Map(document.getElementById('map-canvas'), {
		zoom: 12,
		center: {lat: lats, lng: lngs},
		maxZoom:14,
	});
	
	$.ajax({
		url : '<?php echo base_url("home/titik_sungai") ?>',		
		dataType : 'json',
		data: data_sungai,
		type : 'POST',
		success : function(data){
			// console.log(data);

			var biru = {
				url: '<?= base_url("assets/img/blue.png") ?>',
			};
			var kuning = {
				url: '<?= base_url("assets/img/yellow.png") ?>',
			};
			var orange = {
				url: '<?= base_url("assets/img/orange.png") ?>',
			};
			var merah = {
				url: '<?= base_url("assets/img/red.png") ?>',
			};
			var marker = [];
			var toolTips;
			var temp;
			for (var i = 0; i < data.length; i++){
				var titik = data[i];

				var infowindow = new google.maps.InfoWindow({
					map: map,
				});
				if(data[i].status_titik == 'Biru'){
					marker[i] = new google.maps.Marker({
						position: {lat: parseFloat(data[i].titik_lat), lng: parseFloat(data[i].titik_lng)},
						map: map,
						icon: biru,
						id : data[i].id_kel_sungai,
						status_sungai : data[i].status_titik,
						waktu_kirim : data[i].waktu_tambah,
						status_setuju : data[i].status_setuju,
						lampiran : data[i].lampiran,
						foto : data[i].foto,
						keterangan : data[i].keterangan,
						waktu_setuju : data[i].waktu_setuju,
						zIndex : 2,
						id_user : data[i].id_relawan_sub,
					});
				}else if(data[i].status_titik == 'Kuning'){
					marker[i] = new google.maps.Marker({
						position: {lat: parseFloat(data[i].titik_lat), lng: parseFloat(data[i].titik_lng)},
						map: map,
						icon: kuning,
						id : data[i].id_kel_sungai,
						status_sungai : data[i].status_titik,
						waktu_kirim : data[i].waktu_tambah,
						status_setuju : data[i].status_setuju,
						lampiran : data[i].lampiran,
						foto : data[i].foto,
						keterangan : data[i].keterangan,
						waktu_setuju : data[i].waktu_setuju,
						zIndex : 2,
						id_user : data[i].id_relawan_sub,
					});
				}else if(data[i].status_titik == 'Orange'){
					marker[i] = new google.maps.Marker({
						position: {lat: parseFloat(data[i].titik_lat), lng: parseFloat(data[i].titik_lng)},
						map: map,
						icon: orange,
						id : data[i].id_kel_sungai,
						status_sungai : data[i].status_titik,
						waktu_kirim : data[i].waktu_tambah,
						status_setuju : data[i].status_setuju,
						lampiran : data[i].lampiran,
						foto : data[i].foto,
						keterangan : data[i].keterangan,
						waktu_setuju : data[i].waktu_setuju,
						zIndex : 2,
						id_user : data[i].id_relawan_sub,
					});
				}else if(data[i].status_titik == 'Merah'){
					marker[i] = new google.maps.Marker({
						position: {lat: parseFloat(data[i].titik_lat), lng: parseFloat(data[i].titik_lng)},
						map: map,
						icon: merah,
						id : data[i].id_kel_sungai,
						status_sungai : data[i].status_titik,
						waktu_kirim : data[i].waktu_tambah,
						status_setuju : data[i].status_setuju,
						lampiran : data[i].lampiran,
						foto : data[i].foto,
						keterangan : data[i].keterangan,
						waktu_setuju : data[i].waktu_setuju,
						zIndex : 2,
						id_user : data[i].id_relawan_sub,
					});
				}

				google.maps.event.addListener(marker[i], 'click', function(event) {    
					var a;
					var e;
					var status_titik;
					var foto;
					var lampiran;
					if(this.status_setuju == 0){ a = "Proses Verifikasi Admin"; }else if(this.status_setuju == 1){a = "Diterima"; }else if(this.status_setuju == 2){ a="Ditolak"; }
					if(this.waktu_setuju == null){e="Belum Disetujui";}else{e=this.waktu_setuju;}

					if(this.status_sungai == 'Biru'){
						status_titik = '<span class="label label-primary">Baik</span>'
					}else if(this.status_sungai == 'Kuning'){
						status_titik = '<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>'
					}else if(this.status_sungai == 'Orange'){
						status_titik = '<span class="label label-warning">Cemar Sedang</span>'
					}else if(this.status_sungai == 'Merah'){
						status_titik = '<span class="label label-danger">Cemar Berat</span>'
					}
					if(this.foto != null){
						foto = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+this.foto+'">';
					}else{
						foto = 'Tidak Ada Foto';
					}
					if(this.lampiran != null){
						lampiran = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+this.lampiran+'">';
					}else{
						lampiran = 'Tidak Ada Lampiran';
					}
					toolTips='<div id="content">'+
					'<div id="siteNotice">'+
					'</div>'+
					'<div class="row">'+							
					'<div class="col-sm-5"><label><b>ID Titik Lokasi: </b></label></div><div class="col-sm-7">'+this.id+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+							
					'<div class="col-sm-5"><label><b>User Relawan: </b></label></div><div class="col-sm-7">'+this.id_user+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+
					'<div class="col-sm-5"><label><b>Status Sungai : </b></label></div><div class="col-sm-7">'+status_titik+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+							
					'<div class="col-sm-5"><label><b>Waktu Kirim: </b></label></div><div class="col-sm-7">'+this.waktu_kirim+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+
					'<div class="col-sm-5"><label><b>Status Laporan : </b></label></div><div class="col-sm-7">'+a+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+
					'<div class="col-sm-5"><label><b>Foto Lampiran Uji Lab : </b></label></div><div class="col-sm-7">'+lampiran+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+
					'<div class="col-sm-5"><label><b>Foto Sungai : </b></label></div><div class="col-sm-7">'+foto+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+
					'<div class="col-sm-5"><label><b>Keterangan Sungai : </b></label></div><div class="col-sm-7">'+this.keterangan+'</div>'+
					'</div>'+
					'<br>'+
					'<div class="row">'+							
					'<div class="col-sm-5"><label><b>Waktu Setuju: </b></label></div><div class="col-sm-7">'+e+'</div>'+
					'</div><br><br>'+
					'<div class="row"><div class="col-sm-12">'+
					'<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modal_maps(\''+this.id+'\')">Hasil Uji Pengukuran</button>'
					'</div></div>'+

					'</div>';

					infowindow.setPosition(event.latLng);
					infowindow.setContent(toolTips);
					infowindow.open( map);
				});
			}
		},
		error: function(){
			alert("ERROR");
		}

	});
var temp;
// var dasar = [];
var dasar2;
var garis_dasar = [];
var poly2 = [];
var temp = [];
var i;
var temps;
$.ajax({
	url : '<?php echo base_url("home/kel_dasar_sungai") ?>',		
	dataType : 'json',
	data : data_sungai,
	type : 'POST',
	success : function(data){
		// var dasar=[];
		for(i = 0; i<data.length;i++){
			temp[i] = data[i].id_kel_dasar_sub;
		}
		// var aaa = document.getElementById('aaa');
// $('#aaa').val(temp);
// aaa.value(temp);
var z=0;
for(var i = 0;i<temp.length;i++){
	// console.log(temp[i]);
	$.ajax({
		url : '<?php echo base_url("home/dasar_sungai/") ?>'+temp[i],		

		dataType : 'json',
		type : 'POST',
		success : function(data){
			
		// console.log(data[i]);
		// console.log(temp[i]);
		// console.log(temp.length);
		// console.log(data.length);
				// for (var j = 0; j < data.length; j++){
				//  console.log(data[j]);
				// }
				console.log(z);
var dasar = [];
			  console.log(dasar[z]);
			 for (var j = 0; j < data.length; j++){
			 	temps = {lat: parseFloat(data[j].lat_koor_dasar), lng: parseFloat(data[j].lng_koor_dasar)};
			 	dasar.push(temps);
			 }
			 poly2[z] = new google.maps.Polyline({
			 	path: dasar,
			 	geodesic: true,
			 	strokeOpacity: 1.0,
			 	strokeColor : 'rgba(30, 63, 207, 0.5)',
			 	clickable : true,
			 	map:map,
			 	strokeWeight: 6,
			 	zIndex : 1,
			 	clickable : false
			 });
			 poly2[z].setMap(map);
				// console.log(data);
				z++;
			},
			error : function(){
				alert("error");
			}
		});
}
},
error : function(){
	alert("error");
}
});
console.log(temp);

// $.ajax({
// 	url : '<?php echo base_url("home/dasar_sungai") ?>',		
// 	dataType : 'json',
// 	data : data_sungai,
// 	type : 'POST',
// 	success : function(data){
// 		for (var i = 0; i < data.length; i++){
// 			temp = {lat: parseFloat(data[i].lat_koor_dasar), lng: parseFloat(data[i].lng_koor_dasar)};
// 			dasar.push(temp);
// 		}
// 		var poly2 = new google.maps.Polyline({
// 			path: dasar,
// 			geodesic: true,
// 			strokeOpacity: 1.0,
// 			strokeColor : 'rgba(30, 63, 207, 0.5)',
// 			clickable : true,
// 			map:map,
// 			strokeWeight: 6,
// 			zIndex : 1,
// 			clickable : false
// 		});
// 		poly2.setMap(map);
// 		var infowindow = new google.maps.InfoWindow({
// 			map: map,
// 		});
// 		var tips = '<div id="content">'+
// 		'<div id="siteNotice" style="overflow:hidden">'+

// 		'<div class="row">'+							
// 		'<div class="col-sm-12"><h2>'+data[0].nm_sungai+'</h2></div>'+
// 		'</div>'+
// 		'</div>'+
// 		'</div>';
// 		infowindow.setPosition({lat: lats, lng: lngs});
// 		infowindow.setContent(tips);
// 		infowindow.open( map);
// 	},
// 	error : function(){
// 		alert("error");
// 	}
// });
}
function initMap() {
	// The location of Uluru

	var uluru = {lat: -1.2772576, lng: 118.6252287};
	// The map, centered at Uluru
	var map = new google.maps.Map(
		document.getElementById('map-canvas'), {zoom: 5, center: uluru, maxZoom:5});
	// The marker, positioned at Uluru
}

google.maps.event.addDomListener(window, 'load', initMap);
</script>
<!-- <?= form_open('home/dasar_sungai'); ?>
<input type="text" name="sungai" id="aaa">
<button>aa</button>
<?= form_close(); ?> -->
<nav id="nav-kecil" class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#" style="color: #fafbfc">Sistem Informasi Geografis Status Sungai Indonesia</a>
			<button type="button" id="btns" class="navbar-toggle" onclick="sidebars()">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		</div>
	</div>
</nav> 
<div id="sidebar">
	<div class="col-sm-12" id="jdl">
		<h2>Sistem Informasi Geografis Status Sungai Indonesia</h2>
	</div>
	<div class="col-sm-12" style="padding: 15px 15px">
		<br>
		<div class="form-group">
			<button id="btn-profile" data-toggle="modal" data-target="#modalInfo" class="btn"><span class="glyphicon glyphicon-globe" style="margin-right: 5px"></span> Profile Website</button>
		</div> 
		<br>
		<h4 id="status_s" class="jdl-relawan" style="margin-top: 10px">
			
		</h4>

		<h3 id="search"><span class="glyphicon glyphicon-search" style="margin-right: 5px"></span>Search : </h3>
		<div class="form-group" style="width: 100%">
			<label for="sel1" style="color:#1dc1e1">Pilih Pulau:</label>
			<select id="pulau" class="form-control js-example-basic-single" style="width: 100%">
				<option value="0">-- Pilih Pulau --</option>
				<option value="p_jawa">Pulau Jawa</option>
				<option value="p_ntt">Pulau NTT</option>
			</select>
		</div> 
		<br>
		<div class="form-group" >
			<label for="sel1" style="color:#1dc1e1">Pilih Sungai:</label>
			<select id="sungai" class="form-control js-example-basic-single" style="width: 100%">
				<option value="0">-- Pilih Sungai --</option>
			</select>
		</div> 
		<br>
		<div class="form-group">
			<button id="btn-cari" class="btn"><span class="glyphicon glyphicon-search" style="margin-right: 5px"></span> Search</button>
		</div> 
		<br>
		<h3 id="search"><span class="glyphicon glyphicon-info-sign" style="margin-right: 5px;position: relative;top:3px"></span>Keterangan : </h3>
		<br>
		<div class="col-sm-12" style="padding: 0px;color:#555;margin-bottom:30px">
			<img src="<?php echo base_url('assets/img/red.svg') ?>" style="width: 40px;float: left;margin-right:15px"><p style="font-family: myf;position: relative;top:8px">Status Titik Tercemar Berat</p>
		</div>
		<br>
		<div class="col-sm-12" style="padding: 0px;color:#555;margin-bottom:30px">
			<img src="<?php echo base_url('assets/img/orange.svg') ?>" style="width: 40px;float: left;margin-right:15px"><p style="font-family: myf;position: relative;top:8px">Status Titik Tercemar Sedang</p>
		</div>
		<div class="col-sm-12" style="padding: 0px;color:#555;margin-bottom:30px">
			<img src="<?php echo base_url('assets/img/yellow.svg') ?>" style="width: 40px;float: left;margin-right:15px"><p style="font-family: myf;position: relative;top:8px">Status Titik Tercemar Ringan</p>
		</div>
		<div class="col-sm-12" style="padding: 0px;color:#555;margin-bottom:30px">
			<img src="<?php echo base_url('assets/img/d.svg') ?>" style="width: 40px;float: left;margin-right:15px"><p style="font-family: myf;position: relative;top:8px">Status Titik Tidak Tercemar</p>
		</div>
		
		<br>
		<br>
		<?php if(!isset($_SESSION['user']) && !isset($_SESSION['admin'])){
			?>
			<p id="tanya">Apakah Anda Relawan ? <b><a href="<?= base_url('auth/login_relawan_view') ?>">Login</a></b></p>
			<br>
			<p id="tanya">Apakah Anda Admin ? <b><a href="<?= base_url('admin') ?>">Login</a></b></p>
			<?php 
		} else if(isset($_SESSION['user'])){
			?>
			<p id="tanya">Anda sudah masuk. <b><a href="<?= base_url('relawan/index') ?>">Beranda</a></b></p>
			<?php 
		}else if(isset($_SESSION['admin'])){
			?>
			<p id="tanya">Anda sudah masuk. <b><a href="<?= base_url('admin/peta') ?>">Beranda</a></b></p>
			<?php 
		} 
		?>
	</div>
</div>
<div id="maps">
	<div class="panel-body" style="height:100%;width: 100%" id="map-canvas">
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
<div id="modalInfo" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Informasi Website</h4>
			</div>
			<div class="modal-body">
				<p style="text-align: justify;">
					Sistem Informasi Geografis (SIG) Status Sungai Indonesia, website ini memberikan informasi geografis berupa status mutu air sungai yang ada di Indonesia dengan data yang relatif uptodate. Status mutu air sendiri merupakan tingkat kondisi mutu air yang menunjukkan kondisi cemar atau kondisi baik pada suatu sumber air dalam waktu tertentu dengan membandingkan dengan baku mutu air yang ditetapkan. Dalam penentuan status mutu air, SIG Status Sungai Indonesia menggunakan Metoda STORET. Metoda STORET merupakan salah satu metoda untuk menentukan status mutu air yang umum digunakan. Dengan metoda STORET ini dapat diketahui parameter-parameter yang telah memenuhi atau melampaui baku mutu air. 
					Secara prinsip metoda STORET ini membandingkan antara data kualitas air dengan baku mutu air yang disesuaikan dengan peruntukannya guna menentukan status mutu air. Cara untuk menentukan status mutu air adalah dengan menggunakan sistem nilai dari “US-EPA (Environmental Protection Agency)” dengan mengklasifikasikan mutu air dalam empat kelas, yaitu :
					(1) Kelas A : baik sekali, skor = 0 = > memenuhi baku mutu<br>
					(2) Kelas B : baik, skor = -1 s/d -10 => cemar ringan<br>
					(3) Kelas C : sedang, skor = -11 s/d -30 => cemar sedang<br>
					(4) Kelas D : buruk, skor ≥ -31 => cemar berat<br>
					Dalam penerapannya, SIG Status Sungai Indonesia bekerja sama dengan relawan yang akan menginputkan data pengukuran secara uptodate. Ada 7 parameter yang akan digunakan untuk menentukan status mutu air sungai, yaitu : Temperatur/Suhu, EC/DHL/Daya Hantar Listrik, TDS/Partikel Terlarut, pH/Derajat Keasaman, DO/Oksigen Terlarut, BOD, dan E Coli. Nantinya relawan akan menginputkan hasil pengukuran di lapangan ke dalam form yang telah disediakan berdasarkan ketujuh parameter di atas. Setelah menekan kirim, data yang telah dimasukkan akan dikirimkan ke admin yang bertugas memverifikasi data. Sedangkan untuk menghitung status mutu air akan otomatis dilakukan oleh sistem.
					Pedoman yang digunakan untuk menentukan status mutu air dengan Metoda STORET dilakukan sesuai dengan peraturan yang berlaku di daerah tersebut. Sebagai contoh, untuk menentukan status mutu air di Sungai Gajah Wong, kami menggunakan pedoman baku mutu dari PP DIY No 20 tahun 2008. Sehingga akan didapatkan data pemrosesan kualitas air sungai yang tepat. Dengan adanya sistem ini, diharapkan dapat mempermudah dalam hal sharing informasi mengenai status sungai di Indonesia yang uptodate dan relatif akurat. 
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.js-example-basic-single').select2();
	});

</script>
<script type="text/javascript">
	$('#pulau').on('select2:select', function (e) {
		var data = e.params.data.id;
		var b;
		var c;
		var htmls;
		if(data!='0'){
			$.ajax({
				url : "<?php echo site_url('home/sungai/')?>" + data,
				type: "GET",
				dataType: "JSON",
				success: function(data)
				{
					for(var i=0;i<data.length;i++){
						b = '<option value="'+data[i].kd_sungai+'">'+data[i].nm_sungai;
						c = '</option>';
						htmls += (b+c);
					}
					$('#sungai').html(htmls);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
		}else{
			b = '<option value="0">-- Pilih Sungai --';
			c = '</option>';
			htmls = (b+c);
			
			$('#sungai').html(htmls);
		}
	});
</script>
<script type="text/javascript">
	function sidebars() {
		var side = document.getElementById("sidebar");
		var nav = document.getElementById("nav-kecil");
		var mapss = document.getElementById("maps");

		if(side.style.display == "none"){
			side.style.display = "block";
			mapss.style.display = "none";
			side.style.width = "100%";
		}else{
			side.style.display = "none";
			mapss.style.display = "block";
			side.style.width = "100%";
		}
		
	}
	function modal_maps(id){
		var  a;
		var i;
		var b;
		var c;
		var d ='<br>';
		var j =1;
		var e;
		var foto;
		var lampiran;
		var status_titik;
		$.ajax({
			url : "<?php echo base_url('home/tampil_modal/')?>" + id,
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
				.html(d);

			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('erors');
			}
		});
	}
</script>