<?php

/*******************************************************
 * 
 * B2 Project aka "GoogleEarth vs TontonMi" : API
 *
 * => http://www.movable-type.co.uk/scripts/latlong.html (les formules qui vont bien)
 * => http://williams.best.vwh.net/avform.htm (pour être complet sur le sujet)
 *
 * @author DaBoyzBeBop <daboyzbebop@gmail.com>
 *
 *******************************************************/

class B2Project
{
	var $RefLat = array();
	var $RefLon = array();
	var $Rayon;
	var $Altitude;
	var $CirclePoints = array();
	var $Balise;
	var $dR;
	var $XML;

	/*********************************************
	 * Met à jour les coordonnées du point de référence et autres petites choses issues du formulaire
 	 *********************************************/
	public function setInitialDataFromForm()
	{
		if (isset($_POST["boulegue"]))
		{
			$this->RefLat["Card"] = $_POST["LatNS"];
			$this->RefLat["Deg"] = (int) $_POST["LatDeg"];
			$this->RefLat["Min"] = (int) $_POST["LatMin"];
			$this->RefLat["Sec"] = (int) $_POST["LatSec"];
			$this->RefLat["Dec"] = $this->RefLat["Deg"] + (($this->RefLat["Min"] + $this->RefLat["Sec"]/1000) /60);
			$this->RefLat["Rad"] = deg2rad($this->RefLat["Dec"]);

			$this->RefLon["Card"] = $_POST["LonEW"];
			$this->RefLon["Deg"] = (int) $_POST["LonDeg"];
			$this->RefLon["Min"] = (int) $_POST["LonMin"];
			$this->RefLon["Sec"] = (int) $_POST["LonSec"];
			$this->RefLon["Dec"] = $this->RefLon["Deg"] + (($this->RefLon["Min"] + $this->RefLon["Sec"]/1000) /60);
			$this->RefLon["Rad"] = deg2rad($this->RefLon["Dec"]);

			$this->Rayon = (int) $_POST["Rayon"];

			$this->Altitude = (int) $_POST["Altitude"];

			$this->NbPoints = (int) $_POST["NbPoints"];

			$this->Balise = $_POST["Balise"];

			// conversion du rayon en distance angulaire en radian
			$this->dR = deg2rad(($this->Rayon/1000)/6371);			
		}
	}

	/*********************************************
	 * Retourne la latitude du point de référence en degré sous forme de chaîne
	 *
	 * @return string
 	 *********************************************/
	public function getRefLatDegToString()
	{
		return $this->RefLat["Card"] . "&nbsp;" . $this->RefLat["Deg"] . "&deg;" 
			. $this->RefLat["Min"] . "." . $this->RefLat["Sec"]; 
	}

	/*********************************************
	 * Retourne la longitude du point de référence en degré sous forme de chaîne
	 *
	 * @return string
 	 *********************************************/
	public function getRefLonDegToString()
	{
		return $this->RefLon["Card"] . "&nbsp;" . $this->RefLon["Deg"] . "&deg;" 
			. $this->RefLon["Min"] . "." . $this->RefLon["Sec"]; 
	}

	/*********************************************
	 * Génére les points sur le cercle
	 *
	 * @var int Nombre de points à générer (36 par défaut)
 	 *********************************************/
	public function generateCirclePoints($NbPoints = 36)
	{
		// on calcule le pas
		$step = 360 / $NbPoints;

		// on commence par le Nord
		$bearing = 0;

		// 3, 2, 1 ...
		for ($i = 0; $i < $NbPoints; $i++)
		{
			$this->CirclePoints[$i]["bearing"] = $bearing;
			$currentBearingRad = deg2rad($bearing);

			// ASIN(SIN(lat1)*COS(d/R) + COS(lat1)*SIN(d/R)*COS(brng)
			$this->CirclePoints[$i]["Lat"]["Rad"] = asin(sin($this->RefLat["Rad"])*cos($this->dR)
									+ cos($this->RefLat["Rad"])*sin($this->dR)*cos($currentBearingRad));

			$this->CirclePoints[$i]["Lat"]["Dec"] = rad2deg($this->CirclePoints[$i]["Lat"]["Rad"]);

			// lon1 + ATAN2(COS(d/R)-SIN(lat1)*SIN(lat2), SIN(brng)*SIN(d/R)*COS(lat1))
			$this->CirclePoints[$i]["Lon"]["Rad"] = $this->RefLon["Rad"] 
									+ atan2(
										sin($currentBearingRad)*sin($this->dR)*cos($this->RefLon["Rad"])
										, 
										cos($this->dR)-sin($this->RefLat["Rad"])*sin($this->CirclePoints[$i]["Lat"]["Rad"])
										);

			$this->CirclePoints[$i]["Lon"]["Dec"] = rad2deg($this->CirclePoints[$i]["Lon"]["Rad"]);

			$bearing += $step;

			// 1 tour suffira
			if ($bearing > 360) $i = $NbPoints;
		}
	}

	/*********************************************
	 * Génére le code XML des points du cercle
 	 *********************************************/
	public function generateXML()
	{

		$this->XML  = "<Placemark>";
		$this->XML .= "<name>" . $this->Balise . "</name>";
		$this->XML .= "<MultiGeometry>";
		$this->XML .= "<LineString>";
		$this->XML .= "<!-- north wall -->";
		$this->XML .= "<extrude>1</extrude>";
		$this->XML .= "<altitudeMode>clampToGround</altitudeMode>";
		$this->XML .= "<coordinates>";

		// on parse les points pour remplir le truc
		for ($i = 0; $i < $this->NbPoints; $i++)
		{
			$this->XML .= "<!-- " . $this->CirclePoints[$i]["bearing"] . " degrees -->";
			// longitude d'abord (cf. mail TontonMi)
			$this->XML .= $this->CirclePoints[$i]["Lon"]["Dec"] . ", ";
			$this->XML .= $this->CirclePoints[$i]["Lat"]["Dec"] . ", ";
			$this->XML .= $this->Altitude;
		}

		$this->XML .= "</coordinates>";
		$this->XML .= "</LineString>";
		$this->XML .= "</MultiGeometry>";
		$this->XML .= "</Placemark>";
	}

	/*********************************************
	 * Retourne la chaîne XML formatée bien comme il faut pour qu'elle s'affiche dans le navigateur
	 *
	 * @return string
 	 *********************************************/
	public function getXMLToDisplay()
	{
		return htmlentities($this->XML);
	}
}

?>
