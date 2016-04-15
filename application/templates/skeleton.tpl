<!DOCTYPE html>
<html lang="fr">
<head>
	<title>YouPoncture</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Easily search acupuncture pathology with key words">
	<meta name="keywords" content="acupuncture, pathology, search" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<link media="screen and (min-device-width: 501px)" href="css/stylesheet.css" rel="stylesheet" type="text/css"/>
	<link media="max-device-width: 500px)" href="css/stylesheet_mobile.css" rel="stylesheet" type="text/css"/>
	<link media="print" href="css/stylesheet_print.css" rel="stylesheet" type="text/css"/>

	<script type="text/javascript" src="../vendors/jquery/jquery.min.js"></script>
</head>

<body>
	<header>
		<div class="bandeau">
			<h1>YouPoncture</h1>
			<span><i class="fa fa-user"></i></span>
		</div>
		<div class="menu">
				<ul>
					<li><a href="../public/index.php?p=1">Accueil</a></li>
					<li><a href="../public/index.php?p=2">Wiki pathologie</a></li>
					<li><a href="../public/index.php?p=3">Informations</a></li>			
					<li>{$smarty.session.logon_status}</li>	
				</ul>
		</div>
	</header>

	<div class="content">
		{block name=contenu}{/block}
	</div>

	<footer>
		<ul>
			<li><a href="">Auteur</a></li>
			<li><a href="">Plan du site</a></li>
			<li>Copyright</li>
		</ul>
	</footer>

</body>
</html>