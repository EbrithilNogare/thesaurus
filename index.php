<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="images/favicon.ico">
	<title>Thesaurus UK</title>

	<script src="scripts/main.js"></script>

	<link rel="stylesheet" href="styles/main.css">

	<link rel="stylesheet" href="styles/materialize.css">
	<script src="scripts/materialize.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="light-blue lighten-4">
	<?php include("templates/components/header.html"); ?>
	<section class="container">
		<div class="row card-panel light-blue lighten-3">
			<?php include("templates/navigation.php"); ?>
			<?php include("templates/translator.php"); ?>			
		</div>
	</section>
</body>

</html>