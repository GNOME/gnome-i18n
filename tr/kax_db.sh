#!/bin/sh
#############################################################################
# kax_db.sh - wanted ever know what a person without a docbook implementation
#  does in GNOME ? -- Here you can see it ..
#############################################################################
# By Fatih Demir <kabalak@gmx.net> , R 0.0.0.1.1
#############################################################################
export BAS="<html><head><title>$TITLE</title></head><body bgcolor=\"grey\" text=\"navy\">"
export METIN='<p align="center">Burada benim �ok sevdidim ve GNOME terc�melerimde
kulland���m baz� standard-d��� kelimelerin �izgiyesi ( tablosu ) var .
Yine de sorular�n varsa , o zaman bana bir ePosta <a href="mailto:kabalak@gmx.net" title="ePosta">g�nder</a> .</p>'
export SON="</body>
</html>"
export DOSYA="kelimeler"
export SONUC_DOSYA="index.html"
#############################################################################
yap  ()  {
export TITLE="Fatih Demir'in GNOME terc�melerinde kulland��� acaip kelimeler"
echo $BAS > $SONUC_DOSYA
echo $METIN >> $SONUC_DOSYA
echo "<br><br>" >> $SONUC_DOSYA
echo '<table border="0" align="center" width="82 %" bgcolor="red" text="white">' >> $SONUC_DOSYA
echo "<tr><td>Ingilizce orjinal kelimesi</td><td>Benim kulland�d�m T�rk�e kelime</td></tr>" >> $SONUC_DOSYA
for i in `cat $DOSYA`
	do
	echo "<tr>" >> $SONUC_DOSYA
	bir="`echo $i|sed -e 's/;;.*$//g' -e 's/_/ /g'`"
	iki="`echo $i|sed -e 's/^.*;;//g' -e 's/_/ /g'`"
	echo "<td>$bir</td><td>$iki</td> " >> $SONUC_DOSYA
	echo "</tr>" >> $SONUC_DOSYA
	done
echo "</table>" >> $SONUC_DOSYA
echo "<br><br>" >> $SONUC_DOSYA
echo "<p align=\"center\">Son yarat�l�� : `date +%Y-%m-%d\ %H:%M`</p>" >> $SONUC_DOSYA
echo "<br><br>" >> $SONUC_DOSYA
echo $SON >> $SONUC_DOSYA
echo "Bitti ." && exit 0
}
##############################################################################
[ -f $DOSYA ] && { 
	rm -f $SONUC_DOSYA && yap
}
echo "\"$DOSYA\" bulunamad� ! " && exit 1
##############################################################################
#define I_LOVE_VIM 1
##############################################################################
