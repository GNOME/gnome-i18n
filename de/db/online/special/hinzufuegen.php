<?php
        require ("path.inc.php");
        require ("../config.php");

        function get_result($wid, $tid, $word, $trans, $type, $comment, $count) {
                $retval = "<tr>";

                if ($count > 1) {
                        $retval .= "<td></td>";
                } else {
                        $retval .= "<td valign=\"top\">$word</td>";
                }

                $comment = wordwrap($comment, 25, "\n", 1);
                //$comment = str_replace("\n", "<br>", $comment);

                $html_trans = htmlentities ($trans);
                $html_comment = htmlentities ($comment);

                $retval .= "
                  <td valign=\"top\">$count.</td>
                  <td valign=\"top\">$type</td>
                  <td valign=\"top\">$trans</td>
                  <td valign=\"top\">$comment</td>
                  <td valign=\"top\"><a href=\"/l10n/glossar/special/bearbeiten.php?wid=$wid&tid=$tid&trans_old=$trans\">Bearbeiten</a> -
                  <a href=\"/l10n/glossar/special/loeschen.php?wid=$wid&tid=$tid&trans=$trans\">L&ouml;schen</a></td>
                  </tr>";

                return $retval;
        }

        // connect to the database (with write access)
        mysql_connect($hostname, $username, $password);

        if ($action != "add") {
                // we were called without parameter action="add", so give the
                // interface

                /* Vars sent by the form:
                   $action       - what to do
                   $word         - the word to be added
                   $type         - type of the translation
                                   we only support one translation at a time
                   $translation  - translation of $word
                   $comment      - comment for the translation
                */

                $smarty->assign ('caption', 'hinzuf&uuml;gen');
                $smarty->assign ('content', '
                <form action="" method="post">
                  <input type="hidden" name="action" value="add" />
                  <table>
                    <tr><td>Neues Wort:</td><td><input name="word"></td></tr>
                    <tr><td>&Uuml;bersetzung:</td><td><input name="translation"></td></tr>
                    <tr><td>Worttyp:</td>
                                <td>
                                <select name="type">
                                <option>Nomen</otpion>
                                <option>Verb</option>
                                <option>Adjektiv</option>
                                </select>
                                </td>
                    </tr>
                    <tr><td>Kommentar:</td><td><textarea name="comment" cols="20" rows="5"></textarea></td></tr>
                    <tr><td colspan=2><input type="submit" value="Hinzuf&uuml;gen"></td></tr>
                  </table>
                </form>');
        } else {
                // the user wants to add a new word...

                /* Vars are comming from the form (s.a.) */

                // first check if the word is already in the database

                $already_in_db = false;
                $query = "SELECT * FROM $wordstable WHERE word = '$word'";
                $result_main = mysql_db_query($dbName, $query);

                if ($result_main && mysql_num_rows($result_main) > 0) {
                        // here is the real check:

                        $already_in_db = true;

                        $smarty->assign ('caption', 'Fehler beim Hinzuf&uuml;gen');
                        $content = "<p>Das Wort existiert bereits in der Datenbank:</p>\n";

                        $i = 1;
                        $content .= "<table cellpadding=\"3\">\n";
                        $content .= "<tr><td>Wort</td><td></td><td>Wortart</td><td>&Uuml;bersetzung</td><td>Kommentar</td><td>Aktionen</td></tr>";
                        $content .= "<tr><td>&nbsp</td></tr>";

                        $query = "SELECT w.wid, w.word, typ.tid, typ.type, tr.translation, tr.comment
                                      FROM $transtable tr, $typestable typ, $wordstable w
                                      WHERE tr.tid = typ.tid
                                      AND typ.wid = w.wid
                                      AND w.word = '$word'
                                          GROUP BY type
                                          ORDER BY type";

                        $result = mysql_query($query);

                        while ($res = mysql_fetch_array($result)) {
                                $content .= get_result(
                                                $res[wid],
                                                $res[tid],
                                                $res[word],
                                                $res[translation],
                                                $res[type],
                                                $res[comment],
                                                $i);
                                /* Ist das wirklich notwendig ??
                                if ($i == 1)
                                        $wid = $res[wid];
                                */
                                $i++;
                        }

                        if ($translation || $type || $comment) {
                                $content .= "<tr><td></td><td>$i.</td><td>$type</td><td>$translation</td><td>$comment</td>
                                                <td><a href=\"/l10n/glossar/special/erweitern.php?wid=$wid&trans=$translation&type=$type&comment=$comment\">Erweitern</a></td></tr>";
                        }
                        $content .= "<tr><td>&nbsp</td></tr>\n";
                        $content .= "</table>\n";

                        $smarty->assign ('content', $content);

                } else {

                        // word is not yet in the db

                        $smarty->assign ('caption', 'hinzuf&uuml;gen');

                        // insert in words table
                        $query = "INSERT INTO $wordstable VALUES(NULL, '$word', 'english')";
                        $result1 = mysql_query($query);
                        $error1 = "<p>Fehler 1: ".mysql_error()."</p>";

                        // get the wid and tid, which have been chosen by mysql
                        $wid = mysql_insert_id();
                        $tid = mysql_insert_id();

                        // insert in types table
                        $query = "INSERT INTO $typestable VALUES('$tid', '$wid', '$type')";
                        $result2 = mysql_query($query);
                        $error2 = "<p>Fehler 2: ".mysql_error()."</p>";

                        // insert in translations table
                        $query = "INSERT INTO $transtable VALUES('$tid', '$translation', '$comment')";
                        $result3 = mysql_query($query);
                        $error1 = "<p>Fehler 3: ".mysql_error()."</p>";

                        if ((!$result1) || (!$result2) || (!$result3)) {
                                // an error occured...
                                $error = "";
                                if (!$result1) {
                                        $error .= $error1;
                                }
                                if (!$result2) {
                                        $error .= $error2;
                                }
                                if (!$result3) {
                                        $error .= $error3;
                                }
                                $smarty->assign ('content', "$error<p>Beim Eintragen ist ein Fehler aufgetreten. Bitte kontaktieren sie den <a href='mailto:webmaster@gnome-de.org'>Webmaster</a>!</p>");
                        } else {
                                $smarty->assign ('content', "
                                <p>Datensatz erfolgreich hinzugef&uuml;gt.</p>
                                <a href=\"/l10n/glossar/suchen.php?show=results&query=$word&in=e\">Zum Eintrag</a>");
                        }
                }
        }

        $smarty->display ("standard.tpl");

?>
