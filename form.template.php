Entrez les coordonn&eacute;es du point de r&eacute;f&eacute;rence :

<br /><br />

<form action="index.php" method="post">

	<select name="LatNS">
		<option value="N">N</option>
		<option value="S">S</option>
	</select>

	<input type="text" name="LatDeg" style="width: 4em;" value="43"/>&nbsp;&deg;&nbsp;
	<input type="text" name="LatMin" style="width: 4em;" value="36"/>&nbsp;&prime;&nbsp;
	<input type="text" name="LatSec" style="width: 4em;" value="263"/>&nbsp;&Prime;&nbsp;

	<br />

	<select name="LonEW">
		<option value="E">E</option>
		<option value="W">W</option>
	</select>

	<input type="text" name="LonDeg" style="width: 4em;" value="1"/>&nbsp;&deg;&nbsp;
	<input type="text" name="LonMin" style="width: 4em;" value="26"/>&nbsp;&prime;&nbsp;
	<input type="text" name="LonSec" style="width: 4em;" value="602"/>&nbsp;&Prime;&nbsp;

	<br /><br />

	Rayon&nbsp;:&nbsp;<input type="text" name="Rayon" style="width: 4em;" value="200"/>&nbsp;(en m&egrave;tre)

	<br /><br />

	Altitude&nbsp;:&nbsp;<input type="text" name="Altitude" style="width: 4em;" value="2000"/>&nbsp;(en m&egrave;tre)

	<br /><br />

	Nombre&nbsp;de&nbsp;points&nbsp;&agrave;&nbsp;g&eacute;n&eacute;rer&nbsp;:&nbsp;<input type="text" name="NbPoints" style="width: 4em;" value="36"/>

	<br /><br />

	Nom&nbsp;de&nbsp;la&nbsp;balise&nbsp;:&nbsp;<input type="text" name="Balise" style="width: 10em;" value="B2"/>

	<br /><br />

	<input type="hidden" name="boulegue" value="true" />
	<input type="submit" value="Boul&egrave;gues !!!" />

</form>

