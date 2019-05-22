<html>
<head>
<title>Error</title>
<style type="text/css">

body{
font-family:Arial, Helvetica, sans-serif; 
font-size:13px;
}
.error {

background-color: #FEEFB3;
border-top:  3px solid  #D7AD03;
border-bottom: 3px solid #D7AD03;
margin: 15px 0px;
padding:15px 10px 15px 50px;
background-repeat: no-repeat;
background-position: 5px top;
background-image:  url('<?php echo BASE_URL."errors/exception.png"?>');
}

</style>
</head>
<body>

<div class="error" width="%100" >
	<b><?php echo $heading; ?></b>
<br>
<li> Error code : <?php echo  $e->getCode(); ?></li>
<li> File : <?php echo $e->getFile(); ?></li>
<li> Line : <?php echo $e->getLine(); ?></li>
<li> Message :<b> <?php echo $e->getMessage(); ?></b></li>
<li> Trace : </li><?php echo $e->getTraceAsString(); ?>
</div>


</body>
</html>