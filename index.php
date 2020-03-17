<?php
	include("templates/header.php");
	include("templates/admin.php");
	include("templates/navigation.php");
	include("templates/translator.php");
	include("templates/login.php");
	
	$login = new Login(); 
	$header = new Header($login->userInfo);

	$pageToRender = "";
	if(isset($_GET["action"]))
		$pageToRender = $_GET["action"];
	
	if(!$login->logged)
		$pageToRender = "login";
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="images/favicon.ico">
	<title>Thesaurus UK</title>
	
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
	
	<script src="scripts/main.js"></script>

	<link rel="stylesheet" href="styles/main.css">

</head>

<body>
	<?php
		
		$header->render();
		switch($pageToRender){
			case "login":
				$login->render();
				break;				
			case "logout":
				setcookie("sessionID", "", time() - 3600); // todo
				header('Location: ?action=home');
				$login->render();
				break;				
			case "admin":
				$admin = new Admin($login->userInfo);
				$admin->render();
				break;
			default:
				$navigation = new Navigation();
				$translator = new Translator();
				$navigation->render();
				$translator->render();
				break;
		}
		
	?>
</body>

</html>