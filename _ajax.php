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

require_once(__DIR__ . '/iine.php');

$iineApp = new iine();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    if (!isset($_POST['mode'])) {
      throw new \Exception('mode not set!');
    }

    switch ($_POST['mode']) {
      case 'check':
        // URLを丸めてから渡す
        $postPath = $iineApp->checkURL($_POST['path']);
        return $iineApp->iineCount($postPath);
      case 'uncheck':
        // URLを丸めてから渡す
        $postPath = $iineApp->checkURL($_POST['path']);
        return $iineApp->iineUncheck($postPath);
    }
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
} else {
  try {
    if (!isset($_GET['mode'])) {
      throw new \Exception('mode not set!');
    }
      // URLを丸めてから渡す
      $getPath = $iineApp->checkURL($_GET['path']);
      return $iineApp->showCount($getPath);
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
}
