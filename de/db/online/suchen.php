<?php
    require ("path.inc.php");
    require ("config.php");

    function get_result($wid, $tid, $word, $trans, $type, $comment, $count) {

        $retval = "<tr>";

        if ($count > 1) {
            $retval .= "<td></td>";
        } else {
            $retval .= "<td valign=\"top\">$word</td>";
        }

        $comment = wordwrap($comment, 25, "\n", 1);
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

    // connect to the databse (read access)
    mysql_connect($hostname, $selectuser, $selectpwd);

    /* Vars (passed from the form)
        $show       - What to do (one of: "results" or nothing)
        $query      - Word or part of a word to look for
        $in         - Where to look in (one of: e - english, g - german)
    */

    $content = "";

    if ($show) {

        if (($show == "results") && (($in == "e") || ($in == "g"))) {

            if ($in == "e") {

                $query = "SELECT *
                          FROM $wordstable w, $transtable tr, $typestable typ
                              WHERE tr.tid = typ.tid
                              AND typ.wid = w.wid
                              AND w.word LIKE '%{$_POST['query']}%'
                              ORDER BY word";
            }

            if ($in == "g") {

                $query = "SELECT *
                          FROM $wordstable w, $transtable tr, $typestable typ
                              WHERE typ.tid = tr.tid
                              AND w.wid = typ.wid
                              AND tr.translation LIKE '%{$_POST['query']}%'
                              ORDER BY word";
            }

/* ***********************************************
            # debugging info for the query
            $content .= "$query<br />";
            $content .= "{$_POST['query']}<br />";
   ******************************************** */

            $result = mysql_db_query($dbName, $query);
            $content .= mysql_error();

            if ($result && (mysql_num_rows($result) > 0)) {

                $content .= "<table cellpadding=\"3\"><tr>
                                 <th>Wort</th>
                                 <th></th>
                                 <th>Wortart</th>
                                 <th>&Uuml;bersetzung</th>
                                 <th>Kommentar</th>
                                 <th>Aktionen</th>
                                 </tr>";

                $word_old = '';
                $i = 0;

                while ($res = mysql_fetch_array($result)) {

                    $content .= mysql_error();

                    if ($word_old != $res[word]) {

                        if (($word_old != '') && ($i > 1)) {

                            $content .= "
                                        <tr>
                                            <td colspan='6' align='right'>
                                            <a href=\"special/erweitern.php?wid=$wid_old\">Erweitern</a> - <a href=\"special/loeschen.php?wid=$wid_old&all=1\">Alle l&ouml;schen</a>
                                            </td>
                                        </tr><tr>
                                            <td>&nbsp</td>
                                        </tr>";
                        }

                        else if ($word_old != '') {

                            $content .= "
                                        <tr>
                                            <td colspan='6' align='right'>
                                            <a href=\"special/erweitern.php?wid=$wid_old\">Erweitern</a>
                                            </td>
                                        </tr><tr>
                                            <td colspan='6'>&nbsp;</td>
                                        </tr>
                                        ";
                        }

                        $word_old = $res[word];
                        $i = 0;
                    }

                    $content .= get_result($res['wid'],$res['tid'],$res['word'],$res['translation'],$res['type'],$res['comment'],$i+1);

                    $i++;
                    $wid_old = $res[wid];
                }

                if ($i > 1) {

                    $content .= "
                                <tr>
                                    <td colspan='6' align='right'>
                                    <a href=\"erweitern.php?wid=$wid_old\">Erweitern</a>&nbsp;-
                                    <a href=\"loeschen.php?wid=$wid_old&all=1\">Alle&nbsp;l&ouml;schen</a>
                                    </td>
                                </tr>";
                } else {

                    $content .= "
                                <tr>
                                    <td colspan='6' align='right'>
                                    <a href=\"erweitern.php?wid=$wid_old\">Erweitern</a>
                                    </td>
                                </tr>";
                }

            } else {

                $content = "Suche erfolglos! Der Suchstring konnte in der Datenbank nicht gefunden werden!";
                $content .= "<a href='/l10n/glossar/suchen.php' style='display:block; float: right'>Zur Suche</a>\n"
            }
        }

        $content .= "</table>";

    } else {

        // $show is not set, so give us the interface
        $content .=
                  "<form action=\"\" method=\"POST\">
                   <input type=\"hidden\" name=\"show\" value=\"results\">
                   <table><tr>
                       <td>Suchbegriff</td>
                       <td><input name=\"query\"></td>
                    </tr><tr>
                        <td valign=\"top\">Suche in</td>
                        <td>
                        <input type=\"radio\" name=\"in\" value=\"e\" checked=\"checked\">Englisch<br>
                        <input type=\"radio\" name=\"in\" value=\"g\">Deutsch
                        </td>
                    </tr><tr>
                        <td colspan=\"2\" align=\"right\">
                        <input type=\"submit\" value=\"Suchen!\">
                        </td>
                    </tr></table>

                    <p>
                        <b>Hinweise:</b>
                    </p>
                        <ol>
                            <li>
                                Es wird nach &raquo;&auml;hnlichen&laquo; Begriffen gesucht, d.h.
                                &raquo;at&laquo; findet auch &raquo;attach&laquo, etc.
                            </li>
                            <li>
                                Wenn nichts im Suchfeld eingetragen wird, wird die komplette Datenbank
                                ausgegeben!
                            </li>
                        </ol>";
    }

    $smarty->assign ('caption', 'suchen');
    $smarty->assign ('content', $content);

    $smarty->display ("standard.tpl");
?>
