<?php
    require ("path.inc.php");
    require ("../config.php");

    // connect to the database (with write access)
    mysql_connect($hostname, $username, $password);

    /* Vars passed to us:
        $wid            - Word ID
        $tid            - Type ID
        $trans_old      - old translation (which is in the DB)
        $trans          - translation (optional)
        $type           - type of the word (optional)
        $comment        - comment of the translation (optional)
        $processed      - set if the form has been displayed and the
                          user hit submit
           !!! DO NET SET BY HAND !!!

        (optional) means that it's optional on the first run -> form
        will be shown
    */

    if (!$wid || !$tid) {
        $smarty->assign ('caption', 'fehler');
        $smarty->assign ('content', '<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>');
        $smarty->display ('standard.tpl');
        exit;
    }

    $query = "SELECT *
                FROM $wordstable w, $transtable tr, $typestable typ
                WHERE tr.translation = '$trans_old'
                AND tr.tid = '$tid'
                AND typ.tid = '$tid'
                AND typ.wid = '$wid'
                AND w.wid = '$wid'
                    ORDER BY word";
    $result = mysql_db_query($dbName, $query);
    $res = mysql_fetch_array($result);

    $smarty->assign ('caption', 'eintrag bearbeiten');
    $content = "";

    if (($processed != 1) || !$trans || !$type) {

        // no translation or type given

        if ((!$trans || !$type) && ($processed == 1)) {

            $content .= "<p><font color=\"red\">Es wurde keine &Uuml;bersetzung oder Wortart angegeben!</font></p>";
            $content .= "<p>&nbsp</p>";
        }

        $nom_selected = "";
        $ver_selected = "";
        $adj_selected = "";

        if ($res[type] == "Nomen")
            $nom_selected = " selected";
        if ($res[type] == "Verb")
            $ver_selected = " selected";
        if ($res[type] == "Adjektiv")
            $adj_selected = " selected";

        $content .= '
                    <form action="" method="post">
                    <input type="hidden" name="wid" value="'.$wid.'">
                    <input type="hidden" name="tid" value="'.$tid.'">
                    <input type="hidden" name="trans_old" value="'.$trans_old.'">
                    <input type="hidden" name="processed" value="1">
                    <table cellpadding="3">
                        <tr><td></td><td>alt</td><td>neu</td></tr>
                        <tr><td>Wort:</td><td>'.$res[word].'</td><td></td></tr>
                        <tr><td>Worttyp:</td><td>'.$res[type].'</td>
                            <td>
                            <select name="type">
                            <option'.$nom_selected.'>Nomen</option>
                            <option'.$ver_selected.'>Verb</option>
                            <option'.$adj_selected.'>Adjektiv</option>
                            </select>
                            </td>
                        </tr>
                        <tr><td>&Uuml;bersetzung:</td><td>'.$res[translation].'</td><td><input name="trans" value="'.$res[translation].'"></td></tr>
                        <tr><td>Kommentar:</td><td>'.$res[comment].'</td><td><textarea name="comment" cols="20" rows="5">'.$res[comment].'</textarea></td></tr>
                        <tr><td colspan="2"><input type="submit" value="&Auml;ndern"></td></tr>
                    </table>
                    </form>
                    <p>ACHTUNG: Wenn eine &Uuml;bersetzung sowohl gro&szlig; als auch klein geschrieben ein und demselben Wort zugeordnet ist, werden beide durch diesen Befehl ge&auml;ndert!</p>
                    ';
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
                    $content .= "<p><font color=\"red\">Diese &Uuml;bersetzung mit dieser Wortart existiert bereits!</font></p>";
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
                          WHERE tr.tid = typ.tid
                          AND typ.tid = '$tid_old'";
            $result = mysql_db_query($dbName,$query);

            if ($result && mysql_num_rows($result) == 0) {

                // no, so delete it
                $query = "DELETE FROM $typestable WHERE tid = '$tid_old'";
                $delresult = mysql_db_query ($dbName,$query);
            }

            if (!$result2) {

                $content .= '<p>Es ist ein Fehler beim Schreiben der bearbeiteten Daten aufgetreten. Bitte kontaktieren sie den <a href="mailto:webmaster@gnome-de.org">Webmaster</a>!</p>';

            } else {

                $content .= "<p>Bearbeitete Daten wurden erfolgreich &uuml;bernommen.</p>";
            }
        }
    }

    $smarty->assign ('content', $content);
    $smarty->display ('standard.tpl');
?>
