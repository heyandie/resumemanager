<html>
<style type="text/css">
	body{
		position: relative;
		font-family: Helvetica;
		font-size: 13px;

	}

	a{
		cursor: pointer;
		text-decoration: none;
		font-weight: bold;
		color:#111;
	}
	h1{
		width: auto;
		display: inline-block;
	}
</style>
<body style="position: relative;font-family: Helvetica;font-size: 13px;">
<div style="margin: 6% auto;width: 88%;background: #fff;">

<div class="mail-container" style="padding: 20px 20px;background: #fff;">
<img src="http://resume.upcapes.org/images/mailheader.png" width="100%"></img>
<div style="margin: 6% auto;width: 88%;">
<p>Hi <b style="color:#D9AF0B"><?php echo $first_name." ".$last_name;?></b>,</p>

<p>You have requested to reset your password. Kindly go to <a href="http://resume.upcapes.org/login/reset_password?id=<?php echo $key;?>">http://resume.upcapes.org/login/reset_password?id=<?php echo $key;?></a></p>
<br>
<br>Kindest regards,<br>
UP CAPES<br><br>

</div>
<img src="http://resume.upcapes.org/images/mailfooter.png" width="100%"></img>
</div>

</div>
</body>
</html>