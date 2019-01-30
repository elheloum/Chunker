<?php

	if((isset($_POST["GO"])) || (isset($_POST["Save"])) || (isset($_POST["Stat"])) || (isset($_POST["nblexique"])) || (isset($_POST["nbregle"]))){
		//séparer le texte tokeniser entré au niveau des retours chariots et le stoker dans $tabToken
		if(isset($_POST["texteTok"])){
			$texteTok= $_POST["texteTok"];
			$tabToken= explode("\n", $texteTok);

		}

		//création d'un tableau qui contiendra les règles ainsi que leur numéro correspondant, ce tableau sera utilisé pour le graphe
		$tableStat = array();
		$nbregle=0;
		//on regarde si des règles existent et on les sépares au niveau des \n
		if(isset($_POST["regles"])){
			$regles=$_POST["regles"];
			$tabregles = explode("\n", $regles);
			
			for($j=0;$j<sizeof($tabregles);$j++){
				$regle = $tabregles[$j];
				//on ne prend pas les phrases qui commencent par un # car ce sont des commentaires
				if (substr($regle, 0,1)!="#") {
					$regle=trim($regle);	
					$reg = explode(":", $regle);
					$nbregle++;
					//création d'un tableau qui contient le numéro de la règle la condition, l'action ainsi que le compteur qui sera incrémenté dès qu'on utilise chaque règles
					array_push($tableStat, array("id" => $j+1,"condition" => $reg[0],"action" => $reg[1],"compteur" => 0));
				}
			}		
		}
		//print_r($tabToken);
		//print_r($tabToken[1]);
		//parcours du array contenant les mots du texte tokenisé
		
		for($i=0; $i<sizeof($tabToken); $i++){
			$nblexique=0;
			//le token prendra à chaque fois une partie de tableau $tabToken et on met tout en minuscule pour ne pas avoir de problèmes quand on va comparer
			$token=trim(strtolower($tabToken[$i]));
			
			//on sépare les catégories rentrées au niveau du ','
			if(isset($_POST["categories"])){
				$categories=$_POST["categories"];
				$tabcategories = explode("\n", $categories);	
			}
			//print_r($tabcategories);
			//pour chaque couplet de cat on le sépare au niveau des : et on met la première partie dans motLex et le deuxième dans carLex
			
			foreach ($tabcategories as $cat) {
				$cat=trim($cat);
				//on initialise $motLex 
				$motLex="";
				//on ne prend pas les phrases qui commencent par un # car ce sont des commentaires
				if (substr($cat, 0,1)!="#") {
					$tabLexCat= explode(":", $cat);
					//$lexicon[$tabTokCat[0]]=$tabTokCat[1];
					$motLex=$tabLexCat[0];		
					$categLex=$tabLexCat[1];
					if ((strlen($motLex)>0) && (strlen($categLex)>0)){
						$motLex=$tabLexCat[0];		
						$categLex=$tabLexCat[1];
						$nblexique++;
					}
				} 
				//si le mot du texte tokénisé est égale au mot des catégories rentrés
				if($token==$motLex){		

					// pour chaque règle, on la sépare au niveau du : et on met les conditions dans une variable et les actions dans une autre variable
					foreach ($tableStat as $regle) {
						//print_r("a".$action."<br/>");
						//si on a plusieurs conditions
						$conditions=explode("&", $regle["condition"]);
						//print_r("c2".$conditions."<br/>");
						//si la catégorie correspond à une condition on applique la règle
						if($categLex==$conditions[0]){
							//si une deuxième conditon existe
							if(isset($conditions[1])){	
							//on la compare avec la catégorie du chunking qui est en cours. Si elle est pareille on ne modifie pas le chunking sinon on
							//le modifie en ajoutant l'action et en changeant la val du chunk précédent
							if($conditions[1]!="!$chunkPrec"){
								$texteChunk = $texteChunk.$regle["action"]." ";
								$chunkPrec=$categLex;
								//on incrémente le compteur quand on utilise la règle
								$tableStat[$regle["id"]-1]["compteur"]++;
							}
						}
						//si il y n'y a qu'une condition
						else{
							$texteChunk = $texteChunk.$regle["action"]." ";
							$chunkPrec=$categLex;
							$tableStat[$regle["id"]-1]["compteur"]++;
							}
						}
					}
				}
			}	
			//on ajoute le mot du texte tokénisé au chunk
			$texteChunk= $texteChunk.$token." ";
		}	
		//on ferme le chunk
		$texteChunkFinal=$texteChunk."]";	
		//enlever le ] de plus au début du texte chunké
		$texteChunkFinal=substr($texteChunkFinal, 1);
		//echo $texteChunkFinal."<br/>\n";	
	}	
	//tri du tableau par ordre croissant pour le graphique
	$compteur = array();
	foreach ($tableStat as $key => $row)
	{
  	  $compteur[$key] = $row['compteur'];
	}
	array_multisort($compteur, SORT_DESC, $tableStat);
?>