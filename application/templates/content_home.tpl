{extends file='./skeleton.tpl'}
{block name=contenu}

		<div class="container">
			<div class="col1">
				<div class="login">
					<h1>Se connecter <i class="fa fa-sign-in" aria-hidden="true"></i></h1>
					<form action="index.php?p=1&q=2" method="post" name="login_form">
						<small class="text-muted">Votre identifiant correspond à l'adresse e-mail que vous avez utilisez lors de votre inscription.</small>
						<input class="form-control" type="email" id="email_addr" name="login" tabindex="4" placeholder="Identifiant" required>
						<input class="form-control" type="password" id="password" name="password" tabindex="5" placeholder="Mot de passe" required>
						<input class="form-control" type="submit" tabindex="6" name="connection" value="Connexion">
					</form>
				</div>

				<div class="register">
					<h1>Nouvel utilisateur <i class="fa fa-user-plus" aria-hidden="true"></i></h1>
					<form action="index.php?p=1&q=1" method="post" name="sign_form">
						<input class="form-control" type="text" id="lst_name_subscr" name="name" placeholder="Nom" tabindex="7" required>
						<input class="form-control" type="text" id="frst_name_subscr" name="first_name" placeholder="Prénom" tabindex="8" required>
						<input class="form-control" type="email" id="lgn_subscr" name="login" placeholder="Adresse email" tabindex="9" required>
						<input class="form-control" type="password" id="pwd_subscr" name="pwd_subscr" tabindex="10" placeholder="Mot de passe" required>
						<input class="form-control" type="password" class="confirmed" id="pwd_2_subscr" name="pwd_2_subscr" tabindex="11" placeholder="Confirmez le mot de passe" required>
						<input class="form-control" type="submit" id="accnt_subscr" name="accnt_subscr" tabindex="12" value="S'enregistrer">
					</form>
				</div>
			</div>

			<div class="col2">
				<div class="rss_feed">
					<h1 title="Flux RSS">Flux RSS <i class="fa fa-rss-square" aria-hidden="true"></i></h1>
					<aside>
						<table id="rss">
							{section name=result_rss loop=$rss}
							<tr>
								<td><a href="{$rss[result_rss].ITEM_LINK}">{$rss[result_rss].ITEM_TITLE}</td>			
								<td>{$rss[result_rss].ITEM_DESCRIPTION}</td>
							</tr>		
							{/section}	
						</table>				
					</aside>
				</div>
			</div>
		</div>
	
{/block}
