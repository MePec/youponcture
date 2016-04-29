{extends file='./skeleton.tpl'}
{block name=contenu}

	<div class="search">
		<h1>Recherche</h1>
		<form action="index.php?p=2&q=2" method="post">
			<fieldset class="public_search">
				<legend>Par critère : </legend>
				<label class="type_critere" for="type_patho">Type de Pathologie :</label>
				 <select name="type_patho" id="type_patho">
				   <option value="m" selected="selected">Méridien</option>
				   <option value="tf">Organe/Viscère</option>
				   <option value="l">Luo</option>
				   <option value="mv">Merveilleux vaisseaux</option>
				   <option value="j">Jing jin</option>
				</select> 

				<label class="caracteristiques_meridien" for="caracteristiques">Caractéristiques :</label>
				 <select name="caracteristiques_meridien" class="caracteristiques_meridien">
				 <option selected="true" value="default">-</option>
				   <option value="i">Interne</option>
				   <option value="e">Externe</option>
				   <option value="p">Plein</option>
				   <option value="c">Chaud</option>
				   <option value="v">Vide</option>
				   <option value="f">Froid</option>
				</select> 

				<label class="type_critere" for="type_meridien">Choix des méridiens :</label>
				  <select name="type_meridien[]" multiple >
				 	{section name=merid loop=$meridiens}
					<option value="{$meridiens[merid].DESC}" selected="selected">{$meridiens[merid].DESC}</option>
					{/section}
				</select> 
				<input type="submit" value="Rechercher"></input>
			</fieldset>
		</form>
		<form action="index.php?p=2&q=3" method="post" class="member_search">
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
			   <th>Pathologie</th>
			   <th>Symptômes</th>
			</tr>
			{section name=result_patho loop=$patho_ky}
			<tr>
				<td>{$patho_ky[result_patho].PATHOS}</td>	
				<td>{$patho_ky[result_patho].SYMPT}</td>			
			</tr>		
			{/section}	
		</table>
	</div>

	<div class="criter_results">
		<h1>Résultats par critères :</h1>
		<table id="cri_results_pat" border="1" >
			<tr>
			   <th>Pathologies</th>
			   <th>Symptômes</th>
			</tr>
			{section name=result_criter loop=$patho_res}
			<tr>
				<td>{$patho_res[result_criter].RESULT_PATHO}</td>
				<td><ul>{section name=itera loop=$sy_res[{$smarty.section.result_criter.index}]}<li>{$sy_res[{$smarty.section.result_criter.index}].{$smarty.section.itera.index}.RESULT_SY}</li>{/section}</ul></td>
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
				<td>{$pathology[customer].DESC}</td>	
			</tr>
			{/section}
		</table>

		<table id="list_result_sympt" border="1" >
			<tr>
			   <th>Symptomes</th>
			</tr>
			{section name=sympt loop=$symptoms}
			<tr>
				<td>{$symptoms[sympt].DESC}</td>	
			</tr>
			{/section}
		</table>
	</div>

	<script type="text/javascript">Meridien_selected();</script>
	<script type="text/javascript">$(window).load(loadKwSearch());</script>

{/block}