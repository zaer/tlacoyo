<?php
	$db = new DataBase();
	$db->connect();
	$conf = new Site_config($db);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title><?=$conf->getCve('TITULO')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<link href="//fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css" media="all">
</head>
<body>
	<div class="w3l-signinform">
		<!-- container -->
		<div class="wrapper">
			<!-- main content -->
			<div class="w3l-form-info">
				<div class="w3_info">
					<h1>bienvenido</h1>
					<p class="sub-para"><?=$conf->getCve('APP_NAME')?></p>
					<h2>Inicio de sesión</h2>
					<p style="color: crimson;">
						<?php
							foreach($data["errores"] as $error)
							{
								echo "<span style='color:#fff;margin:10px;'>Error: </span>".$error;
							}
							foreach($data["msg"] as $msg)
							{
								echo "<span style='color:#fff;margin:10px;'>Error: </span>".$msg;
							}
						?>
					</p>
					<form action="#" method="post">
						<div class="input-group">
							<span><i class="fa fa-user" aria-hidden="true"></i></span>
							<input type="text" placeholder="Email or Username" name="usuario" required="">
						</div>
						<div class="input-group two-groop">
							<span><i class="fa fa-key" aria-hidden="true"></i></span>
							<input type="Password" placeholder="Password" name="password" required="">
							<input type="hidden" name="login" value="<?=base64_encode(time())?>">
						</div>
						<div class="form-row bottom">
							<div class="form-check">
								<input type="checkbox" id="remenber" name="remenber" value="remenber">
								<label for="remenber">Recordar contraseña?</label>
							</div>
							<a href="#url" class="forgot">Omitir recordatorio?</a>
						</div>
						<button class="btn btn-primary btn-block" type="submit">Ingresar</button>
					</form>
				
					<p class="account">Quiere ingresar al sistema? <a href="#register">Registro</a></p>
				</div>
			</div>
			<!-- //main content -->
		</div>
		<!-- //container -->
		<!-- footer -->
		<div class="footer">
			<p><?=$conf->getCve('FOOTER')?></p>
		</div>
		<!-- footer -->
	</div>
</body>
</html>