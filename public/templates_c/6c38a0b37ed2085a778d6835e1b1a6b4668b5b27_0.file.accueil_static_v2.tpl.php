<?php
/* Smarty version 3.1.29, created on 2016-03-11 00:06:26
  from "C:\Program Files (x86)\wamp\www\youponcture\application\templates\accueil_static_v2.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56e1fdf25ffb26_99024569',
  'file_dependency' => 
  array (
    '6c38a0b37ed2085a778d6835e1b1a6b4668b5b27' => 
    array (
      0 => 'C:\\Program Files (x86)\\wamp\\www\\youponcture\\application\\templates\\accueil_static_v2.tpl',
      1 => 1457646482,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./trame.tpl' => 1,
  ),
),false)) {
function content_56e1fdf25ffb26_99024569 ($_smarty_tpl) {
$_smarty_tpl->ext->_inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->ext->_inheritance->processBlock($_smarty_tpl, 0, 'contenu', array (
  0 => 'block_2544856e1fdf2547158_13966682',
  1 => false,
  3 => 0,
  2 => 0,
));
$_smarty_tpl->ext->_inheritance->endChild($_smarty_tpl);
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:./trame.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block 'contenu'}  file:../application/templates/accueil_static_v2.tpl */
function block_2544856e1fdf2547158_13966682($_smarty_tpl, $_blockParentStack) {
?>

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
<?php
}
/* {/block 'contenu'} */
}
