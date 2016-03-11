<!DOCTYPE>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="keywords" content="xhtml, html5, form" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<title>YouPoncture</title>
	<link href="assets/css/stylesheet.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<header>
		<div class="bandeau">
			<img src="" alt="">
			<span>YouPoncture</span>
			<span><i class="fa fa-user"></i></span>
		</div>
		<div class="menu">
			<nav>
				<ul>
					<li>Accueil</li>
					<li>Wiki pathologie</li>
					<li>Auteur</li>
				</ul>
			</nav>
		</div>
	</header>


	<div class="content">
		<div class="register">
			<h1>Nouvel utilisateur</h1>
			<form action="#">
				<input type="text" alt="Nom" name="nom" value="etrer votre nom"></input>
				<input type="text" alt="Prenom" name="prenom" ></input>
				<input type="text" ></input>
				<input type="text" type="password"></input>
				<input type="text" class="confirm" type="password"></input>
				<input type="submit" value="Créer un compte"></input>
			</form>
		</div>

		<div class="login">
			<h1>Se connecter</h1>
			<form action="#">
				<input class="mail"></input>
				<input class="mdp" type="password"></input>
				<input type="submit">Connexion</input>

			</form>
		</div>
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