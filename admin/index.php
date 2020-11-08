<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/AdminRoute.php"?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/UserAuthRoute.php"; ?>
<?
    $route = new AdminRoute();

    $uRote = new UserAuthRoute();
    $currentUser = $uRote->getUser(AuthUtil::getLoggedInfo()->id);
    if(!AuthUtil::isLoggedIn() && $currentUser["isAdmin"] == 1){
        echo "<script>location.href='/mygift/admin/pages';</script>";
    }
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>mygift Admin</title>
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/main_icon_li.png"/>
</head>

<script src="../js/sweetalert.min.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/AjaxUtil.js"></script>
<script src="../shared/lib/polyfill/babel.min.js"></script>
<script type="text/babel">
    $(document).ready(() => {
        $(".login-area").hide();
        $(".login-area").fadeIn();

        $(".jLogin").click(() => {
            if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                swal ( "알림" ,  "회원 정보를 입력하세요.", "error" );
                return;
            }
            callJson(
                "/mygift/shared/public/route.php?F=UserAuthRoute.requestLogin",
                {
                    email : $(".jEmailTxt").val(),
                    pwd : $(".jPasswordTxt").val()
                },
                (data) => {
                    if(data.returnCode > 0){
                        if(data.returnCode > 1) swal ( "알림" ,  data.returnMessage, "info" );
                        else location.href = "/mygift/admin/pages";
                    }else swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                }
            )
        });

        $('input').on("keydown", (event) => {
            if(event.keyCode == 13) $(".jLogin").trigger("click");
        });
    });
</script>

<body>
  <div class="container-scroller login-area">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo mb-0">
                <img class="float-right" src="images/icon_main_dark.png">
                <!--<img src="../../images/logo.svg">-->
              </div>
              <h4 class="float-right">Dashboard Login</h4>
              <form class="pt-3">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg jEmailTxt" id="exampleInputEmail1" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg jPasswordTxt" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="mt-3">
                  <a class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn jLogin">SIGN IN</a>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      자동 로그인
                    </label>
                  </div>
                </div>
                <div class="mb-2">
                  <button type="button" class="btn-block btn-sm btn-facebook auth-form-btn">
                    <i class="mdi mdi-facebook mr-2"></i>Facebook으로 로그인
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
</body>

</html>
