<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("backend/bbcode.php");
dbconn();

$id = (int)$_GET["id"];
$type = $_GET["type"];
$edit = (int)$_GET["edit"];
$delete = (int)$_GET["delete"];
$dossier = $CURUSER['bbcode'];

if ($edit == 1 || $delete == 1 || $_GET["takecomment"] == 'yes') loggedinonly();

if (!isset($id) || !$id || ($type != "torrent" && $type != "news"))
	show_error_msg("ERROR", T_("ERROR"), 1);

if ($edit=='1'){
	$row = mysql_fetch_assoc(SQL_Query_exec("SELECT user FROM comments WHERE id=$id"));

    if (($type == "torrent" && $CURUSER["edit_torrents"] == "no" || $type == "news" && $CURUSER["edit_news"] == "no") && $CURUSER['id'] != $row['user'])   
		show_error_msg("ERROR","You cant do this!",1);

		$save = (int)$_GET["save"];

		if($save){
			$text = sqlesc($_POST['text']);

			$query="UPDATE comments SET text=$text WHERE id=$id";
			$result=SQL_Query_exec($query);
			write_log($CURUSER['username']." has edited comment: ID:$id");
			show_error_msg(T_("COMPLETE"), "Comment Edited OK",1);
		}

		stdhead("Edit Comment");

		$res = SQL_Query_exec("SELECT * FROM comments WHERE id=$id");
		$arr = mysql_fetch_array($res);

		begin_framec("Edit Comment");
		print("<center><b>Edit comment </b><p>\n");
		print("<form method=\"post\" name=\"comment\" action=\"comments.php?type=$type&amp;edit=1&save=1&amp;id=$id\">\n");
		print ("".textbbcode("comment","text",$dossier,"" . htmlspecialchars($arr["text"]) . "")."");
		print("<p><input type=\"submit\"  value=\"Submit Changes\" /></p></form></center>\n");
		end_framec();
		stdfoot();
		die();
}

if ($delete=='1'){
	if ($CURUSER["delete_news"] == "no" && $type == "news" || $CURUSER["delete_torrents"] == "no" && $type == "torrent")  
		show_error_msg("ERROR","You cant do this!",1);

	if ($type == "torrent") {
		$res = SQL_Query_exec("SELECT torrent FROM comments WHERE id=$id");
		$row = mysql_fetch_assoc($res);
		if ($row["torrent"] > 0) {
			SQL_Query_exec("UPDATE torrents SET comments = comments - 1 WHERE id = $row[torrent]");
		}
	}

	SQL_Query_exec("DELETE FROM comments WHERE id = $id");
	write_log("Upado","blue",$CURUSER['username']." has deleted comment: ID: $id");
	show_error_msg(T_("COMPLETE"), "Comment deleted OK", 1);
}


stdhead(T_("COMMENTS"));


//take comment add
if ($_GET["takecomment"] == 'yes'){
	$body = $_POST['body'];
	
	if (!$body)
		show_error_msg(T_("ERROR"), T_("YOU_DID_NOT_ENTER_ANYTHING"), 1);

	if ($type =="torrent"){
		SQL_Query_exec("UPDATE torrents SET comments = comments + 1 WHERE id = $id");
	}

	SQL_Query_exec("INSERT INTO comments (user, ".$type.", added, text) VALUES (".$CURUSER["id"].", ".$id.", '" .get_date_time(). "', " . sqlesc($body).")");

	if (mysql_affected_rows() == 1)
			show_error_msg(T_("COMPLETED"), "Your Comment was added successfully.", 0);
		else
			show_error_msg(T_("ERROR"), T_("UNABLE_TO_ADD_COMMENT"), 0);
}//end insert comment

//NEWS
if ($type =="news"){
	$res = SQL_Query_exec("SELECT * FROM news WHERE id = $id");
	$row = mysql_fetch_array($res);

	if (!$row){
		show_error_msg(T_("ERROR"), "News id invalid", 0);
		stdfoot();
	}

	begin_framec(T_("NEWS"));
	echo htmlspecialchars($row['title']) . "<br /><br />".format_comment($row['body'])."<br />";
	end_framec();
	
}

//TORRENT
if ($type =="torrent"){
	$res = SQL_Query_exec("SELECT id, name, safe FROM torrents WHERE id = $id");
	$row = mysql_fetch_array($res);

	if (!$row){
		show_error_msg(T_("ERROR"), "News id invalid", 0);
		stdfoot();
	}
	if ($row["safe"] == "yes") {
begin_framec(T_("COMMENTS"));
	echo "<center><b>Torrent:</b> <a href='torrents-details.php?id=".$row['id']."'>".htmlspecialchars($row['name'])."</a></center><br />";
           echo "<script type='text/javascript' src='scripts/comments.php?id=$id'></script>\n";
		end_framec();
}
else{
		show_error_msg(T_("ERROR"), "Torrent aguardando aprovação", 0);
}
}
if ($row["safe"] == "yes") {
begin_framec(T_("COMMENTS"));
	
	$dossier = $CURUSER['bbcode'];
			      print("<table align=center cellpadding='3' cellspacing='0' class='download' width='100%' border='0'><tr><td align='center' colspan='2' >\n");
	   echo "<div id='commentsdel'></div>";
	       if ($CURUSER) {
                echo "<BR><iframe name='commentframe' id='commentframe' src='comments_ajax.php?id=$id&do=postcomment' frameborder='0' width='100%' height='450'/></iframe>";
              print("</td> </tr></table>\n");
		}
        echo "<div id='commentsdiv' name='comments'><center><img src='images/loading.gif' border='0'><BR>Loading...</center><script language='JavaScript'>loadComments(-1)</script></div>";

    

	end_framec();
}
stdfoot();
?>
