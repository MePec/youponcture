{extends file='./skeleton.tpl'}
{block name=contenu}
		<div class="login">
			<h1>Se connecter</h1>
			<form action="index.php?p=1&q=2" method="post">
				<input class="mail" type="text" name="login" autofocus><label for="Login">Identifiant</label></input>
				<input class="mdp" type="password" name="mdp"><label for="MDP">Mot de Passe</label></input>
				<input type="submit" name="connexion" value="Se connecter">Connexion</input>

			</form>
		</div>

		<div class="register">
			<h1>Nouvel utilisateur</h1>
			<form action="index.php?p=1&q=1" method="post">
				<input type="text" alt="Nom" name="nom" value="Rentrer votre nom"></input>
				<input type="text" alt="Prenom" name="prenom" value="Rentrer votre prenom"></input>
				<input type="text" alt="Login" name="login" value="e mail"></input>
				<input type="text" type="password" name="mdp" value="mdp"></input>
				<input type="text" class="confirm"  type="password" name="mdp_bis" value="mdp bis">MDP Bis</input>
				<input type="submit" value="Créer un compte" name="send_signup"></input>
			</form>
		</div>

		
{/block}

<!-- 		<div class="login">
			<h1>Se connecter</h1>
			<form action="#" method="post">
				<input class="email" type="email" id="email_addr" name="email_addr" tabindex="1" required oninput="checkMail(this)">
				<input class="pwd" type="password" id="password" name="password" tabindex="2" required>
				<input type="submit" tabindex="3">Connexion>
			</form>
		</div>

		<div class="register">
			<h1>Nouvel utilisateur</h1>
			<form action="#" method="post">
				<input type="text" id="lst_name_subscr" name="nom" placeholder="Rentrez votre nom" tabindex="4">
				<input type="text" id="frst_name_subscr" name="prenom" placeholder="Rentrez votre prenom" tabindex="5">
				<input type="text" id="lgn_subscr" name="login" placeholder="Rentrez votre adresse email" tabindex="6">
				<input type="password" id="pwd_subscr" name="pwd_subscr" tabindex="7">
				<input type="password" class="confirmed" id="pwd_2_subscr" name="pwd_2_subscr" tabindex="8">
				<input type="submit" id="accnt_subscr" name="accnt_subscr" tabindex="9">
			</form>
		</div> -->