<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Bedankt!</title>
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
<h1>Foutrapport ontvangen</h2>
<p>Bedankt voor je foutrapport. We gaan het uitzoeken en laten het je weten zodra
we de fout hebben gevonden in de vertaling.</p>
<p><a href="index.php">Klik hier</a> om terug te gaan naar de hoofdpagina.</p>
<p><a href="foutrapport.php">Klik hier</a> om nog een vertaalfout door te geven.</p>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
