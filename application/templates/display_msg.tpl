{extends file='./skeleton.tpl'}
{block name=contenu}
		<script type="text/javascript" src="../public/js/display_msg.js"></script>

		<script type="text/javascript">Mesg("{$contenu_msg}");</script>
		<script type="text/javascript">Redirection();</script>

		<!-- <div onload="Mesg('{$contenu_msg}');Redirection();"></div> -->
{/block}