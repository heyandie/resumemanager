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

<p>Your resume has been successfully generated. We have attached .pdf copy of it in this email. You can always update and resubmit your resume at resume.upcapes.org</p>

<?php if(isset($upload)):?>
	<p>Your own resume has also been uploaded and we will consider it as your official resume.</p>
<?php endif ?>

<p>For graduating students, kindly print a copy of this email and the attachment then submit it to the administration (MH 204) or Graduates Office (MH 119) during application for graduation.</p>

<br>
<br>Kindest regards,<br>
UP CAPES<br><br>

</div>
<img src="http://resume.upcapes.org/images/mailfooter.png" width="100%"></img>
</div>

</div>
</body>
</html>