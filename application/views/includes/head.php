<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
  <title>Resume Manager</title>

  <!--loading stylesheets-->
  <?php if(isset($admin_css)){echo $admin_css;}?>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/unsemantic-grid-responsive-tablet.css">
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/resumebox.css">
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/font-awesome.css">
  <link href='http://fonts.googleapis.com/css?family=Bitter:400,700' rel='stylesheet' type='text/css'>
  <link rel="icon" href="http://upcapes.org/wp-content/uploads/2011/11/CAPES-Logo.png" type="image/png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<?php if(!isset($nopreload)):?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/jpreloader.css">
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jpreloader.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
        
    

        $jpreOptions = { splashID: "#jpreSplash", loaderVPos: "50%" ,showSplash: true}
        $('body').jpreLoader($jpreOptions);

        
   });
</script>
<?php endif ?>
</head>


<body>