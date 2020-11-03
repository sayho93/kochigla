<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
    <script>
        $(document).ready(function(){

        });
    </script>
    <!-- Main -->
    <section id="one" class="wrapper style2" style="">
        <div class="content">
            <div class="row gtr-uniform gtr-50">
                <div class="col-10 col-12-xsmall  col-8-medium">
                    <input type="text" id="bannerSearch" placeholder="무엇이든지 찾아보세요!" />
                </div>
                <div class="col-2 col-12-xsmall col-4-medium">
                    <a href="#" class="fit primary button icon fa-search">찾기</a>
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