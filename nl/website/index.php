<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Welkom</title>
<? html_head() ?>
</head>
<body>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<div class="rightbox">
<?transstatus($important_branch); ?>
</div>
<div class="content">
<h1>Welkom bij GNOME Nederlands</h1>
<p>Deze pagina is gemaakt als centrale plaats om het vertaalproject
voor Gnome te coördineren. Het doel van het project is duidelijk: 
<b>Gnome 100% Nederlands</b>. Dit is natuurlijk een eeuwige strijd omdat
we een bewegend doel hebben. Toch is bijna 100% goed haalbaar.</p>
<p>Op deze website is informatie te vinden over de voortgang van het project,
alsmede informatie voor nieuwe vertalers.</p>
<p>Ook voor gebruikers hebben we een stukje van deze site ingedeeld om
uit te leggen hoe ze gebruik kunnen maken van deze vertalingen.</p>
<p>In geval van opmerkingen over / problemen met vertalingen kunt u het beste de
volgende dingen doen: </p><ol><li>Kijk eerst of er een nieuwere versie is van het
programma.</li><li>Neem contact op met de vertaler, te vinden via de 
statuspagina (klik op het woordje download en kijk dan naar de regel "Last-translator")
</li><li>Als dat geen resultaat oplevert, neem dan contact op met de maker / coördinator
van het programma.</li></ol>
</div>
<? gnome_foot() ?>

</div>
</body>
</html>
