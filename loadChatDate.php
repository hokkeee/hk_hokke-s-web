<?
if(!isset($_SESSION)) session_start();

// キャッシュを取らないように
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

$max_chid = isset($_SESSION["max_chid"]) ? $_SESSION["max_chid"] : 0 ;

// チャットの内容の取得
$_chat = array();
$rst = mysql_query("select * from chat where chid > {$max_chid} order by date desc limit 30");
while($col=mysql_fetch_assoc($rst)) $_chat[$col["chid"]] = $col;
mysql_free_result($rst);

// 直近のID
$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : $max_chid ;

// チャットデータの書き出し
foreach($_chat as $val){?>
<tr><td><?=htmlspecialchars($val["name"])?></td><td><?=substr($val["date"],5,14)?></td><td><?=htmlspecialchars($val["text"])?></td></tr>
<?}?>