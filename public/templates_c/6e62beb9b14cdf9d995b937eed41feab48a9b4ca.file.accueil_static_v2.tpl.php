<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-03-11 11:07:04
         compiled from "../application/templates/accueil_static_v2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:94287274656e298a2c44b14-83539473%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e62beb9b14cdf9d995b937eed41feab48a9b4ca' => 
    array (
      0 => '../application/templates/accueil_static_v2.tpl',
      1 => 1457690822,
      2 => 'file',
    ),
    'f87efefc3b703e922c4cbad0469c4efe18b4b2ad' => 
    array (
      0 => '/home/maxime/public_html/application/templates/trame.tpl',
      1 => 1457639852,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '94287274656e298a2c44b14-83539473',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56e298a2c4b3c4_09367252',
  'variables' => 
  array (
    'name' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56e298a2c4b3c4_09367252')) {function content_56e298a2c4b3c4_09367252($_smarty_tpl) {?><!DOCTYPE>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="keywords" content="xhtml, html5, form" />
	<meta name="author" content="PE Cochet - Fr Michel - Ch Chaumier - Mx Brunet" />
	<title>YouPoncture</title>
	<link href="display/css/stylesheet.css" rel="stylesheet" type="text/css"/>
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
					<li><a href="infos.tpl">Informations<a/></li>
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
			<li>Copyright <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</li>
		</ul>
	</footer>

</body>
</html><?php }} ?>
