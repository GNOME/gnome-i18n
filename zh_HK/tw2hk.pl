#!/usr/bin/perl -w

######################################################################
# This script converts a finished traditional Chinese translation with
# Taiwan flavored words into Hong Kong flavored words. No AI here, that
# is a university research topic and much more difficult than corresponding
# English one.
#
# To use this script, one should use it as a filter, instead of appending
# file name as argument (since for some reason any chinese in comment
# would be converted into garbage):
#
# tw2hk.pl < zh_TW.po > zh_HK.po

######################################################################
# (c) 2005, 2006 Abel Cheung <abelcheung [AT] gmail [DOT] com>
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

sub do_trans {
	my ($tf, $tt) = @_;
	$msg_str =~ s/$tf/$tt/g;
}

sub query_trans {
	my ($tf, @tt) = @_;
	my $matched_string;
	if ( $msg_str =~ m/($tf)/i ) {
		$matched_string = $+;
		print STDERR "\n" . ('='x75) . "\n${msg_id}\n${msg_str}\n";
		my $prompt = "0 - Don't modify the string\n";
		my $answer;
		my $i = 0;
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
}

sub translate_header() {
	my $curdate = strftime ("%Y-%m-%d %H:%M+0800", localtime());

	$msg_str =~ s/^("PO-Revision-Date: ).*\\n"/$1$curdate\\n"/m;
	$msg_str =~ s/^("Language-Team: ).*\\n"/$1Chinese (Hong Kong) <community\@linuxhall.org>\\n"/m;
	return;
}

sub translate() {

	# order of the words can sometimes be important!

	# place/country names
	# 沙特阿拉伯 ?
	# Dominica ?
	do_trans("聖克里斯多福(及尼維斯)?", "聖吉斯納域斯");		# Saint Kitts And Nevis
	do_trans("聖文森([及和]格瑞那丁)?", "聖文森特和格林納丁斯");	# Saint Vincent And The Grenadines
	do_trans("波士尼亞[及和]?赫塞哥維[納那]", "波斯尼亞和黑塞哥维那"); # Bosnia and Herzegovina
	do_trans("阿拉伯聯合大公國", "阿拉伯聯合酋長國");	# United Arab Emirates
	do_trans("巴布亞紐幾內亞", "巴布亞新畿內亞");	# Papua New Guinea
	do_trans("安地卡[及和]巴布達", "安提瓜和巴布達");	# Antigua and Barbuda
	do_trans("千里達托貝哥", "特立尼達和多巴哥");		# Trinidad And Tobago
	do_trans("新喀里多尼亞", "新喀里多尼亞");	# New Caledonia
	do_trans("沙烏地阿拉伯", "沙地阿拉伯");	# Saudi Arabia
	do_trans("維爾?京群島", "處女羣島");	# British / US Virgin Islands
	do_trans("哥斯大黎加", "哥斯達黎加");	# Costa Rica
	do_trans("列支敦斯登", "列支敦士登");	# Liechtenstein
	do_trans("斯洛[維凡]尼亞", "斯洛文尼亞");	# Slovenia
	do_trans("索羅門群島", "所羅門羣島");	# Solomon Islands
	do_trans("茅利塔尼亞", "毛里塔尼亞");	# Mauritania
	do_trans("布吉納法索", "布基納法索");	# Burkina Faso
	do_trans("幾內亞比索", "畿內亞比紹");	# Guinea-Bissau
	do_trans("克羅埃西亞", "克羅地亞");		# Croatia
	do_trans("(埃立特里亞|厄利垂亞)", "厄立特里亞");	# Eritrea
	do_trans("塔吉克(?!斯坦)", "塔吉克斯坦");	# Tajikistan
	do_trans("吉里巴斯", "基里巴斯");		# Kiribati
	do_trans("坦尚尼亞", "坦桑尼亞");		# Tanzania
	do_trans("格瑞納達", "格林納達");		# Grenada
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
	do_trans("史瓦濟蘭", "斯威士蘭");		# Switzerland
	do_trans("(莫多瓦|莫爾達瓦)", "摩爾多瓦");	# Moldova
	do_trans("厄瓜多(?!爾)", "厄瓜多爾");	# Ecuador
	do_trans("瓦努阿圖", "萬那杜");		# Vanuatu
	do_trans("索馬利亞", "索馬里");		# Somalia
	do_trans("突尼西亞", "突尼斯");		# Tunisia
	do_trans("辛巴威", "津巴布韋");		# Zimbabwe
	do_trans("巴貝多", "巴巴多斯");		# Barbados
	do_trans("波札那", "博茨瓦納");		# Botswana
	do_trans("喬治亞", "格魯吉亞");		# Georgia
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
	do_trans("卡達", "卡塔爾");			# Qatar
	do_trans("蓋亞[納那]", "圭亞那");		# Guyana
	do_trans("葛摩", "科摩羅");			# Comoros
	do_trans("教廷", "梵蒂岡");		# Holy See
	do_trans("查德", "乍得");			# Chad
	do_trans("漢城", "首爾");			# Seoul, Korea
	do_trans("葉門", "也門");			# Yemen
	do_trans("貝南", "貝寧");			# Benin
	do_trans("加彭", "加蓬");			# Gabon
	do_trans("迦納", "加納");			# Ghana
	do_trans("肯尼?亞", "肯雅");			# Kenya
	do_trans("馬利", "馬里");			# Mali
	do_trans("諾魯", "瑙魯");			# Nauru
	do_trans("東加", "湯加");			# Tonga
	do_trans("尼日(?![利爾])", "尼日爾");	# Niger
	do_trans("奈及利亞", "尼日利亞");		# Nigeria

	# language names
	do_trans("布里多尼", "不列塔尼");
	do_trans("加泰羅尼亞", "加泰隆尼亞");		# Catalan
	do_trans("弗里西亞", "弗里斯蘭");
	do_trans("加利西亞", "加里西亞");
	do_trans("印地語", "印度語");		# Hindi

	# ssl certificate, CA, PKI related
	do_trans("憑證廢止清冊", "證書撤銷清單");
	do_trans("憑證管理中心", "核證機關");
	do_trans("私鑰", "私人密碼匙");
	do_trans("公鑰", "公開密碼匙");
	do_trans("金鑰", "密碼匙");
	do_trans("鑰匙圈", "密碼匙圈");
	do_trans("憑證", "證書");
	do_trans("簽章", "簽署");
	do_trans("隱私", "私隱");

	# generic terms from mediawiki
	# 位元組 -> 字節 ?
	# 數據機 -> 調制解調器 ?
	do_trans("([軟硬])體", "\$1件");
	do_trans("([二三])極體", "\$1極管");
	do_trans("人工智慧", "人工智能");
	do_trans("機器人", "機械人");
	do_trans("太空梭", "穿梭機");
	do_trans("行動電話", "流動電話");
	do_trans("(速食麵|泡麵)", "即食麵");
	do_trans("土豆", "薯仔");
	do_trans("計程車", "的士");
	do_trans("公車", "巴士");
	do_trans("[台撞]球" ,"桌球");
	do_trans("冰淇淋" ,"雪糕");
	do_trans("衛生" ,"衞生");
	do_trans("老人" ,"長者");
	do_trans("簡訊", "短訊");

	# some terms common in China mainland but not in Hong Kong
	do_trans("([光軟硬磁])盤", "\$1碟");
	do_trans("服務器", "伺服器");
	#do_trans("上載", "上傳");

	# other generic terms
	do_trans("自由軟體基金會", " Free Software Foundation ");
	do_trans("(筆記型|攜帶型|膝上型)電腦", "手提電腦");
	do_trans("個人資訊", "個人資料");
	do_trans("積體電路", "集成電路");
	do_trans("行動通訊", "流動通訊");
	do_trans("西洋棋", "國際象棋");
	do_trans("中國跳棋", "波子棋");
	do_trans("布林值", "邏輯值");
	do_trans("命令殼", "命令解譯器");
	do_trans("內插法", "插值法");
	query_trans("計算機", "計數機", "電腦");
	do_trans("解析度", "解像度");
	do_trans("腳踏車", "單車");
	do_trans("撥接", "撥號");
	do_trans("正體", "繁體");
	do_trans("團隊", "隊伍");
	do_trans("名片", "咭片");
	do_trans("內建", "內置");
	do_trans("連繫", "聯繫");
	do_trans("連絡", "聯絡");
	do_trans("建構", "建立");
	#do_trans("算式", "數式");
	do_trans("變更", "更改");
	do_trans("公分", "厘米");
	do_trans("麻將", "麻雀");
	do_trans("字元", "字符");
	do_trans("頭銜", "銜頭");
	do_trans("寬度", "闊度");
	do_trans("視訊", "視像");
	query_trans("線上", "網上");
	do_trans("公尺", "米");

	# "function", but don't change "library"
	do_trans("函式(?!庫)", "函數");

	do_trans("印表機", "打印機");
	# 「列印」後面有括號，多數是選單的項目，表示「打印」
	do_trans("列印(?=\\()", "打印");
	# 有時「列印」表示在螢幕顯示字句，這時用「顯示」較好
	query_trans("列印", "打印", "顯示");

	# digital, but don't change "decimal point"
	do_trans("(?<!小)數位", "數碼");

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
	# FIXME: affect too much translation
	# perhaps search the context and determine if it's about game?
	#query_trans("(?<!(對話|核取))方塊", "階磚");

	# Hong Kong government tend to use 圖像, but 影像 means lots of different
	# things (image, video, graphics, ...) so ask instead of forcefully
	# convert
	query_trans("影像", "圖像", "圖片");

	# some characters are used solely in Hong Kong but infrequently in Taiwan
	do_trans("您", "你");
	do_trans("裡", "裏");

	# some character are forced on Hong Kong because they are not
	# available in Big5 encoding
	do_trans("群", "羣");
	#do_trans("線", "綫");		# this is problematic

	# http://www.linuxfans.org/nuke/modules.php?name=Forums&file=viewtopic&t=25997
	# 著作 著者 著名 著述 著書 所著 名著 土著 顯著 編著
	do_trans("(?<![所名土顯編])著(?![作者名述書])", "着");

}

while (<>) {

	if  (/^#/) {
		if (m/^#\s*zh_HK:\s*(.*)/i) {
			# zh_HK uses specialized translation, shouldn't convert from zh_TW
			$force_msg_str .= $1;
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
}

# ex: softtabstop=4: tabstop=4: shiftwidth=4: noexpandtab
# -*- mode: perl; tab-width: 4; indent-tabs-mode: t; coding: utf-8 -*-
