<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?

    $requestId = $_REQUEST["id"];


?>
    <script>
        $(document).ready(function(){

        });
    </script>
    <!-- Main -->

    <section id="five" class="wrapper style2 special fade" style="padding:1.5em 1.5em;">
        <div class="container">
            <div class="col-12 align-left">
                <h5><i class="icon fa-search"></i> 작품검색</h5>
            </div>
            <div class="row gtr-uniform gtr-50">
                <div class="col-9 col-12-xsmall"><input type="text" class="jSearchText" placeholder="무엇이든지 찾아보세요!" /></div>
                <div class="col-3 col-12-xsmall"><a href="#" class="fit primary button icon fa-search">찾기</a></div>
            </div>
            dd
        </div>
    </section>

    <!-- Content -->
    <section id="content" class="wrapper">
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

    <!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>