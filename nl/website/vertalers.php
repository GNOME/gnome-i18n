<?php ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>
<head>
  <title>Gnome-nl -- Vertaalstatus</title>
<? html_head() ?></head>

<body class="inhoud" background="#FFFFFF">
<?
if (!isset($gnomeversion)) {
$gnomeversion=$important_branch;
}
echo "<ul><li><a href=\"index.php\">Thuis</a></li>";
echo "<li><a href=\"vertalers.php?gnomeversion=$gnomeversion\">Vertalerlijst</a></li></ul>";
$filename = "text/versions.text";
$fcontents = file($filename);
if (!isset($no_buttons)) {
	while (list ($line_num, $line) = each ($fcontents)) {
		$line = chop ($line);
		echo "<span class=\"gnomebutton\"><form 
method=\"get\">\n";
		echo "  <input type=\"hidden\" name=\"gnomeversion\" value=\"$line\">\n";
		if (isset($maint_num)) {
			echo "  <input type=\"hidden\" name=\"maint_num\" value=\"$maint_num\">\n";
		}
		echo "  <input type=\"submit\" value=\"$line\">\n";
		echo "</form></span>\n";
	}
}
$maintainerlist = array();
$packagelist = array();
$fmaintainers = "text/translators.text";
$fpackages = file( "text/packages.$gnomeversion.text" );
$fhpackages = file( "text/packages.HEAD.text" );
$fp = fopen( $fmaintainers, 'r' );
$fmaintainers_contents = fread( $fp, filesize( $fmaintainers ) );
fclose( $fp );
// Place the individual lines from the file contents into an array.
$maintainers_lines = explode ( "\n", $fmaintainers_contents );
// Split each of the lines into a name, an email and a packagelist
foreach ( $maintainers_lines as $line ) {
	list( $name, $email, $packages ) = explode( ':', $line );
	$length = strlen ( $line);
	if ("$length" != "0") {
		array_push($maintainerlist, $name);
		array_push($packagelist, $packages);
	}
}
if (!isset($maint_num)) {
	$maint_num = 0;
	foreach ( $maintainerlist as $maintainer ) {
		$maint_num = $maint_num + 1;
		$maintainer = chop($maintainer);
		echo "<a href=\"vertalers.php?gnomeversion=$gnomeversion&maint_num=$maint_num\">$maint_num : $maintainer</a> <br>\n";
	}
} else {
	$urls = array();
	$cur_maint_num = 0;
	foreach ( $maintainers_lines as $line ) {
		$cur_maint_num = $cur_maint_num + 1;
		if ("$cur_maint_num" == "$maint_num") {
//			echo "$line <br>";
			list( $name, $email, $packages ) = explode( ':', $line );
			echo "<table border=\"0\"><tr><th colspan=\"2\" class=\"module\">$maint_num : $name</th></tr>\n";
			echo "<tr class=\"oneven\"><td>Module</td><td>&nbsp;.Po</td></tr>\n";
			$packagelist = explode( ' ', $packages );
			foreach ( $packagelist as $package ) {
				//for current gnome version.
				list ($pline_num, $pline) = each ($fpackages);
				list ($pline_num, $pline) = each ($fpackages);
				$url_prefix = chop ($pline);
				while (list ($pline_num, $pline) = each ($fpackages)) {
					$packagename = chop ($pline);
					if ("$packagename" == "$package") {
					    $packagename = $pline;
					    list ($pline_num, $pline) = each ($fpackages);
					    list ($pline_num, $branch) = each ($fpackages);
					    list ($pline_num, $transl) = each ($fpackages);
					    list ($pline_num, $percentage) = each ($fpackages);
					    list ($pline_num, $fuzzy) = each ($fpackages);
					    list ($pline_num, $pline) = each ($fpackages);
					    list ($pline_num, $untransl) = each ($fpackages);
					    list ($pline_num, $pline) = each ($fpackages);
					    list ($pline_num, $pline) = each ($fpackages);
					    $url = "$url_prefix$gnomeversion/$url";
						echo "  <tr><td>$packagename</td>";
						$kleur=percentagekleur($percentage);
						echo "    <td class=\"percentage\" bgcolor=\"$kleur\">$percentage</td>\n";
						if ($percentage < 100) {
							array_push($urls, $url);
						}
						echo "</tr>\n";
					}
					$url = $pline;
				}
				reset($fpackages);
				//for HEAD
				list ($pline_num, $pline) = each ($fhpackages);
				list ($pline_num, $pline) = each ($fhpackages);
				$url_prefix = chop ($pline);
				while (list ($pline_num, $pline) = each ($fhpackages)) {
					$packagename = chop ($pline);
					if ("$packagename" == "$package") {
					    $packagename = $pline;
					    list ($pline_num, $pline) = each ($fhpackages);
					    list ($pline_num, $branch) = each ($fhpackages);
					    list ($pline_num, $transl) = each ($fhpackages);
					    list ($pline_num, $percentage) = each ($fhpackages);
					    list ($pline_num, $fuzzy) = each ($fhpackages);
					    list ($pline_num, $pline) = each ($fhpackages);
					    list ($pline_num, $untransl) = each ($fhpackages);
					    list ($pline_num, $pline) = each ($fhpackages);
					    list ($pline_num, $pline) = each ($fhpackages);
					    $url = "$url_prefix$gnomeversion/$url";
						echo "  <tr><td>$packagename</td>";
						$kleur=percentagekleur($percentage);
						echo "    <td class=\"percentage\" bgcolor=\"$kleur\">$percentage</td>\n";
						if ($percentage < 100) {
							array_push($urls, $url);
						}
						echo "</tr>\n";
					}
					$url = $pline;
				}
				reset($fhpackages);
			}
		}
	}
	echo "</table>\n";
	echo "Urls: <br>";
	echo "<pre>";
	while (list ($url_num, $url) = each ($urls)) {
		echo "$url\n";
	}
	echo "</pre>\n";
}
?></body>
</html>
