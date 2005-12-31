<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Status
  <? echo $gnomeversion; ?>
  </title>
<?if (isset($gnomeversion)) {
?>

<script language="javascript" type="text/javascript"><!--
var r
function showMessage(pak,main) {
    if (r) {
        r.style.display='none'
    }
    r = document.getElementById('rev_'+main)
    var l = document.getElementById(pak)
    var t = l.offsetTop
    var p = l.offsetParent
    while (p.tagName != 'BODY') {
        t = t + p.offsetTop
        p = p.offsetParent
    }
    r.style.top = t - 20
    r.style.left = 5
    r.style.display=''
}
function hideMessage() {
    if (r) {
        r.style.display='none'
    }
}
//--></script>
<? 
}
html_head(); ?>
</head>
<?
if (isset($gnomeversion)) {
 echo "<body onClick=\"hideMessage()\">";
}
?>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<?
if (!isset($gnomeversion)) {
//
//
// No gnomeversion set. So, display the standard graphs
//
//
// print a little info about the statuspages...
//
?>
<div class="content">
<div class="rightbox">
<? print_cvs(); ?>
</div>
<h1>Vertaalstatus Gnome-NL</h1>
<p align="left">De Vertaalstatus pagina's worden beheerd door "The Gnome Translation Project" op gnome.org. Iedere dag worden alle vertalingen gecontroleerd en worden de statuspagina's ververst.
 Via de homepage van het <a href="http://developer.gnome.org/projects/gtp/">Gnome Translation Project</a> vind je naast de statuspagina's ook nog extra informatie wat betreft het vertalen van GNOME.</p>
<p>Alle statusinfo hier op sourceforge wordt dynamisch aangemaakt en minstens 1x per dag ververst. Ook biedt de lokale modulelijst enige voordelen boven de lijst op gnome.org. U wordt daarom aangeraden om gebruik te maken van deze lijst in plaats van de lijst op gnome.org.</p>
<p>De Nederlandse vertalingen zijn volledig genoeg voor een mooie plaats in de 
<a href="http://l10n-status.gnome.org/<? echo "$important_branch"; ?>/top.html">top</a> van best ondersteunde talen. Op het moment staan we op plaats 
<?
$filename = "text/top10.text";
$fcontents = file($filename);
while (list ($line_num, $line) = each ($fcontents)) {
	$topten = chop ($line);
	echo "$topten";
	echo ".";
} //End of while.
?>
</p>
<table>
<?
$filename = "text/versions.text";
$fcontents = file($filename);
while (list ($line_num, $line) = each ($fcontents)) {
	$gnomeversion = chop ($line);
	echo "<tr><td colspan=\"2\"><h1>$gnomeversion: </h1>";
	transstatus("$gnomeversion");
	echo "<a href=\"status.php?gnomeversion=$gnomeversion\">";
	echo "<img src=\"data/status.$gnomeversion.png\" ";
	echo "alt=\"$gnomeversion vertaalstatusgrafiek\"></a>";
	echo "</td></tr>";

} //End of while.

echo "</table>";

} else {
//
//
//
// Gnomeversion has been detected!!!
//
//
//
// Create a standard packagelist
//
//
//
//
echo "<div class=\"content\">\n";
echo "<H1>Vertaalstatus $gnomeversion</H1>";
$fpackages = file("text/packages.$gnomeversion.text");
//$fmaintainers = "text/translators.text";
//$fp = fopen( $fmaintainers, 'r' );
//$fmaintainers_contents = fread( $fp, filesize( $fmaintainers ) );
//fclose( $fp );
//$fmaintainers_lines = explode ( "\n", $fmaintainers_contents );
$popup = array();
list ($pline_num, $pline) = each ($fpackages);
echo "<blockquote><center>";
echo "Laatst bijgewerkt: ";
echo $pline;
echo "</center></blockquote>\n";
list ($pline_num, $pline) = each ($fpackages);
$url_prefix=$pline;
?>
<table border="0" class="statustable">
  <tr>
    <th class="module">Module</th>
    <th class="module">Vertaler</th>
    <th class="module">Vertaald</th>
    <th class="module">Onzeker</th>
    <th class="module">Onvertaald</th>
    <th class="module">%</th>
  </tr>
<?

//connect to the db for our maintainerlist
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

// Now we are going to print all of the packages in one big table
while (list ($pline_num, $pline) = each ($fpackages)) {
	if ($regel == "oneven") {
		$regel = "even";
	} else {
		$regel = "oneven";
	}
//	To work around an empty group, we must detect lines with --
	if ((chop(htmlspecialchars ($pline))) == "--") {
		list ($pline_num, $pline) = each ($fpackages);
	}
	if (substr($pline, 0, 3) == "<--") {
		// A new group of packages
		echo "  <tr><th colspan=\"6\" class=\"module\">";
		echo substr($pline, 3, -4);
		echo "</th></tr>\n";
	} elseif ((chop(htmlspecialchars ($pline))) == "TOTAL") {
		// The totals of the group
		$maintainer="Gnome-nl Vertaalteam";
		list ($pline_num, $pline) = each ($fpackages);
		$trans=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$percentage=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$fuzzy=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		list ($pline_num, $pline) = each ($fpackages);
		$untrans=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		print_trans($regel, "TOTAL", "NULL", "NULL", "HEAD", $trans, $fuzzy, $untrans, $percentage, $maintainer);
		echo "  <tr><td colspan=\"6\"></td></tr>\n";
		flush();
	} else {
		// A normal package line.
		// Get a full list of maintainers from the db
		$sql = "select * from users";
		$users = mysql_query($sql);
		$URL="$url_prefix$gnomeversion/$pline";
		list ($pline_num, $pline) = each ($fpackages);
		$naam=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$cvslocatie=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$branch=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$trans=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$percentage=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		$fuzzy=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		list ($pline_num, $pline) = each ($fpackages);
		$untrans=$pline;
		list ($pline_num, $pline) = each ($fpackages);
		list ($pline_num, $pline) = each ($fpackages);
//		Now, search for the translator...
		$found = 0;
   		$maintainer = "-- Vrij --";
		while ($user = mysql_fetch_assoc($users)) {
			$maintname = $user[name];
			$email = $user[email];
			$packages = $user[packages];
			$packagelist = explode( ' ', $packages );
//				echo "$naam .. $packages : <br>";
			foreach ( $packagelist as $package ) {
				if (chop($naam) == $package) {
					$found = 1;
					$maintainer = $maintname;
//						echo "$maintainer <br>";
//						remember the translator for the popup thingie
					$temp = array();
					$tempo = array();
					$tempo[$percentage] = $URL;
					$temp[$naam] = $tempo;
					$popup[$maintainer][] = $temp;
					break;
				}
			}
		}
		print_trans($regel, $naam, $cvslocatie, $URL, $branch, $trans, $fuzzy, $untrans, $percentage, $maintainer);
	}
}
echo "</table>\n";

// Now we make the popup windows for the maintainers.
while (list ($maintainer, $package_array) = each ($popup)) {
	translator_div($maintainer, $package_array);
}

// the end.
} 
?>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
