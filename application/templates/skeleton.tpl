<!DOCTYPE html>
<html lang="fr">
<head>
	<title>YouPoncture</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Easily search acupuncture pathology with key words">
	<meta name="keywords" content="acupuncture, pathology, search" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<link media="screen and (min-width: 780px)" href="css/stylesheet.css" rel="stylesheet" type="text/css"/>
	<link media="(max-width: 779px)" href="css/stylesheet_mobile.css" rel="stylesheet" type="text/css"/>
	<link media="print" href="css/stylesheet_print.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="../vendors/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="../vendors/parsley/doc/assets/docs.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

</head>

<body>
	<div class="page-wrap">
		<header>

			<div class="bandeau" role="banner">
				<a href="../public/index.php?p=1"><h1>YouPoncture</h1></a>
				<ul class="list-inline">
					<li class="list-inline-item"  tabindex="1"><a href="../public/index.php?p=1">Accueil</a></li>
					<li class="list-inline-item"  tabindex="2"><a href="../public/index.php?p=2">Wiki pathologie</a></li>
					<li class="list-inline-item"  tabindex="3"><a href="../public/index.php?p=3">Informations</a></li>			
					<li class="list-inline-item">{$smarty.session.logon_status}</li>	
				</ul>
			</div>
		</header>

		<div class="content">
			{block name=contenu}{/block}
		</div>
	</div>
	
	<footer>
		<div class="container-fluid">
			<div class="row foo-content">
				<div class="col-md-4"><a tabindex="13" href="../public/index.php?p=3#auteurs">Auteur</a></div>
				<div class="col-md-4"><a tabindex="14" href="">Plan du site</a></div>
				<div class="col-md-4">Copyright 2016</div>
			</div>			
		</div>
	</footer>

</body>
</html>