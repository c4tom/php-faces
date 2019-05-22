<?php header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page Not Found</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
<p>
<?php echo  $message ?>
</p>
</div>


</body>
</html>