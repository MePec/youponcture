{extends file='./skeleton.tpl'}
{block name=contenu}
	<div class="search">
		<h1>Recherche</h1>
		<form action="index.php?p=2&q=2" method="post">
			<fieldset class="public_search">
				<legend>Par critère : </legend>
				<label class="type_critere" for="type_patho">Type de Pathologie :</label>
				 <select name="type_patho">
				   <option value="meridien">Méridien</option>
				   <option value="organe_viscere">Organe/Viscère</option>
				   <option value="luo">Luo</option>
				   <option value="merveilleux">Merveilleux vaisseaux</option>
				   <option value="jing">Jing jin</option>
				</select> 
				<label class="type_critere" for="type_meridien">Choix des méridiens :</label>
				 <select name="type_meridien" multiple tabindex="">
				 	{section name=merid loop=$meridiens}
					<option value="{$meridiens[merid].MERID_DESC}">{$meridiens[merid].MERID_DESC}</option>
					{/section}
				</select> 
				<label class="type_critere" for="caracteristiques">Caractéristiques :</label>
				 <select name="caracteristiques_meridien" tabindex="">
				   <option value="plein">Plein</option>
				   <option value="chaud">Chaud</option>
				   <option value="vide">Vide</option>
				   <option value="froid">Froid</option>
				   <option value="interne">Interne</option>
				   <option value="externe">Externe</option>
				</select> 
				<input type="submit" value="Rechercher"></input>
			</fieldset>
		</form>
		<form action="index.php?p=2&q=3" method="post">
			<fieldset class="member_search">
				<legend>Par mot-clés : </legend>
				<input type="text" id="search_input" name="keywords"/>
				<input type="submit" value="Rechercher"></input>
			</fieldset>
		</form>
	</div>

	<div class="keyword_results">
		<h1>Résultats par mots-clé :</h1>
<!-- 		<table id="ky_results" border="1" >
			<tr>
			   <th>Résultat</th>
			</tr>
			{section name=result_sympt loop=$symptoms_ky}
			<tr>
				<td>{$symptoms_ky[result_sympt].SYMPTOMS}</td>	
			{/section}
			</tr>
			
		</table> -->
	</div>

	<div class="list">
		<h1>Liste des résultats :</h1>
		<table id="list_result" border="1" >
			<tr>
			   <th>Pathologies</th>
<!-- 			   <th>Méridiens</th>
			   <th>Symptomes</th> -->
			</tr>
			{section name=customer loop=$pathology}
			<tr>
				<td>{$pathology[customer].PATHO_DESC}</td>	
<!-- 			{/section}
			{section name=merid loop=$meridiens}
				<td>{$meridiens[merid].MERID_DESC}</td>	
			{/section}
			{section name=sympt loop=$symptoms}
				<td>{$symptoms[sympt].SYMPT_DESC}</td> -->	
				
			</tr>
			{/section}
		</table>
	</div>

{/block}


