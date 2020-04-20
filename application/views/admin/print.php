<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> SIG Status Sungai Indonesia </title>

	<link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet">	
	<link href="<?php echo base_url('assets/datatables/css/jquery.dataTables.min.css') ?>" rel="stylesheet">	
	<script src="<?php echo base_url() ?>assets/js/jque.js"></script>
	<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url() ?>assets/datatables/js/jquery.dataTables.min.js"></script>
	<link href="<?php echo base_url() ?>assets/fontawesome/css/font-awesome.min.css" rel="stylesheet">
	<style type="text/css">
		table,th,td{
			border:1px solid #555;
		}
	</style>
</head>
<body>
	<div class="container" style="padding-bottom: 40px">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="jdl-relawan" style="color: #19aac6">Sistem Informasi Geografis Status Sungai Indonesia</h1>
				<hr style="border:1px solid">
			</div>

			<div class="col-sm-12" style="padding-top: 0px">
				<h2 style="color: #18525d;">Status <?= $_SESSION['nm_sungai'] ?> : <b>
					<?php if($sungai->status_sungai >= 0){?>
						Tidak Tercemar
					<?php }else if($sungai->status_sungai >= -10 && $sungai->status_sungai <= -1){ ?>
						Cemar Ringan
					<?php }else if($sungai->status_sungai >= -30 && $sungai->status_sungai <= -11){ ?>
						Cemar Sedang
					<?php }else if($sungai->status_sungai <= -31){ ?>
						Cemar Berat
					<?php } ?>
				</b></h2>
			</div>
			<?php $k=1; foreach ($titiks as $data) {
					# code...
				?>
				<div class="col-sm-12" style="padding-top: 30px">

					<h3 style="color: #18525d;"> Titik Pengambilan Sampel Sungai <?= $k ?></h3>
					<br>
					<h4 style="color: #18525d;margin-top: 0px">Titik Koordinate Latitude = <?= $data->titik_lat ?>  </h4>
					<br>
					<h4 style="color: #18525d;margin-top: 0px">Titik Koordinate Longitude = <?= $data->titik_lng ?>   </h4>
					<br>
					<h4 style="color: #18525d;margin-top: 0px">Status Titik Pengambilan Sampel Sungai = <b>
						<?php if($data->status_titik == 'Biru'){?>
							Tidak Tercemar
						<?php }else if($data->status_titik == 'Kuning'){ ?>
							Cemar Ringan
						<?php }else if($data->status_titik == 'Orange'){ ?>
							Cemar Sedang
						<?php }else if($data->status_titik == 'Merah'){ ?>
							Cemar Berat
						<?php } ?>
					</b>  </h4>
					<br>
					<br>
					<table style="table-layout:fixed;width: 730px">
						<thead>
							<tr>
								<th style="color: #18525d;vertical-align: middle;width:20px; word-wrap:break-word;">No</th>
								<th style="color: #18525d;vertical-align: middle;width:120px; word-wrap:break-word;">Temperatur / Suhu Pengukuran</th>
								<th style="color: #18525d;vertical-align: middle;width:120px; word-wrap:break-word;">EC / DHL / Daya Hantar Listrik </th>
								<th style="color: #18525d;vertical-align: middle;width:90px; word-wrap:break-word;">TDS / Partikel Terlarut </th>
								<th style="color: #18525d;vertical-align: middle;width:90px; word-wrap:break-word;" >pH / Derajat Keasaman </th>
								<th style="color: #18525d;vertical-align: middle;width:90px; word-wrap:break-word;">DO / Oksigen Terlarut </th>
								<th style="color: #18525d;vertical-align: middle;width:60px; word-wrap:break-word;">BOD</th>
								<th style="color: #18525d;vertical-align: middle;width:90px; word-wrap:break-word;">E-Coli</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach ($detail as $d) {
								if($d->id_kel_sungai_sub == $data->id_kel_sungai){
								?>
								<tr>
									<td><?= $i; ?></td>
									<td><?= $d->temperatur.' celcius' ?></td>
									<td><?= $d->ec.' mhos/cm' ?></td>
									<td><?= $d->tds.' mg/l' ?></td>
									<td><?= $d->ph.' mg/l' ?></td>
									<td><?= $d->do.' mg/l' ?></td>
									<td><?= $d->bod.' mg/l' ?></td>
									<td><?= $d->ecoli.' MPN/100 ml' ?>  </td>
								</tr>
							<?php $i++;} }?>
							</tbody>
						</table>
						<hr>
					</div>
					<?php $k++;} ?>
				</div>
			</div>
			<h5 style="color:#6C6D8A;text-align: center;margin-bottom:10px"><?= date("l jS \of F Y h:i:s A") ?></h5>