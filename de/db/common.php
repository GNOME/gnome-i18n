<?php

function head($title) {

echo "<html>\n<head>\n<title>$title</title>\n</head>\n<body>\n";
echo "<a href=\"search.php\">Neue Suche</a> - <a href=\"add.php\">Neuer Eintrag</a>\n";
echo "<hr>\n";

}

function tail() {

echo "<hr>\n";
echo "\n<a href=\"search.php\">Neue Suche</a> - <a href=\"add.php\">Neuer Eintrag</a>\n";
echo "</body>\n</html>";

}

function print_result($wid,$tid,$word,$trans,$type,$comment,$count) {

echo "<tr>";
      if ($count>1) {
        echo "<td></td>";
      } else {
        echo "<td>$word</td>";
      }
      echo "<td>$count.</td><td>$type</td><td>$trans</td><td>$comment</td><td><a href=\"edit.php?wid=$wid&tid=$tid&trans_old=$trans\">Bearbeiten</a> - 
	  <a href=\"delete.php?wid=$wid&tid=$tid&trans=$trans\">Löschen</a></td></tr>\n";

}

?>
