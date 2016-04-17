{extends file='./skeleton.tpl'}
{block name=contenu}

			<script type="text/javascript">
				function Mesg() {
					alert("{$contenu_msg}"); 	
				} 
				function Redirection(){
					document.location.href="index.php?p=1";
				}	
			</script>

			<script type="text/javascript">Mesg();</script>
			<script type="text/javascript">Redirection();</script>

{/block}