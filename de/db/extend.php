<?php

require "config.php";
require "common.php";

head("Eintrag erweitern");

// connect to the database (with write access)
mysql_connect($hostname, $username, $password);

/* Vars passed to us:
	$wid		- Word ID (needed)
	$trans		- translation of the word with ID $wid (optional)
	$type		- type of the word with ID $wid (optional)
	$comment	- comment of the translation (optional)
	$processed	- set if the form has been displayed and the user hit submit
				  DO NOT SET BY HAND!
*/

// see how we were called
if (!$wid) {
	echo "<h1>Fehler</h1>
	<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>";
	tail();
	exit;
}

echo "<h1>Eintrag erweitern</h1>\n";

if (($processed != 1) || !$trans || !$type) {
	// no translation or type given
	if ((!$trans || !$type) && ($processed == 1)) {
		echo "<p><font color=\"red\">Es wurde (noch) keine Übersetzung oder Wortart angegeben!</font></p>\n";
		echo "<p>&nbsp</p>\n";
	}
	
	// first of all show the existing entry
	echo "<p>Der folgende Eintrag wird erweitert:</p>\n";
	echo "<table cellpadding=\"3\">\n";
	echo "<tr><td>Wort</td><td></td><td>Wortart</td><td>Übersetzung</td><td>Kommentar</td><td>Aktionen</td></tr>";
	
	$query = "SELECT *
		FROM $wordstable w, $transtable tr, $typestable typ
		WHERE 1 = 1
		AND tr.tid = typ.tid
		AND typ.wid = w.wid
		AND w.wid = '$wid'
		order by word";
	$result = mysql_db_query($dbName, $query);

	if ($result && mysql_num_rows($result) > 0) {
		$res = mysql_fetch_array($result);
		$word_old = $res[word];
		$i = 1;
	
		do {
			if ($word_old != $res[word]) {
				$word_old = $res[word];
				$i = 1;
	  			echo "<tr><td>&nbsp</td></tr>";
			}
		print_result($res[wid],$res[tid],$res[word],$res[translation],$res[type],$res[comment],$i);
		$i++;
		} while ($res = mysql_fetch_array($result));
	}
	echo "<tr><td>&nbsp</td></tr>\n";
	echo "</table>\n";

print <<< END
<form action="$PHP_SELF" method="POST">
<input type="hidden" name="wid" value="$wid">
<input type="hidden" name="processed" value=1>
<table cellpadding="3">
<tr><td>Wortart:</td>
	<td>
	<select name="type">
	<option>Nomen</otpion>
	<option>Verb</option>
	<option>Adjektiv</option>
	</select>
	</td>
<tr><td>Neue &Uuml;bersetzung</td><td><input name="trans" value="$trans"></td></tr>
<tr><td>Kommentar:</td><td><textarea name="comment" cols="20" rows="5">$comment</textarea></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Erweitern"></td></tr>
</table>
</form>\n
END;

} else {
// all needed data has been passed to us, so just insert the new entry

// here we need the "old style" sql-queries, because we exactly need to know
// steps by step which tables do contain stuff

// first, see if the type $type is already in the table
$type_exists = 0;
$query = "SELECT * FROM $typestable WHERE type = '$type' AND wid = '$wid'";
$result_temp = mysql_db_query($dbName,$query);
$r_temp = mysql_fetch_array($result_temp);
if (!$r_temp) {
	// type does not exist, so insert it
	//echo "DEBUG: Type does not exist!\n";
	//echo "DEBUG: Inserting new type in DB...\n";
	$query = "INSERT INTO $typestable VALUES(NULL, '$wid', '$type')";
	$result1 = mysql_query($query);
} else {
//echo "DEBUG: Type exists!\n";
$type_exists = 1;
}

// get the tid which has been assigned by mySQL
//echo "DEBUG: Getting TID of WID '$wid'\n";
$query = "SELECT * FROM $typestable WHERE type = '$type' AND wid = '$wid'";
$result2 = mysql_db_query($dbName,$query);
$r_temp = mysql_fetch_array($result2);
$tid = $r_temp[tid];
//echo "DEBUG: TID is '$tid'\n";

// see if the translation already exists
$query = "SELECT * FROM $transtable WHERE translation = '$trans' AND tid = '$tid'";
$result3 = mysql_db_query($dbName,$query);
if (0 == mysql_num_rows($result3)) {
	// does not exist
	// now we can put the new translation in the DB
	//echo "DEBUG: Insert new translation in DB...\n";
	$query = "INSERT INTO $transtable VALUES('$tid', '$trans', '$comment')";
	$result3 = mysql_query($query);

	if (($type_exists == 0 && !$result1) || !$result2 || !$result3) {
		echo "<p><font color=\"red\">Es ist ein Fehler beim Eintragen aufgetreten. Bitte kontaktieren sie den Webmaster!</font></p>";
	} else {
		echo "<p>Ergänzung wurde erfolgreich in die Datenbank aufgenommen.</p>";
	}
} else {
	echo "<p><font color=\"red\">Die &Uuml;bersetzung f&uuml;r dieses Wort ist in der DB bereits vorhanden!</font></p>";
}

}

tail();

?>