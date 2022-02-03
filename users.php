<?php
require "include/bittorrent.php";
require_once("include/secrets.php");
global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
$con_link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_VIP)
	site_error_message("Foutmelding", "U heeft geen rechten om deze pagina te bekijken.");

$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

for ($i = 0; $i < strlen($letters); ++$i)
	{
	$letter = substr($letters,$i,1);
	@$mod_letter .= "<font size=3><a class=altlink_lblue href='users.php?letter=".$letter."'>" . $letter . "</a> ";
	}

@$start = 0 + $_GET['start'];
$letter = @$_GET['letter'];
if (strlen($letter) <= 0)
	$letter = "A";
$sorteren = @$_GET['sorteren'];
if (!$sorteren || $sorteren == "ratio")
	{
	$sorteren_sql = "(uploaded / downloaded)";
	$sorteren_tekst = "ratio";
	}
if ($sorteren == "gezien")
	{
	$sorteren_sql = "last_access";
	$sorteren_tekst = "laatst gezien";
	}

$sorted = "<a class=altlink_lblue href=users.php?letter=".$letter."&amp;sorteren=ratio><font size=3><b>Ratio</b></font></a>";
$sorted .= "<font color=#66FFFF> - </font><a class=altlink_lblue href=users.php?letter=".$letter."&amp;sorteren=gezien><font size=3><b>Laatst gezien</b></font></a>";
	
$totaal = get_row_count("users","WHERE username LIKE '".$letter."%'");
$per_pagina = 40;
$paginas = floor($totaal / $per_pagina);
if ($paginas * $per_pagina < $totaal)
  ++$paginas;

	$site_pager = "<font color=yellow size=3>";
	for ($i = 0; $i < $paginas; ++$i)
		{
		$begin = $i*$per_pagina;
		if ($i > 0) $site_pager .= "&nbsp;-&nbsp;";
		$pagina = $i + 1;
		if ($start/$per_pagina == $i)
			$site_pager .= "" . $pagina . "";
		else
			$site_pager .= "<a href=users.php?letter=".$letter."&amp;sorteren=".$sorteren."&amp;start=" . $begin . "><font color=yellow size=3><b>" . $pagina . "</b></font></a>";
		}
		$site_pager .= "</font>";

site_header("Gebruiker zoeken");
page_start();
tabel_start();
print "<center><font size=3 color=#66FFFF>Gesorteerd op : " . $sorted . "<hr></center>";
print "<center>" . $mod_letter . "<hr></center>";
print "<center>" . $site_pager . "<br></center>";
tabel_einde();
print "<br>";
tabel_top($totaal . " gebruikers beginnend met: " . $letter . " gesorteerd op " . $sorteren_tekst, "center");
tabel_start();

$res = mysqli_query($con_link, "SELECT * FROM users WHERE username LIKE '".$letter."%' ORDER BY $sorteren_sql LIMIT $start,$per_pagina") or sqlerr(__FILE__, __LINE__);

print "<table align=center class=sitetable border=1 width=98% cellspacing=0 cellpadding=5>";
print "<tr>";
print "<td class=colheadsite></td>";
print "</tr >";
	print "<tr class=colomn_view>";
while ($row = mysqli_fetch_assoc($res))
	{

	print "<td ><a class=altlink_blue href='userdetails.php?id=".$row['id']."' target=_blank>".get_usernamesitesmal($row['id'])."</a></td>";

	}
	print "</tr>";
print "</td></tr></table>";
tabel_einde();
page_einde();
site_footer();
?>