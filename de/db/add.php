<?php

require "config.php";
require "common.php";

head("Neuer Eintrag");

// connect to the database (with write access)
mysql_connect($hostname, $username, $password);

// see how we were called...
if ($action != "add") {

// we were called without parameter action="add", so give the interface

/* Vars sent by the form:
   $action       - what to do
   $word         - the word to be added
   $type         - type of the translation
                   we only support one translation at a time
   $translation  - translation of $word
   $comment      - comment for the translation */

print <<< END
<h1>Hinzufügen</h1>
<form action="$PHP_SELF" method="POST">
  <input type="hidden" name="action" value="add">
  <table>
    <tr><td>Neues Wort:</td><td><input name="word"></td></tr>
    <tr><td>Übersetzung:</td><td><input name="translation"></td></tr>
    <tr><td>Worttyp:</td>
		<td>
		<select name="type">
		<option>Nomen</otpion>
		<option>Verb</option>
		<option>Adjektiv</option>
		</select>
		</td>
    <tr><td>Kommentar:</td><td><textarea name="comment" cols="20" rows="5"></textarea></td></tr>
    <tr><td colspan=2><input type="submit" value="Hinzufügen"></td></tr>
  </table>
</form>
END;

} else

{

// the user wants to add a new word...

/* Vars are comming from the form (s.a.) */

// first check if the word is already in the database

$already_in_db = false;
$query = "SELECT * FROM $wordstable WHERE word = '$word'";
$result_main = mysql_db_query($dbName,$query);

if ($result_main && mysql_num_rows($result_main) > 0) {
  // here is the real check:

  $already_in_db = true;

  echo "<h1>Fehler beim Hinzufügen!</h1>\n";
  echo "<p>Das Wort existiert bereits in der Datenbank:</p>\n";

  $i = 1;
  echo "<table cellpadding=\"3\">\n";
  echo "<tr><td>Wort</td><td></td><td>Wortart</td><td>Übersetzung</td><td>Kommentar</td><td>Aktionen</td></tr>";
  echo "<tr><td>&nbsp</td></tr>";


  $query = "SELECT w.wid, w.word, typ.tid, typ.type, tr.translation, tr.comment 
	    FROM $transtable tr, $typestable typ, $wordstable w
	    WHERE 1 = 1
	    and tr.tid = typ.tid 
	    and typ.wid = w.wid
	    and w.word = '$word'
		group by type
	    order by type";

  $result= mysql_query($query);

  while ($res = mysql_fetch_array($result)) {
    print_result($res[wid],$res[tid],$res[word],$res[translation],$res[type],$res[comment],$i);
	if ($i == 1) $wid = $res[wid];
    $i++;
  }

  if ($translation || $type || $comment) {
	echo "<tr><td></td><td>$i.</td><td>$type</td><td>$translation</td><td>$comment</td>
	<td><a href=\"extend.php?wid=$wid&trans=$translation&type=$type&comment=$comment\">Hinzufügen</a></td></tr>";
  }
  echo "<tr><td>&nbsp</td></tr>";
  echo "</table>\n";
} else { // word is not yet in the db

  echo "<h1>Hinzufügen</h1>";

  // insert in words table
  $query = "INSERT INTO $wordstable VALUES(NULL, '$word', 'english')";
  //echo "<p>INSERT INTO $wordstable VALUES(NULL, '$word', 'english')<br>";
  $result1 = mysql_query($query);

  // get the wid, which has been chosen by mysql
  $wid = mysql_insert_id();

  // insert in types table
  $query = "INSERT INTO $typestable VALUES(NULL, '$wid', '$type')";
  //echo "INSERT INTO $typestable VALUES(NULL, '$wid', '$type')<br>";
  $result2 = mysql_query($query);

  // get tid, which has been chosen by mysql
  $tid = mysql_insert_id();

  // insert in translations table
  $query = "INSERT INTO $transtable VALUES('$tid', '$translation', '$comment')";
  //echo "INSERT INTO $transtable VALUES('$tid', '$translation', '$comment')</p>";
  $result3 = mysql_query($query);

  if ((!$result1) || (!$result2) || (!$result3)) {
    // an error occured...
    echo "<p>Beim Eintragen ist ein Fehler aufgetreten. Bitte kontaktieren sie den Webmaster!</p>";
  } else {
  echo "<p>Datensatz erfolgreich hinzugefügt.</p>";
  echo "<a href=\"search.php?show=results&query=$word&in=e\">Zum Eintrag</a>";
  }
}


}

tail();

?>
