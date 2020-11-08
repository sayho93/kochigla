<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/UserAuthRoute.php"; ?>
<?
    $route = new UserAuthRoute();
    $API_PATH = $route->PF_API;
    $URL = $route->PF_URL;
    if(AuthUtil::isLoggedIn()){
        echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";

    }
?>
    <script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.1.js" charset="utf-8"></script>
    <script>
        $(document).ready(function(){
            $(".jInputLogin").keydown((event) => {
                if(event.keyCode === 13) doLogin(event);
            });

            $(".jLogin").click((event) => {
                doLogin(event);
            });

            function doLogin(event){
                event.preventDefault();
                if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                    Swal.fire("info" ,  "회원 정보를 입력하세요.", "error");
                    return;
                }
                callJson("<?=$API_PATH?>UserAuthRoute.requestLogin", {
                        email : $(".jEmailTxt").val(),
                        pwd : $(".jPasswordTxt").val()
                    }, (data) => {
                        if(data.returnCode > 0){
                            if(data.returnCode > 1) Swal.fire( "알림" ,  data.returnMessage, "info" );
                            else location.href = "index.php";
                        }
                        else Swal.fire( "info" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                    }
                )
            }

            var naverLogin = new naver.LoginWithNaverId({
                    clientId: "geLGWRn0PuvRYhy3Pm0X",
                    callbackUrl: "<?=$URL?>/login.php",
                    isPopup: false,
                    loginButton: {color: "green", type: 3, height: 45},
                    callbackHandle: true
            });
            naverLogin.init();

            window.addEventListener('load', function(){
                naverLogin.getLoginStatus(function(status){
                    if (status){
                        var email = naverLogin.user.getEmail();
                        var name  = naverLogin.user.getName();
                        var oAuthId = naverLogin.user.getId();
                        var accessToken = naverLogin.accessToken;
                        if( name == undefined || name == null){
                            Swal.fire("info", "이름은 필수정보입니다. 정보제공을 동의해주세요.", "error").then(() => {
                                naverLogin.reprompt();
                            })
                            return;
                        }

                        callJson("<?=$API_PATH?>UserAuthRoute.checkUser", {
                                oAuthId : oAuthId,
                            }, function(data){
                                if(data.returnCode === 1){
                                    Swal.fire({
                                        title: "info",
                                        text: "가입되지 않은 계정입니다. 가입을 진행하시겠습니까?",
                                        icon: "info",
                                        buttons: ['Cancel', 'Ok'],
                                        dangerMode: true,
                                    }).then((isConfirm) => {
                                        if(isConfirm) oAuthJoin();
                                    })
                                }
                                else doNALogin(oAuthId, accessToken);
                            }
                        )
                    }else{
                        console.log("auth expired");
                    }
                });
            });

            function oAuthJoin(){
                var oAuthId = naverLogin.user.getId();
                var name  = naverLogin.user.getName();
                var profileImage = naverLogin.user.getProfileImage();
                var birthday = naverLogin.user.getBirthday();
                var uniqId = naverLogin.user.getId();
                var gender = naverLogin.user.getGender() === "M" ? 1: 0;
                var accessToken = naverLogin.accessToken;
                var age = naverLogin.user.getAge();

                callJson("<?=$API_PATH?>UserAuthRoute.joinUser", {
                        oAuthId: oAuthId,
                        name: name,
                        sex: gender,
                        from: "NA",
                        "accessToken": accessToken.accessToken,
                    }, function(data){
                        if(data.returnCode > 1){}
                        else{
                            Swal.fire({
                                title: "알림",
                                text: data.returnMessage,
                                icon: "success",
                                closeOnClickOutside: false,
                            }).then((result) => {
                                if(result) doNALogin(oAuthId, accessToken);
                            });
                        }
                    }
                )
            }

            function doNALogin(oAuthId, accessToken){
                callJson("<?=$API_PATH?>UserAuthRoute.requestNALogin", {
                        id: oAuthId,
                        accessToken: accessToken.accessToken
                    }, (data) => {
                        if(data.returnCode === 1) location.href = "/mygift";
                        else Swal.fire("info" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error");
                    }
                )
            }
        });
    </script>

				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>회원 로그인</h2>
						</header>

							<section id="content">
                                <form method="post" action="#">
                                    <div class="row gtr-uniform gtr-50">
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jInputLogin jEmailTxt" type="email" name="email" id="email" value="" placeholder="이메일" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <input class="jInputLogin jPasswordTxt" type="password" name="password" id="password" value="" placeholder="패스워드" />
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jLogin button primary icon fa-sign-in small">이메일로 로그인</a>
                                            <a href="join.php" class="button icon fa-edit small">회원가입</a>
                                        </div>
                                        <div class="col-12 col-12-xsmall align-center">
                                            <div id="naverIdLogin"></div>
<!--                                            <a href="#" class="facebook button icon small fa-facebook">Facebook으로 로그인</a>-->
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>