<?
header("Content-Type: text/xml; charset=UTF-8");
include "functions.php";
echo "<?xml version=\"1.0\"?>\n";
?>
<rss version="2.0">
    <channel>
	<title>GNOME-NL Nieuws</title>
	<link>http://nl.gnome.org/nieuws.php</link>
	<description>Nieuws van GNOME-NL</description>
	<language>nl-NL</language>
	<pubDate><?print date("r"); ?></pubDate>
	<lastBuildDate><?print date("r"); ?></lastBuildDate>
	<generator>My own php-mysql script</generator>
	<managingEditor>V.vanAdrighem@dirck.mine.nu</managingEditor>
	<webMaster>V.vanAdrighem@dirck.mine.nu</webMaster>
<?
	$max_news = 1000; //arbitrary value, increase if you need more

// connect to mysql and select database
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

// build the query, order by newest ID
$sql = "select * from news order by id desc limit ".$max_news;

// run the query and set a result identifier to the recordset
$res = mysql_query($sql);

// loop the recordset
while ( $aantal < $max_news && $newsitem = mysql_fetch_assoc($res) ) {
  // this sticks the results row by row into an array called $newsitem. rows are called via $array["COLUMN"]
  $aantal++;
echo "		<item>\n";
//echo "			<pubDate>";
//echo $newsitem["posted"];
//echo "</pubDate>\n";
echo "			<title>";
echo $newsitem["title"];
echo "</title>\n";
echo "			<description><![CDATA[";
echo mb_convert_encoding($newsitem["summary"],'UTF-8');
//echo htmlentities ($newsitem["summary"],ENT_COMPAT,'UTF-8');
echo "]]></description>\n";
echo "			<guid>";
echo "http://nl.gnome.org/nieuws.php?item=";
echo $newsitem["id"];
echo "</guid>\n";
echo "		</item>\n";
}; ?>
    </channel>
</rss>
