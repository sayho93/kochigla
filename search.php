<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
    <script>
        $(document).ready(function(){
            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/mygift/ajaxPages/ajaxSearchList.php",
                    {
                        page : page,
                        query : "<?=$_REQUEST["query"]?>",
                        type: 1
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

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("noticeID");
                location.href = "searchDetail.php?id=" + id;
            });

            $(".jSearch").click(function(){
                doSearch();
            });

            $("#bannerSearch").keydown(function(key) {
                if(key.keyCode == 13) doSearch();

            });

            function doSearch(){
                location.href = "/mygift/search.php?query=" + $("#bannerSearch").val();
            }
        });
    </script>

    <section id="one" class="wrapper style2" style="">
        <div class="content">
            <div class="row gtr-uniform gtr-50">
                <div class="col-10 col-12-xsmall  col-8-medium">
                    <input type="text" id="bannerSearch" placeholder="무엇이든지 찾아보세요!" value="<?=$_REQUEST["query"]?>"/>
                </div>
                <div class="col-2 col-12-xsmall col-4-medium">
                    <a href="#" class="fit primary button icon fa-search jSearch">찾기</a>
                </div>
                <?if(!AuthUtil::isLoggedIn()){?>
                    <div class="col-12 col-12-xsmall">
                        <a href="join.php" class="button icon fa-pencil small">간편 회원가입</a>
                    </div>
                <?}else{?>
                    <div class="col-12 col-12-xsmall">
                        <a href="register.php" class="button icon fa-user small">동행 구하기</a>
                        <a href="myList.php" class="button icon fa-list small">내 동행 목록</a>
                    </div>
                <?}?>
            </div>
        </div>
    </section>

    <div id="main" class="wrapper style1">
        <div class="container">
            <section id="content">
                <div class="col-12 jContainer">

                </div>
                <div class="col-12 align-center">
                    <a href="#" class="jLoadMore button icon fa-spinner small">더보기</a>
                </div>
            </section>
        </div>
    </div>


<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>