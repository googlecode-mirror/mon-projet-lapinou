<! doctype html>
<html>
	<!-- head section commence ici -->
	<head>
		<link rel="stylesheet" type="text/css" href="styles/chat.css" />
		<script type="text/javascript" language="Javascript" src="scripts/prototype.js"></script>
		<script type="text/javascript" language="Javascript" src="scripts/jquery.min.js"></script>
		<script type="text/javascript" language="Javascript" src="scripts/chat.js"></script>
	</head>
	<!-- body section commence ici -->
	<body>
		<h1 align="center">LAPI.NET</h1>
		<a href="javascript:montrer_lapiphone();">montrer</a> : afficher <br/>
		<a href="javascript:cacher_lapiphone();">cacher</a> : cacher <br/>
		<a href="javascript:ecouter();">ecouter</a> : verifier les messages sur le serveur<br/>
		<a href="javascript:switch_conversation( 'Cynthia' );">conversation avec Cynthia</a><br/>
		<a href="javascript:switch_conversation( 'Roger' );">conversation avec Roger</a><br/>
		<a href="javascript:switch_conversation( '????' );">conversation avec ????</a><br/>
		<p id="test">/test/</p>

		<div style="width:50%;border:2px black dashed; padding:0.5em;font-family:sans-serif">
			<p>Voil&agrave; : le service de chat c&ocirc;t&eacute; client, il va chercher des infos
			sur le serveur automatiquement toutes les 10 secondes. Les conversations avec de nouveaux 
			messages apparaissent en bleu dans la liste.</p>
		</div>
		<!--lapiphone-->
		<div id="lapiphone">
			<div id="deco"><img src="img/oreilles.png" /></div>
			<div id="phone">
				<div class="centrer"><img src="img/lapiphone.png" /></div>
				<div id="messages_div"><!--messages--></div>
				<div><form action="javascript:envoyer();">
					<div class="gauche"><span>texte: </span></div>
					<div class="centrer"><textarea class="centrer" id="message" name="message" rows=2 cols=16 ></textarea></div>
					<hr/>
					<div class="centrer"><select id="amis" size=2 multiple></select></div>
					<div class="centrer"><input type="submit" value="Envoyer"/></div>
				</form></div>
			</div>
		</div>
		<!-- fin lapiphone -->
	</body>
</html>
