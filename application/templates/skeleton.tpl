<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="keywords" content="xhtml, html5, form" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<title>YouPoncture</title>
	<link href="css/stylesheet.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<header>
		<div class="bandeau">
			<img src="" alt="">
			<h1>YouPoncture</h1>
			<span><i class="fa fa-user"></i></span>
		</div>
		<div class="menu">
			<nav>
				<ul>
					<li>Accueil</li>
					<li>Wiki pathologie</li>
					<li><a href="infos.tpl">Informations<a/></li>
				</ul>
			</nav>
		</div>
	</header>

	<div class="content">
		{block name=contenu}{/block}
	</div>

	<footer>
		<ul>
			<li><a href="">Auteur</a></li>
			<li><a href="">Plan du site</a></li>
			<li>Copyright {$name}</li>
		</ul>
	</footer>

</body>
</html>