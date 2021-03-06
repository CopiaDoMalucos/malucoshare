<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
 loggedinonly();
if ($_GET["passkey"]) {
	$CURUSER = mysql_fetch_array(SQL_Query_exec("SELECT * FROM users INNER JOIN groups ON users.class=groups.group_id WHERE passkey=".sqlesc($_GET["passkey"])." AND enabled='yes' AND status='confirmed'"));
}

//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();
	
	if($CURUSER["can_download"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_PERMISSION_TO_DOWNLOAD"), 1);
}

$id = (int)$_GET["id"];

if (!$id)
	show_error_msg(T_("ID_NOT_FOUND"), T_("ID_NOT_FOUND_MSG_DL"), 1);

$res = SQL_Query_exec("SELECT filename, banned, external, announce, owner, safe FROM torrents WHERE id =".intval($id));
$row = mysql_fetch_array($res);
if ($row["safe"] == "no") {
    if ($row["owner"] == $CURUSER["id"] || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador") {
	$chave= 'verdadeiro';

	}
		else{
	$chave= 'falso'; 
	}
	}
$torrent_dir = $site_config["torrent_dir"];

$fn = "$torrent_dir/$id.torrent";

if (!$row)
	show_error_msg(T_("FILE_NOT_FOUND"), T_("ID_NOT_FOUND"),1);
if ($row["banned"] == "yes")
	show_error_msg(T_("ERROR"), 'Este torrent foi bloqueado para novos downloads!', 1);
if ($chave == "falso")	
	show_error_msg("Permissão Negada", 'Este torrent ainda não está liberado para downloads!', 1);

if (!is_file($fn))
	show_error_msg(T_("FILE_NOT_FOUND"), T_("FILE_NOT_FILE"), 1);
if (!is_readable($fn))
	show_error_msg(T_("FILE_NOT_FOUND"), T_("FILE_UNREADABLE"), 1);

$name = $row['filename'];
$friendlyname = str_replace(".torrent","",$name);
$friendlyext = ".torrent";
$name = $friendlyname ."". $friendlyext;

SQL_Query_exec("UPDATE torrents SET hits = hits + 1 WHERE id = $id");

require_once("backend/BEncode.php");
require_once("backend/BDecode.php");

//if user dont have a passkey generate one, only if tracker is set to members only
if ($site_config["MEMBERSONLY"]){
	if (strlen($CURUSER['passkey']) != 32) {
		$rand = array_sum(explode(" ", microtime()));
		$CURUSER['passkey'] = md5($CURUSER['username'].$rand.$CURUSER['secret'].($rand*mt_rand()));
		SQL_Query_exec("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
	}
}

if ($row["external"]!='yes' && $site_config["MEMBERSONLY"]){// local torrent so add passkey
	$dict = BDecode(file_get_contents($fn));
	$dict['announce'] = sprintf($site_config["PASSKEYURL"], $CURUSER["passkey"]);
	unset($dict['announce-list']);

	header('Content-Disposition: attachment; filename="'.$name.'"');

	header("Content-Type: application/x-bittorrent");

	print(BEncode($dict)); 

}else{// external torrent so no passkey needed
	header('Content-Disposition: attachment; filename="'.$name.'"');

	header("Content-Type: application/x-bittorrent");

	readfile($fn); 
}

mysql_close();

?>