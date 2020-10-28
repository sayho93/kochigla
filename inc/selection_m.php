 <script>
        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;
            var query = "";

            function loadMore(page){
                loadPageInto(
                    "/mygift/ajaxPages/ajaxMajorList.php",
                    {
                        page : page,
                        query : query
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

            $(".jSearch").click(function(){
                var searchText = $(".jSearchTxt").val();
                $(".jLoadMore").show();
                $(".jContainer").html("");
                $(".jRec").html("");
                query = searchText;
                currentPage = 1;
                isFinal = false;
                loadMore(currentPage);
            });

            $(document).on("click", ".jRecList", function(){
                $(".jSearchTxt").val($(this).html());
                $(".jRec").html("");
            });

            $(".jSearchTxt").keyup(function(){
                if($(this).val().trim() == ""){
                    $(".jRec").html("");
                    return;
                }
                callJsonIgnoreError(
                    "/mygift/ajaxPages/ajaxRecommendation.php",
                    {
                        key : $(this).val(),
                        table : "tblDept",
                        col : "deptName"
                    },
                    function(data){
                        console.log(data);
                        var html = "";
                        for(var w = 0; w < data.length; w++){
                            html += "<div class='jRecList'>" + data[w] + "</div>";
                        }
                        $(".jRec").html(html);
                    }
                );
            });

        });
    </script>
 <style>
     .jRecList{
         font-size: 12px;
         border-radius: 3px;
         border: 1px solid #292929; padding:5px; margin-bottom: 2px;
     }
 </style>
 <input type="hidden" id="univValue" class="univValue" name="univValue" value="" />

 <section id="content">
     <div class="row gtr-uniform gtr-50">
         <div class="col-9 col-12-xsmall">
             <input type="text" class="jSearchTxt" placeholder="학과/전공명 검색" value="<?=$_REQUEST["query"]?>" />
         </div>
         <div class="col-3 col-12-xsmall">
             <a href="#" class="fit primary button icon fa-search jSearch">검색</a>
         </div>
         <div class="blog-comments recommend jRec col-12">
         </div>
         <div class="col-12 col-12-xsmall jContainer">

         </div>
         <div class="col-12 align-center">
             <a href="#" class="jLoadMore button icon fa-spinner small">더보기</a>
         </div>
     </div> <!-- end row -->
 </section>
