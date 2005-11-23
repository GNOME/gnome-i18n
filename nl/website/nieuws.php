<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title>Gnome-nl -- Nieuws</title>
<? html_head() ?>

<link rel='alternate' type='application/rss+xml' title='GNOME-NL Nieuws' href='nieuws_rss20.php'>

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
$news_per_page = 10;
if (is_logged_in()) {
	echo "<a href=\"nieuws_edit.php?item=0";
	echo "\"><img src=\"images/button_edit.png\" alt=\"nieuw\">Nieuws toevoegen</a> ";
	}

if ( ! empty( $_GET["alles"] ) )	
	$max_news = 1000; //arbitrary value, increase if you need more
else
	$max_news = $news_per_page ;

// connect to mysql and select database
    $db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

// build the query, order by newest ID and limit it to 10
if ( ! empty( $_GET["item"] ) )	
	$sql = "select * from news where id = '".$_GET["item"]."'";
else
	$sql = "select * from news order by id desc limit ".$max_news;

// run the query and set a result identifier to the recordset
$res = mysql_query($sql);

// loop the recordset
while ( $aantal < $max_news && $newsitem = mysql_fetch_assoc($res) ) {
  // this sticks the results row by row into an array called $newsitem. rows are called via $array["COLUMN"]
  $aantal++;
?><table class="nieuws"><tr><th align="LEFT" class="news"><?
if (is_logged_in()) {
	echo "<a href=\"nieuws_edit.php?item=".$newsitem["id"];
	echo "\"><img src=\"images/button_edit.png\" alt=\"[edit]\"></a> ";
	}
echo $newsitem["posted"];
?>: <?
echo $newsitem["title"];
?>
</th></tr>
<tr><td class="news">
<?
if ( ! empty( $_GET["item"] ) )	
	echo "<b>";
echo $newsitem["summary"];
if ( ! empty( $_GET["item"] ) )	
	echo "</b>"; ?>
</td></tr>
<?
	if ( ! empty( $newsitem["content"] ) ) {
		if ( ! empty( $_GET["item"] ) )	
			echo '<tr><td class="news">'.$newsitem["content"].'</td></tr>';
		else
			echo '<tr><td class="news"><a href="'.$PHP_SELF.'?item='.$newsitem["id"].'">Lees verder...</a></td></tr>';
	};
?>
</table>
<?
};

if ( empty( $_GET["alles"]) &&  empty( $_GET["item"] ) )	
	echo "<table><tr><td><a href=\"".$PHP_SELF."?alles=alles\">Ouder nieuws...</a></td></tr></table>\n";
	
?>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
