<?php 
include("chunk.php");
if (isset($_POST["Save"])) {
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=texteEnChunk.txt");
	echo "Vos catégories: \n\n";
	echo $categories."\n\n";
	echo "Vos règles: \n\n";
	echo $regles."\n\n" ;
	echo "Votre texte d'entrée: \n\n";
	echo $texteTok."\n\n";
	echo "Votre texte en Chunk: \n\n";
	echo $texteChunkFinal;

}

else{
?>
<html>
<head>
<meta charset="utf-8">
<title>Chuncking !</title>
<link rel="stylesheet" type="text/css" href="theme_Ch.css" />
<link href="c3-0.4.21/c3.css" rel="stylesheet">
<script src="c3-0.4.21/d3.min.js" charset="utf-8"></script>
<script src="c3-0.4.21/c3.min.js"></script>
</head>
<body>

<form method="POST" action="#">
	<div class="lesBoutons">
		<div class="button">
			 <input type="submit" name="GO" value="GO"/>
		</div>
		
		<div class="buttonSave">
			 <input type="submit" name="Save" value="Save"/>
		</div>

		<div class="buttonStat">
			<button type="button" onclick="showGraph()">Graphique de Stat</button>
		</div>
		<!-- faire le graphique de stat avec c3js et d3js (version 3.17)-->
		<script type="text/javascript">
		//fonction qui nous donnera un graphique regles x compteur
			function showGraph(){
				var chart = c3.generate({
					//on associe le graphique au chart 
		  			bindto: '#chart',
		  			data: {
		  				//définition de l'axe des x
		  				x:'x',
		  				columns: [
		  				//on  attribue à X les valeurs des règles utilisés
		  					['x',<?php foreach ($tableStat as $regle) {
		  							echo '"'.$regle["condition"].':'.$regle["action"].'",';
		  					}?>],
		  					//on attribue à Y les valeurs du compteur
		  					['compteur',<?php foreach ($tableStat as $regle) {
		  							echo $regle["compteur"].',';
		  					}?>],
		  				],
		  				//on précise que notre graphe sera de type barre
		  				 type: 'bar',

		  				//on attribue la couleur noire pour les barres
		  				 colors: {
		  				 	compteur:'black'
		  				 },

		  			},
		  		 	bar: {
		       			width: {
		           			ratio: 0.5  //on définie la largeur des barres
		       		 	}
		        	},
		        	axis: {
		       			x: {
		           			type: 'category' // this needed to load string x value
		        		}
		  			}
		       });
			}
			console.log("<?php substr($regle["condition"], 0); ?>");
		</script>

		<div class="nblexique">
			 <input type="submit" name="nblexique" value="Combien de catégories ai-je entré?"/>
		</div>
		<?php
			if (isset($_POST["nblexique"])){
				echo "Vous avez entré ".$nblexique." catégories. \n\n";
			} 
		?>
		<div class="nbregle">
			 <input type="submit" name="nbregle" value="Combien de règles ai-je utilisé?"/>
		</div>
		<?php
			if (isset($_POST["nbregle"])){
				echo "Vous avez uilisé ".$nbregle." règles. \n\n";
			} 
		?>

		<div class="buttonReset">
			 <input type="submit" name="RESET" value="RESET"/>
		</div>
	</div>
	<div class="categories">
			<p>
			<br/><b>Catégories</b><br/>
			<div class="textecat">
				<textarea placeholder="Entrez les catégories sous forme de x:Catégorie 
				          y:Catégorie 
				    z:Catégorie... 
				            PS: Ils doivent être dans l'order alphabétique. Pour mettre des commentaires utilisez le '#'" name="categories" ><?php if(isset($categories)){echo $categories;}?></textarea>
			</div>
			</p>
	</div>

	<div class="regles">
			<p>
			<br/><b>Règles</b><br/>
			<div class="textereg" method="POST">
				<textarea placeholder="Entrez les règles sous forme : 
											  Catégorie : ][X
				                              Catégorie : ][Y.... 
				       			 Pour concatener 2 règles utilisez '&' et pour la négation utilisez '!'. Pour mettre des commentaires utilisez le '#'" name="regles"><?php if(isset($regles)){echo $regles;}?></textarea>
			</div>
			</p>
	</div>

	<div class="input">
			<p>
			<br/><b>Texte Tokenisé </b><br/>
			<div class="textein" method="POST">
				<textarea placeholder="Entrez un texte tokenisé. 
							PS: les tokens doivent être séparés par des retours chariot"" name="texteTok"><?php if(isset($texteTok)){echo $texteTok;}?></textarea>
			</div>
			</p>
	</div>

	<div class="output">
			<p>
			<br/><b>Texte sous forme de Chunk </b><br/>
			<div class="texteout">
				<textarea placeholder="La sortie de votre texte en Chunk" name="texteChunk"><?php if (isset($texteChunkFinal)){ echo $texteChunkFinal;}?></textarea>
			</div>
			</p>
	</div>
</form>
<form class="bHelp"  target="_blank" action="helpButton.html">
	<div class="buttonHelp">
		<input type="submit" value="Exemple de données à remplir" />
	</div>
</form>
<form class="tokenise" method="post" target="_blank" action="tokenise.php">
	<div class="buttonHelp">
		<input type="submit" value="tokeniser mon texte" />
	</div>
</form>
<div id="chart"></div>
</body>
</html>
<?php
}
?>