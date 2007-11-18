#!/usr/bin/perl -w

# $Id$
######################################################################
# This script converts a finished traditional Chinese translation with
# Hong Kong flavored words into Taiwan flavored words. No AI here, that
# is a university research topic and much more difficult than corresponding
# English one.
#
# To use this script, one should use it as a filter, instead of appending
# file name as argument (since for some reason any chinese in comment
# would be converted into garbage):
#
#     hk2tw.pl < zh_HK.po > zh_TW.po
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
#     # zh_TW: msgstr "blah blah blah"
#
# then this translation will be used instead of automatically convered
# one. Useful when automatic conversion doesn't work well, or
# translator doesn't want to be prompted again for that string.
#
# 2. If following special string is added in comment before file header:
#
#     # zh_TW:blah:blah2:
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
	$msg_str =~ s/^("Language-Team: ).*\\n"/$1Chinese (Taiwan) <community\@linuxhall.org>\\n"/m;
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
	do_trans("聖基茨島[及和與]尼維斯聯邦", "聖克里斯多福及尼維斯");		# Saint Kitts And Nevis
	do_trans("聖文森特[及和與]格林內丁斯", "聖文森");	# Saint Vincent And The Grenadines
	do_trans("波斯尼亞[及和與]黑塞哥维那", "波士尼亞赫塞哥維納"); # Bosnia and Herzegovina
	do_trans("聖多美[及和與]普林西比", "聖多美普林西比");		# Sao Tome and Principe
	do_trans("阿拉伯聯合酋長國", "阿拉伯聯合大公國");	# United Arab Emirates
	do_trans("巴布亞新畿內亞", "巴布亞紐幾內亞");	# Papua New Guinea
	do_trans("塞爾維亞和黑山", "塞爾維亞與蒙特內哥羅");	# Serbia and Montenegro
	do_trans("安提瓜[及和與]巴布達", "安地卡及巴布達");	# Antigua and Barbuda
	do_trans("荷屬安的列斯(羣島)?", "荷屬安地列斯");		# Netherland Antilles
	do_trans("特立尼達[及和與]多巴哥", "千里達托貝哥");	# Trinidad And Tobago
	do_trans("(多明尼加聯邦|多米尼加)", "多米尼克");		#Dominica
	do_trans("多明尼加(共和國)?", "多明尼加共和國");		# Dominican Republic
	do_trans("沙[地特]阿拉伯", "沙烏地阿拉伯");	# Saudi Arabia
	do_trans("(處女|維爾?京)[羣群]島", "維京群島");		# British / US Virgin Islands
	do_trans("哥斯達黎加", "哥斯大黎加");	# Costa Rica
	do_trans("列支敦士登", "列支敦斯登");	# Liechtenstein
	do_trans("斯洛文尼亞", "斯洛維尼亞");	# Slovenia
	do_trans("所羅門[羣群]島", "索羅門群島");	# Solomon Islands
	do_trans("毛里塔尼亞", "茅利塔尼亞");	# Mauritania
	do_trans("布基納法索", "布吉納法索");	# Burkina Faso
	do_trans("畿內亞比紹", "幾內亞比索");	# Guinea-Bissau
	do_trans("蘇彝士運河", "蘇伊士運河");
	do_trans("玻利尼西亞", "玻里尼西亞");	# Polynesia
	do_trans("厄立特里亞", "厄利垂亞");		# Eritrea
	do_trans("埃塞俄比亞", "衣索匹亞");		# Ethiopia
	do_trans("伊斯坦布爾", "伊斯坦堡");		# Istanbul, Turkey
	do_trans("克羅地亞", "克羅埃西亞");		# Croatia
	# 中國也有塔吉克族
	do_trans("塔吉克斯坦", "塔吉克");		# Tajikistan
	do_trans("基里巴斯", "吉里巴斯");		# Kiribati
	do_trans("坦桑尼亞", "坦尚尼亞");		# Tanzania
	do_trans("格林納達", "格瑞那達");		# Grenada
	do_trans("亞塞拜彊", "亞賽拜然");		# Azerbaijan
	do_trans("波斯尼亞", "波士尼亞");		# Bosnia
	do_trans("賽浦路斯", "賽普勒斯");		# Cyprus
	do_trans("危地馬拉", "瓜地馬拉");		# Guatemala
	do_trans("洪都拉斯", "宏都拉斯");		# Honduras
	do_trans("馬爾代夫", "馬爾地夫");		# Maldives
	do_trans("(毛里[裘求]斯)", "模里西斯");		# Mauritius
	do_trans("莫桑比克", "莫三比克");		# Mozambique
	do_trans("利比里亞", "賴比瑞亞");		# Liberia
	do_trans("聖盧西亞", "聖露西亞");		# Saint Lucia
	do_trans("聖馬力諾", "聖馬利諾");		# San Marino
	do_trans("基里巴斯", "吉里巴斯");		# Kiribati
	do_trans("斯威士蘭", "史瓦濟蘭");		# Swaziland
	do_trans("科特迪瓦", "象牙海岸");		# Cote d'Ivoire
	do_trans("維珍尼亞", "維吉尼亞");		# Virginia, USA
	do_trans("薩拉熱窩", "塞拉耶佛");		# Sarajevo, Bosnia
	# assume this is not football team
	do_trans("華倫西亞", "瓦倫西亞");		# Valencia, Spain
	# Confederation 有點特別
	do_trans("瑞士聯邦", "瑞士邦聯");		# Swiss Confederation
	do_trans("聖赫勒拿", "聖赫倫那");		# Saint Helena
	do_trans("伏爾加河", "窩瓦河");		# Volga River
#	Moldova need not change
	do_trans("厄瓜多爾", "厄瓜多");		# Ecuador
	do_trans("津巴布韋", "辛巴威");		# Zimbabwe
	do_trans("巴巴多斯", "巴貝多");		# Barbados
	do_trans("博茨瓦納", "波札那");		# Botswana
	do_trans("格魯吉亞", "喬治亞");		# Georgia
	# Montreal => 滿地可 是較早年的叫法
	do_trans("(蒙特利爾|滿地可)", "蒙特婁");		# Montreal, Canada
	do_trans("塞拉利昂", "獅子山");		# Sierra Leone
	do_trans("高雲地利", "考文垂");		# Coventry, England
	do_trans("瓦努阿圖", "萬那杜");		# Vanuatu
	do_trans("突尼斯", "突尼西亞");		# Tunisia
	do_trans("意大利", "義大利");		# Italy
	do_trans("圖瓦盧", "吐瓦魯");		# Tuvalu
	do_trans("岡比亞", "甘比亞");		# Gambia
	do_trans("盧旺達", "盧安達");		# Rwanda
	do_trans("布隆迪", "浦隆地");		# Burundi
	do_trans("佛得角", "維德角");		# Cape Verde
	do_trans("吉布提", "吉布地");		# Djibouti
	do_trans("萊索托", "賴索托");		# Lesotho
	do_trans("新西蘭", "紐西蘭");		# New Zealand
	do_trans("塞舌爾", "塞席爾");		# Seychelles
	do_trans("蘇里南", "蘇利南");		# Suriname
	do_trans("贊比亞", "尚比亞");		# Zambia
	do_trans("伯利兹", "貝里斯");		# Belize
	do_trans("畿內亞", "幾內亞");		# .* Guinea .*
	do_trans("馬拉維", "馬拉威");		# Malawi
	# translation of "French Guyana" are the same in 2 places
	do_trans("(?<!法屬)圭亞那", "蓋亞那");	# Guyana
	do_trans("荷里活", "好萊塢");		# Hollywood
	do_trans("布吉島", "普吉島");		# Phuket
	do_trans("卡拉奇", "喀拉蚩");		# Karachi
	do_trans("霍巴特", "荷巴特");		# Hobart
	do_trans("三藩市", "舊金山");		# San Francisco, USA
	do_trans("伯明罕", "伯明翰");		# Bermingham, UK
	do_trans("休斯敦", "休斯頓");		# Houston, US
	query_trans("泰米爾", "坦米爾", "塔米爾");		# Tamil
	do_trans("卡塔爾", "卡達");			# Qatar
	do_trans("科摩羅", "葛摩");			# Comoros
	do_trans("梵蒂岡", "教廷");			# Holy See
	do_trans("黑山(?!縣)", "蒙特內哥羅");		# Montenegro
	do_trans("乍得", "查德");			# Chad
	do_trans("漢城", "首爾");			# Seoul, South Korea
	do_trans("老撾", "寮國");			# Laos
	do_trans("也門", "葉門");			# Yemen
	do_trans("貝寧", "貝南");			# Benin
	do_trans("加蓬", "加彭");			# Gabon
	do_trans("加納", "迦納");			# Ghana
	do_trans("肯雅", "肯亞");			# Kenya
	do_trans("瑙魯", "諾魯");			# Nauru
	do_trans("湯加", "東加");			# Tonga
	do_trans("萌島", "曼島");			# Isle of Man
	do_trans("悉尼", "雪梨");			# Sydney, Australia
	do_trans("帕勞", "帛琉");			# Palau
	do_trans("波黑", "波赫");			# Bosnia and Herzegovina 簡稱
	# Taiwan officials doesn't like simplified word of 臺
	do_trans("台灣", "臺灣");			# Taiwan

	# Nigeria and Niger
	do_trans("尼日利亞", "奈及利亞");	# Nigeria
	do_trans("尼日爾", "尼日");			# Niger

	do_trans("索馬里", "索馬利亞");		# Somalia
	# seems usage of Maryland is unified
	do_trans("馬利蘭", "馬里蘭");		# Maryland, USA
	# avoid Maryland and Mariana Islands
	do_trans("馬里(?!(蘭|亞納))", "馬利");

	# language names
#	do_trans("布里多尼", "布列塔尼");		# Breton
	do_trans("加泰羅", "加泰隆");	# Catalan
#	do_trans("弗里西亞", "弗里斯蘭");
#	do_trans("印地語", "印度語");			# Hindi

	# special name, movie
	# might be seen in screensavers
	do_trans("廿二世紀殺人網絡", "駭客任務");		# Matrix
	do_trans("未來戰士", "魔鬼終結者");		# Terminator

	# ssl certificate, CA, PKI related
	do_trans("證書撤銷清單", "憑證廢止清冊");	# Certificate Revocation List
	do_trans("核證機關", "憑證管理中心");		# Certificate Authority
	do_trans("配對密碼匙", "密鑰對");			# Key pair
	do_trans("私人密碼匙", "私鑰");				# Private key
	do_trans("公開密碼匙", "公鑰");				# Public key
	do_trans("密碼匙圈", "鑰匙圈");				# Key ring
	do_trans("證書", "憑證");					# Certificate
	# 名詞或動詞情況不同
	query_trans("簽署", "簽章", "簽名");		# Signature / Sign
	do_trans("私隱", "隱私");					# Privacy
	# put to bottom, as a catch-all conversion
	do_trans("密碼匙", "金鑰");					# Key

	# generic terms from mediawiki
	do_trans("(?<=[軟硬])件", "體");
	do_trans("(?<=[二三])極管", "極體");
	do_trans("人工智能", "人工智慧");
	do_trans("機械人", "機器人");
	do_trans("穿梭機", "太空梭");
	do_trans("流動通訊器材", "行動裝置");		# mobile device
	do_trans("流動(?=(電話|通訊))", "行動");	# mobile phone / communication
	do_trans("即食麵", "速食麵");
	do_trans("花生", "土豆");
	do_trans("的士", "計程車");
	do_trans("(?<!空中)巴士", "公車");
	do_trans("桌球" ,"撞球");
	do_trans("雪糕" ,"冰淇淋");
	do_trans("衞生" ,"衛生");
	#do_trans("長者" ,"老人");
	do_trans("短[信訊]", "簡訊");
	do_trans("兼容", "相容");
	do_trans("海洛英", "海洛因");
	do_trans("國際足球協會", "國際足球總會");		# FIFA
	do_trans("單鏡反光機", "單眼相機");
	do_trans("健力士世界紀錄", "金氏世界紀錄");
	do_trans("黎克特制", "芮氏");
	do_trans("震央", "震中");
	do_trans("多啦A夢", "哆啦A夢");
	do_trans("納米(?!比亞)", "奈米");		# avoid Namibia
	do_trans("知識產權", "智慧財產權");
	do_trans("士多啤梨", "草莓");
	do_trans("雪條", "冰棒");
	do_trans("飯盒", "便當");
	do_trans("行人路", "人行道");
	do_trans("行人過路綫", "行人穿越道");
	do_trans("車站大堂", "車站大廳");
	do_trans("記事簿", "記事本");
	do_trans("電扶梯", "扶手電梯");
	do_trans("雞翼", "雞翅");
	do_trans("結他", "吉他");
	do_trans("系數", "係數");
	do_trans("木球", "板球");			# cricket
	do_trans("[芯晶]片", "晶元");
	do_trans("蹦床", "彈床");
	do_trans("士碌架", "斯諾克");		# Snooker
	do_trans("等離子" ,"電漿");			# plasma
	do_trans("可卡因", "古柯鹼");		# cocaine

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
	do_trans("\\s?Free Software Foundation\\s?", "自由軟體基金會");
	do_trans("量度單位", "度量衡單位");
	do_trans("手提電腦", "筆記型電腦");
	do_trans("個人資料(?!夾)", "個人資訊");
	do_trans("集成電路", "積體電路");
	do_trans("國際象棋", "西洋棋");
	do_trans("波子棋", "中國跳棋");
	do_trans("蘋果棋", "黑白棋");
	do_trans("邏輯(?=(值|代數))", "布林");
#	do_trans("命令殼", "命令解譯器");
	do_trans("插值法", "內插法");
	do_trans("計數機", "計算機");
	do_trans("解像度", "解析度");
	do_trans("愚人節", "笨蛋節");
	do_trans("鼠標", "滑鼠指標");
	do_trans("撥號", "撥接");
	do_trans("繁體", "正體");
	do_trans("隊伍", "團隊");
	do_trans("[卡咭]片", "名片");
	do_trans("內置", "內建");
	do_trans("聯繫", "連繫");
	do_trans("聯絡", "連絡");
#	do_trans("建立", "建構");
	do_trans("數式", "算式");
	do_trans("更改", "變更");
	do_trans("厘米", "公分");
	do_trans("麻雀", "麻將");		# mahjong
	# avoid 香港增補字符集
	do_trans("(?<!香港增補)字符", "字元");
	do_trans("銜頭", "頭銜");
	do_trans("闊度", "寬度");
	do_trans("視像", "視訊");
	do_trans("網上", "線上");		# online
	do_trans("激光", "雷射");		# laser, 在香港兩個都接受
	do_trans("傳呼機", "呼叫器");	# pager
	do_trans("漸變色", "漸層");		# gradient
	do_trans("嘗試[着著]?", "試著");
	do_trans("指定的", "給定的");
	do_trans("[滙匯]報", "回報");		# report bug
	do_trans("推出", "釋出");		# release
	do_trans("家用(?=(傳真|電話))", "住家");		# home ???
	do_trans("住址", "住家地址");
	do_trans("(?<=[日月])蝕", "食");
	do_trans("(?<=[日月])全蝕", "全食");
	do_trans("(?<=[日月])偏蝕", "偏食");
	do_trans("(?<=[日月])環蝕", "環食");
	do_trans("濕度", "溼度");		# humidity
	do_trans("潮濕", "潮溼");
	do_trans("自選(?![取擇])", "自訂");
	do_trans("根據(?!地)", "依");			# 台灣較常用「依...」或「依照」代表 according to
#	do_trans("米", "公尺");			# 這個有點困難

	# bicycle and motorcycle
	do_trans("電單車", "摩托車");
	do_trans("單車", "腳踏車");

	# function
	do_trans("函數", "函式");

	# printer, print
	do_trans("打印機", "印表機");
	# 有時「列印」表示在螢幕顯示字句，這時用「顯示」較好
	query_trans("打印", "列印", "顯示");

	# digital
	do_trans("數碼", "數位");

	# network and internet
	do_trans("互聯網", "網際網路");
	do_trans("網絡", "網路");

	query_trans("(?<!電子)郵件[位地]址", "電子郵件位址", "郵寄地址");

	# card game
	do_trans("啤牌", "紙牌");
	do_trans("葵扇", "黑桃");
	do_trans("階磚", "方塊");
	do_trans("俘虜", "葫蘆");		# full house

	# Hong Kong tend to use 圖像, but 影像 is more used in
	# Taiwan, though 影像 can mean lots of different things (image, video,
	# graphics, ...), so ask instead of forcefully convert
	query_trans("圖像", "影像", "圖片", "圖案");

	# 您 is more polite in Taiwan
	do_trans("你", "您");

	# some character specific to Hong Kong as they are not
	# available or rarely used in Big5 encoding
	do_trans("羣", "群");
	do_trans("綫", "線");
	do_trans("裏", "裡");
	do_trans("着", "著");

	# chemical element table
	do_trans("鍀", "鎝");		# technetium, 43
	do_trans("鑥", "鎦");		# lutetium, 71
	do_trans("砹", "砈");		# astatine, 85
	do_trans("鈁", "鍅");		# francium, 87
	do_trans("鎿", "錼");		# neptunium, 93
	do_trans("鈈", "鈽");		# plutonium, 94
	do_trans("鎇", "鋂");		# americium, 95
	do_trans("錇", "鉳");		# berkelium, 97
	do_trans("鐦", "鉲");		# californium, 98
	do_trans("鎄", "鑀");		# einsteinium, 99

	# }}}
}

#
# Main conversion routine
#
while (<>) {
	# {{{

	if  (/^#/) {

		# using such comment means zh_TW uses specialized translation,
		# and shouldn't convert from zh_HK
		if (m/^#\s*zh_TW:\s*(.*)/i) {

			if ($mode == 0) {
				# placing this on po file header means this is a global choice
				if (m/^#\s*zh_TW:([^:]*):([^:]*):/i) {
					$remembered_choice{$1} = $2;
				}
			} else {
				# this is a per-string choice
				$force_msg_str .= $1;
			}
		}

		# header
		if ($mode == 0) {
			s/traditional\s+chinese/Chinese \(Taiwan\)/i;
			s/chinese\s+\(?(traditional|hong kong)\)?/Chinese \(Taiwan\)/i;
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
