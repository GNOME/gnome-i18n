<?php
        require ("path.inc.php");
        require ("../config.php");

        // connect to the database (with write access)
        mysql_connect($hostname, $username, $password);

        /* Vars passed to us:
                $wid        - Word ID (needed)
                $tid        - Type ID (needed)
                $trans      - translation of the word with ID $wid
                $all        - set to 1 if you want to delete all records of $wid
                $processed  - set if the form has been displayed and the user
                              hit submit
                !!! DO NET SET BY HAND !!!
        */

        if (!$wid || (!$tid && $all != 1) || (!$trans && $all != 1)) {

                $smarty->assign ('caption', 'fehler');
                $smarty->assign ('content', '<p>Dieses Script kann nur von anderen Scripten aus aufgerufen werden!</p>');
                $smarty->display ('standard.tpl');
                exit;
        }

        $smarty->assign ('caption', 'l&ouml;schen');
        $content = "";

        if ($processed != 1) {

            // user has to confirm the action
            $content = "
            <form action=\"\" method=\"post\">
            <p>Wollen Sie wirklich l&ouml;schen?</p>
            <input type=\"hidden\" name=\"processed\" value=1>
            <input type=\"hidden\" name=\"wid\" value=\"$wid\">
            <input type=\"hidden\" name=\"tid\" value=\"$tid\">
            <input type=\"hidden\" name=\"trans\" value=\"$trans\">
            <input type=\"hidden\" name=\"all\" value=$all>
            <input type=\"submit\" value=\"Ja, sofort l&ouml;schen\">
            </form>";
        } else {

                // so, user really wants to delete... let's do it

                if ($all == 1 && $trans == "") {

                        // delete all record connected with $wid
                        // first look up which types are connected with $wid
                        $query = "SELECT * FROM $typestable WHERE wid = '$wid'";
                        $result_types = mysql_db_query($dbName, $query);

                        if ($result_types && mysql_num_rows($result_types) > 0) {

                            while ($rtype = mysql_fetch_array($result_types)) {

                                // now delete the entries with these types in the
                                // transtable and in the typestable
                                $query = "DELETE FROM $transtable WHERE tid = '$rtype[tid]'";
                                $delresult1 = mysql_db_query ($dbName, $query);

                                $query = "DELETE FROM $typestable WHERE tid = '$rtype[tid]'";
                                $delresult2 = mysql_db_query ($dbName, $query);
                            }
                            // now delete the word
                            $query = "DELETE FROM $wordstable WHERE wid = '$wid'";
                            $delresult3 = mysql_db_query($dbName,$query);
                        }

                        if (!$delresult1 || !$delresult2 || !$delresult3) {

                            $content = '<p>Es trat ein Fehler beim L&ouml;schen auf. Bitte kontaktieren Sie den <a href="mailto:webmaster@gnome-de.org">Webmaster</a>!</p>';
                        } else {

                            $content = '<p>Alle Eintr&auml;ge wurden erfolgreich gel&ouml;scht</p>';
                        }
                }

                if ($trans) {

                    // user wants to delete only one translation
                    // first, just delete the word
                    $query = "DELETE FROM $transtable WHERE translation = '$trans' AND tid = '$tid'";
                    $delresult1 = mysql_db_query($dbName,$query);

                    if ($delresult1) {

                        $content = "<p>Eintrag wurde gel&ouml;scht</p>";

                        // now check if there are more word connected with the tid
                        // if no, then also delete this type entry
                        $query = "SELECT * FROM $transtable WHERE tid = '$tid'";
                        $result_trans = mysql_db_query($dbName,$query);

                            if (!$rwords = mysql_fetch_array($result_trans)) {

                                // no entry, so delete the type
                                $query = "DELETE FROM $typestable WHERE tid = '$tid'";
                                $delresult2 = mysql_db_query($dbName,$query);
                                $content .= "<p>Wortart f&uuml;r dieses Wort wurde gel&ouml;scht, da keine &Uuml;bersetzung mehr darauf zugreift.</p>\n";

                                // last step: check if there is a type left for the word
                                $query = "SELECT * FROM $typestable WHERE wid = '$wid'";
                                $result_types = mysql_db_query($dbName,$query);

                                if (!$rtypes = mysql_fetch_array($result_types)) {

                                    // no type left -> delete the word itself
                                    $query = "DELETE FROM $wordstable WHERE wid = '$wid'";
                                    $delresult3 = mysql_db_query($dbName,$query);
                                    $content .= "<p>Wort wurde aus Datenbank entfernt, da keine &Uuml;bersetzung mehr vorhanden war.</p>\n";
                                }
                            }

                        } else {

                                $content = '<p>Es trat ein Fehler beim Löschen des Eintrags auf. Bitte kontaktieren Sie den <a href="mailto:webmaster@gnome-de.org">Webmaster!</a></p>';
                        }
                }
        }

        $smarty->assign ('content', $content);
        $smarty->display ('standard.tpl');
?>
