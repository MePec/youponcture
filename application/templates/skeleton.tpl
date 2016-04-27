<!DOCTYPE html>
<html lang="fr">
<head>
	<title>YouPoncture</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Easily search acupuncture pathology with key words">
	<meta name="keywords" content="acupuncture, pathology, search" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<link media="screen and (min-device-width: 501px)" href="css/stylesheet.css" rel="stylesheet" type="text/css"/>
<!-- 	<link media="max-device-width: 500px)" href="css/stylesheet_mobile.css" rel="stylesheet" type="text/css"/>
	<link media="print" href="css/stylesheet_print.css" rel="stylesheet" type="text/css"/> -->

	<script type="text/javascript" src="../vendors/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="../vendors/parsley/doc/assets/docs.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

</head>

<body>
	<header>

		<div class="bandeau" role="banner">
			<a href="../public/index.php?p=1"><h1>YouPoncture</h1>
			<ul class="list-inline">
				<li class="list-inline-item"><a href="../public/index.php?p=1">Accueil</a></li>
				<li class="list-inline-item"><a href="../public/index.php?p=2">Wiki pathologie</a></li>
				<li class="list-inline-item"><a href="../public/index.php?p=3">Informations</a></li>			
				<li class="list-inline-item">{$smarty.session.logon_status}</li>	
			</ul>
		</div>

		<!-- <div class="menu">
				<ul>
					<li><a href="../public/index.php?p=1">Accueil</a></li>
					<li><a href="../public/index.php?p=2">Wiki pathologie</a></li>
					<li><a href="../public/index.php?p=3">Informations</a></li>			
					<li>{$smarty.session.logon_status}</li>	
				</ul>
		</div> -->
	</header>

	<div class="content">
		{block name=contenu}{/block}
	</div>

	<footer>
		<ul>
			<li><a tabindex="13" href="../public/index.php?p=3#auteurs">Auteur</a></li>
			<li><a tabindex="14" href="">Plan du site</a></li>
			<li>Copyright</li>
		</ul>
	</footer>

</body>
</html>