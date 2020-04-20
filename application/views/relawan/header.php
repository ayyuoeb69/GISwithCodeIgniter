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
</head>
<body>
	<nav id="nav-relawan" class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand">RELAWAN</a>
			</div>

			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li><a href="<?= base_url('') ?>"><span class="glyphicon glyphicon-home" style="margin-right:5px"></span>Home</a></li>
					<li><a href="<?= base_url('relawan/index') ?>">Input Data</a></li>
					<li><a href="<?= base_url('relawan/lihat') ?>">Lihat Data</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?= base_url('auth/logout_relawan') ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>	