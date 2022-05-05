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
            $conn = "host=ec2-52-86-56-90.compute-1.amazonaws.com dbname=d2iqphapr6gh21 user=jhhlglwtokdznp password=709cb5ce0d25b460d4a517315df49c1be396b507b30a868565d93057b8da46e8";
            $link = pg_connect($conn);
            echo pg_query("SELECT schemaname, tablename from pg_tables");
            if (!$link) {
                die('接続失敗です。' . pg_last_error());
            }
            // PostgreSQLに対する処理
            $result = pg_query("SELECT * FROM car_data");
            $item_count = pg_query("SELECT COUNT (*) FROM item WHERE item_name LIKE '%$keyword%' AND item_status is true");
            while ($row_count = pg_fetch_array($item_count)) {
                $count = $row_count['count'];
                print "<p class=count_result>検索結果は'$count'件です</p>";
            }
            print "<div class=space></div>";
            print "<ul class=cardUnit>";
            while ($row = pg_fetch_array($result)) {
                $item_id = $row['item_id'];
                print "<li class=card>";
                print "<a href=detail.php?res_name=$item_id>";
                $pic = $row['item_pic'];
                $name = $row['item_name'];
                $price = $row['item_price'];
                print "<img src=$pic alt=>";
                print "<p>商品名 $name</p>";
                print "<p>価格 $price</p>";
                print "</a>";
                print "</li>";
            }
            print "</ul>";
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