<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Details
  <? echo $gnomeversion; ?>
  </title>
<? html_head(); ?>
</head>
<body>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<div class="content">
<div class="rightbox">
<? print_cvs(); ?>
</div>
<h1>Detailgrafieken van de vertaalstatus Gnome-NL</h1>
<table>
<?
$versionfilename= "text/versions.text";
$versioncontents = file($versionfilename);
while (list ($line_num, $line) = each ($versioncontents)) {
	$version = chop ($line);
	echo "<tr><td colspan=\"2\"><h1>$version:</h1></td></tr>";
	$filename = "text/groups.$version.text";
	$fcontents = file($filename);
	while (list ($line_num, $line) = each ($fcontents)) {
		$group = chop ($line);
		echo "<tr><td colspan=\"2\"><h2>$group:</h2>";
		echo "<a href=\"status.php?gnomeversion=$version\">";
		echo "<img src=\"data/status.$group.$version.png\" ";
		echo "alt=\"$gnomeversion vertaalstatusgrafiek $group\"></a>";
		echo "</td></tr>";
	} //End of while.
} //End of while.

echo "</table>";

// the end.
?>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
