{extends file='./skeleton.tpl'}
{block name=contenu}
	<div class="search">
		<h1>Recherche</h1>
		<form action="#">
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
				   <option value="volvo">Volvo</option>
				   <option value="saab">Saab</option>
				   <option value="vw">VW</option>
				   <option value="audi">Audi</option>
				</select> 
				</select> 
				<label class="type_critere" for="caracteristiques">Caractéristiques :</label>
				 <select name="type_meridien" tabindex="">
				   <option value="plein">Plein</option>
				   <option value="chaud">Chaud</option>
				   <option value="vide">Vide</option>
				   <option value="froid">Froid</option>
				   <option value="interne">Interne</option>
				   <option value="externe">Externe</option>
				</select> 
				<input type="submit" value="Rechercher"></input>
			</fieldset>
			<fieldset class="member_search">
				<legend>Par mot-clés : </legend>
				<input type="text" id="search_input" />
				<input type="submit" value="Rechercher"></input>
			</fieldset>
		</form>
	</div>

	<div class="list">
		<h1>Liste des résultats :</h1>
		<table id="list_result" border="0.5" >
			<tr>
			   <th>Pathologies</th>
			   <th>Symptômes</th>
			</tr>
			<tr>
				<td>{$PATHO}</td>
				<td>{$SYMPT}</td>		
			</tr>
		</table>
	</div>

{/block}