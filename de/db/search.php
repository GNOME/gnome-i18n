<?php

require "config.php";
require "common.php";

head("Suche");

// connect to the databse (read access)
mysql_connect($hostname, $selectuser, $selectpwd);

/* Vars (passed from the form)
	$show		- What to do (one of: "results" or nothing)
	$query		- Word or part of a word to look for
	$in			- Where to look in (one of: e - english, g - german)
*/

if ($show) {

echo "<h1>Ergebnisse</h1>\n";
echo "<table cellpadding=\"3\">\n";
echo "<tr><td>Wort</td><td></td><td>Wortart</td><td>&Uuml;bersetzung</td><td>Kommentar</td><td>Aktionen</td></tr>";
echo "<tr><td>&nbsp</td></tr>";

if ($show == "results" && (($in == "e") || ($in == "g"))) {

if ($in == "e") {
	$query = "SELECT *
		FROM $wordstable w, $transtable tr, $typestable typ
		WHERE 1 = 1
		AND tr.tid = typ.tid
		AND typ.wid = w.wid
		AND w.word LIKE '%$query%'
		order by word";
}


if ($in == "g") { 
	$query = "SELECT *
		FROM $wordstable w, $transtable tr, $typestable typ
		WHERE 1 = 1
		AND typ.tid = tr.tid
		AND w.wid = typ.wid
		AND tr.translation LIKE '%$query%'
		order by word";
}



$result = mysql_db_query($dbName, $query);

if ($result && mysql_num_rows($result) > 0) {
	$res = mysql_fetch_array($result);
	$word_old = $res[word];
	$i = 1;
	
	do {
		if ($word_old != $res[word]) {
			$word_old = $res[word];
			$i = 1;
			echo "<tr><td></td><td></td><td></td><td></td><td></td><td><a href=\"extend.php?wid=$wid_old\">Hinzufügen</a> - 
<a href=\"delete.php?wid=$wid_old&all=1\">Alle löschen</a></td></tr>\n";
	  		echo "<tr><td>&nbsp</td></tr>";
		}
		print_result($res[wid],$res[tid],$res[word],$res[translation],$res[type],$res[comment],$i);
		$i++;
		$wid_old = $res[wid];
	} while ($res = mysql_fetch_array($result));
	
	echo "<tr><td></td><td></td><td></td><td></td><td></td><td><a href=\"extend.php?wid=$wid_old\">Hinzufügen</a> - 
<a href=\"delete.php?wid=$wid_old&all=1\">Alle löschen</a></td></tr>\n";
	echo "<tr><td>&nbsp</td></tr>";
      
      
} 
}

echo "</table>";

} else { // $show is not set, so give us the interface

echo "<h1>Suche</h1>\n";
echo "<form action=\"$PHP_SELF\" method=\"POST\">
<input type=\"hidden\" name=\"show\" value=\"results\">
<table>
<tr><td>Suchbegriff</td><td><input name=\"query\"></td></tr>
<tr><td valign=\"top\">Suche in</td>
<td>
<input type=\"radio\" name=\"in\" value=\"e\" checked>Englisch<br>
<input type=\"radio\" name=\"in\" value=\"g\">Deutsch
</td>
</tr>
<tr><td colspan=2><input type=\"submit\" value=\"Suchen!\"></td></tr>
</table>
<p>Hinweis: Es wird nach \"$auml;hnlichen\" W&ouml;rtern gesucht, d.h. 'at' findet auch 'attach', etc.<br>
Hinweis: Wenn nichts im Suchfeld eingetragen wird, wird die komplette Datenbank ausgegeben!</p>";

}

tail();

?>
