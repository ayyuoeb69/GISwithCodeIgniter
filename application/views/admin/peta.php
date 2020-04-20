 
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyBPU0Y6pTubcYNjKyQMslqJJDtl8Ndbf6E&sensor=false"></script> 
<!-- <script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyBhyxxSEIZC8-jOAeDAEyHQRVyeOJx0S5w&sensor=false"></script> -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script> -->
<script>
	var map;
	var poly;
	var sungais = '<?= $_SESSION['sungai_adm']; ?>';
	// var map = new google.maps.Map(document.getElementById('map-canvas'), {
	// 	zoom: 16,
	// 	center: {lat: parseFloat(-7.8741482), lng: parseFloat(110.3956515)}
	// });
	$(document).on('click','#verif_titik',verif_titik);
	$(document).on('click','#terima',setuju_titik);
	function addLatLng(event) {
		var path = poly.getPath();
		path.push(event.latLng);
		var marker = new google.maps.Marker({
			position: event.latLng,
			title: 'Aaaas' + path.getLength(),
			map: map
		});
	}
	function clearmap(){
		$.ajax({
			url : '<?php echo site_url("admin/hapusdaftarkoordinatjalan") ?>',
			dataType : 'json',
			type : 'POST',
			success : function(data,status){
				if (data.status!='error') {
					$('#daftarkoordinat').load('<?php echo current_url()."/ #daftarkoordinat > *" ?>');
					location.reload();
				}else{
					alert(data.msg);
				}
			}
		})
	}
	function simpandaftardasar(){

		$.ajax({
			url : '<?php echo site_url("admin/simpandasarsungai") ?>',
			dataType : 'json',
			type : 'POST',
			success : function(data,status){
				if (data.status!='error') {
					alert("sukses");
				}else{
					alert(data.msg);
				}
			},
			error: function(){
				alert("ERROR");
			}
		})
	}
	function klikkanan(map){
		google.maps.event.addListener(map, 'rightclick', addLatLng);
		google.maps.event.addListener(map, "rightclick", function(event) {

			var lat = event.latLng.lat();
			var lng = event.latLng.lng();
			var datakoordinat = {'latitude':lat, 'longitude':lng};
			$.ajax({
				url : '<?php echo site_url("admin/tambahdasarsungai") ?>',
				dataType : 'json',
				data : datakoordinat,
				type : 'POST',
				success : function(data,status){
					if (data.status!='error') {
						$('#daftarkoordinat').load('<?php echo current_url()."/ #daftarkoordinat > *" ?>');
					}else{
						alert("Eror");
					}
				},
				error: function(){
					alert("Error");
				}
			})
		});
	}
	function verif_titik(){
		var datakoordinat = {'id_kel':$(this).data('idtitik')};
		var infowindow = new google.maps.InfoWindow({
			map: map
		});  
		console.log(datakoordinat);
		$.ajax({
			url : '<?php echo site_url("admin/lihat_titik") ?>',
			data : datakoordinat,
			dataType : 'json',
			type : 'POST',
			success : function(data){
				
				titik(parseFloat(data.titik_lat),parseFloat(data.titik_lng),1,data);
			},
			error : function(){
				alert("error");
			}
		})
	}
	function setuju_titik(){
		var datakoordinat = {'id_kel':$(this).data('idtitiks')};
		var infowindow = new google.maps.InfoWindow({
			map: map
		});  
		console.log(datakoordinat);
		$.ajax({
			url : '<?php echo site_url("admin/lihat_titik") ?>',
			data : datakoordinat,
			dataType : 'json',
			type : 'POST',
			success : function(data){
				
				titik(parseFloat(data.titik_lat),parseFloat(data.titik_lng),2,data);
			},
			error : function(){
				alert("error");
			}
		})
	}
	function titik(lats,lngs, point, dataa){
		var map = new google.maps.Map(document.getElementById('map-canvas'), {
			zoom: 14,
			center: {lat: lats, lng: lngs},
		});
		
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
		klikkanan(map);
		$.ajax({
			url : '<?php echo base_url("admin/titik_sungai") ?>',		
			dataType : 'json',
			type : 'POST',
			success : function(data){
				console.log(data);

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
						'<div class="col-sm-5"><label><b>ID Titik Lokasi : </b></label></div><div class="col-sm-7">'+this.id+'</div>'+
						'</div>'+
						'<br>'+
						'<div class="row">'+
						'<div class="col-sm-5"><label><b>Status Sungai : </b></label></div><div class="col-sm-7">'+status_titik+'</div>'+
						'</div>'+
						'<br>'+
						'<div class="row">'+							
						'<div class="col-sm-5"><label><b>Waktu Kirim : </b></label></div><div class="col-sm-7">'+this.waktu_kirim+'</div>'+
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
			}
		});
var temp;

var dasar2;
var garis_dasar = [];
<?php $i=0; foreach($dasar as $d){ ?>
$.ajax({
	url : '<?php echo base_url("admin/dasar_sungai/").$d->id_kel_dasar_sub ?>',		
	dataType : 'json',
	type : 'POST',
	success : function(data){
		// alert(data.length);
		var dasar<?= $i ?>=[];
		for (var i = 0; i < data.length; i++){
			temp = {lat: parseFloat(data[i].lat_koor_dasar), lng: parseFloat(data[i].lng_koor_dasar)};
			dasar<?= $i ?>.push(temp);
		}
		var poly2<?= $i ?> = new google.maps.Polyline({
			path: dasar<?= $i ?>,
			geodesic: true,
			strokeOpacity: 1.0,
			strokeColor : 'rgba(30, 63, 207, 0.5)',
			clickable : true,
			map:map,
			strokeWeight: 6,
			zIndex : 1,
			clickable : false
		});
		poly2<?= $i ?>.setMap(map);
	},
	error : function(){
		alert("errore");
	}
});
<?php $i++; } ?>
var infowindow = new google.maps.InfoWindow({
	map: map,
})
if(point == 1){
	
	var biru2 = {
		url: '<?= base_url("assets/img/biru3.png") ?>',
	};
	var kuning2 = {
		url: '<?= base_url("assets/img/kuning3.png") ?>',
	}
	var orange2 = {
		url: '<?= base_url("assets/img/oranye3.png") ?>',
	};
	var merah2 = {
		url: '<?= base_url("assets/img/merah3.png") ?>',
	};
	if(dataa.status_titik == 'Biru'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: biru2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 5,
		});
	}else if(dataa.status_titik == 'Kuning'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: kuning2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 5,
		});
	}else if(dataa.status_titik == 'Orange'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: orange2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 5,
		});
	}else if(dataa.status_titik == 'Merah'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: merah2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 5,
		});
	}
	var infowindow = new google.maps.InfoWindow({
		map: map,
	});
	var a;
	var e;
	var status_titik;
	var foto;
	var lampiran;
	
	if(markers.status_setuju == 0){ a = "Proses Verifikasi Admin"; }else if(markers.status_setuju == 1){a = "Diterima"; }else if(markers.status_setuju == 2){ a="Ditolak"; }
	if(markers.waktu_setuju == null){e="Belum Disetujui";}else{e=markers.waktu_setuju;}

	if(markers.status_sungai == 'Biru'){
		status_titik = '<span class="label label-primary">Baik</span>'
	}else if(markers.status_sungai == 'Kuning'){
		status_titik = '<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>'
	}else if(markers.status_sungai == 'Orange'){
		status_titik = '<span class="label label-warning">Cemar Sedang</span>'
	}else if(markers.status_sungai == 'Merah'){
		status_titik = '<span class="label label-danger">Cemar Berat</span>'
	}
	if(markers.foto != null){
		foto = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+markers.foto+'">';
	}else{
		foto = 'Tidak Ada Foto';
	}
	if(markers.lampiran != null){
		lampiran = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+markers.lampiran+'">';
	}else{
		lampiran = 'Tidak Ada Lampiran';
	}
	toolTips='<div id="content">'+
	'<div id="siteNotice">'+
	'</div>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>ID Titik Lokasi: </b></label></div><div class="col-sm-7">'+markers.id+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+
	'<div class="col-sm-5"><label><b>Status Sungai : </b></label></div><div class="col-sm-7">'+status_titik+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>Waktu Kirim: </b></label></div><div class="col-sm-7">'+markers.waktu_kirim+'</div>'+
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
	'<div class="row">'+
	'<div class="col-sm-5"><label><b>Keterangan Sungai : </b></label></div><div class="col-sm-7">'+markers.keterangan+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>Waktu Setuju: </b></label></div><div class="col-sm-7">'+e+'</div>'+
	'</div><br><br>'+
	'<div class="row"><div class="col-sm-12">'+
	'<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modal_maps(\''+markers.id+'\')">Hasil Uji Pengukuran</button>'
	'</div></div>'+

	'</div>';

	infowindow.setPosition({lat: lats, lng: lngs});
	infowindow.setContent(toolTips);
	infowindow.open( map);
}
else if(point == 2){
	var biru2 = {
		url: '<?= base_url("assets/img/blue.png") ?>',
	};
	var kuning2 = {
		url: '<?= base_url("assets/img/yellow.png") ?>',
	}
	var orange2 = {
		url: '<?= base_url("assets/img/orange.png") ?>',
	};
	var merah2 = {
		url: '<?= base_url("assets/img/red.png") ?>',
	};
	if(dataa.status_titik == 'Biru'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: biru2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 3,
		});
	}else if(dataa.status_titik == 'Kuning'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: kuning2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 3,
		});
	}else if(dataa.status_titik == 'Orange'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: orange2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 3,
		});
	}else if(dataa.status_titik == 'Merah'){
		var markers = new google.maps.Marker({
			position:{lat: lats, lng: lngs},
			map: map,
			icon: merah2,
			id : dataa.id_kel_sungai,
			status_sungai : dataa.status_titik,
			waktu_kirim : dataa.waktu_tambah,
			status_setuju : dataa.status_setuju,
			lampiran : dataa.lampiran,
			foto : dataa.foto,
			keterangan : dataa.keterangan,
			waktu_setuju : dataa.waktu_setuju,
			zIndex : 3,
		});
	}
	var infowindow = new google.maps.InfoWindow({
		map: map,
	});
	var a;
	var e;
	var status_titik;
	var foto;
	var lampiran;
	
	if(markers.status_setuju == 0){ a = "Proses Verifikasi Admin"; }else if(markers.status_setuju == 1){a = "Diterima"; }else if(markers.status_setuju == 2){ a="Ditolak"; }
	if(markers.waktu_setuju == null){e="Belum Disetujui";}else{e=markers.waktu_setuju;}

	if(markers.status_sungai == 'Biru'){
		status_titik = '<span class="label label-primary">Baik</span>'
	}else if(markers.status_sungai == 'Kuning'){
		status_titik = '<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>'
	}else if(markers.status_sungai == 'Orange'){
		status_titik = '<span class="label label-warning">Cemar Sedang</span>'
	}else if(markers.status_sungai == 'Merah'){
		status_titik = '<span class="label label-danger">Cemar Berat</span>'
	}
	if(markers.foto != null){
		foto = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+markers.foto+'">';
	}else{
		foto = 'Tidak Ada Foto';
	}
	if(markers.lampiran != null){
		lampiran = '<img class="img-responsive" style="width:200px" src="<?= base_url('assets/upload/') ?>'+markers.lampiran+'">';
	}else{
		lampiran = 'Tidak Ada Lampiran';
	}
	toolTips='<div id="content">'+
	'<div id="siteNotice">'+
	'</div>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>ID Titik Lokasi: </b></label></div><div class="col-sm-7">'+markers.id+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+
	'<div class="col-sm-5"><label><b>Status Sungai : </b></label></div><div class="col-sm-7">'+status_titik+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>Waktu Kirim: </b></label></div><div class="col-sm-7">'+markers.waktu_kirim+'</div>'+
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
	'<div class="row">'+
	'<div class="col-sm-5"><label><b>Keterangan Sungai : </b></label></div><div class="col-sm-7">'+markers.keterangan+'</div>'+
	'</div>'+
	'<br>'+
	'<div class="row">'+							
	'<div class="col-sm-5"><label><b>Waktu Setuju: </b></label></div><div class="col-sm-7">'+e+'</div>'+
	'</div><br><br>'+
	'<div class="row"><div class="col-sm-12">'+
	'<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modal_maps(\''+markers.id+'\')">Hasil Uji Pengukuran</button>'
	'</div></div>'+

	'</div>';

	infowindow.setPosition({lat: lats, lng: lngs});
	infowindow.setContent(toolTips);
	infowindow.open( map);
}

google.maps.event.addListener(markers, 'click', function(event) {    
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
	'<div class="col-sm-5"><label><b>Foto Lampiran Uji Lab: </b></label></div><div class="col-sm-7">'+lampiran+'</div>'+
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
function init(){
	if(sungais == 's_gajah'){
		titik(-7.8279434, 110.3992647, 0, 0);
	}else if(sungais == 's_brantas'){
		titik(-7.455030, 112.244856, 0, 0);
	}else if(sungais == 's_kambaniru'){
		titik(-9.7223402, 120.267, 0, 0);
	}
}
google.maps.event.addDomListener(window, 'load', init);
</script>

<div style="width: 100%;height: 580px;display: block;overflow: auto;" >
	
	<div style="height:100%;width: 100%" id="map-canvas">
	</div>
</div>
<table class="table">
	<th>No</th>
	<th>Lat</th>
	<th>Long</th>
	<th></th>
	<tbody id="daftarkoordinat">
		<?php if($this->cart->contents()!=null){

			foreach ($this->cart->contents() as $koordinat) {

				echo '<tr><td>'.$koordinat["id"].'</td><td>'.$koordinat["latitude"].'</td><td>'.$koordinat["longitude"].'</td>

				</tr>';
				?>

				<?php
				
			}
		} ?>
	</tbody>
</table> 
<div class="container" style="padding-bottom: 60px;padding-top:40px">
	<button onclick="simpandaftardasar()">Tambah Dasar</button>
	<button onclick="clearmap()">Hapus Dasar</button>
	<div id="notifications" style="margin-top: 40px"><?php echo $this->session->flashdata('msg'); ?></div> 
	<br>
	<div class="col-sm-12">
		<h3 class="jdl-relawan" style="margin-top: 10px">Status <b> <?= $_SESSION['nm_sungai'] ?> </b>:
			<?php if($sungai->status_sungai >= 0){?>
				<span class="label label-primary">Baik</span>
			<?php }else if($sungai->status_sungai >= -10 && $sungai->status_sungai <= -1){ ?>
				<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>
			<?php }else if($sungai->status_sungai >= -30 && $sungai->status_sungai <= -11){ ?>
				<span class="label label-warning">Cemar Sedang</span>
			<?php }else if($sungai->status_sungai <= -31){ ?>
				<span class="label label-danger">Cemar Berat</span>
			<?php } ?>
			
		</div>
		<div class="col-sm-12">
			<h3 class="jdl-relawan" style="margin-top: 10px">Data Proses Verifikasi</h3>
			<table id="verif" class="table table-responsive table-striped nowrap">
				<thead>
					<tr>
						<th>No</th>
						<th>User</th>
						<th>ID Titik</th>
						<th>Status Titik</th>
						<th>Status Kirim</th>
						<th>Waktu Kirim</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; foreach ($verif as $k) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $k->id_relawan_sub ?></td>
							<td><?= $k->id_kel_sungai ?></td>
							<td><?php if($k->status_titik == 'Biru'){?>
								<span class="label label-primary">Baik</span>
							<?php }else if($k->status_titik == 'Kuning'){ ?>
								<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>
							<?php }else if($k->status_titik == 'Orange'){ ?>
								<span class="label label-warning">Cemar Sedang</span>
							<?php }else if($k->status_titik == 'Merah'){ ?>
								<span class="label label-danger">Cemar Berat</span>
							<?php } ?>

						</td>
						<td><?php if($k->status_kirim == 1){
							echo "Kirim Baru";
						}else if($k->status_kirim == 2){
							echo "Data Edit";
						} ?>
					</td>
					<td><?= $k->waktu_tambah ?></td>
					<td>
						<a href="#map-canvas">
							<button id="verif_titik" class="btn btn-primary" data-idtitik="<?php echo $k->id_kel_sungai ?>">TITIK</button>
						</a>
						<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modal('<?php echo $k->id_kel_sungai ?>')">DETAIL</button>
						<a href="<?= base_url('admin/setuju_verif/').$k->id_kel_sungai ?>">
							<button class="btn btn-success">SETUJU</button>
						</a>
						<a href="<?= base_url('admin/tolak_verif/').$k->id_kel_sungai ?>">
							<button class="btn btn-danger">TOLAK</button>
						</a>
					</td>
				</tr>
				<?php $i++; }  ?>
			</tbody>
		</table>
	</div>
	<div class="col-sm-12" style="margin-top:40px">
		<h3 class="jdl-relawan" style=" float: left;margin-right:10px;">Data Diterima</h3>
		<a href="<?= base_url('admin/cetak') ?>">
		<button id="btn-profile" data-toggle="modal" data-target="#modalInfo" class="btn" style="margin-top:35px;background-color: #115a68;float: right;"><span class="glyphicon glyphicon-print" style="margin-right: 5px;"></span> Print PDF</button>
	</a>
		<table id="terima" class="table table-responsive table-striped nowrap">
			<thead>
				<tr>
					<th>No</th>
					<th>User</th>
					<th>ID Titik</th>
					<th>Status Titik</th>
					<th>Status Kirim</th>
					<th>Waktu Kirim</th>
					<th>Waktu Setuju</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $j = 1; foreach ($setuju as $s) {
					?>
					<tr>
						<td><?= $j ?></td>
						<td><?= $s->id_relawan_sub ?></td>
						<td><?= $s->id_kel_sungai ?></td>
						<td><?php if($s->status_titik == 'Biru'){?>
							<span class="label label-primary">Baik</span>
						<?php }else if($s->status_titik == 'Kuning'){ ?>
							<span class="label label-default" style="background-color: #ffff45;color:#555">Cemar Ringan</span>
						<?php }else if($s->status_titik == 'Orange'){ ?>
							<span class="label label-warning">Cemar Sedang</span>
						<?php }else if($s->status_titik == 'Merah'){ ?>
							<span class="label label-danger">Cemar Berat</span>
						<?php } ?>

					</td>
					<td><?php if($s->status_kirim == 1){
						echo "Kirim Baru";
					}else if($s->status_kirim == 2){
						echo "Data Edit";
					} ?></td>
					<td><?= $s->waktu_tambah ?></td>
					<td><?= $s->waktu_setuju ?></td>
					<td>
						<a href="#map-canvas">
							<button id="terima" class="btn btn-primary" style="margin-right: 2px" data-idtitiks="<?php echo $s->id_kel_sungai ?>">TITIK</button>
						</a>
						<button class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="modal('<?php echo $s->id_kel_sungai ?>')">DETAIL</button>
						<a href="<?= base_url('admin/tolak_verif/').$s->id_kel_sungai ?>">
							<button class="btn btn-danger" onclick="return Confirm();">HAPUS</button>
						</a>
					</td>
				</tr>
				<?php $j++; }  ?>
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
		$('#verif').DataTable({"scrollX": true});
		$('#terima').DataTable({"scrollX": true});
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
	function modal(id){
		var a;
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
			url : "<?php echo base_url('admin/tampil_modal/')?>" + id,
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
				alert('erors');
			}
		});
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
		url : "<?php echo base_url('admin/tampil_modal/')?>" + id,
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
				'<div class="col-sm-5"><label><b>Temperatur /Suhu Pengukuran Ke - '+j+' : </b></label></div><div class="col-sm-7">'+data[i].temperatur+' celcius </div>'+
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
