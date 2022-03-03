<?
if(!isset($_SESSION)) session_start();

$msg = "<p>名前と文章を入力して送信ボタンを押してください。</p>";

// チャット内容の取得
$_chat = array();
$rst = mysql_query("select * from chat order by date desc limit 30");
while($col=mysql_fetch_assoc($rst)) $_chat[$col["chid"]] = $col;
mysql_free_result($rst);

// 直近のIDをセッションに登録
$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : 0 ;
?>