#!/usr/bin/perl -w

# $Id$
######################################################################
# This script converts a finished traditional Chinese translation with
# Taiwan flavored words into Hong Kong flavored words. No AI here, that
# is a university research topic and much more difficult than corresponding
# English one.
#
# To use this script, one should use it as a filter, instead of appending
# file name as argument (since for some reason any chinese in comment
# would be converted into garbage, should be fixed later):
#
#     tw2hk.pl < zh_TW.po > zh_HK.po
#
# In case of multiple choice, you will be prompted for an answer. But
# only existing choices are accepted for now, translators can't type
# their own.
#
######################################################################
# Some special case handling:
#
# 1. In user-defined comment before each msgid, if translator places
# 
#     # zh_HK: msgstr "blah blah blah"
#
# then this translation will be used instead of automatically convered
# one. Useful when automatic conversion doesn't work well, or
# translator doesn't want to be prompted again for that string.
#
# 2. If following special string is added in comment before file header:
#
#     # zh_HK:blah:blah2:
#     (notice the colons! there is no space in between!)
#
# this means 'blah' will be globally converted to 'blah2' throughout the
# whole file. Useful when translator were prompted too many times for
# one specific word. But _really_ make sure such word has only one
# possible meaning before using this!
# And the per-string choice documented above overrides this global choice.
#
######################################################################
# (c) 2005, 06, 07 Abel Cheung <abelcheung [AT] gmail [DOT] com>
#
# Based on Americal -> British english conversion script written by:
# (c) 2000 Abigail Brady
#     2002 Bastien Nocera
#
# Released under the GNU General Public Licence, either version 2
# or at your option, any later version
#
# NO WARRANTY!

require 5.8.0;
use strict;
use utf8;
use encoding 'utf8';
use POSIX qw(strftime);
use Term::ReadLine;

binmode(STDIN, ":utf8");
binmode(STDOUT, ":utf8");
binmode(STDERR, ":utf8");

my $mode = 0;
my $rl = Term::ReadLine->new("String Replacement");

my $msg_id = "";
my $msg_str = "";
my $force_msg_str = "";
my %remembered_choice = ();

#
# Replace word with another
#
sub do_trans {
	my ($tf, $tt) = @_;
	$msg_str =~ s/$tf/$tt/g;
}

#
# Provide multiple choice for words
#
sub query_trans {
	# {{{
	my ($tf, @tt) = @_;
	if ( $msg_str =~ m/($tf)/i ) {

		my $answer;
		my $i = 0;
		my $matched_string = $+;

		# if word conversion is defined in header, just do it, don't ask
		for my $key (keys %remembered_choice) {

			# can't compare with $tf, $tf may contain PCRE constructs
			if ( $matched_string eq $key ) {
				do_trans ( $tf, $remembered_choice{$key} );
				return;
			}
		}

		print STDERR "\n" . ('='x75) . "\n${msg_id}\n${msg_str}\n";

		my $prompt = "0 - Don't modify the string\n";
		while ($i++ < @tt) {
			$prompt .= sprintf("%d - Change '%s' to '%s'\n", $i, $matched_string, $tt[$i-1]);
		}
		print STDERR $prompt;

		do {
			($answer = $rl->readline( "Please specify your choice (integer only): " )) =~ s/^\s*(\d+)\s*$/$1/;
			 
		} until ( ($answer =~ m/^\d+$/) && ($answer <= @tt) );
		if ($answer == 0) {
			print STDERR "Not changed\n";
		} else {
			printf STDERR ("Result: '%s' --> '%s'\n", $matched_string, $tt[$answer-1]);
			do_trans( $tf, $tt[$answer-1] );
		}
	}
	# }}}
}

#
# Only apply when transforming po file header
#
sub translate_header() {
	# {{{
	my $curdate = strftime ("%Y-%m-%d %H:%M+0800", localtime());

	$msg_str =~ s/^("PO-Revision-Date: ).*\\n"/$1$curdate\\n"/m;
	$msg_str =~ s/^("Language-Team: ).*\\n"/$1Chinese (Hong Kong) <community\@linuxhall.org>\\n"/m;
	return;
	# }}}
}

#
# List of words to be transformed
#
sub translate() {
	# {{{

	# order of the words can sometimes be important!

	# place/country names
	do_trans("聖克里斯多福([及和暨與]尼維斯)?", "聖基茨島及尼維斯聯邦");		# Saint Kitts And Nevis
	do_trans("聖文森([及和暨與]格瑞那丁)?", "聖文森特和格林內丁斯");	# Saint Vincent And The Grenadines
	do_trans("波士尼亞[及和暨與]?赫塞哥維[納那]", "波斯尼亞和黑塞哥维那"); # Bosnia and Herzegovina
	do_trans("阿拉伯聯合大公國", "阿拉伯聯合酋長國");	# United Arab Emirates
	do_trans("聖多美[及和與]普林西比", "聖多美和普林西比");		# Sao Tome and Principe
	do_trans("巴布亞紐幾內亞", "巴布亞新畿內亞");	# Papua New Guinea
	do_trans("塞爾維亞[及和與]蒙特內哥羅", "塞爾維亞和黑山");	# Serbia and Montenegro
	do_trans("安地卡[及和暨與]巴布達", "安提瓜和巴布達");	# Antigua and Barbuda
	do_trans("荷屬安地列斯", "荷屬安的列斯羣島");		# Netherland Antilles
	do_trans("千里達托貝哥", "特立尼達和多巴哥");		# Trinidad And Tobago
	do_trans("(多明尼加聯邦|多米尼克)", "多米尼加");		#Dominica
	do_trans("多明尼加(共和國)?", "多明尼加共和國");		# Dominican Republic
	do_trans("沙烏地阿拉伯", "沙地阿拉伯");	# Saudi Arabia
	do_trans("(維爾?京|處女)群島", "維爾京羣島");	# British / US Virgin Islands
	do_trans("玻里尼西亞", "玻利尼西亞");	# Polynesia
	do_trans("哥斯大黎加", "哥斯達黎加");	# Costa Rica
	do_trans("列支敦斯登", "列支敦士登");	# Liechtenstein
	do_trans("斯洛[維凡]尼亞", "斯洛文尼亞");	# Slovenia
	do_trans("索羅門群島", "所羅門羣島");	# Solomon Islands
	do_trans("茅利塔尼亞", "毛里塔尼亞");	# Mauritania
	do_trans("布吉納法索", "布基納法索");	# Burkina Faso
	do_trans("幾內亞比索", "畿內亞比紹");	# Guinea-Bissau
	do_trans("蘇伊士運河", "蘇彝士運河");
	do_trans("克羅埃西亞", "克羅地亞");		# Croatia
	do_trans("蒙特內哥羅", "黑山");			# Montenegro
	do_trans("伊斯坦堡", "伊斯坦布爾");		# Istanbul, Turkey
	do_trans("(埃立特里亞|厄利垂亞)", "厄立特里亞");	# Eritrea
	# 中國也有塔吉克族
	do_trans("塔吉克(?!(斯坦|族))", "塔吉克斯坦");	# Tajikistan
	do_trans("吉里巴斯", "基里巴斯");		# Kiribati
	do_trans("坦尚尼亞", "坦桑尼亞");		# Tanzania
	do_trans("格瑞[納那]達", "格林納達");		# Grenada
	do_trans("亞賽拜然", "亞塞拜彊");		# Azerbaijan
	do_trans("波士尼亞", "波斯尼亞");		# Bosnia
	do_trans("賽普勒斯", "賽浦路斯");		# Cyprus
	do_trans("[依衣]索[比匹]亞", "埃塞俄比亞");	# Ethiopia
	do_trans("瓜地馬拉", "危地馬拉");		# Guatemala
	do_trans("宏都拉斯", "洪都拉斯");		# Honduras
	do_trans("馬爾地夫", "馬爾代夫");		# Maldives
	do_trans("(模里西斯|毛里求斯)", "毛里裘斯");		# Mauritius
	do_trans("莫三比克", "莫桑比克");		# Mozambique
	do_trans("賴比瑞亞", "利比里亞");		# Liberia
	do_trans("聖露西亞", "聖盧西亞");		# Saint Lucia
	do_trans("聖馬利諾", "聖馬力諾");		# San Marino
	do_trans("吉里巴斯", "基里巴斯");		# Kiribati
	do_trans("史瓦濟蘭", "斯威士蘭");		# Swaziland
	do_trans("維吉尼亞", "維珍尼亞");		# Virginia, USA
	do_trans("塞拉耶佛", "薩拉熱窩");		# Sarajevo, Bosnia
	# assume this is not football team
	do_trans("華倫西亞", "瓦倫西亞");		# Valencia, Spain
	# Confederation 有點特別
	do_trans("瑞士邦聯", "瑞士聯邦");		# Swiss Confederation
	do_trans("聖赫倫那", "聖赫勒拿");		# Saint Helena
	do_trans("(莫多瓦|莫爾達瓦)", "摩爾多瓦");	# Moldova
	do_trans("厄瓜多(?!爾)", "厄瓜多爾");	# Ecuador
	do_trans("突尼西亞", "突尼斯");		# Tunisia
	do_trans("萬那杜", "瓦努阿圖");		# Vanuatu
	do_trans("考文垂", "高雲地利");		# Coventry, England
	do_trans("辛巴威", "津巴布韋");		# Zimbabwe
	do_trans("巴貝多", "巴巴多斯");		# Barbados
	do_trans("波札那", "博茨瓦納");		# Botswana
	do_trans("窩瓦河", "伏爾加河");		# Volga River
	do_trans("喬治亞(?!州)", "格魯吉亞");		# Georgia
	# Montreal => 滿地可 是較早年的叫法
	do_trans("(蒙特婁|滿地可)", "蒙特利爾");		# Montreal, Canada
	# 臺灣的翻譯應該不可能會提及香港的獅子山吧？ -- Abel
	do_trans("獅子山", "塞拉利昂");		# Sierra Leone
	do_trans("義大利", "意大利");		# Italy
	do_trans("吐瓦魯", "圖瓦盧");		# Tuvalu
	do_trans("甘比亞", "岡比亞");		# Gambia
	do_trans("盧安達", "盧旺達");		# Rwanda
	do_trans("浦隆地", "布隆迪");		# Burundi
	do_trans("維德角", "佛得角");		# Cape Verde
	do_trans("吉布地", "吉布提");		# Djibouti
	do_trans("賴索托", "萊索托");		# Lesotho
	do_trans("紐西蘭", "新西蘭");		# New Zealand
	do_trans("塞席爾", "塞舌爾");		# Seychelles
	do_trans("蘇利南", "蘇里南");		# Suriname
	do_trans("尚比亞", "贊比亞");		# Zambia
	do_trans("貝里斯", "伯利兹");		# Belize
	do_trans("幾內亞", "畿內亞");		# .* Guinea .*
	do_trans("馬拉威", "馬拉維");		# Malawi
	do_trans("好萊塢", "荷里活");		# Hollywood
	do_trans("普吉島", "布吉島");		# Phuket
	do_trans("喀拉蚩", "卡拉奇");		# Karachi
	do_trans("荷巴特", "霍巴特");		# Hobart
	do_trans("舊金山", "三藩市");		# San Francisco, USA
	do_trans("伯明罕", "伯明翰");		# Bermingham, UK
	do_trans("休斯頓", "休斯敦");		# Houston, US
	do_trans("[塔坦]米爾", "泰米爾");		# Tamil
	do_trans("卡達", "卡塔爾");			# Qatar
	do_trans("蓋亞[納那]", "圭亞那");		# Guyana
	do_trans("葛摩", "科摩羅");			# Comoros
	do_trans("教廷", "梵蒂岡");			# Holy See
	do_trans("查德", "乍得");			# Chad
	do_trans("漢城", "首爾");			# Seoul, Korea
	do_trans("葉門", "也門");			# Yemen
	do_trans("貝南", "貝寧");			# Benin
	do_trans("加彭", "加蓬");			# Gabon
	do_trans("迦納", "加納");			# Ghana
	do_trans("肯尼?亞", "肯雅");			# Kenya
	do_trans("諾魯", "瑙魯");			# Nauru
	do_trans("東加", "湯加");			# Tonga
	do_trans("曼島", "萌島");			# Isle of Man
	do_trans("帛琉", "帕勞");			# Palau
	# 老撾在香港也是上一輩的稱呼
	do_trans("老撾", "寮國");			# Laos
	# Hopefully fruit won't appear in translation
	do_trans("雪梨", "悉尼");			# Sydney, Australia

	# Nigeria and Niger
	do_trans("尼日(?![利爾])", "尼日爾");	# Niger
	do_trans("奈及利亞", "尼日利亞");		# Nigeria

	do_trans("索馬利亞", "索馬里");		# Somalia
	# seems usage of Maryland is unified
	do_trans("馬利蘭", "馬里蘭");		# Maryland, USA
	do_trans("馬利(?!蘭)", "馬里");			# Mali

	# language names
	#do_trans("布列塔尼", "不列塔尼");		# Breton
	#do_trans("加泰隆", "加泰羅");			# Catalan
	#do_trans("弗里西亞", "弗里斯蘭");
	#do_trans("印地語", "印度語");			# Hindi

	# special name, movie
	# might be seen in screensavers
	do_trans("駭客任務", "廿二世紀殺人網絡");		# Matrix
	do_trans("魔鬼終結者", "未來戰士");		# Terminator

	# Certificate and PKI related
	do_trans("憑證廢止清冊", "證書撤銷清單");	# Certificate Revocation List
	do_trans("憑證管理中心", "核證機關");		# Certificate Authority
	do_trans("密鑰對", "配對密碼匙");			# Key pair
	do_trans("私鑰", "私人密碼匙");				# Private key
	do_trans("公鑰", "公開密碼匙");				# Public key
	do_trans("憑證", "證書");					# Certificate
	do_trans("簽章", "簽署");					# Signature / Sign
	do_trans("簽名(?!檔)", "簽署");				# Signature
	do_trans("隱私", "私隱");					# Privacy
	# put to bottom, as a catch-all conversion
	do_trans("(金鑰|密鑰|鑰匙)", "密碼匙");		# key

	# generic terms from mediawiki
	do_trans("(?<=[軟硬])體", "件");
	do_trans("(?<=[二三])極體", "極管");
	do_trans("人工智慧", "人工智能");
	do_trans("機器人", "機械人");
	do_trans("太空梭", "穿梭機");
	do_trans("行動裝置", "流動通訊器材");		# mobile device
	do_trans("行動(?=(電話|通訊))", "流動");	# mobile phone
	do_trans("(速食麵|泡麵)", "即食麵");
	do_trans("土豆", "花生");
	do_trans("計程車", "的士");
	do_trans("公車", "巴士");
	do_trans("[台撞]球" ,"桌球");
	do_trans("桌球" ,"乒乓球");
	do_trans("冰淇淋" ,"雪糕");
	do_trans("衛生" ,"衞生");
	#do_trans("老人" ,"長者");
	do_trans("(簡訊|短信)", "短訊");
	do_trans("相容", "兼容");
	do_trans("海洛因", "海洛英");
	do_trans("國際足球總會", "國際足球協會");		# FIFA
	do_trans("單眼相機", "單鏡反光機");
	do_trans("金氏世界紀錄", "健力士世界紀錄");
	do_trans("芮氏", "黎克特制");
	do_trans("震中", "震央");
	do_trans("哆啦A夢", "多啦A夢");
	do_trans("奈米", "納米");
	do_trans("智慧財產權", "知識產權");
	do_trans("草莓", "士多啤梨");
	do_trans("冰棒", "雪條");
	do_trans("便當", "飯盒");
	do_trans("人行道", "行人路");
	do_trans("行人穿越道", "行人過路綫");
	do_trans("車站大廳", "車站大堂");
	do_trans("記事本", "記事簿");
	do_trans("扶手電梯", "電扶梯");
	do_trans("雞翅", "雞翼");
	do_trans("係數", "系數");
	do_trans("吉他", "結他");
	do_trans("板球", "木球");			# cricket
	do_trans("(芯片|晶元)", "晶片");
	do_trans("彈床", "蹦床");
	do_trans("斯諾克(桌球)?", "士碌架");	# Snooker
	do_trans("電漿" ,"等離子");			# plasma
	do_trans("古柯鹼", "可卡因");		# cocaine

	# some terms common in China mainland only
	do_trans("字節", "位元組");
	do_trans("調制解調器", "數據機");
	do_trans("(?<=[光軟硬磁])盤", "碟");
	do_trans("(?<=[光軟])驅", "碟機");
	do_trans("服務器", "伺服器");
	do_trans("上載", "上傳");
	do_trans("注冊", "註冊");
	do_trans("單擊", "點選");
	do_trans("雙擊", "連按兩下");

	# other generic terms
	do_trans("自由軟體基金會", " Free Software Foundation ");
	do_trans("(筆記型|攜帶型|膝上型)電腦", "手提電腦");
	do_trans("度量衡單位", "量度單位");
	do_trans("個人資訊", "個人資料");
	do_trans("積體電路", "集成電路");
	do_trans("西洋棋", "國際象棋");
	do_trans("中國跳棋", "波子棋");
	do_trans("黑白棋", "蘋果棋");
	do_trans("滑鼠指標", "鼠標");
	do_trans("布林", "邏輯");		# boolean ???
	do_trans("命令殼", "命令解譯器");
	do_trans("內插法", "插值法");
	query_trans("計算機", "計數機", "電腦");
	do_trans("解析度", "解像度");
	do_trans("笨蛋節", "愚人節");
	do_trans("撥接", "撥號");
	do_trans("正體", "繁體");
	do_trans("團隊", "隊伍");
	do_trans("名片", "卡片");
	do_trans("內建", "內置");
	do_trans("連繫", "聯繫");
	do_trans("連絡", "聯絡");
	do_trans("建構", "建立");
	#do_trans("算式", "數式");
	do_trans("變更", "更改");
	do_trans("公分", "厘米");
	do_trans("麻將", "麻雀");		# mahjong
	do_trans("字元", "字符");
	do_trans("頭銜", "銜頭");
	do_trans("寬度", "闊度");
	do_trans("視訊", "視像");
	do_trans("臭[蟲虫]", "錯誤");		# report bug
	do_trans("嘗?試著", "嘗試");
	do_trans("給定的", "指定的");
	do_trans("呼叫器", "傳呼機");		# pager
	do_trans("漸層", "漸變色");		# gradient
	do_trans("回報(?=(問題|錯誤|給))", "匯報");		# report bug
	do_trans("回報(?=(這個|該)(問題|錯誤))", "匯報");		# report bug
	do_trans("釋出", "推出");		# release
	do_trans("住家(?=(傳真|電話))", "家用");		# home ???
	do_trans("住家地址", "住址");
	do_trans("(?<=[日月])食", "蝕");
	do_trans("(?<=[日月])全食", "全蝕");
	do_trans("(?<=[日月])偏食", "偏蝕");
	do_trans("(?<=[日月])環食", "環蝕");
	do_trans("溼度", "濕度");		# humidity
	do_trans("潮溼", "潮濕");
	do_trans("自訂(?!閱)", "自選");
	do_trans("公尺", "米");
	do_trans("依照", "根據");		# Ambrose:「依照」不是香港口語，台灣也會用
	do_trans("依(?![從然靠])", "根據");
	do_trans("[暱昵]稱", "網名");			# 這兒反而是口語化顯得生動活潑

	# online ???
	do_trans("線上(?=(銀行|搜尋|字典|交易|遊戲|文件|說明|服務))", "網上");
	query_trans("(?<![直弧連底])線上", "網上");

	# bicycle and motorcycle
	do_trans("摩托車", "電單車");
	do_trans("腳踏車", "單車");

	# "function", but don't change "library"
	do_trans("函式(?!庫)", "函數");

	do_trans("印表機", "打印機");
	# 「列印」後面有括號，多數是選單的項目，表示「打印」
	do_trans("列印(?=\\()", "打印");
	do_trans("(?<=(預覽|取消|正在))列印", "打印");
	do_trans("列印(?=(預覽|文件|工作|數量|範圍|設定))", "打印");
	# 有時「列印」表示在螢幕顯示字句，這時用「顯示」較好
	query_trans("列印", "打印", "顯示");

	# digital, but don't change "decimal point"
	# 數位長輩、數位朋友等等會出問題
	do_trans("數位(?=(相機|相片|相簿|簽名|簽署|簽章|化))", "數碼");
	query_trans("(?<!小)數位", "數碼");

	# network and internet
	do_trans("網際網路", "互聯網");
	do_trans("網路", "網絡");

	# email vs postal address
	# mostly disabled for now, as per Walter Cheuk's opinion
	#do_trans("電子郵件", "電郵");
	#query_trans("郵件", "電郵");
	query_trans("(?<!電子)郵件[位地]址", "電子郵件地址", "郵寄地址");

	# card game
	do_trans("紙牌", "啤牌");
	do_trans("黑桃", "葵扇");
	do_trans("葫蘆", "俘虜");		# full house
	# FIXME: affect too much translation
	# perhaps search the context and determine if it's about game?
	#query_trans("(?<!(對話|核取))方塊", "階磚");

	# Hong Kong tend to use 圖像, but 影像 means lots of different
	# things (image, video, graphics, ...) so ask instead of forcefully
	# convert
	query_trans("(?<!光碟)影像", "圖像", "圖片", "圖案");

	# some characters are used solely in Hong Kong but infrequently in Taiwan
	do_trans("您", "你");
	do_trans("裡", "裏");

	# some character are forced on Hong Kong because they are not
	# available in Big5 encoding
	do_trans("群", "羣");
	# this is problematic, probably doesn't need changing because
	# they are the same character?
	#do_trans("線", "綫");

	# http://www.linuxfans.org/nuke/modules.php?name=Forums&file=viewtopic&t=25997
	# 著作 著者 著名 著述 著書 所著 名著 土著 顯著 編著 著錄 原著
	do_trans("(?<![所名土顯編原])著(?![作者名述書錄])", "着");

	# chemical element table
	do_trans("鎝", "鍀");		# technetium, 43
	do_trans("鎦", "鑥");		# lutetium, 71
	do_trans("砈", "砹");		# astatine, 85
	do_trans("鍅", "鈁");		# francium, 87
	do_trans("錼", "鎿");		# neptunium, 93
	do_trans("鈽", "鈈");		# plutonium, 94
	do_trans("鋂", "鎇");		# americium, 95
	do_trans("鉳", "錇");		# berkelium, 97
	do_trans("鉲", "鐦");		# californium, 98
	do_trans("鑀", "鎄");		# einsteinium, 99

	# }}}
}

#
# Main conversion routine
#
while (<>) {
	# {{{

	if  (/^#/) {

		# using such comment means zh_HK uses specialized translation,
		# and shouldn't convert from zh_TW
		if (m/^#\s*zh_HK:\s*(.*)/i) {

			if ($mode == 0) {
				# placing this on po file header means this is a global choice
				if (m/^#\s*zh_HK:([^:]*):([^:]*):/i) {
					$remembered_choice{$1} = $2;
				}
			} else {
				# this is a per-string choice
				$force_msg_str .= $1;
			}
		}

		# header
		if ($mode == 0) {
			s/traditional\s+chinese/Chinese \(Hong Kong\)/i;
			s/chinese\s+\(?(traditional|taiwan)\)?/Chinese \(Hong Kong\)/i;
		}

		print;

	} elsif (/^msgctxt/) {
		print;
	} elsif (/^msgid/) {
		$msg_id .= $_;
		$mode = 1;
	} elsif (/^msgstr/) {
		$msg_str .= $_;
		$mode = 2;
	} elsif (/^"/) {
		if ($mode == 1) {
			$msg_id .= $_;
		} elsif ($mode == 2) {
			$msg_str .= $_;
			# make sure substitution won't fail because of something like
			# 硬"\n"體
			$msg_str =~ s/\"\n\"// unless ($msg_id =~ /^msgid ""\n$/);
		}
	} else {
		if ($msg_id || $msg_str) {
			if ($msg_id =~ /^msgid ""\n$/) {
				translate_header();
			} else {
				if ($force_msg_str) {
					$msg_str = $force_msg_str . "\n";
				} else {
					translate();
				}
				if ($msg_str =~ /\\n[^"]/) {
					# 2 passes, to account for situation like \n\n
					$msg_str =~ s/\\n([^"])/\\n"\n"$1/g;
					$msg_str =~ s/\\n([^"])/\\n"\n"$1/g;
					$msg_str =~ s/(msgstr(\[\d+\])? )/$1""\n/g;
				}
			}
			print "$msg_id";
			print "$msg_str";
			$msg_id = "";
			$msg_str = "";
			$force_msg_str = "";
		}
		print $_;
	}
}

# Last message may or may not followed by new line
if ($msg_id || $msg_str) {
	if ($force_msg_str) {
		$msg_str = $force_msg_str . "\n";
	} else {
		translate();
	}
	if ($msg_str =~ /\\n[^"]/) {
		# 2 passes, to account for situation like \n\n
		$msg_str =~ s/\\n([^"])/\\n"\n"$1/g;
		$msg_str =~ s/\\n([^"])/\\n"\n"$1/g;
		$msg_str =~ s/(msgstr(\[\d+\])? )/$1""\n/g;
	}
	print "$msg_id";
	print "$msg_str";
	$msg_id = "";
	$msg_str = "";
	$force_msg_str = "";

	# }}}
}

# ex: softtabstop=4: tabstop=4: shiftwidth=4: noexpandtab: foldmethod=marker
# -*- mode: perl; tab-width: 4; indent-tabs-mode: t; coding: utf-8 -*-
