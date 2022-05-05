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

        <form action="result.php" method="GET" class="search_cartype" name="serch_form" onsubmit="return Check()">

            <div class="row mb-2">
                <label for="formControlInput" class="col-sm-3 form-label">メーカー：</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="formControlInput" placeholder="" name="maker" pattern= "[^#&?=%\+_'.,]+">
                </div>
            </div>

            <div class="row mb-2">
                <label for="formControlInput" class="col-sm-3 form-label">車種：</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="formControlInput" placeholder="" name="cartype" pattern= "[^#&?=%\+_'.,]+">
                </div>
            </div>

            <div class="row mb-2">
                <label for="formControlInput" class="col-sm-3 form-label">走行距離：</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="formControlInput" placeholder="" name="distance" pattern= "[^#&?=%\+_'.,]+">
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-lg d-grid gap-2 col-6 mx-auto">検索</button>
            <div class="space"></div>
        </form>
    </div>


    <div class="space_f"></div>
    <div class="footer">
        <p class="copy_right">©yutasato & yukioda</p>
    </div>
    <!--ここまで-->

    <body>