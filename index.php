<?php

/*******************************************************
 * B2 Project aka "GoogleEarth vs TontonMi"
 *
 * @author vfourastie <vfourastie@gmail.com>
 *******************************************************/

require_once('b2project.api.php');

// il faut d'abord valider le formulaire avec les données idoines
if (!isset($_POST["ok"]))
{
	// d'où qu'on part ?
	include_once('form.template.php');
}
else
{
	// on mouline ...
	$b2 = new B2Project();
	$b2->setInitialDataFromForm();
	$b2->generateCirclePoints($b2->NbPoints);
	$b2->generateXML();

	// on affiche les données initiales
	echo "Coordonn&eacute;es du point de r&eacute;f&eacute;rence = "
		. $b2->getRefLatDegToString() . "&nbsp;&nbsp;" 
		. $b2->getRefLonDegToString() . "&nbsp;&nbsp;"
		. "(" . $b2->RefLat["Dec"] . ",&nbsp;" . $b2->RefLon["Dec"] .")";
	echo "<br /><br />";	
	echo "Rayon = " . $b2->Rayon . "&nbsp;m";
	echo "<br /><br />";
	echo "Altitude = " . $b2->Altitude . "&nbsp;m";
	echo "<br /><br />";
	echo $b2->NbPoints . " points ont &eacute;t&eacute; g&eacute;n&eacute;r&eacute;s.";
	echo "<br /><br />";

	// on affiche le résultat
	echo "XML :<br /><br />";

	echo $b2->getXMLToDisplay();

	echo "<br /><br />";

	// encore ?
	unset($_POST["ok"]);
	echo "<form action=\"index.php\" method=\"post\">";
	echo "<input type=\"submit\" value=\"Encore\" />";
	echo "</form>";
}

?>
