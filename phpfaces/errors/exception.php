<html>
<head>
<title>Exception</title>
<style type="text/css">

body{
font-family:Arial, Helvetica, sans-serif; 
font-size:13px;
}
 .Exception{
border: 1px solid;
margin: 10px 0px;
padding:15px 10px 15px 50px;
background-repeat: no-repeat;
background-position: 20px top;
color: #9F6000;
background-color: #FEEFB3;
background-image:  url('<?php echo BASE_URL."errors/exception.png"?>') ;
}





</style>
</head>
<body>
<div class="Exception">
	<b>&nbsp;&nbsp; <?php echo $heading; ?> </b>
<br><br>

<li> Error code : <?php echo  $e->getCode(); ?></li>
<li> File : <?php echo $e->getFile(); ?></li>
<li> Line : <?php echo $e->getLine(); ?></li>
<li> Message : <?php echo $e->getMessage(); ?></li>
<li> Trace : <?php echo $e->getTraceAsString(); ?></li>

</div>


</body>
</html>