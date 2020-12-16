<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
    if(!AuthUtil::isLoggedIn()){
        echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
    }
?>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>

        $(document).ready(function(){
            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/mygift/ajaxPages/ajaxTripList.php",
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
                location.href = "searchDetail.php?id=" + id + "&type=my&applierId=" + $(this).attr("applierId");
            });

            $(document).on("click", ".jAd", () => {
                window.open("https://www.youtube.com/watch?v=SvqWhMzSHgA");
            })

            $(document).on("click", ".jAd2", () => {
                window.open("https://youtu.be/EG4HOTLELLk");
            })
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