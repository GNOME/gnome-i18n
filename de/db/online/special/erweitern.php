<?php
    require ("path.inc.php");
    require ("../config.php");

    function get_result($wid, $tid, $word, $trans, $type, $comment, $count) {

        $retval = "<tr>";

        if ($count>1) {
            $retval .= "<td></td>";
        } else {
            $retval .= "<td valign=\"top\">$word</td>";
        }

        $comment = wordwrap($comment, 25, "\n", 1);
        // $comment = str_replace("\n", "<br>", $comment);

        $html_trans = htmlentities ($trans);
        $html_comment = htmlentities ($comment);

        $retval .= "
                    <td valign=\"top\">$count.</td>
                    <td valign=\"top\">$type</td>
                    <td valign=\"top\">$html_trans</td>
                    <td valign=\"top\">$html_comment</td>
                    <td valign=\"top\"><a href=\"special/bearbeiten.php?wid=$wid&tid=$tid&trans_old=$trans\">Bearbeiten</a>&nbsp;-
                    <a href=\"special/loeschen.php?wid=$wid&tid=$tid&trans=$trans\">L&ouml;schen</a></td>
                    </tr>";

        return $retval;
    }

    // connect to the database (with write access)
    mysql_connect($hostname, $username, $password);

    /* Vars passed to us:
            $wid           - Word ID (needed)
            $trans         - translation of the word with ID $wid (optional)
            $type          - type of the word with ID $wid (optional)
            $comment       - comment of the translation (optional)
            $processed     - set if the form has been displayed and the user hit submit
                !!! DO NOT SET BY HAND !!!
    */

    // see how we were called
    if (!$wid) {

        $smarty->assign ('caption', 'fehler');
        $smarty->assign ('content', '<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>');
        $smarty->display ('standard.tpl');
        exit;
    }

    $smarty->assign ('caption', 'eintrag erweitern');
    $content = "";

    if (($processed != 1) || !$trans || !$type) {

        // no translation or type given
        if ((!$trans || !$type) && ($processed == 1)) {

            $content .= "<p><font color=\"red\">Es wurde (noch) keine &Uuml;bersetzung oder Wortart angegeben!</font></p>\n";
            $content .= "<p>&nbsp</p>\n";
        }

        // first of all show the existing entry
        $content .= "<p>Der folgende Eintrag wird erweitert:</p>\n";
        $content .= "<table cellpadding=\"3\">\n";
        $content .= "<tr><td>Wort</td><td></td><td>Wortart</td><td>&Uuml;bersetzung</td><td>Kommentar</td><td>Aktionen</td></tr>";

        $query = "SELECT *
                    FROM $wordstable w, $transtable tr, $typestable typ
                    WHERE tr.tid = typ.tid
                    AND typ.wid = w.wid
                    AND w.wid = '$wid'
                        ORDER by word";
        $result = mysql_db_query($dbName, $query);

        if ($result && mysql_num_rows($result) > 0) {

            /*
            $res = mysql_fetch_array($result);
            $word_old = $res[word];
            */
            $word_old = "";
            $i = 1;

            while ($res = mysql_fetch_array($result)) {

                if ($word_old != $res[word]) {

                    $word_old = $res[word];
                    $i = 1;
                    $content .= "<tr><td>&nbsp</td></tr>";
                }
                $content .= get_result($res[wid],$res[tid],$res[word],$res[translation],$res[type],$res[comment],$i);
                $i++;
            }
        }
        $content .= "<tr><td>&nbsp</td></tr>";
        $content .= "</table>";

        $content .= '
                    <form action="" method="post">
                    <input type="hidden" name="wid" value="'.$wid.'" />
                    <input type="hidden" name="processed" value="1" />
                    <table cellpadding="3">
                      <tr><td>Wortart:</td>
                        <td>
                          <select name="type">
                          <option>Nomen</option>
                          <option>Verb</option>
                          <option>Adjektiv</option>
                          </select>
                        </td>
                      </tr>
                      <tr><td>Neue &Uuml;bersetzung</td><td><input name="trans" value="'.htmlentities($trans).'"></td></tr>
                      <tr><td>Kommentar:</td><td><textarea name="comment" cols="20" rows="5">'.htmlentities($comment).'</textarea></td></tr>
                      <tr><td colspan="2"><input type="submit" name="submit" value="Erweitern"></td></tr>
                    </table>
                    </form>
                    ';

    } else {

        // all needed data has been passed to us, so just insert the new entry

        // here we need the "old style" sql-queries, because we exactly need to know
        // steps by step which tables do contain stuff

        // first, see if the type $type is already in the table
        $type_exists = 0;
        $debug ='';
        $query = "SELECT * FROM $typestable WHERE type = '$type' AND wid = '$wid'";

        // saving $query temporary in $query_temp to assign it later
        $query_temp = $query;

        $result_temp = mysql_db_query($dbName, $query);
        $r_temp = mysql_fetch_array($result_temp);

        if (!$r_temp) {

            // type does not exist, so insert it
            $query = "INSERT INTO $typestable VALUES(NULL, '$wid', '$type')";
            $result1 = mysql_query($query);
            $debug .= 'Fehler 1: '.mysql_error().'<br>';

        } else {

            $type_exists = 1;
            $debug .= 'Fehler 1: Die Wortart existiert<br>';
        }

            // get the tid which has been assigned by mySQL
            $query = $query_temp;
            $result2 = mysql_db_query($dbName, $query);
            $debug .= 'Fehler 2: '.mysql_error().'<br>';
            $r_temp = mysql_fetch_array($result2);
            $debug .= 'Fehler 3: '.mysql_error().'<br>';
            $tid = $r_temp[tid];

            // see if the translation already exists
            $query = "SELECT * FROM $transtable WHERE translation = '$trans' AND tid = '$tid'";
            $result3 = mysql_db_query($dbName ,$query);
            $debug .= 'Fehler 4: '.mysql_error().'<br>';

            if (0 == mysql_num_rows($result3)) {

                // does not exist
                // now we can put the new translation in the DB
                $query = "INSERT INTO $transtable VALUES('$tid', '$trans', '$comment')";
                $result3 = mysql_query($query);
                $debug .= 'Fehler 5: '.mysql_error().'<br>';

                if (($type_exists == 0 && !$result1) || !$result2 || !$result3) {

                    $content .= "<p><font color='red'>Es ist ein Fehler beim Eintragen aufgetreten. Bitte kontaktieren sie den <a href='mailto:webmaster@gnome-de.org'>Webmaster</a>. Geben Sie dabei bitte folgende Informationen an:</font></p><p><code>$debug</code></p>";

                } else {

                    $content .= "<p>Erg&auml;nzung wurde erfolgreich in die Datenbank aufgenommen.</p>";
                }

            } else {

                $content .= "<p><font color=\"red\">Die &Uuml;bersetzung f&uuml;r dieses Wort ist in der DB bereits vorhanden!</font></p>";
           }
    }

    $smarty->assign ('content', $content);
    $smarty->display ('standard.tpl');

?>
