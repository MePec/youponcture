{extends file='./skeleton.tpl'}
{block name=contenu}
		<div class="login">
			<h1>Se connecter</h1>
			<form action="#">
				<input class="mail"></input>
				<input class="mdp" type="password"></input>
				<input type="submit">Connexion</input>

			</form>
		</div>

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
{/block}