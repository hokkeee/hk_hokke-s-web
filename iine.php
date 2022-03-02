<?php

///////////////////////////////////////////////////
// 個人サイト向けいいねボタンプログラム Ver1.1.1
// 製作者    ：ガタガタ
// サイト    ：https://do.gt-gt.org/
// ライセンス：MITライセンス
// 全文      ：https://ja.osdn.net/projects/opensource/wiki/licenses%2FMIT_license
// 公開日    ：2020.08.21
// 最終更新日：2020.11.09
//
// このプログラムはどなたでも無償で利用・複製・変更・
// 再配布および複製物を販売することができます。
// ただし、上記著作権表示ならびに同意意志を、
// このファイルから削除しないでください。
///////////////////////////////////////////////////

$include = get_included_files();
if (array_shift($include) === __FILE__) {
    die('このファイルへの直接のアクセスは禁止されています。');
}

class iine {

  private $csv;
  private $time;

  	// コンストラクタ宣言
  	public function __construct() {

      $this->csv = 'data.csv';

      date_default_timezone_set('Asia/Tokyo');
      $this->time = date("Y/m/d-H:i:s");
  	}

    // URL名がindex.htmlもしくはindex.phpで終わる場合はURLを丸める
    public function checkURL($url) {
      $filenames = array('index.html', 'index.php');
      foreach ($filenames as $filename) {
        if (strpos($url, $filename) !== false) {
          $url = rtrim($url, $filename);
        }
      }
      return $url;
    }

    // PHP5.5以下でもarray_columnに相当する関数を使う
    public function check_column ($target_data, $column_key, $index_key = null) {
      if (is_array($target_data) === FALSE || count($target_data) === 0) return array(false,false,false);

      $result = array();
      foreach ($target_data as $array) {
        if (array_key_exists($column_key, $array) === FALSE) continue;
        if (is_null($index_key) === FALSE && array_key_exists($index_key, $array) === TRUE) {
          $result[$array[$index_key]] = $array[$column_key];
          continue;
        }
        $result[] = $array[$column_key];
      }

      if (count($result) === 0) return array(false,false,false);
      return $result;
    }

    // CSVを開いて当該URLに関するデータを引っ張り出す関数
    private function openCSV($url) {
      $fp = fopen($this->csv,"r");
      $csvArray = array();

      // CSVからデータを取得し二次元配列に変換する
      $row = 0;
      while( $ret_csv = fgetcsv( $fp, 0 ) ){
        for($col = 0; $col < count( $ret_csv ); $col++ ){
          $csvArray[$row][$col] = $ret_csv[$col];
        }
        $row++;
      }
      fclose($fp);

      $checkURL = $url;

      // データがある場合は、取得した二次元配列から、リクエストの飛んできたページのデータを探す
      $num = array_search($checkURL, $this->check_column($csvArray, 0), true);
      return array($num, $csvArray, $checkURL);
    }

    // CSVファイルに二次元配列を上書きする関数
    private function rewriteCSV($datas) {
      $fp = fopen($this->csv, 'w');

      // 二次元配列を１行ずつCSV形式に直して書き込む
      foreach ($datas as $v) {
        $line = implode(',' , $v);
        fwrite($fp, $line . "\n");
      }

      // ファイルを閉じる
      fclose($fp);
    }

    // アクセスされたページのいいね数を表示する
    public function showCount($url) {
      // 当該データを引っ張り出す
      list($num, $csvArray, $checkURL) = $this->openCSV($url);

      if ($num === false) {
        $count = 0;
      } else {
        $count = $csvArray[$num][2];
      }

      // とってきたいいね数を返す
      echo $count;
    }

    // いいね数を増やす関数
    public function iineCount($postPath) {
      // 当該データを引っ張り出す
      list($num, $csvArray, $checkURL) = $this->openCSV($postPath);

      if($num === false) {
        // まだいいねを押されたことがない場合、CSVに新たな行を追加する
        $data = array($postPath, $this->time, 1);
        $fp = fopen($this->csv, 'a');
        $line = implode(',' , $data);
        fwrite($fp, $line . "\n");
        fclose($fp);
        echo '1';
      } else {
        // それ以外の場合は、CSVの該当する行のカウント数を１増やして上書き
        $count = $csvArray[$num][2] + 1;
        $addArray = array(array($checkURL, $this->time, $count));
        array_splice($csvArray, $num, 1, $addArray);

        $this->rewriteCSV($csvArray);
        echo $count;
      }

    }

    // いいね数を減らす関数
    public function iineUncheck($postPath) {
      // 当該データを引っ張り出す
      list($num, $csvArray, $checkURL) = $this->openCSV($postPath);

      // 当該URLに記録がない場合、何もせずに処理を終える
      if($num === false) {
        return;
      }

      // カウント数を１減らして二次元配列に上書きし、新しい数値を返す
      $count = $csvArray[$num][2] - 1;
      $addArray = array(array($checkURL, $this->time, $count));
      array_splice($csvArray, $num, 1, $addArray);

      $this->rewriteCSV($csvArray);
      echo $count;
    }

} // end class iine

 ?>
