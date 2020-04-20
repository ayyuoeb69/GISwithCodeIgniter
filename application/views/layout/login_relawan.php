<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> SIG Status Sungai Indonesia </title>
  
      <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/stylelogin.css">

  
</head>

<body>

<!--   <div id="clouds">
</div> -->
<div class="col-sm-12" id="bg_login_relawan">
  <div id="bg_hitam">
 <div class="container" style="text-align: center;">


      <div id="login">
      	<h1 style="font-family: myf;margin-bottom:20px;font-weight: bold;font-size: 40px;line-height: 1.3em;color: #19aac6">Login Relawan</h1>

          <fieldset class="clearfix">
          		<?php echo form_open('Auth/login_relawan'); ?>
            <p><span class="fontawesome-user"></span><input type="text" value="Username" name="user" onBlur="if(this.value == '') this.value = 'Username'" onFocus="if(this.value == 'Username') this.value = ''" required></p> 
           <!--  <p><span class="fontawesome-lock"></span><input type="text" name="nama"></p>  -->
            <p><span class="fontawesome-lock"></span><input type="password" name="pass"  value="Password" onBlur="if(this.value == '') this.value = 'Password'" onFocus="if(this.value == 'Password') this.value = ''" required></p> 
            <!-- <p><span class="fontawesome-lock"></span><input type="text" name="status"></p>  -->
            <p><input type="submit" value="MASUK"></p>
				<?php echo form_close(); ?>
        <a href="<?= base_url(); ?>">
        <h2 style="color: #19aac6;font-family: myf2;font-weight: bold">Klik untuk kembali ke Halaman Utama</h2>
      </a>
          </fieldset>



      </div> 

    </div>
    
  
</div></div>
</body>

</html>