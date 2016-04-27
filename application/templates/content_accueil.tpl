{extends file='./skeleton.tpl'}
{block name=contenu}

		<div class="login">
			<h1>Se connecter <i class="fa fa-sign-in" aria-hidden="true"></i></h1>

			<form action="index.php?p=1&q=2" method="post" name="login_form">
				<input class="email" type="email" id="email_addr" name="login" tabindex="4" required oninput="checkMail(this)" placeholder="Rentrez votre login">
				<input class="pwd" type="password" id="password" name="password" tabindex="5" required placeholder="Rentrez votre MDP">
				<input type="submit" tabindex="6" name="connection" value="Connexion">
			</form>
		</div>

		<div class="register">
			<h1>Nouvel utilisateur</h1>
			<form action="index.php?p=1&q=1" method="post" name="sign_form">
				<input type="text" id="lst_name_subscr" name="name" placeholder="Rentrez votre nom" tabindex="7">
				<input type="text" id="frst_name_subscr" name="first_name" placeholder="Rentrez votre prenom" tabindex="8">
				<input class="email" type="email" id="lgn_subscr" name="login" placeholder="Rentrez votre adresse email" tabindex="9" oninput="checkMail(this)">
				<input type="password" id="pwd_subscr" name="pwd_subscr" tabindex="10" placeholder="Rentrez un MDP">
				<input type="password" class="confirmed" id="pwd_2_subscr" name="pwd_2_subscr" tabindex="11" placeholder="Rentrez un MDP">
				<input type="submit" id="accnt_subscr" name="accnt_subscr" tabindex="12" value="S'enregistrer">
			</form>
		</div>

		<div class="flux_rss">
			<h1 title="Flux RSS">Flux RSS</h1>
			<table id="rss">
				{section name=result_rss loop=$rss}
				<tr>
					<!-- <td>{$rss[result_rss].ITEM_DATE}</td>;	 -->
					<td><a href="{$rss[result_rss].ITEM_LINK}">{$rss[result_rss].ITEM_TITLE}</td>			
					<td>{$rss[result_rss].ITEM_DESCRIPTION}</td>
				</tr>		
				{/section}	
			</table>
		</div>

{/block}
