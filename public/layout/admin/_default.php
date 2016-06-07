<html>
	<head>
		<?php if(file_exists( $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR  . '_head.php')) 
			include  $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_head.php'; 
		?>
	</head>
	<body>
		<?php if(file_exists( $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_body.php')) 
			include  $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_body.php';
		?>
	</body>
</html>