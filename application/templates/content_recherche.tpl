{extends file='./skeleton.tpl'}
{block name=contenu}

	<script type="text/javascript" src="js/ajax_check.js"></script>

	<div class="content_infos">
		<h1>Recherche <i class="fa fa-search" aria-hidden="true"></i></h1>
		<form class="form-horizontal" action="index.php?p=2&q=2" method="post">
		  	<div class="form-group">
			    <label class="col-sm-2 control-label">Type de Pathologie :</label>
			    <div class="col-sm-6">
			      <select class="form-control" name="type_patho" id="type_patho">
						<option value="m" selected="selected">Méridien</option>
						<option value="tf">Organe/Viscère</option>
						<option value="l">Luo</option>
						<option value="mv">Merveilleux vaisseaux</option>
						<option value="j">Jing jin</option>
					</select>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label class="col-sm-2 control-label">Caractéristiques :</label>
			    <div class="col-sm-6">
			        <select class="form-control" name="caracteristiques_meridien" class="caracteristiques_meridien">
						<option selected="true" value="default">-</option>
						<option value="i">Interne</option>
						<option value="e">Externe</option>
						<option value="p">Plein</option>
						<option value="c">Chaud</option>
						<option value="v">Vide</option>
						<option value="f">Froid</option>
					</select> 
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label class="col-sm-2 control-label">Choix des méridiens :</label>
			    <div class="col-sm-6">
			        <select class="form-control" name="type_meridien[]" multiple >
					 	{section name=merid loop=$meridiens}
						<option value="{$meridiens[merid].DESC}" selected="selected">{$meridiens[merid].DESC}</option>
						{/section}
					</select> 
		  		</div>
			</div>
			<input class="form-control"  type="submit" value="Rechercher"></input> 
		</form>

<!-- 		<form action="index.php?p=2&q=2" method="post">
			<fieldset class="public_search"> -->
				<!-- <legend>Par critère :</legend> -->
				<!-- <label class="control-label" for="type_patho">Type de Pathologie :</label>
				<select class="form-control" name="type_patho" id="type_patho">
					<option value="m" selected="selected">Méridien</option>
					<option value="tf">Organe/Viscère</option>
					<option value="l">Luo</option>
					<option value="mv">Merveilleux vaisseaux</option>
					<option value="j">Jing jin</option>
				</select> -->

				<!-- <h1><small>Caractéristiques :</small></h1>
				<select class="form-control" name="caracteristiques_meridien" class="caracteristiques_meridien">
				<option selected="true" value="default">-</option>
				   <option value="i">Interne</option>
				   <option value="e">Externe</option>
				   <option value="p">Plein</option>
				   <option value="c">Chaud</option>
				   <option value="v">Vide</option>
				   <option value="f">Froid</option>
				</select>  -->
				<h1><small>Choix des méridiens :</small></h1>
				<select class="form-control" name="type_meridien[]" multiple >
				 	{section name=merid loop=$meridiens}
					<option value="{$meridiens[merid].DESC}" selected="selected">{$meridiens[merid].DESC}</option>
					{/section}
				</select> 
				<input class="form-control"  type="submit" value="Rechercher"></input>
			</fieldset>
		</form>
		<form action="index.php?p=2&q=3" method="post" class="member_search">
			<fieldset class="member_search">
				<h1><small>Par mot-clés :</small></h1>
				<!-- <legend>Par mot-clés : </legend> -->
				<input class="form-control" type="text" id="search_input" name="keywords"/>
				<input class="form-control" type="submit" value="Rechercher"></input>
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
		<div class="table-responsive">
			<table class="table" id="list_result_pat" border="1" >
				<tr>
				   <th>Pathologies</th>
				</tr>
				{section name=customer loop=$pathology}
				<tr>
					<td>{$pathology[customer].DESC}</td>	
				</tr>
				{/section}
			</table>
		</div>

		<div class="table-responsive">
			<table class="table" id="list_result_sympt" border="1" >
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
	</div>

	<script type="text/javascript">Meridien_selected();</script>
	<script type="text/javascript">$(window).load(loadKwSearch());</script>

{/block}