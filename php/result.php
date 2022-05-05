<!DOCTYPE html>
<html lang="ja">
<header>
    <meta name="description" content="燃料代計算">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/css_home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Luxurious+Roman&display=swap" rel="stylesheet">
    <title>ホーム</title>
</header>

<body>
    <script src="../bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <!--ここから-->

    <div class="title">燃料計算サイト</div>

    <div class="price_gasoline">
        <div class="price_gasoline_title">ガソリン現在価格<br></div>
        <?php
        require_once("./phpQuery-onefile.php");
        $html = file_get_contents("https://gogo.gs/");
        $doc = phpQuery::newDocument($html);
        echo "ハイオク : ";
        echo pq(".price:eq(1)")->text();
        echo "円</br>";
        echo "レギュラー : ";
        echo pq(".price:eq(0)")->text();
        echo "円</br>";
        echo "軽油 : ";
        echo pq(".price:eq(2)")->text();
        echo "円</br>";
        ?>
    </div>

    <div class="search">
        <div class="search_title">車種検索</br></div>
        <?php
        //テストエリア
        //ここまで 
        $maker = $_GET['maker'];
        $cartype = $_GET['cartype'];
        $distance = $_GET['distance'];

        try {
            $conn = "host=ec2-34-227-120-79.compute-1.amazonaws.com dbname=d179rc1nokp593 user=vcfevfhdquolnz password=283ce561335e66e891dce180f042308031f6e8be2942f44332025848f21e20e1
            ";
            $link = pg_connect($conn);
            if (!$link) {
                die('接続失敗です。' . pg_last_error());
            }
            // PostgreSQLに対する処理
            $result = pg_query("SELECT * FROM car_data");
            while ($row = pg_fetch_array($result)) {
                $count = $row['car'];
                print "<p>$count</p>";
            
            }
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
        ?>


        <!--ここまで-->
    </div>
    <div class="space_f"></div>
    <div class="footer">
        <p class="copy_right">©yutasato & yukioda</p>
    </div>


</body>