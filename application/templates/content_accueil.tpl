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