<!DOCTYPE html>
<html>

<head>
	<title>Identificación</title>
	<meta charset='utf-8' />
</head>

<body>

	<div style="width:80%;margin:auto">
		<h1>IDENTIFICACIÓN</h1>
		<hr />
		<form action='validar.php' method='get'>
			<fieldset>
				<legend>Identificación</legend>
				Número de matrícula:<br />
				<input type="number" name="num_matricula" min='1' />
				<br />
				Clave:<br />
				<input type='password' name='clave' />
				<br />
			</fieldset><br />
			<fieldset>
				<input type='submit' value='Entrar' />
				<input type='reset' value='Limpiar' />
			</fieldset>
		</form>
	</div>
    <div class="bg-indigo-200 w-3/4 mx-auto">
        <h3><?php if(isset($_GET['banner'])) { echo $_GET['banner'];} ?></h3>
    </div>
</body>

</html>