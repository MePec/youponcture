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
		<table id="ky_results" border="1" >
			<tr>
			   <th>Résultat</th>
			   <th>Symptômes</th>
			</tr>
			{section name=result_patho loop=$patho_ky}
			<tr>
				<td>{$patho_ky[result_patho].PATHOS}</td>			
			</tr>		
			{/section}	
		</table>
	</div>


 
	<div class="list">
		<h1>Liste des résultats :</h1>
		<table id="list_result_pat" border="1" >
			<tr>
			   <th>Pathologies</th>
			</tr>
			{section name=customer loop=$pathology}
			<tr>
				<td>{$pathology[customer].PATHO_DESC}</td>	
			</tr>
			{/section}
		</table>

		<table id="list_result_mer" border="1" >
			<tr>
			   <th>Méridiens</th>
			</tr>
			{section name=merid loop=$meridiens}
			<tr>
				<td>{$meridiens[merid].MERID_DESC}</td>	
			</tr>
			{/section}
		</table>

		<table id="list_result_sympt" border="1" >
			<tr>
			   <th>Symptomes</th>
			</tr>
			{section name=sympt loop=$symptoms}
			<tr>
				<td>{$symptoms[sympt].SYMPT_DESC}</td>	
			</tr>
			{/section}
		</table>
	</div>

{/block}


