<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Refresh" content="2; url=
<?
echo "$pagina";
?>.php">
</head>
<body><?
flush;
if ($naam=="") $naam = "Naamloos";
if ($email=="@") $email = "onbekend@adres";
if ($email=="") $email = "onbekend@adres";
$header .= "Reply-To: $naam <$email>\n";
if ($object=="Gnome-nl foutrapport") {
$onderwerp = $object . ": " . $prog;
$volledigbericht = "Programma: " . $prog . " versie: " . $versie . "\n"
	. "Foute vertaling:\n" . $fout . "\nVerbeterde vertaling:\n" . $goed
	. "\n\nExtra info:\n" . $extra . "\n"
	. "\n--------\nComputer: " . getenv("REMOTE_HOST") . "\n"
	. "Browser: " . getenv("HTTP_USER_AGENT") . "\n";
} else {
$onderwerp = $object . " van " . $naam;
$volledigbericht = $extra . "\n"
	. "\n--------\nComputer: " . getenv("REMOTE_HOST") . "\n"
	. "IP-adres: " . getenv("REMOTE_ADDR") . "\n"
	. "Browser: " . getenv("HTTP_USER_AGENT") . "\n";
}
echo "<p>sending mail...</p>\n";
flush;
if (mail("adrighem@gnome.org",$onderwerp,$volledigbericht,$header)==true) {
	sleep(1);
//	header( "Location: " . $pagina . ".php" );
echo "<b>sent!</b>";
}
?>
</body>
</html>
