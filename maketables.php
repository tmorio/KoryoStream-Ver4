<?php
require_once('./backend/myid.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        echo "DBサーバーとの接続を確立しました。\n";
     } catch (PDOException $e) {
        echo "DBサーバーへの接続に失敗しました。myid.phpが正しく記入されているかご確認下さい。\n";
        exit();
     }

try {
        echo "テーブルの作成を行なっています...\n";
        $sql = "CREATE TABLE Data(ID int(11) null AUTO_INCREMENT PRIMARY KEY, USER_NAME text null, USER_ID text null, USER_ICON text null, TEXT text null, MEDIA text null, VIDEO tinyint(1) null";
        $stmt = $pdo->query($sql);
        echo "データセットの準備を行なっています...\n";
        $sql = "INSERT INTO `Data` (`ID`, `USER_NAME`, `USER_ID`, `USER_ICON`, `TEXT`, `MEDIA`, `VIDEO`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $counter = 0;
        while ($counter < 5) {
                $stmt = $pdo->query($sql);
                $counter++;
        }
        echo "DBサーバーとの接続を解除しています...\n";
        $pdo = null;
        echo "初期設定が完了しました。「rm maketables.php」でこのファイルを削除して下さい。\n";
    } catch (PDOException $e) {
        echo "エラーが発生しました。データーベースへの書き込み権限があるかご確認の上、テーブルを削除し再度お試し下さい。\n";
        $pdo = null;
        }

?>