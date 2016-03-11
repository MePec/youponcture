<?php
/* Smarty version 3.1.29, created on 2016-03-11 14:24:26
  from "C:\Applications\www\6_YouPoncture\application\templates\trame.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56e2d51a3c25f0_71311990',
  'file_dependency' => 
  array (
    'c470321127d9951961dee64ac85558a10fb7499a' => 
    array (
      0 => 'C:\\Applications\\www\\6_YouPoncture\\application\\templates\\trame.tpl',
      1 => 1457706109,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56e2d51a3c25f0_71311990 ($_smarty_tpl) {
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
  0 => 'block_3116356e2d51a3a31e3_25388166',
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
function block_3116356e2d51a3a31e3_25388166($_smarty_tpl, $_blockParentStack) {
}
/* {/block 'contenu'} */
}
