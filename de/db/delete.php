<?php

require "config.php";
require "common.php";

head("Löschen");

// connect to the database (with write access)
mysql_connect($hostname, $username, $password);

/* Vars passed to us:
	$wid		- Word ID (needed)
	$tid		- Type ID (needed)
	$trans		- translation of the word with ID $wid
	$all		- set to 1 if you want to delete all records of $wid
	$processed	- set if the form has been displayed and the user hit submit
				  DO NET SET BY HAND!
*/

if (!$wid || (!$tid && $all != 1) || (!$trans && $all != 1)) {
	echo "<h1>Fehler</h1>
	<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>";
	tail();
	exit;
}

if ($processed != 1) {
	// user has to confirm the action
	echo "<h1>Löschen</h1>
	<form action=\"$PHP_SELF\" method=\"POST\">
	<p>Wollen Sie wirklich löschen?</p>
	<input type=\"hidden\" name=\"processed\" value=1>
	<input type=\"hidden\" name=\"wid\" value=\"$wid\">
	<input type=\"hidden\" name=\"tid\" value=\"$tid\">
	<input type=\"hidden\" name=\"trans\" value=\"$trans\">
	<input type=\"hidden\" name=\"all\" value=$all>
	<input type=\"submit\" value=\"Ja, sofort löschen\">
	</form>\n";
} else {
	// so, user really wants to delete... let's do it
	if ($all = 1 && $trans == "") {
		// delete all record connected with $wid
		// first look up which types are connected with $wid
		$query = "SELECT * FROM $typestable WHERE wid = '$wid'";
		$result_types = mysql_db_query($dbName,$query);
		if ($result_types) {
			if ($rtype = mysql_fetch_array($result_types)) {
				do {
					// now delete the entries with these types in the
					// transtable and in the typestable
					$query = "DELETE FROM $transtable WHERE tid = '$rtype[tid]'";
					$delresult1 = mysql_db_query ($dbName,$query);
					$query = "DELETE FROM $typestable WHERE tid = '$rtype[tid]'";
					$delresult2 = mysql_db_query ($dbName,$query);
				} while ($rtype = mysql_fetch_array($result_types));
			}
		// now delete the word
		$query = "DELETE FROM $wordstable WHERE wid = '$wid'";
		$delresult3 = mysql_db_query($dbName,$query);
		}
		if (!$delresult1 || !$delresult2 || !$delresult3) {
			echo "<p>Es trat ein Fehler beim Löschen auf. Bitte kontaktieren Sie den Webmaster!</p>\n";
		} else {
			echo "<p>Alle Einträge wurden erfolgreich gelöscht</p>\n";
		}
	}
	if ($trans) {
		// user wants to delete only one translation
		// first, just delete the word
		$query = "DELETE FROM $transtable WHERE translation = '$trans' AND tid = '$tid'";
		$delresult1 = mysql_db_query($dbName,$query);
		if ($delresult1) {
			echo "<p>Eintrag wurde gelöscht</p>";
			// now check if there are more word connected with the tid
			// if no, then also delete this type entry
			$query = "SELECT * FROM $transtable WHERE tid = '$tid'";
			$result_trans = mysql_db_query($dbName,$query);
			if (!$rwords = mysql_fetch_array($result_trans)) {
				// no entry, so delete the type
				$query = "DELETE FROM $typestable WHERE tid = '$tid'";
				$delresult2 = mysql_db_query($dbName,$query);
				echo "<p>Wortart für dieses Wort wurde gelöscht, da keine Übersetzung mehr darauf zugreift.</p>\n";
				// last step: check if there is a type left for the word
				$query = "SELECT * FROM $typestable WHERE wid = '$wid'";
				$result_types = mysql_db_query($dbName,$query);
				if (!$rtypes = mysql_fetch_array($result_types)) {
					// no type left -> delete the word itself
					$query = "DELETE FROM $wordstable WHERE wid = '$wid'";
					$delresult3 = mysql_db_query($dbName,$query);
					echo "<p>Wort wurde aus Datenbank entfernt, da keine Übersetzung mehr vorhanden war.</p>\n";
				}
			}
		} else {
			echo "<p>Es trat ein Fehler beim Löschen des Eintrags auf. Bitte kontaktieren Sie den Webmaster!</p>";
		}
	}
}

tail();
					
?>