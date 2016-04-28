				// fonction qui gère affichage recherche par mot-clés
				function loadKwSearch() {
					   $.ajax({
					       url : 'index.php?p=7',
					       type : 'GET',
					       dataType : 'text',
					       success : function(retour, statut){ 				       
					       	 if(retour == 'connected'){
							  	// on montre le formulaire + resultats rechercher par mot-clés
							  	$(".member_search").show();	// affichage du formulaire (form)
							  	$(".keyword_results").show();	
							  }
							  else{
							  	$(".member_search").hide();	// cacher le formulaire (form)
							  	$(".keyword_results").hide();	
							  }
					       },
					       error : function(resultat, statut, erreur){
					       	 alert('Erreur AJAX JS :' + retour);
					       }
					    });
					} 

				function Meridien_selected(){

					$(".caracteristiques_meridien").show();	
					$("select[name='caracteristiques_meridien'] option:not([value='e'],[value='i'])").hide();

					$('#type_patho').change(function()
					{
						 $Option_selected = $("select[name='type_patho'] > option:selected").val();

						switch ($Option_selected) {
						  case 'm':
						    $(".caracteristiques_meridien").show();					
							$("select[name='caracteristiques_meridien'] option:not([value='e'],[value='i'])").hide();
							$("select[name='caracteristiques_meridien'] option:not([value='c'],[value='f'],[value='p'],[value='v'])").show();
						    break;
						  case "tf": 
						  	$(".caracteristiques_meridien").show();	
						  	$("select[name='caracteristiques_meridien'] option:not([value='c'],[value='f'],[value='p'],[value='v'])").hide();
						  	$("select[name='caracteristiques_meridien'] option:not([value='e'],[value='i'])").show();

							break; 		    
						  case 'j': 
						   $(".caracteristiques_meridien").hide();	
						    break; 
						  case 'l':
						    $(".caracteristiques_meridien").show();	
						    $("select[name='caracteristiques_meridien'] option:not([value='p'],[value='v'])").hide();
							$("select[name='caracteristiques_meridien'] option:not([value='c'],[value='f'],[value='i'],[value='e'])").show();	
						    break;
						  case 'mv':
						    $(".caracteristiques_meridien").hide();	
						    break;
						  default:
						    $(".caracteristiques_meridien").show();	
							$("select[name='caracteristiques_meridien'] option:not([value='e'],[value='i'])").hide();
							$("select[name='caracteristiques_meridien'] option:not([value='c'],[value='f'],[value='p'],[value='v'])").show();
						}
					});
				}