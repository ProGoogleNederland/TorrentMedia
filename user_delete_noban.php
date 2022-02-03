<?php
require "include/bittorrent.php";
require_once("include/secrets.php");
global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
$con_link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
dbconn();
loggedinorreturn();

if (get_user_class() < UC_OWNER)
	site_error_message("Foutmelding", "U ben niet bevoegd om deze pagina te bekijken.");

$userid = @$_POST['userid'];

$returnto = @$_POST['returnto'];
if (!$userid)
	site_error_message("Foutmelding", "Geen gebruikers ID ontvangen.");

if ($userid == 2564 || $userid == 1 || $userid == 2 || $userid == 3 || $userid == 6)
	site_error_message("Foutmelding", "Deze gebruiker kan niet verwijderd worden.");

$action = @$_POST['action'];
if (!$action)
	site_error_message("Foutmelding", "Geen opdracht ontvangen.");
//var_dump($userid);
$res = mysqli_query($con_link, "SELECT * FROM users WHERE id=".$userid) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_array($res);

if (!$row)
	site_error_message("Foutmelding", "Geen gebruiker gevonden.");

if ($action == "verwijderen")
	verwijder_gebruiker($userid);

if ($returnto)	
	header("Location: $BASEURL/$returnto");
else
	header("Location: $BASEURL/bans_view.php");
?>
