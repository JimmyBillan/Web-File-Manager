<?php

class repertoire
{
	var $_repList;
	
	function __construct($array) {
		$this->_repList = $this->$array;
		var_dump($this->_repList);
	}

	public function nettoyerRepertoire($repertoire) 
	{
	
		if (($key = array_search('..', $repertoire)) !== false) {
	    unset($repertoire[$key]);
		}
		if (($key = array_search('.', $repertoire)) !== false) {
	    unset($repertoire[$key]);
		}
		return array_values($repertoire);
	
	}

	public function parcoursRepertoire($path, $repertoire, $collection)
	{
		$long = count($repertoire) - 1;
		
		for ($i=0; $i <= $long; $i++) { 
			//echo $path."/".$repertoire[$i]."\n";
			if(is_dir($path."/".$repertoire[$i])) {
				
				$tempRepertoire = scandir($path."/".$repertoire[$i]);

				$tempRepertoire = repertoire::nettoyerRepertoire($tempRepertoire);
				array_push($tempRepertoire, $path."/");
				$collection[$path."/".$repertoire[$i]."/"] = $tempRepertoire;
				$collection = repertoire::parcoursRepertoire($path."/".$repertoire[$i] , $tempRepertoire, $collection);

			}
		}
		
		return $collection;

	}

	public function getInfoDossier($dossier){

	}

	public function getInfoFichier($fichier){
		$info = array();
		$info['date']=date ("d/m/Y H:i", filemtime($fichier));
		
		$ext = strtoupper(substr(strrchr($fichier, "."), 1));
		$info['type']= "Fichier ". $ext;
		$info['classExtension'] = repertoire::typeExtension($ext);
		if(!$info['classExtension'])
			$info['classExtension'] = "flaticon-text70";
		$info['taille']= repertoire::convertFilesize(filesize($fichier));
		return $info;
	}

	public function convertFilesize($tbit){
		$oldstr =  ceil($tbit/1024);

		$a = repertoire::filesizeReadable( (string) $oldstr);
		foreach ($a as $key) {
			# code...
			$oldstr = substr_replace($oldstr, " ", $key, 0);
		}
		return $oldstr." ko";
		 
			
	}

	public function filesizeReadable($str){
		/* Limiter à 999go flemme de faire une boucle*/
		$l = strlen($str);
		$a = array();
		if($l > 3){array_push($a, $l-3);
			if($l > 6){array_push($a, $l-6);}
				if($l > 9){array_push($a, $l-9);}
		}
		return $a;
	}

	
	public function typeExtension($ext){
		switch ($ext) {
		    case "MP4":
		        return "flaticon-mp42";
		        break;
		    case "MP3":
		       return "flaticon-mp34";
		        break;
		    case "AVI":
		        return "flaticon-avi2";
		        break;
		    case "MKV":
		        return "flaticon-movie47";
		    	break;
		    default:
		    	return "flaticon-text70";
		    	break;
		   
		}
		
	}

	public function retourBouton($dossier){
		$c = count($dossier);
		return $dossier[$c - 1];
		
	}

	public function deleteRetourElement($dossier){
		$c = count($dossier);
		unset($dossier[$c-1]);
		return $dossier;
	}

}


$root = realpath($_SERVER["DOCUMENT_ROOT"]);

$path = 'repertoire';

$repertoire = scandir('repertoire'); //repertoire Mere
$collection = array();



$repertoire = repertoire::nettoyerRepertoire($repertoire);
$collection[$path."/"] = $repertoire;
$collection = repertoire::parcoursRepertoire($path, $repertoire, $collection);



/* -----
		View
		------ */
echo '<html><head><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="monnuage.css"></head>';
echo '<div id="tableau">';


$c = 0;
foreach ($collection as $key => $value) {
	if($c == 0) {
		
		echo "<div id='dossier' link=$key style='display : block'>";
		echo "<div class='headTab'><div class='col-name'>Nom</div><div class='col-date'>Date</div><div class='col-type'>Type</div><div class='col-taille'>Taille</div></div>";
	} else {

		$retour = repertoire::retourBouton($value);
		$value = repertoire::deleteRetourElement($value);
		echo "<div id='dossier'  link=$key style='display : none'>";
		echo "<div class='headTab'><div class='col-name'>Nom</div><div class='col-date'>Date</div><div class='col-type'>Type</div><div class='col-taille'>Taille</div></div>";
		echo "<div class='headRetour' target='$retour'><div class='col-name'>Retour</div></div>";
	}
		foreach ($value as $keyValue) {
			
							if(is_dir($key.$keyValue)){
								$info = repertoire::getInfoDossier($key.$keyValue);
								echo "<div class='line dossier '>";
								echo "<div class='col-name flaticon-open127' id='linkdossier' compteur=$c link=$key$keyValue/>$keyValue</div>";
								//echo "<div class='col-date'>".$info['date']."</div>";
								echo "<div class='col-type'>Repertoire</div>";
								//echo "<div class='col-taille'>".$info['taille']."</div>";

								echo "</div>";
							}
							else{
								$info = repertoire::getInfoFichier($key.$keyValue);
								echo "<div id='menuFile' class='line fichier ' lien='".$key.$keyValue."' title='Clic droit pour afficher les actions'>";
								echo "<div class='col-name ".$info['classExtension']."'>$keyValue</div>";
								echo "<div class='col-date'>".$info['date']."</div>";
								echo "<div class='col-type'>".$info['type']."</div>";
								echo "<div class='col-taille'>".$info['taille']."</div>";
								
								echo "</div>";
							}
			
		}
		echo "</div>";
	
	
	$c++;
	
}
echo '</div>';
?>

<div id="pop" class="pop">
	<div id="titlepop" class="lineTitle" title=''></div>
	<div id="telecharger" lien="" class="linePop">Telecharger</div>
	<div id="streaming" lien="" class="linePop">Streaming</div>
</div>


<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="monnuage.js"></script>
</html>



