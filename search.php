<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
    <script>
        $(document).ready(function(){

        });
    </script>
    <!-- Main -->
    <section id="banner" class="ribbon fixedSmall" style="background-image: url('./images/main_icon.png');">
        <div class="content">
            <header>
                <h2>우리 일상 속 숨겨진 보물을 찾아보세요</h2>
                <p>당신이 기다리고 원하던 일상 속 아름다움을 찾고,<br />
                    소중한 당신의 작품을 손쉽게 선보이세요!</p>
            </header>
            <div class="container">
                <header>
                </header>
                <p>test</p>
            </div>
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
                        <a href="profile.php" class="button icon fa-user small">마이페이지</a>
                        <a href="data.php" class="button icon fa-list small">내 작품 목록</a>
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