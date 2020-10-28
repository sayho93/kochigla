<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}
?>
    <script>
        $(document).ready(function(){
            $(".jRefresh").click(function(){ location.reload(); });
            $(".jCharge").click(function(){
                var price = $("#price").val();
                var uName = "<?=AuthUtil::getLoggedInfo()->name?>";
                var userEmail = "<?=AuthUtil::getLoggedInfo()->email?>";
                var userAddr = "<?="미입력"?>";
                var userPhone = "<?=AuthUtil::getLoggedInfo()->phone?>";

                if(price == ""){
                    swal ( "알림" ,  "충전할 금액을 선택하세요.", "info" );
                    return;
                }
                var unique = $("#price option:selected").attr("pUniq");
                var pName = "포인트 " + unique;

                callJson(
                    "/mygift/shared/public/route.php?F=PayRoute.getPayUnique",
                    {
                        unique : unique,
                        price : price
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            var orderId = data.data;
                            requestPayment(orderId, unique, price, pName, uName, userEmail, userAddr, userPhone);
                        }else{
                            swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                        }
                    }
                );

            });

            /*
            * Load
            * */
            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/mygift/ajaxPages/ajaxPayList.php",
                    {
                        page : page,
                        query : "<?=$_REQUEST["query"]?>"
                    },
                    ".jContainer",
                    true,
                    function(){
                        isFinal = true;
                        currentPage--;
                        $(".jLoadMore").hide();
                    }
                );
            }

            loadMore(currentPage);

            $(".jLoadMore").click(function(e){
                e.preventDefault();
                loadMore(++currentPage);
            });
        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>나의 포인트 관리</h2>
                            <p><i class="icon fa-database"></i> 잔여 포인트 : <?=$balance?>P</p>
						</header>

						<!-- Content -->
							<section id="content">
                                <div class="row gtr-uniform gtr-50">
                                    <div class="col-12">
                                        <p>포인트 충전</p>
                                        <select name="price" id="price">
                                            <option value="">충전할 금액 선택</option>
                                            <option value="2400" pUniq="2000">2,000원(수수료20.0%)</option>
                                            <option value="6000" pUniq="5000">5,000원(수수료20.0%)</option>
                                            <option value="12000" pUniq="10000">10,000원(수수료20.0%)</option>
                                            <option value="23000" pUniq="20000">20,000원(수수료15.0%)</option>
                                            <option value="33750" pUniq="30000">30,000원(수수료12.5%)</option>
                                            <option value="55000" pUniq="50000">50,000원(수수료10.0%)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 align-left">
                                        <a href="#" class="jCharge button primary icon fa-database small">충전하기</a>
                                        <a href="#" class="jRefresh button icon fa-refresh small">새로고침</a>
                                        <hr class="col-12"/>
                                    </div>
                                    <div class="col-12">
                                        <p>포인트 거래내역</p>
                                        <div class="col-12 jContainer">

                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jLoadMore button icon fa-spinner small">더보기</a>
                                        </div>
                                    </div>
                                    <div class="col-12 align-center">

                                    </div>
                                </div>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>