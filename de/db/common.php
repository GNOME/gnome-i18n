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
        echo "<td valign=\"top\">$word</td>";
      }
	  $comment = wordwrap($comment, 40, "\n", 1);
	  $comment = str_replace("\n", "<br>", $comment);
      print <<< END
	  <td valign="top">$count.</td>
	  <td valign="top">$type</td>
	  <td valign="top">$trans</td>
	  <td valign="top">$comment</td>
	  <td valign="top"><a href="edit.php?wid=$wid&tid=$tid&trans_old=$trans">Bearbeiten</a> - 
	  <a href="delete.php?wid=$wid&tid=$tid&trans=$trans">Löschen</a></td>
	  </tr>
END;

}

?>
