<?

$name = isset($_POST["name"]) ? $_POST["name"] : "" ;
$text = isset($_POST["text"]) ? $_POST["text"] : "" ;

$err = array();
if(!$name) $err[] = "名前 を入力してください";
if(mb_strlen($name)>10) $err[] = "名前 は10文字以内で入力してください";
if(!$text) $err[] = "文章 を入力してください";
if(mb_strlen($text)>50) $err[] = "文章 は50文字以内で入力してください";

if(!count($err)){
	mysql_query("insert into chat set date = now(), name = '".addslashes($name)."', text = '".addslashes($text)."'");
}else{
	$msg = showerr($err);
}

?>