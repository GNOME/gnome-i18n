<?php
	include ("path.inc.php");

	$smarty->assign ("caption", "online-glossar");
	$smarty->assign ("content",
'		<p>
			text halt
		</p>
		<table width="100%"><tr>
		<td align="left">
			<a href="suchen.php">Eintrag suchen</a>
		</td><td align="right">
			<a href="special/hinzufuegen.php">Eintrag hinzuf&uuml;gen</a>
		</td>
		</tr></table>');

	$smarty->display ("standard.tpl");
?>
