<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Nieuws</title>
<? html_head() ?>
</head>
<body>
<div class="body">

<?
meter();
gnome_head();
gnome_menu();
?>
<div class="content">
<h1>GNOME Vertaalnieuws</h1>
<?
$filename = "text/nieuws.text";
$fcontents = file($filename);
$pos = 0;
$aantal = 0;
$max_nieuws = 5;
if (!isset($alles)) {
	while ($aantal != $max_nieuws) {
	    list ($line_num, $line) = each ($fcontents);
	    if ($line_num == $pos) {
	        echo "<table class=\"nieuws\"><tr>";
	        echo "<th class=\"news\">", htmlspecialchars ($line), ": ";
	    } elseif ($line_num == $pos + 1) {
	        echo htmlspecialchars ($line), "</th></tr>\n";
		echo "<tr><td class=\"news\">";
	    } elseif (chop($line) == "---") {
	        echo "</td></tr></table>\n";
	        $pos = $line_num + 1;
		$aantal = $aantal + 1;
	    } else {
		echo $line;
	    }
	}
	echo "<table><tr><td><a href=\"nieuws.php?alles=alles\">Ouder nieuws...</a>\n";
} else {
	while (list ($line_num, $line) = each ($fcontents)) {
	    if ($line_num == $pos) {
	        echo "<table><tr>";
	        echo "<th class=\"news\">", htmlspecialchars ($line), ": ";
	    } elseif ($line_num == $pos + 1) {
	        echo htmlspecialchars ($line), "</th></tr>\n";
		echo "<tr><td class=\"news\">";
	    } elseif (chop($line) == "---") {
	        echo "</td></tr></table>";
	        $pos = $line_num + 1;
	    } else {
		echo $line;
	    }
	}
}
echo "</td></tr></table>";

?>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
