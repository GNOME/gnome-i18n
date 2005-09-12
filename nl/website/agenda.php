<?
include "functions.php";
is_logged_in();

$max_events = (!empty($_GET["alles"])) ? 10 : 9999;

// connect to mysql and select database
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

// build the query, order by newest ID and limit it
//@TODO the queries don't look like they do what the comment says.
if ( ! empty( $_GET["item"] ) )	
	$sql = "select * from `events` where id = '".$_GET["item"]."'";
else
	$sql = "select * from `events` order by `from` asc limit ".$max_events;

//run the query and set a result identifier to the recordset
$res = mysql_query($sql);

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

<? if (is_logged_in()) { ?>
	<a href="agenda_edit.php?item=0" ><img src="images/button_edit.png" alt="nieuw">Evenement toevoegen</a>
<?}



// loop the recordset
while ($event = mysql_fetch_assoc($res)) {
	$id        = $event['id'];
	$from      = $event['from']; //from is a bad name, perhaps change sometime in DBtable.
	$title     = $event['title'];
	$summary   = $event["summary"];
	$location  = $event["location"];
	$personen  = $event["personen"];
	$content   = $event["content"];
	
	$loged_in_html = "<a href=\"agenda_edit.php?item=$id\"><img src=\"images/button_edit.png\" alt=\"[edit]\"></a>";
?>

<table class="nieuws">
	<tr>
		<th align="LEFT" class="news"><?=(is_logged_in()) ? $loged_in_html : "";?> <?=$from?> : <?=$title?></th>
	</tr>
	<tr>
		<td class="news">
<?if (!empty($_GET["item"])){ ?>
				<h4>Samenvatting</h4>
				<?=$summary?>
				<h4>Locatie</h4>
				<?=$location;?>
				<h4>Wie gaan er?</h4>
				<?=$personen;?>
				<h4>Meer info:</h4>
				<?=$content;?>
<?} else {?>
			<p><?=$summary;?></p>
<? } ?>
		</td>
	</tr>

<? if (empty($_GET["item"])){ ?>	
	<tr>
		<td class="news"><a href="<?$PHP_SELF?>?item=<?=$id?>">Lees verder...</a></td>
	</tr>
<? } ?>
</table>
<? } //END WHILE LOOP ?>

<?if (empty($_GET["alles"]) &&  empty($_GET["item"])) {	?>
<table>
	<tr>
		<td><a href="<?=$PHP_SELF?>?alles=alles">Hele agenda...</a></td>
	</tr>
</table>
<? } ?>	

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
