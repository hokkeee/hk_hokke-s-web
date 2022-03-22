// 初期化処理
window.onload = function() {
    // ボタンが押されたイベントハンドラを設定
    $("postBtn").onclick = writeLog;
    // ログを読み込む
    showLog();
};
 
// ログの表示
function showLog() {
    // Ajaxでログを取得
    ajaxGet(
        "api.php?type=getLog",
        function (xhr, text) {
            var logs = JSON.parse(text);
            renderLog(logs);
        });
};
 
// ログデータに基づき描画
function renderLog(logs) {
    var html = "";
    for(var i in logs) {
        var m = logs[i];
        var date = m["date"];
        var name = m["name"];
        var body = m["body"];
        html += "<li>" + date + "：" + name + "<br>「" + body + "」</li>";
    }
    $("logList").innerHTML = html;
}
 
// 書き込みを投稿する
function writeLog() {
    var name = $("name").value;
    var body = $("body").value;
    var params = "type=writeLog&" + "nam=" + encodeURI(name) + "&" + "body=" + encodeURI(body);
    ajaxGet("api.php?" + params, function(xhr, text) {
        // テキストフィールドを初期化                                 
        $("body").value = "";
        // 書き込みを反映
        showLog();
    });
}
 
 
// Ajax用
function ajaxGet(url, callback) {
    // XMLHttpRequestのオブジェクトを作成
    var xhr = new XMLHttpRequest();
    // 非同期通信でURLをセット
    xhr.open('GET', url, true);
    // 通信状態が変化したときのイベント
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4) {
            if(xhr.status == 200) {
                callback(xhr, xhr.responseText);
            }
        }
    };
    xhr.send('');
    return xhr;
}
 
 
// 任意のIDを得る
function $(id) {
    return document.getElementById(id);
}
