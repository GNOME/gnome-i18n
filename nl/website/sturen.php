<?php
if ($naam=="") $naam = "Naamloos";
if ($email=="@") $email = "onbekend@adres";
if ($email=="") $email = "onbekend@adres";
$onderwerp = $object . ": " . $prog;
$header .= "Reply-To: $naam <$email>\n";

$volledigbericht = "Programma: " . $prog . " versie: " . $versie . "\n"
	. "Foute vertaling:\n" . $fout . "\nVerbeterde vertaling:\n" . $goed
	. "\n\nExtra info:\n" . $extra . "\n"
	. "\n--------\nComputer: " . getenv("REMOTE_HOST") . "\n"
	. "IP-adres: " . getenv("REMOTE_ADDR") . "\n"
	. "Browser: " . getenv("HTTP_USER_AGENT") . "\n";

if (mail("adrighem@gnome.org",$onderwerp,$volledigbericht,$header)==true) {
	header( "Location: verzonden.php" );
}
?>
