<?php
	$error='';
	$n_error='';
	if(isset($_GET['error'])){
		$error=$_GET['error'];
	}
	if(isset($_GET['n_error'])){
		$n_error=$_GET['n_error'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	
    <title>Error <?php echo $n_error;?></title>
</head>
<body>
	<h1>ERROR </h1>
	<hr/>
	<h1>ERROR  <?php echo $n_error;?></h1>
	<hr/>
	<h2><?php echo $error;?></h2>
	<h2><a href='index.php'>Volver</a></h2>
</body>
</html>