<?php
echo "<body bgcolor=white><h1>Search in GNOME pos</h1><br>";
echo "<form action=db.php method=post>";
echo "<select name=what value=$what><option value=id>msgid";
echo "<option value=str>msgstr</select>";
echo "<input type=text name=search value=$search>";
echo "<input name=case type=checkbox value=$case checked>Case sensitive";
echo "<input type=submit value=Submit>";
echo "</form>";
if (!(($what=="") or ($search==""))) 
{
$database = pg_Connect ("user=username password=passwd dbname=term");
if ($case == 0)
$result = pg_exec ($database, "SELECT * FROM memory where $what ~* '$search'");
else
$result = pg_exec ($database, "SELECT * FROM memory where $what ~ '$search'");

if (!$result) {
    echo "Error in query.\n";
    exit;
}

if(pg_NumRows ($result)==0) {
echo "No hits.<br>";
} else {
echo "<table border=1><tr><td bgcolor=#999999>Fájlnév";
echo "<td bgcolor=#999999>msgid (Original)";
echo "<td bgcolor=#999999>msgstr (Translations)</tr>";
$i=0;
while (pg_NumRows ($result)!=$i) {

 $arr = pg_fetch_array ($result, $i++);
 if ($c == "#FFFFFF") $c="#DDDDDD"; else $c="#FFFFFF";
 echo "<tr><td bgcolor=$c>" . $arr["file"] . "<td bgcolor=$c>" . $arr["id"] . "<td bgcolor=$c>". $arr["str"];
  }
echo "</table>";
 } 
} 
?>
