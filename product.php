<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
    $info = $storeRoute->getProductInfo();
?>
    <script type="text/babel">
        $(document).ready(() => {
            let value = '<?=$info["price"]?>';
            let setPrice = (cnt, price) => {
                $(".jPrice").empty();
                $(".jPrice").html((price * cnt).toLocaleString());
            };

            setPrice($(".jProductCnt").val(), value);

            $(".jProductCnt").change((element) => {
                setPrice(element.currentTarget.value, value);
            });
        });
    </script>

    <section id="content" class="wrapper container">
        <div class="row">
            <div class="col-6 col-12-xsmall align-center">
                <span class="image"><img src="images/pic01.jpg" alt="상품 이미지" /></span>
            </div>
            <div class="col-6 col-12-xsmall">
                <div class="col-3">
                    <header>
                        <h3><?=$info["name"]?></h3>
                        <h4><?=$info["subTitle"]?></h4>
                        <p>카테고리 / ㅇㅇㅇㅇ</p>
                    </header>

                    <hr/>

                    <div class="row gtr-uniform gtr-25">
                        <div class="col-3">
                            <p>판매가</p>
                            <p>적립</p>
                            <p>배송비</p>
                            <p>수량</p>
                        </div>
                        <div class="col-7">
                            <p><b><?=number_format($info["price"])?></b>원</p>
                            <p>1%</p>
                            <p>(조건) 배송비를 확인하세요</p>
                            <select class="jProductCnt">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>

                    <hr style="margin-bottom: 1em;"/>

                    <div class="align-center">
                        <h4 class="button small fit">총 상품 금액 <b class="text-danger"><strong class="jPrice"></strong></b> 원</h4>
                    </div>

                    <div class="row align-center">
                        <div class="col-12">
                            <ul class="actions fit" style="margin-bottom: 0;">
                                <li><h5 class="button primary fit icon fa-arrow-circle-right" style="margin-bottom: 0;">바로구매</h5></li>
                                <li><h5 class="naver button fit icon fa-arrow-circle-right">NPay 구매</h5></li>
                            </ul>
                            <ul class="actions fit">
                                <li><h5 class="button fit icon fa-shopping-cart" style="margin-bottom: 0;">장바구니</h5></li>
                                <li><h5 class="button fit icon fa-heart" style="margin-bottom: 0;">관심상품</h5></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <hr style="margin-bottom: 0;" />

    <section id="content" class="wrapper style1 special">
        <div class="container">
            <header class="major">
                <h2>상품 상세</h2>
            </header>
        </div>
    </section>

    <!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>