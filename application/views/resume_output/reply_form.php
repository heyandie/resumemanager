<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style type="text/css">

body{
	/*background-image: url(images/paper.jpg);
	background-size: auto 100%;
	background-position: center;*/
	min-height: 1100px;
	color: #080808;
	font-size: 12.5px;
	font-family: Helvetica,sans-serif;
}


body,div,span,b,h1,h2,h3,h4,h5{
	color: #080808;
}
span.fullname{
	margin: 0px;
	font-size: 24px;
	color: #000;
	font-weight: bold;
}

</style>
</head>
<body>
	<div style="text-align:right">
		<h3><?php echo $this->session->userdata('first_name');?> <?php echo $this->session->userdata('last_name');?>
			<?php echo '<'.$this->session->userdata('email_address').'>';?></h3>

	</div>

</body>