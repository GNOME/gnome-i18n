<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title>Gnome-nl -- Agenda</title>
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
<h1>Gnome-nl Agenda</h1>
<?
$events_per_page = 10;
if (is_logged_in()) {
	echo "<a href=\"agenda_edit.php?item=0";
	echo "\"><img src=\"images/button_edit.png\" alt=\"nieuw\">Evenement toevoegen</a> ";
	}

if ( ! empty( $_GET["alles"] ) )	
	$max_events = 1000; //arbitrary value, increase if you need more
else
	$max_events = $events_per_page ;

// connect to mysql and select database
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

// build the query, order by newest ID and limit it
if ( ! empty( $_GET["item"] ) )	
	$sql = "select * from `events` where id = '".$_GET["item"]."'";
else
	$sql = "select * from `events` order by `from` asc limit ".$max_events;

// run the query and set a result identifier to the recordset
$res = mysql_query($sql);

// loop the recordset
while ( $aantal < $max_events && $event = mysql_fetch_assoc($res) ) {
  // this sticks the results row by row into an array called $event. rows are called via $array["COLUMN"]
  $aantal++;
?><table class="nieuws"><tr><th align="LEFT" class="news"><?
if (is_logged_in()) {
	echo "<a href=\"agenda_edit.php?item=".$event["id"];
	echo "\"><img src=\"images/button_edit.png\" alt=\"[edit]\"></a> ";
	}
echo $event["from"];
?>: <?
echo $event["title"];
?>
</th></tr>
<tr><td class="news">
<?
if ( ! empty( $_GET["item"] ) )	{
	echo "<b>Samenvatting</b>";
	echo "<p>";
	echo $event["summary"];
	echo "</p>";
	echo "<b>Locatie</b>";
	echo "<p>";
	echo $event["location"];
	echo "</p>";
	echo "<b>Wie gaan er?</b>";
	echo "<p>";
	echo $event["personen"];
	echo "</p>";
	echo "<b>Meer info:</b>";
	echo "<p>";
	echo $event["content"];
	echo "</p>";
}
else
	echo $event["summary"];
?>
</td></tr>
<?
if ( empty( $_GET["item"] ) )	
	echo '<tr><td class="news"><a href="'.$PHP_SELF.'?item='.$event["id"].'">Lees verder...</a></td></tr>';
?>
</table>
<?
};

if ( empty( $_GET["alles"]) &&  empty( $_GET["item"] ) )	
	echo "<table><tr><td><a href=\"".$PHP_SELF."?alles=alles\">Hele agenda...</a></td></tr></table>\n";
	
?>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
