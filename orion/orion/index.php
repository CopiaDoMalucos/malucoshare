<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functionsnew.php");
dbconn(true);
header('Content-Type: text/html; charset=utf-8');



loggedinonly();  
stdhead("Home");


 $vertor  = (int)$_GET["tor"]; 
      if ($vertor == 10) {
	  	mysql_query("UPDATE users SET  vertorr= '10' WHERE id='".$CURUSER['id']."' ");

	  }
      if ($vertor == 20) {
	  	mysql_query("UPDATE users SET  vertorr= '20' WHERE id='".$CURUSER['id']."' ");
echo"20";
	  }
      if ($vertor == 30) {
	  	mysql_query("UPDATE users SET  vertorr= '30' WHERE id='".$CURUSER['id']."' ");
echo"30";
	  }
//Site News
if ($site_config['NEWSON']){
	$res = mysql_query("SELECT news.newid, news.title, news.added, news.body, users.username, users.id FROM news LEFT JOIN users ON news.userid = users.id WHERE ADDDATE(news.added, INTERVAL 90 DAY) > '".get_date_time()."' ORDER BY added DESC LIMIT 1");

	if (mysql_num_rows($res) > 0){
		print("<table class=teste2 border=0 cellpadding=0 width=100% ><tr><td>\n<ul>");
		$news_flag = 0;

		while($array = mysql_fetch_assoc($res)){



////$username_new = "<a href=account-details.php?id=$array[id]>$array[username]</a>";


			$numcomm = number_format(get_row_count("comments", "WHERE news='".$array['newid']."'"));

	begin_framec("". $array['title'] . "");

				print("<br><div align='right'> <i>Postado em ".date("d/m/y", utc_to_tz_time($array['added']))." às ". date("H:i:s", utc_to_tz_time($array['added'])) . "</i></div>");

				print(" ".format_comment($array["body"])." <br /><br /><p align='right' >Esta notícia tem ".number_format($numcomm)." Comentários. </p><BR><p align='right'><a href='comentario.php?type=news&amp;id=".$array['newid']."#bottom'><i>Comentar / Ver Comentários </a></i></p><br /> ");


		}
		print("</ul></td></tr></table>\n");
	}

	end_framec();
}

begin_framec("Torrents lançados recentemente");

     ?>
     <div > 
	 <ul id="tabs"> 
    <li>  <a href="javascript:void(0);" onclick="switchTab(1);" style="display: block;" id="tab-1">Geral</a></li>
    <li>  <a href="javascript:void(0);" onclick="switchTab(2);"  style="display: block;" id="tab-2">Músicas</a></li>
	<li> <a href="javascript:void(0);" onclick="switchTab(3);" style="display: block;"  id="tab-3">Cursos</a></li>
    <li> <a href="javascript:void(0);" onclick="switchTab(4);"  style="display: block;" id="tab-4">Adultos</a></li>
    </ul> 
     <div id="tabContent"></div>
  
     <script>
     function switchTab( tab )
     {
         var selected = null;
 
         switch ( tab )
         {
             default:
             case 1:
                  selected = 'tab-1'; break;
                  
             case 2:
                  selected = 'tab-2'; break;
			 
			 case 3:
                  selected = 'tab-3'; break;
			
    		 case 4:
                  selected = 'tab-4'; break;	  
         }
         
         for ( i = 1; i <= 4; i++ )
         {
             document.getElementById( 'tab-' + i ).className = 'tab';
         }
         
         document.getElementById( selected ).className = 'selected';
         
         getTab( tab );
     }
     
     function httpObject()
     {
         var httpObject;
     
         if ( typeof XMLHttpRequest != 'undefined' )
         {
               httpObject = new XMLHttpRequest();
         }
         else
         {
               httpObject = new ActiveXObject( 'MSXML2.XMLHTTP.3.0' );
         }
     
         return httpObject;
     }
     
     function getTab( tab )
     {
         var httpObject = this.httpObject();
         
         httpObject.open( 'GET', 'ajax/torrents.php?tid=' + tab, true );
         
         httpObject.onreadystatechange = function()
         {
             if ( ( httpObject.readyState == 4 ) && ( httpObject.status == 200 ) )
             {           
                  document.getElementById( 'tabContent' ).innerHTML = httpObject.responseText;
                                          
                  return;
             }
         }
         
         httpObject.setRequestHeader( 'TT', 1 );
         
         httpObject.send();
     }
     
     switchTab( 1 );
     </script>
     </div> 
     <?php
     end_framec();








if ($site_config['SHOUTBOX'] ){ 

	begin_framec("Shoutbox");
	echo '<iframe name="shout_frame" src="shoutbox.php" frameborder="0" marginheight="0" marginwidth="0" width="99%" height="410" scrolling="no" align="middle"></iframe>';
	end_framec();
}



// Latest Forum Topics
begin_framec("Últimas do Fórum");
print("<div align='justify' class='framecentro'><table align=center cellpadding=1 cellspacing=0 style='border-collapse: collapse' bordercolor=#646262 width=100% border=1 ><tr>".
"<center> [ <a href=forums.php>Índice</a> | <a href='forums.php?action=viewunread'> + novos posts</a> | <a href=forums.php?action=naolidos>Posts não lidos</a> | <a href='forums.php?action=search'>Pesquisa</a> ] </center>".
"<td  align=left class=ttable_head  width=400><b>Tópico</b></td>".
"<td align=center class=ttable_head ><b>Resp.</b></td>".
"<td  align=center class=ttable_head ><b>Fórum</b></td>".
"<td  align=right class=ttable_head ><b>Últimas Postagem</b></td>".
"</tr>");


/// HERE GOES THE QUERY TO RETRIEVE DATA FROM THE DATABASE AND WE START LOOPING ///
$for = mysql_query("SELECT * FROM forum_topics ORDER BY lastpost DESC LIMIT 5");

while ($topicarr = mysql_fetch_assoc($for)) {
// Set minclass

$whereaad=array();



if ($CURUSER["ver_xxx"]!="yes") {
    $whereaad[] = "forum_forums.id != '43'";
    $whereaad[] = "forum_forums.id != '16'";

}
     $whereaad[] = "forum_forums.id != '0'";
$wheread = implode(" AND ", $whereaad);
    //





$res = mysql_query("SELECT name,minclassread FROM forum_forums WHERE id=$topicarr[forumid] AND ".$wheread."  ") ;
$forum = mysql_fetch_assoc($res);

if ($forum && get_user_class() >= $forum["minclassread"] || $forum["guest_read"] == "yes"){

$forumname = "<a href=/forums.php?action=viewforum&forumid=$topicarr[forumid]><b>" . htmlspecialchars($forum["name"]) . "</b></a>";

$topicid = $topicarr["id"];
$topic_title = stripslashes($topicarr["subject"]);
$topic_userid = $topicarr["userid"];
// Topic Views
$views = $topicarr["views"];
// End
$postsperpage = 20;
$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
$posts = $arr[0];
$replies = max(0, $posts - 1);
$tpages = ceil($posts / $postsperpage);
if ($tpages > 1) {
	  $topicpages = " (<img src='". $site_config['SITEURL'] ."/images/forum/multipage.png' alt='' />";
		
		$ultima_pag = $tpages;
		$penultima = $ultima_pag - 1;
		$adjacentes = 2;
		
		if ($ultima_pag <= 5){
			for ($i=1; $i< $ultima_pag+1; $i++){
				$topicpages .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'> $i </a>";	
			}
		}
		
		if ($ultima_pag > 5){
			if ($pag < 1 + (2 * $adjacentes)){
				for ($i=1; $i< 2 + (2 * $adjacentes); $i++){
					$topicpages .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'> $i </a>";	
				}
				$topicpages .= ' ... ';
				$topicpages .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page='.$penultima.'">'.$penultima.' </a>';
				$topicpages .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page='.$ultima_pag.'">'.$ultima_pag.' </a>';
			}elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3){
				$paginacao .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page=1">1</a>';				
				$paginacao .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page=2">2</a> ... ';	
				for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++){
					$topicpages .= "<a href='forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page=$i'> $i </a>";	
				}
				$topicpages .= ' ... ';
				$topicpages .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page='.$penultima.'">'.$penultima.' </a>';
				$topicpages .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page='.$ultima_pag.'">'.$ultima_pag.' </a>';
			}else {
				$paginacao .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page=1">1</a>';				
				$paginacao .= '<a href="forums.php?action=viewtopic&amp;topicid='.$topicid.'&amp;page=2">2</a> ... ';	
				for ($i = $ultima_pag - (4 + (2 * $adjacentes)); $i <= $ultima_pag; $i++){
					$topicpages .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'> $i </a>";	
				}
			}
		} 

	  $topicpages .= ")";
}else $topicpages = "";




/// GETTING USERID AND DATE OF LAST POST ///  
$res = mysql_query("SELECT * FROM forum_posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
$postid = 0 + $arr["id"];
$userid = 0 + $arr["userid"];
$added = "<nobr>" . date("d-m-Y H:i:s", utc_to_tz_time($arr["added"])) . "</nobr>";

/// GET NAME OF LAST POSTER ///
$res = mysql_query("SELECT id, username FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$username = "<a href=account-details.php?id=$userid>$arr[username]</a>";
}
else
$username = "[Desconhecido]";

/// GET NAME OF THE AUTHOR ///
$res = mysql_query("SELECT username FROM users WHERE id=$topic_userid") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$author = "$arr[username]";
}
else
$author = "[Desconhecido]";

/// GETTING THE LAST INFO AND MAKE THE TABLE ROWS ///
$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$userid AND topicid=$topicid") or sqlerr(__FILE__, __LINE__);
$a = mysql_fetch_row($r);
$subject = "<a  title='Autor: <b>$author</b><br>Visitas: <B>$views</B>' href=forums.php?action=viewtopic&topicid=$topicid><b>" . stripslashes(encodehtml($topicarr["subject"])) . "</b></a>$topicpages";

print("<tr><td class=ttable_col2 align=left >$subject </td>".
"<td class=ttable_col2 align=center ><font class=tipo_forum1>$replies</font></td>" .
"<td class=ttable_col2 align=center>$forumname</td> " .
"<td class=tab1_col3 align=right ><font class=tipo_forum1>por</font>&nbsp;$username<br><font class=tipo_forum1>$added</font></td>");

print("</tr>");
} // while
}
print("</table></div><br>");
end_frame();

$whereag=array();








if ($CURUSER["grupo_cyber"]!="yes") {
    $whereag[] = "id != '13'";
}
if ($CURUSER["grupo_peace"]!="yes") {
     $whereag[] = "id != '17'";
}
if ($CURUSER["grupo_files"]!="yes") {
     $whereag[] = "id != '18'";
}
if ($CURUSER["grupo_lord"]!="yes") {
     $whereag[] = "id != '25'";
}
   $whereag[] = "id != '60'";
     $whereag[] = "id != '0'";

$whereg = implode("  AND ", $whereag);


$query=mysql_query("SELECT * FROM teams WHERE ". $whereg ."  ORDER BY RAND()");

while ($dados = mysql_fetch_assoc($query)) {
	if ($dados["info"]!="") {
	

			echo "<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";	
			 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1>".$dados["name"]."</td></tr>");
		print("<td align=center colspan=2 class=postgrupo><center  style='background-color:#FFFFFF;'>".$dados["info"]."</center></td>");
 print("<tr><td align=center colspan=2 class=postgrupo><center  style='background-color:#FFFFFF;'><a href='grupos_lancamentos.php?id=".$dados['id']."#bottom'>Todos os lançamentos deste grupo</a></center></td></tr>");
		
		
		
		
 		 echo "</table>";
		
	}
}




stdfoot();
?>
