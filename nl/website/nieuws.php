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
translate();
gnome_head();
gnome_menu();
?>
<div class="content">
<h1>Gnome-nl Nieuws</h1>
<?
$filename = "text/nieuws.text";
$fcontents = file($filename);
$pos = 0;
$aantal = 0;
$news_per_page = 5;

if ( ! empty( $_GET["alles"] ) )	
	$max_nieuws = 1000; //arbitrary value, increase if you need more
else
	$max_nieuws = $news_per_page ;

	while( (list ($line_num, $line) = each ($fcontents)) && $aantal < $max_nieuws  ) {
	    if ($line_num == $pos) {
	        echo "<table class=\"nieuws\"><tr>";
	        echo "<th align=LEFT class=\"news\">", htmlspecialchars ($line), ": ";
	    } elseif ($line_num == $pos + 1) {
	        echo htmlspecialchars ($line), "</th></tr>\n";
			  echo "<tr><td class=\"news\">";
	    } elseif (chop($line) == "---") {
	        echo "</td></tr></table>\n<br>";
	        $pos = $line_num + 1;
			  $aantal++;
	    } else {
			  echo $line;
	    }
	}

if ( ! empty( $line ) )	
	echo "<table><tr><td><a href=\"nieuws.php?alles=alles\">Ouder nieuws...</a></td></tr></table>\n";
	
?>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
