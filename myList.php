<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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
                        type: 1,
                        user: true
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
                location.href = "register.php?id=" + id;
            });
        });
    </script>

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