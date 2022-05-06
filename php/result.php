<!DOCTYPE html>
<html lang="ja">
<header>
    <meta name="description" content="燃料代計算">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/css_result.css">
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
        $high_octane = pq(".price:eq(1)")->text();
        $regular = pq(".price:eq(0)")->text();
        $diesel = pq(".price:eq(2)")->text();
        echo "ハイオク : ";
        echo $high_octane;
        echo "円</br>";
        echo "レギュラー : ";
        echo $regular;
        echo "円</br>";
        echo "軽油 : ";
        echo $diesel;
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
        if (!$maker && !$cartype && !$distance) {
            echo "<div class=\"error_message\">エラー：メーカー名，車種名，走行距離を入力してください。</br></div>";
        } elseif (!$maker && !$cartype) {
            echo "<div class=\"error_message\">エラー：メーカー名，車種名を入力してください。</div>";
        } elseif (!$distance) {
            echo "<div class=\"error_message\">エラー：走行距離を入力してください。</div>";
        } else {
            try {
                $conn = "host=ec2-34-227-120-79.compute-1.amazonaws.com dbname=d179rc1nokp593 user=vcfevfhdquolnz password=283ce561335e66e891dce180f042308031f6e8be2942f44332025848f21e20e1";
                $link = pg_connect($conn);
                if (!$link) {
                    die('接続失敗です。' . pg_last_error());
                }
                // PostgreSQLに対する処理
                if (!$cartype) {
                    $result = pg_query("SELECT * FROM car_data WHERE maker LIKE '%$maker%'");
                    $row = pg_fetch_array($result);
                    if ($row == 0) {
                        echo "<div class=\"error_message\">エラー：\"${maker}\"がデータベースに存在しません。</div>";
                    } else {
                        echo "<div class=\"error_message\">エラー：車種名を入力してください。</div></br>";
                        echo "<div class=\"result_title\">${maker}の車種一覧</div>";
                        echo "<div class=\"row row-cols-3 result\">";
                        while ($row2 = pg_fetch_array($result)) {
                            $count = $row2['car'];
                            print "<div class=\"col\">$count</div>";
                        }
                        echo "</div>";
                    }
                } elseif (!$maker) {
                    $result = pg_query("SELECT * FROM car_data WHERE car LIKE '%$cartype%'");
                    $row = pg_fetch_array($result);
                    if ($row == 0) {
                        echo "<div class=\"error_message\">エラー：\"${cartype}\"がデータベースに存在しません。</div>";
                    } elseif (count($row)>10) {
                        $duplicate = count($row)/10;
                        echo "<div class=\"error_message\">エラー：\"${cartype}\"がデータベースに複数(${duplicate})存在します。</br>メーカー名を入力してください。</div>";
                        echo "<div class=\"result_title\">${cartype}のメーカー名一覧</div>";
                        echo "<div class=\"row row-cols-3 result\">";
                        while ($row2 = pg_fetch_array($result)) {
                            $count = $row2['car'];
                            print "<div class=\"col\">$count</div>";
                        }
                        echo "</div>";
                    } else {
                        $maker_output = $row['maker'];
                        $car = $row['car'];
                        $ideal_fuel = $row['ideal_fuel'];
                        $actual_fuel = $row['actual_fuel'];
                        $fuel_kinds = $row['fuel_kinds'];
                        switch ($fuel_kinds) {
                            case "ハイオク":
                                $litre = $high_octane;
                                break;
                            case "レギュラー":
                                $litre = $regular;
                                break;
                            case "軽油":
                                $litre = $diesel;
                        }
                        $ideal_fuel_cost = round((float)$distance / $ideal_fuel * $litre, 1);
                        $actual_fuel_cost = round((float)$distance / $actual_fuel * $litre, 1);



                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">メーカー名</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${maker_output}</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">車種</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${car}</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">走行距離 km</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${distance}</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">理想燃費</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${ideal_fuel} km/L</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">実燃費</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${actual_fuel} km/L</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">油種</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${fuel_kinds}</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">理想燃費燃料代</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${ideal_fuel_cost} 円</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";

                        echo "<div class=\"row row-cols-9 result\">";
                        echo "<div class=\"col\"></div>";
                        echo "<div class=\"col-3\">実燃費燃料代</div>";
                        echo "<div class=\"col\">:</div>";
                        echo "<div class=\"col-3\">${actual_fuel_cost} 円</div>";
                        echo "<div class=\"col\"></div>";
                        echo "</div>";
                    }
                } else {
                    $result = pg_query("SELECT * FROM car_data WHERE car LIKE '%$cartype%' AND maker LIKE '%$maker%'");
                    $row = pg_fetch_array($result);
                    if ($row == 0) {
                        echo "<div class=\"error_message\">エラー：\"${maker}\"の\"${cartype}\"はデータベースに存在しません。</div>";
                    } else
                        $maker_output = $row['maker'];
                    $car = $row['car'];
                    $ideal_fuel = $row['ideal_fuel'];
                    $actual_fuel = $row['actual_fuel'];
                    $fuel_kinds = $row['fuel_kinds'];
                    switch ($fuel_kinds) {
                        case "ハイオク":
                            $litre = $high_octane;
                            break;
                        case "レギュラー":
                            $litre = $regular;
                            break;
                        case "軽油":
                            $litre = $diesel;
                    }
                    $ideal_fuel_cost = round((float)$distance / $ideal_fuel * $litre, 1);
                    $actual_fuel_cost = round((float)$distance / $actual_fuel * $litre, 1);

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">メーカー名</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${maker_output}</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">車種</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${car}</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">走行距離</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${distance} km</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">理想燃費</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${ideal_fuel} km/L</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">実燃費</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${actual_fuel} km/L</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">油種</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${fuel_kinds}</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">理想燃費燃料代</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${ideal_fuel_cost} 円</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";

                    echo "<div class=\"row row-cols-9 result\">";
                    echo "<div class=\"col\"></div>";
                    echo "<div class=\"col-3\">実燃費燃料代</div>";
                    echo "<div class=\"col\">:</div>";
                    echo "<div class=\"col-3\">${actual_fuel_cost} 円</div>";
                    echo "<div class=\"col\"></div>";
                    echo "</div>";
                }

                /*
                    $maker_output = $row['maker'];
                    $car = $row['car'];
                    $ideal_fuel = $row['ideal_fuel'];
                    $actual_fuel = $row['actual_fuel'];
                    $fuel_kinds = $row['fuel_kinds'];
                    $ideal_fuel_cost = $ideal_fuel*$distance;
                    $actual_fuel_cost = $actual_fuel*$distance;

                    print "<p>メーカー名 $maker_output</p>";
                    print "<p>車種 $car</p>";
                    print "<p>走行距離 $distance</p>";
                    print "<p>理想燃費 $ideal_fuel</p>";
                    print "<p>実燃費 $actual_fuel</p>";
                    print "<p>油種 $fuel_kinds</p>";
                    print "<p>理想燃費燃料代 $actual_fuel_cost</p>";
                    print "<p>実燃費燃料代 $actual_fuel_cost</p>";
                    */

                /*
                $result = pg_query("SELECT * FROM car_data");
                while ($row = pg_fetch_array($result)) {
                    $count = $row['car'];
                    print "<p>$count</p>";
                }
                */
            } catch (PDOException $e) {
                print('Error:' . $e->getMessage());
                die();
            }
        }
        ?>
        <div class="space"></div>
        <button type="button" onclick="location.href='home.php'" class="btn btn-success btn-lg d-grid gap-2 col-6 mx-auto">戻る</button>
        <div class="space_f"></div>

        <!--ここまで-->
    </div>

    <div class="footer">
        <p class="copy_right">©yutasato & yukioda</p>
    </div>


</body>