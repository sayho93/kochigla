<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<?
    $categoryId = $_REQUEST["categoryId"] == "" ? "0" : $_REQUEST["categoryId"];
    $displayName = "모아보기";

    $category = "";
    $categoryRoute = new StoreRoute();

    if($categoryId > 0){
        $category = $categoryRoute->getUniv($categoryId);
        $displayName = $category["title"]."(".$category["campusType"].")";
    }else if($categoryId == -1){
        $displayName = "학교를 선택하세요";
    }

?>
    <style>
        .jFeedItem{
            padding:0;
            font-size:13px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .jFeedItemPadding{
            padding: 5px 10px;
        }
    </style>
<?if($categoryId > -1){?>
    <script>
        $(document).ready(function(){

            $(".jRefresh").click(function(){ location.reload(); });

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/mygift/ajaxPages/ajaxNewsList.php",
                    {
                        page : page,
                        userId : "",
                        categoryId : "<?=$categoryId?>"
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

            $(document).on("click", ".jDelThis", function(){
                deleteArticle($(this).attr("nid"));
            });

            $(".jSend").click(function(){
                writeArticle();
            });

            function writeArticle(){
                var categoryId = "<?=$categoryId?>";
                var message = $("#message").val();
                if(message.trim() == ""){
                    swal ( "알림" ,  "내용을 입력하세요.", "info" );
                    return;
                }

                callJson(
                    "/mygift/shared/public/route.php?F=WebRoute.writeNews",
                    {
                        categoryId : categoryId,
                        message : message
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            location.reload();
                        }else{
                            swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                        }
                    }
                );
            }

            function deleteArticle(id){
                callJson(
                    "/mygift/shared/public/route.php?F=WebRoute.deleteNews",
                    {
                        id : id
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            location.reload();
                        }else{
                            swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                        }
                    }
                );
            }

        });
    </script>
    <?}else{?>
<script>
    $(document).ready(function(){
        $(document).on("click", ".jDetail", function(){
            var id = $(this).attr("uid");
            location.href = "news.php?categoryId=" + id;
        });
    });
</script>
    <?}?>
    <section id="five" class="wrapper style2 special fade" style="padding:1.5em 1.5em 0.5em 1.5em;">
        <div class="container">
            <div class="col-12 align-left">
                <h5><i class="icon fa-list"></i> 뉴스피드 |&nbsp;
                    <?=$displayName?>
                    <?if($categoryId > -1 && $categoryId != 0){?>
                        &nbsp;<a class="button small" href="news.php?categoryId=-1" >변경</a>
                    <?}?>
                </h5>
            </div>
            <?if($categoryId > -1){?>
            <div class="col-12">
                <?
                $pHolder = "로그인이 필요한 서비스입니다.";
                if(AuthUtil::isLoggedIn()){
                    $pHolder = "하고 싶은 말이 있나요?";
                }
                ?>
                <textarea name="message" id="message" placeholder="<?=$pHolder?>" rows="3" <?=AuthUtil::isLoggedIn() ? "" : "DISABLED"?>></textarea>
            </div>
            <br/>
            <div class="col-12 align-right">
                <ul class="actions">
                    <?
                    if(AuthUtil::isLoggedIn()){
                        ?>
                        <li><a href="#" class="fit jSend primary button icon fa-pencil small">등록하기</a></li>
                    <?}?>
                    <li><a href="#" class="fit jRefresh button icon fa-refresh small">새로고침</a></li>
                </ul>
            </div>
            <?}?>
        </div>
    </section>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
                        <?if($categoryId > -1){?>
						<!-- Content -->
							<section id="content">
                                <div class="col-12 jContainer">

                                </div>
                                <div class="col-12 align-center">
                                    <a href="#" class="jLoadMore button icon fa-spinner small">더보기</a>
                                </div>
							</section>
                        <?}else{?>
                            <? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/selection_u.php"; ?>
                        <?}?>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>