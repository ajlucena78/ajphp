<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Error en la aplicaci&oacute;n - <?php echo APP_NAME; ?></title>
		<?php include $_SESSION['config']->getPathView() . '/includes/head.php'; ?>
	</head>
	<body>
		<div>
			Error en la aplicaci&oacute;n: <?php echo $error; ?>
		</div>
	</body>
</html>