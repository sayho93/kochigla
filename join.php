<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
if(AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}
?>
    <script>
        $(document).ready(function(){
            $(".jJoin").click(function(){
                var rec = grecaptcha.getResponse();
                var jPrC = $(".jPr").prop("checked");
                var jAgC = $(".jAg").prop("checked");

                if($(".jEmailTxt").val() == ""
                    || $(".jPhoneTxt").val() == ""
                    || $(".jNameTxt").val() == ""
                    || $(".jPasswordTxt").val() == ""
                    ){
                    swal ( "알림" ,  "회원 정보를 모두 입력하세요.", "error" );
                    return;
                }
                if($(".jPasswordTxt").val() != $(".jPasswordCTxt").val()){
                    swal ( "알림" ,  "패스워드 확인이 일치하지 않습니다.", "error" );
                    return;
                }

                if(!jPrC){
                    swal ( "알림" ,  "개인정보처리방침에 동의하시기 바랍니다.", "error" );
                    return;
                }

                if(!jAgC){
                    swal ( "알림" ,  "서비스 이용 약관에 동의하시기 바랍니다.", "error" );
                    return;
                }

                if(rec == ""){
                    swal ( "알림" ,  "reCAPTCHA 인증을 완료하시기 바랍니다.", "error" );
                    return;
                }

                callJson(
                    "/mygift/shared/public/route.php?F=UserAuthRoute.joinUser",
                    {
                        email : $(".jEmailTxt").val(),
                        pwd : $(".jPasswordTxt").val(),
                        phone : $(".jPhoneTxt").val(),
                        name : $(".jNameTxt").val(),
                        from : 'N',
                        recaptcha : rec
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.href = "index.php";
                            }
                        }else{
                            swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
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
							<h2>회원 가입</h2>
						</header>

						<!-- Content -->
							<section id="content">
                                <form method="post" action="#">
                                    <div class="row gtr-uniform gtr-50">
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jNameTxt">성명</label>
                                            <input class="jNameTxt" type="text" placeholder="성명" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jEmailTxt">이메일</label>
                                            <input class="jEmailTxt" type="email" placeholder="이메일" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPhoneTxt">휴대전화 ('-' 없이 입력)</label>
                                            <input class="jPhoneTxt" type="text" placeholder="휴대전화" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPasswordTxt">패스워드</label>
                                            <input class="jPasswordTxt" type="password" placeholder="패스워드" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPasswordCTxt">패스워드 확인</label>
                                            <input class="jPasswordCTxt" type="password" placeholder="패스워드 확인" />
                                        </div>
                                        <div class="col-6 col-12-medium">
                                            <input type="checkbox" id="jPr" class="jPr" name="jPr">
                                            <label for="jPr"><a href="#">개인정보처리방침</a>에 동의합니다.</label>
                                        </div>
                                        <div class="col-6 col-12-medium">
                                            <input type="checkbox" id="jAg" class="jAg" name="jAg">
                                            <label for="jAg">본 서비스 이용에 따른 <a href="#">회원 약관</a>에 동의합니다.</label>
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <div class="g-recaptcha" data-sitekey="<?=REC_SITE_KEY?>"></div>
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jJoin button icon fa-edit small">가입하기</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>