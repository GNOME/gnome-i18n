<?

$reclame = array('<div>
GNOME-NL wordt gesponsord door Kovoks!
<center><a href="http://www.kovoks.nl/">
<img src="http://www.kovoks.nl/image/logo.gif" alt="KovoKs-logo"></a></center>
</div>', 
	'<div><p>Heeft u een vertaalfout gevonden in de software? Laat dat dan direct weten
<a href="foutrapport.php">via dit formulier.</a></p></div>', 
	'<div>Koop nu een GNOME T-shirt op 
<a href="http://www.hackerthreads.com/?page=shop/browse&category_id=d9d98a894330473f447ef478d017aa70">
Hackerthreads</a>. Een deel hiervan zal naar de GNOME Foundation gaan.
</div>');

echo $reclame[rand (0, count ($reclame) - 1)];

?>
