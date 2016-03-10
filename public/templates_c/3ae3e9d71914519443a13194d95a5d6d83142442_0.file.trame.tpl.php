<?php
/* Smarty version 3.1.29, created on 2016-03-11 00:06:26
  from "C:\Program Files (x86)\wamp\www\youponcture\application\templates\trame.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56e1fdf2be5a45_09259533',
  'file_dependency' => 
  array (
    '3ae3e9d71914519443a13194d95a5d6d83142442' => 
    array (
      0 => 'C:\\Program Files (x86)\\wamp\\www\\youponcture\\application\\templates\\trame.tpl',
      1 => 1457646482,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56e1fdf2be5a45_09259533 ($_smarty_tpl) {
$_smarty_tpl->ext->_inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE>
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
		<?php 
$_smarty_tpl->ext->_inheritance->processBlock($_smarty_tpl, 0, 'contenu', array (
  0 => 'block_2768656e1fdf29d6e74_02840674',
  1 => false,
  3 => 0,
  2 => 0,
));
?>

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
</html><?php }
/* {block 'contenu'}  file:./trame.tpl */
function block_2768656e1fdf29d6e74_02840674($_smarty_tpl, $_blockParentStack) {
}
/* {/block 'contenu'} */
}
