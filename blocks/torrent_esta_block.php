<?php
begin_block("Ações");
$id = (int) $_GET["id"];
$scrape = (int)$_GET["scrape"];
if (!is_valid_id($id))
	show_error_msg("ERROR", T_("THATS_NOT_A_VALID_ID"), 1);

$res = mysql_query("SELECT torrents.anon, torrents.freeleechexpire, torrents.tube, torrents.temposeed,  torrents.seeders, torrents.markedby, torrents.filmeresolucalt, torrents.musicalinkloja, torrents.musicalbum, torrents.musicalautor, torrents.filmeresolucao, torrents.markdate, torrents.thanks, torrents.adota, torrents.adotadata, torrents.adota_yes_no, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.points, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.screens1, torrents.screens2, torrents.screens3, torrents.screens4, torrents.screens5, torrents.owner, torrents.save_as, torrents.descr, torrents.filmesinopse,  torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, torrents.safe, torrents.category, torrents.nuked, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, categories.image AS cat_pic, torrentlang.name AS lang_name, torrentlang.image AS lang_image, filmeano.name AS anoteste_name, filmeextensao.name AS testet_name, filmeaudio.name AS filmeaudio1_name, filmequalidade.name AS filmequalidade1_name,  filme3d.name AS filme3d1_name,  legenda.name AS legendaid_name, filmecodecvid.name AS filmecodecvid1_name,  filmecodecaud.name AS filmecodecaud1_name,  filmeduracaoh.name AS filmeduracaoh1_name,  filmeduracaomi.name AS filmeduracaomi1_name,  filmeidiomaorigi.name AS filmeidiomaorigi1_name, aplicrack.name AS aplicrack1_name, apliformarq.name AS apliformarq1_name, musicaqualidade.name AS musicaqualidade1_name, musicatensao.name AS musicatensao1_name, jogosgenero.name AS jogosgenero1_name, jogosformato.name AS jogosformato1_name, jogosmultiplay.name AS jogosmultiplay1_name, revistatensao.name AS revistatensao1_name, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN filmeano ON torrents.filmeano = filmeano.id LEFT JOIN filmeextensao ON torrents.filmeextensao = filmeextensao.id LEFT JOIN filmeaudio ON torrents.filmeaudio = filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filme3d ON torrents.filme3d = filme3d.id  LEFT JOIN legenda ON torrents.legenda = legenda.id LEFT JOIN  filmecodecvid ON torrents. filmecodecvid = filmecodecvid.id LEFT JOIN   filmecodecaud ON torrents.  filmecodecaud =  filmecodecaud.id LEFT JOIN  filmeduracaoh ON torrents.filmeduracaoh = filmeduracaoh.id LEFT JOIN filmeduracaomi ON torrents.filmeduracaomi = filmeduracaomi.id LEFT JOIN  filmeidiomaorigi ON torrents.filmeidiomaorigi = filmeidiomaorigi.id LEFT JOIN  aplicrack ON torrents.aplicrack = aplicrack.id LEFT JOIN  apliformarq ON torrents.apliformarq = apliformarq.id LEFT JOIN  musicaqualidade ON torrents.musicaqualidade = musicaqualidade.id LEFT JOIN musicatensao ON torrents.musicatensao = musicatensao.id  LEFT JOIN jogosgenero ON torrents.jogosgenero = jogosgenero.id LEFT JOIN jogosformato ON torrents.jogosformato = jogosformato.id LEFT JOIN jogosmultiplay ON torrents.jogosmultiplay = jogosmultiplay.id   LEFT JOIN revistatensao ON torrents.revistatensao = revistatensao.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id") or die(mysql_error());
$row = mysql_fetch_assoc($res);

            $username = $row["markedby"];
      
if ($row["safe"] == "no") {
 
           $datadeliberacao = 'aguardando';
    }else{
         $datadeliberacao =  date("d/m/y", utc_to_tz_time($row['markdate']))." às ". date("H:i:s", utc_to_tz_time($row['markdate']));
    
}	
	$res123 = mysql_query("SELECT id, downloaded from users where username='$username'");
		$arr123 = MYSQL_FETCH_ARRAY($res123);


   $ultimaseed = "" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($row["last_action"]))) . " atrás";

?>
<center>
<table cellspacing="1" cellpadding="0" align="center" class="tab1">
<tbody><tr><td align="center" class="tab1_cab1">Dados Gerais</td></tr>
<tr><td class="tab1_col3">Seeders: <b><font color="green"><?php echo  number_format($row["seeders"]) ; ?></font></b>
<br>Leechers: <b><font color="red"><?php echo  number_format($row["leechers"]); ?></font></b><br>(<a href="torrents-peers.php?id=<?php echo $id ;?>" class="lastposter">ver peers</a>)<br></td></tr>
<tr><td align="center" class="tab1_cab1">Data de envio</td></tr>
<tr><td align="center" class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) ;?></td></tr>
	<?php
	if ($row["safe"] == "yes") {
			?>
<tr><td align="center" class="tab1_cab1">Liberado por</td></tr>
		<tr><td align="center" class="tab1_col3"><a href="account-details.php?id=<?php echo $arr123['id'] ;?>" class="lastposter"><?php echo $row["markedby"] ;?></a></td></tr>
			<?php 
			}
			?>
		<tr><td align="center" class="tab1_cab1">Data de liberação</td></tr>
	<tr><td align="center" class="tab1_col3"><?php echo  $datadeliberacao ;?></td></tr><tr><td align="center" class="tab1_cab1">Seed mais recente</td></tr>
<tr><td align="center" class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($row['last_action']))." às ". date("H:i:s", utc_to_tz_time($row['last_action'])) ;?></td></tr></tbody></table></center>

<?php
end_block();
?>
