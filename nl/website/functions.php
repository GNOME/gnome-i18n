<?
// Hier komen alle functies te staan die in alle scripts gebruikt kunnen worden.
// Zo worden de scripts zelf redelijk schoon gehouden.
// Functies tot nu toe zijn:
//
//	gnome_head()
//	gnome_menu(where)
//      print_vertaling(array, even(boolean))
//	check_html()
//      html_head()
//

$important_branch = "gnome-2.10";
function translate() { ?>
<div class="translate">
<? $url = sprintf("%s%s%s","http://",$GLOBALS["HTTP_HOST"],$GLOBALS["REQUEST_URI"]);
 ?>
<form name="TRANS" action="http://www.systranbox.com/systran/box" method="post">
<input name="systran_id" type="hidden" value="SystranSoft-en">
<input name="systran_charset" type="hidden" value="utf-8">
<input type="hidden" value="url" name="ttype">
<input type="hidden" value="<? echo $url; ?>" name="systran_url">
<input type="hidden" name="systran_lp" value="nl_en">
<input type="image" alt="English translation" src="images/uk-flag.gif">
</form>
<?
echo "</div>";
}

function html_head() {
$backgrounds = array('background01.png', 'background02.png', 'background03.png', 'background04.png', 'background05.png');
?>
  <link rel="icon" href="images/gnome-nl-logo.png" type="image/png">
  <link rel="stylesheet" href="css/default.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="author" content="Vincent van Adrighem">
  <meta name="keywords" content="gnome translation dutch nl localisation vertalen i18n nederlands">
  <style type="text/css">
    .hdr {
      background: url("images/<? 
	echo $backgrounds[rand (0, count ($backgrounds) - 1)];
?>") no-repeat;
    }
  </style>
<?
}

function gnome_head() {
$logos = array('gnome-nl-logo.png', 'gnome-nl-logo.png', 'gnome-be-logo.png');
?><div class="hdr">
	<a href="index.php"><img class="logo" src="images/<?
        echo $logos[rand (0, count ($logos) - 1)];
?>" alt="Thuis" title="Terug naar de beginpagina"></a>
</div>
<?
}

function gnome_foot() { ?>
<div class="copyright">
<a href="http://www.kovoks.nl/"><img src="images/kovoks.banner.gif"
	border="0" alt="Hosted by KovoKs.nl"></a>
<a href="http://validator.w3.org/check/referer"><img border="0"
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01!" height="31" width="88"></a>
<a href="http://jigsaw.w3.org/css-validator/validator?uri=http://nl.gnome.org/">
	<img border="0" src="http://jigsaw.w3.org/css-validator/images/vcss"
	 alt="Correct CSS!" height="31" width="88"></a>
<a href="mailto:adrighem&#064;gnome.org">Webmaster</a>
</div>
<? }

function gnome_menu() { ?>
<div class="menu">
<div class="section">
	<div class="sectiontitle">Algemeen</div>
	<div class="sectioncontent">
		<ul class="nobullet">
		<li><a href="index.php">Thuis</a></li>
		<li><a href="nieuws.php">Nieuws</a> <?
//Dit stukje vist de datum uit de nieuwstekst-spool.
	$filename = "text/nieuws.text";
	$fcontents = file($filename);
	list ($line_num, $line) = each ($fcontents);
	$line = chop($line);
	$line = substr($line, 0, strlen($line) - 5);
	echo "(", $line, ")</li>\n"; ?>
		</ul>
	</div>
</div>
<div class="section">
	<div class="sectiontitle">Vertaalstatus</div>
	<div class="sectioncontent">
		<ul class="nobullet">
		<li><a href="status.php">Overzicht</a>
			<ul><li><a href="status_details.php">Details</a></li></ul>
		</li>
<?
//Dit stukje zoekt uit welke projecten nu eigenlijk actief vertaald worden.
	$filename = "text/versions.text";
	$fcontents = file($filename);
	while (list ($line_num, $line) = each ($fcontents)) {
		$line = chop ($line);
		echo "      <li><a href=\"status.php?gnomeversion=$line\">$line</a></li>\n";
	} ?>
		</ul>
	</div>
</div>
<div class="section">
	<div class="sectiontitle">Vertalers</div>
	<div class="sectioncontent">
		<ul class="nobullet">
		<li><a href="vertalers_brief.php">Brief aan nieuwe vertalers.</a></li>
		<li><a href="vertalers_info.php">Korte uitleg</a></li>
		<li><a href="vertalers_anatomie.php">Anatomie van een po</a></li>
		<li><a href="vertalers_programmas.php">Programma's</a></li>
		<li><a href="vertalers_lijsten.php">Discussielijsten</a></li>
		<li><a href="vertalers_hulp.php">E.H.B.V.</a></li>
		<li><a href="vertalers_cvs.php">CVS-toegang</a></li>
		</ul>
	</div>
</div>
<div class="section">
	<div class="sectiontitle">Gebruikers</div>
	<div class="sectioncontent">
		<ul class="nobullet">
		<li><a href="gebruikers_omgeving.php">Gnome Nederlands</a></li>
		<li><a href="foutrapport.php">Vertaalfouten</a></li>
		</ul>
	</div>
</div>
<div class="section">
	<div class="sectiontitle">Diversen</div>
	<div class="sectioncontent">
		<ul class="nobullet">
		<li><a href="woordenboeken.php">Woordenboeken</a></li>
		<li><a href="verwijzingen.php">Andere websites</a></li>
		</ul>
	</div>
</div>
</div>
<? flush();
}

function print_cvs() {
echo "<h2>CVS-updates:</h2>";
$filename = "text/cvs.text";
$fcontents = file($filename);
echo "<ul>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
list ($line_num, $line) = each ($fcontents);
echo "<li>", htmlspecialchars ($line), "</li>";
echo "</ul>";
}

function transstatus($gnomeversion) {
	echo "<H2>Vertaalstatus $gnomeversion</H2><UL>";
	$fpackages = file("text/packages.$gnomeversion.text");
	while (list ($pline_num, $pline) = each ($fpackages)) {
		if (substr($pline, 0, 3) == "<--") {
			$partname=substr($pline, 3, -4);
		}
		if ((chop(htmlspecialchars ($pline))) == "TOTAL") {
			list ($pline_num, $pline) = each ($fpackages);
			list ($pline_num, $pline) = each ($fpackages);
			echo "<li>";
			echo "$partname : ";
			echo $pline;
			echo "%</li>";
		}
	}
echo "</UL>";
}

function percentagekleur(&$percentage) {
    $kleur = "#";
    $getal = 84 - $percentage;
    if ($percentage <= 84) {
    	$kleur = $kleur . "FF";
    } else {
    	$getal = ((((10 + $getal)*153) /20) + (((20 + $getal)*153) /40) + 102);
    	$kleur = $kleur . sprintf("%02X", $getal);
    }
    $getal = -87 + $percentage;
    if ($percentage >= 100) {
    	$kleur = "#00FF00";
    } else {
		if ($percentage < 84) {
    		$kleur = $kleur . "00";
    	} else {
    		$getal = ((($getal*153) /40) + (($getal*153) /20)+ 102);
    		$kleur = $kleur . sprintf("%02X", $getal);
    	}
    	$kleur = $kleur . "00";
    }
    if (chop($percentage) == "inf") {
		$kleur = "#6666DD";
		$percentage = "Onbekend";
    } elseif (chop($percentage) == "N/A"){
		$kleur = "#FF0000";
		$percentage = "0.00";
    } elseif (chop($percentage) == "0.00"){
        $kleur = "#FF0000";
	}
	return $kleur;
}

function print_trans($regel, $naam, $URL, $branch, $trans, $fuzzy, $untrans, $percentage, $maintainer) {
    if ("$naam" == "TOTAL") {
		$found = 2;
		echo "  <tr class=\"total\">\n";
		echo "    <td>Totaal</td>\n";
		echo "    <td>$maintainer</td>\n";
		echo "    <td>", chop(htmlspecialchars ($trans)), "</td>\n";
		echo "    <td>", chop(htmlspecialchars ($fuzzy)), "</td>\n";
		echo "    <td>", chop(htmlspecialchars ($untrans)), "</td>\n";
#		echo "    <td>&nbsp;</td>\n";
		$kleur=percentagekleur($percentage);
		echo "    <td class=\"percentage\" bgcolor=\"$kleur\">$percentage</td>\n";
    } else {
		echo "  <tr class=\"$regel\">\n";
		echo "    <td class=\"module\">";
#		echo "    (<a href=\"http://cvs.gnome.org/registry/file.cgi?	cvsroot=/cvs/gnome&amp;file=nl.po&amp;dir=", chop(htmlspecialchars ($naam)), "/po/&amp;rev=", chop($branch), "\" name=\"", chop(htmlspecialchars ($naam)), "\">CVS</a>)";
		echo "    (<a href=\"http://cvs.gnome.org/viewcvs/", chop(htmlspecialchars ($naam)), "/po/nl.po\" name=\"", chop(htmlspecialchars ($naam)), "\">CVS</a>)";
		echo " <a href=\"http://www.gnomefiles.org/search.php?search=", chop(htmlspecialchars ($naam)), "\">", chop(htmlspecialchars ($naam)), "</a> </td>\n";
		if ("$maintainer" == "-- Vrij --") {
			echo "    <td>$maintainer</td>\n";
		} else {
			if (chop($naam) == "gtk+") {
				$module_id = "gtk";
			} else {
			    if (chop($naam) == "gtk+-properties") {
				$module_id = "gtk-properties";
			    } else {
				$module_id = chop($naam);
			    }
			}
			echo "    <td id=\"", $module_id, "\" ";
			echo "onmouseover=\"showMessage('", $module_id;
			echo "','", str_replace(" ", "", $maintainer),"')\">";
			echo $maintainer, "</td>\n";
		}
		echo "    <td>", chop(htmlspecialchars ($trans)), "</td>\n";
		echo "    <td>", chop(htmlspecialchars ($fuzzy)), "</td>\n";
		echo "    <td>", chop(htmlspecialchars ($untrans)), "</td>\n";
#		echo "    <td><a href=\"", htmlspecialchars ($URL), "\">Download</a></td>\n";
		$kleur=percentagekleur($percentage);
		echo "    <td class=\"percentage\" bgcolor=\"$kleur\"><a href=\"", htmlspecialchars ($URL), "\">$percentage</a></td>\n";
    }
	echo "  </tr>\n";
}

function translator_div($maintainer, $package_array) {
    echo "<div id=\"rev_", str_replace(" ", "", $maintainer), "\" class=\"logmsg\" style=\"display: none;\">";
    echo "<table border=\"0\">";
    $regel = "oneven";
    echo "<tr><th colspan=\"2\" class=\"module\">", $maintainer, "</th></tr>";
    echo "<tr class=\"", $regel, "\"><td>Module</td><td>&nbsp;.Po</td></tr>";
    while (list ($package_num, $package) = each ($package_array)) {
	if ($regel == "oneven") {
		$regel = "even";
        } else {
		$regel = "oneven";
        }
	list ($package_name, $percent_array) = each ($package);
	list ($percentage, $po_url) = each ($percent_array);
	$kleur = "#";
	$getal = 84 - $percentage;
	if ($percentage <= 84) {
	    $kleur = $kleur . "FF";
	} else {
	    $getal = ((((10 + $getal)*153) /20) + (((20 + $getal)*153) /40) + 102);
	    $kleur = $kleur . sprintf("%02X", $getal);
	}
	$getal = -87 + $percentage;
	if ($percentage >= 100) {
	    $kleur = "#00FF";
	} elseif ($percentage < 84) {
	    $kleur = $kleur . "00";
	} else {
	    $getal = ((($getal*153) /40) + (($getal*153) /20)+ 102);
	    $kleur = $kleur . sprintf("%02X", $getal);
	}
	$kleur = $kleur . "00";
	echo "  <tr class=\"", $regel, "\"><td><a href=\"#", chop($package_name), "\">";
	echo chop($package_name);
	echo "</a></td><td class=\"percentage\" bgcolor=\"", $kleur, "\"><a href=\"", $po_url, "\">", chop($percentage), "</a></td></tr>";
    }
    echo "</table></div>\n\n";
}
?>
