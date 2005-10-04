<?

$reclame = array('<div>
GNOME-NL wordt gesponsord door Kovoks!
<center><a href="http://www.kovoks.nl/">
<img src="images/kovoks.banner-groot.gif" alt="KovoKs-logo"></a></center>
</div>', 
'<div>
GNOME-NL wordt gesponsord door Stichting Softwareconsulent!
<center><a href="http://www.softwareconsulent.nl/">
<img src="images/softwareconsulent.gif" alt="logo softwareconsulent"></a></center>
</div>', 
	'<div><p>Heeft u een vertaalfout gevonden in de software? Laat dat dan direct weten
<a href="foutrapport.php">via dit formulier.</a></p></div>', 
	'<div>Koop nu een GNOME T-shirt op 
<a href="http://www.hackerthreads.com/?page=shop/browse&category_id=d9d98a894330473f447ef478d017aa70">
Hackerthreads</a>. Een deel hiervan zal naar de GNOME Foundation gaan.
</div>',
'<div>
GNOME-NL wordt gesponsord door HurryJane!
<center><a href="http://www.hurryjane.com/">
<img src="images/hurryjane.jpg" alt="logo van HurryJane"></a></center>
</div>');

echo $reclame[rand (0, count ($reclame) - 1)];

?>
