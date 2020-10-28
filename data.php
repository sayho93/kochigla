<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}
?>
    <script>
        $(document).ready(function(){
            $(".jLogin").click(function(){
                if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                    alert("회원 정보를 입력하세요.");
                    return;
                }
                callJson(
                    "/mygift/shared/public/route.php?F=UserAuthRoute.requestLogin",
                    {
                        email : $(".jEmailTxt").val(),
                        pwd : $(".jPasswordTxt").val()
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            if(data.returnCode > 1){
                                alert(data.returnMessage);
                            }else{
                                location.href = "index.php";
                            }
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
							<h2>회원 로그인</h2>
						</header>

						<!-- Content -->
							<section id="content">
                                <form method="post" action="#">
                                    <div class="row gtr-uniform gtr-50">
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jEmailTxt" type="email" name="email" id="email" value="" placeholder="이메일" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jPasswordTxt" type="password" name="password" id="password" value="" placeholder="패스워드" />
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jLogin button primary icon fa-sign-in small">이메일로 로그인</a>
                                            <a href="join.php" class="button icon fa-edit small">회원가입</a>
                                        </div>
                                        <div class="col-12 col-12-xsmall align-center">
                                            <a href="#" class="facebook button icon small fa-facebook">Facebook으로 로그인</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>