<?php
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>'
?>

<!-- les dates sont fournies par la fonction time() de php -->
<connectes>
	<derniere_MAJ>156132</derniere_MAJ>
	<ami>
		<nom>Roger</nom>
		<conversation>
			<date>156132</date>
			<message>
				<de>Roger</de>
				<vers>bibi</vers>
				<texte>Salut, c'est Roger</texte>				
			</message>
		</conversation>
	</ami>
	<ami>
		<nom>Quenotte</nom>
	</ami>
	<ami>
		<nom>Cynthia</nom>
		<conversation>
			<date>1156128</date>
			<message>
				<de>Cynthia</de>
				<vers>bibi</vers>
				<texte>Coucou</texte>				
			</message>
			<message>
				<de>bibi</de>
				<vers>Cynthia</vers>
				<texte>Salut !</texte>				
			</message>
			<message>
				<de>Cynthia</de>
				<vers>bibi</vers>
				<texte>ça gaze ?</texte>				
			</message>
		</conversation>
	</ami>
</connectes>

