<?php

require "config.php";
require "common.php";

head("Eintrag bearbeiten");

// connect to the database (with write access)
mysql_connect($hostname, $username, $password);

/* Vars passed to us:
	$wid		- Word ID
	$tid		- Type ID
	$trans_old	- old translation (which is in the DB)
	$trans		- translation (optional)
	$type		- type of the word (optional)
	$comment	- comment of the translation (optional)
	$processed	- set if the form has been displayed and the user hit submit
				  DO NET SET BY HAND!
				  
	(optional) means that it's optional on the first run -> form will be shown
*/

if (!$wid || !$tid) {
	echo "<h1>Fehler</h1>
	<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>";
	tail();
	exit;
}

// get data
/*
$query = "SELECT * FROM $wordstable WHERE wid = '$wid'";
$result = mysql_db_query($dbName,$query);
$r = mysql_fetch_array($result);
$query = "SELECT * FROM $typestable WHERE wid = '$wid' AND tid = '$tid'";
$result2 = mysql_db_query($dbName,$query);
$rtype = mysql_fetch_array($result2);
$query = "SELECT * FROM $transtable WHERE tid = '$tid' AND translation = '$trans_old'";
$result3 = mysql_db_query($dbName,$query);
$rtrans = mysql_fetch_array($result3);
*/

$query = "SELECT *
		FROM $wordstable w, $transtable tr, $typestable typ
		WHERE 1 = 1
		AND tr.translation = '$trans_old'
		AND tr.tid = '$tid'
		AND typ.tid = '$tid'
		AND typ.wid = '$wid'
		AND w.wid = '$wid'
		order by word";
$result = mysql_db_query($dbName,$query);
$res = mysql_fetch_array($result);

echo "<h1>Eintrag bearbeiten</h1>\n";

if (($processed != 1) || !$trans || !$type) {
	// no translation or type given
	if ((!$trans || !$type) && ($processed==1)) {
		echo "<p><font color=\"red\">Es wurde keine Übersetzung oder Wortart angegeben!</font></p>\n";
		echo "<p>&nbsp</p>\n";
	}
print <<< END
<form action="$PHP_SELF" method="POST">
<input type="hidden" name="wid" value="$wid">
<input type="hidden" name="tid" value="$tid">
<input type="hidden" name="trans_old" value="$trans_old">
<input type="hidden" name="processed" value=1>
<table cellpadding="3">
	<tr><td></td><td>alt</td><td>neu</td></tr>
	<tr><td>Wort:</td><td>$res[word]</td><td></td></tr>
	<tr><td>Worttyp:</td><td>$res[type]</td>
		<td>
		<select name="type">
		<option>Nomen</otpion>
		<option>Verb</option>
		<option>Adjektiv</option>
		</select>
		</td>
	<tr><td>&Uuml;bersetzung:</td><td>$res[translation]</td><td><input name="trans" value="$res[translation]"></td></tr>
	<tr><td>Kommentar:</td><td>$res[comment]</td><td><textarea name="comment" cols="20" rows="5">$res[comment]</textarea></td></tr>
	<tr><td colspan="2"><input type="submit" value="&Auml;ndern"></td></tr>
</table>
</form>
<p>ACHTUNG: Wenn eine &Uuml;bersetzung sowohl groß als auch klein geschrieben ein und demselben Wort zugeordnet ist, werden beide
durch diesen Befehl ge&auml;ndert! (Ich arbeite bereits dran, mySQL stellt sich noch ein bisschen in den Weg)
END;
} else {
// all needed fields have been passed, so edit the entry

$tid_old = $tid;
$exists = false;

if ($res[type] != $type) {
	// types are differnet, so look if the new type exists
	$query = "SELECT * FROM $typestable WHERE type = '$type' AND wid = '$wid'";
	$result = mysql_db_query($dbName,$query);
	if ($result && mysql_num_rows($result) > 0) {
		$rtype2 = mysql_fetch_array($result);
		$query2 = "SELECT * FROM $transtable WHERE tid = '$rtype2[tid]' AND translation = '$trans'";
		$result2 = mysql_db_query($dbName,$query2);
		if ($result2 && mysql_num_rows($result2) > 0) {
			// translation with this type already exists!
			echo "<p><font color=\"red\">Diese &Uuml;bersetzung mit dieser Wortart existiert bereits!</font></p>";
			$exists = true;
		} else {
			// it's already there and translations are different, so reasign the $tid
			$tid = $rtype2[tid];
		}
	} else {
		// isn't there, so insert a new type and reasign the $tid
		$query = "INSERT INTO $typestable VALUES(NULL, '$wid', '$type')";
		$result1 = mysql_db_query($dbName,$query);
		// look up the new tid
		$query = "SELECT * FROM $typestable WHERE wid = '$wid' AND type = '$type'";
		$result = mysql_db_query($dbName,$query);
		$rtype2 = mysql_fetch_array($result);
		$tid = $rtype2[tid];
	}
	
	
}

// now update the $transtable
if (!$exists) {
	$query = "UPDATE $transtable SET translation = '$trans', comment = '$comment', tid = '$tid' WHERE tid = '$tid_old' AND translation = '$trans_old'";
	$result2 = mysql_db_query($dbName,$query);

	// now check if the old type is still used by another word
	$query = "SELECT * FROM $typestable typ, $transtable tr
			WHERE 1 = 1
			AND tr.tid = typ.tid
			AND typ.tid = '$tid_old'";
	$result = mysql_db_query($dbName,$query);
	if ($result && mysql_num_rows($result) == 0) {
		// no, so delete it
		$query = "DELETE FROM $typestable WHERE tid = '$tid_old'";
		$delresult = mysql_db_query ($dbName,$query);
	}

	if (!$result2) {
		echo "<p>Es ist ein Fehler beim Schreiben der bearbeiteten Daten aufgetreten. Bitte kontaktieren sie den Webmaster!</p>";
	} else {
		echo "<p>Bearbeitete Daten wurden erfolgreich übernommen.</p>";
	}
}

}

tail();

?>