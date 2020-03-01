<?php
	include("templates/header.php");
	include("templates/navigation.php");
	include("templates/translator.php");
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="images/favicon.ico">
	<title>Thesaurus UK</title>
	
	<script src="scripts/main.js"></script>

	<link rel="stylesheet" href="styles/main.css">
</head>

<body>
	<?php $header = new Header(); $header->render(); ?>
	<?php $navigation = new Navigation(); $navigation->render(); ?>
	<?php $translator = new Translator(); $translator->render(); ?>
</body>

</html>