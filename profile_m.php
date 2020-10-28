<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}
?>
    <script>
        $(document).ready(function(){

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("uid");
                callJson(
                    "/mygift/shared/public/route.php?F=UnivRoute.modifyDept",
                    {
                        deptId : id
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            callJson(
                                "/mygift/shared/public/route.php?F=UserAuthRoute.invalidate",
                                {}
                                , function(data){
                                    if(data.returnCode > 0){
                                        location.href="profile.php";
                                    }else{
                                        alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                                    }
                                }
                            )
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                )
            });

        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>마이페이지</h2>
                            <p>학과/전공 선택<br/> <a class="button small jDetail" uid="0" href="#" >선택 안함</a></p>
						</header>

                        <? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/selection_m.php"; ?>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>