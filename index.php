<?php
	include("templates/navigation.php");
	include("templates/translator.php");
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="images/favicon.ico">
	<title>Thesaurus UK</title>

	<link rel="stylesheet" href="styles/materialize.css">
	<script src="scripts/materialize.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<script src="scripts/main.js"></script>

	<link rel="stylesheet" href="styles/main.css">
</head>

<body class="light-blue lighten-4">
	<?php include("templates/components/header.html"); ?>
		<div class="row">
			<?php $navigation = new Navigation(); $navigation->render(); ?>
			<?php $translator = new Translator(); $translator->render(); ?>
		</div>
</body>

</html>