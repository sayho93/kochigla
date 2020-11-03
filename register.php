<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/PayRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/StoreRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/UserAuthRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

$uRoute = new UserAuthRoute();
$pRoute = new PayRoute();
$sRoute = new StoreRoute();
$user = $uRoute->getUser(AuthUtil::getLoggedInfo()->id);
?>

    <script>
        $(document).ready(function(){
            buttonLink(".jGoBalance", "balance.php");
            buttonLink(".jModifyStore", "profile_u.php");
            buttonLink(".jModifyMajor", "profile_m.php");

            jQuery.datetimepicker.setLocale('ko');
            $(".datetimepicker").datetimepicker({
                format: "Y-m-d H:i:s",
                minDate: 0,
                todayButton: true,
                theme: "dark",
            });
        });
    </script>

    <div id="main" class="wrapper style1">
        <div class="container">
            <header class="major">
                <h2>동행 찾기</h2>
            </header>

            <section id="content">
                <form id="userForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$user["id"]?>"/>
                    <div class="row gtr-uniform gtr-50">
                        <div class="col-12 col-12-xsmall">
                            <label for="jTitle">제목</label>
                            <input class=jTitle" type="text" name="title" value="" placeholder="제목"/>
                        </div>
                        
                        <div class="col-12 col-12-xsmall">
                            <label>위치</label>
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <label for="jContent">내용</label>
                            <textarea class="jContent" type="text" name="content" rows="7"></textarea>
                        </div>

                        <div class="col-6 col-12-xsmall">
                            <label for="datetimepicker">시작일시</label>
                            <input class="datetimepicker" type="text" name="startDate">
                        </div>
                        <div class="col-6 col-12-xsmall">
                            <label for="datetimepicker">종료일시</label>
                            <input class="datetimepicker" type="text" name="endDate">
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <label for="jSex">선호 성별</label>
                            <select class="jSex">
                                <option value="-1" <?=$user["sex"] == -1 ? "selected" : ""?>>무관</option>
                                <option value="1" <?=$user["sex"] == 1 ? "selected" : ""?>>남자</option>
                                <option value="0" <?=$user["sex"] == 0 ? "selected" : ""?>>여자</option>
                            </select>
                        </div>

                        <div class="col-12 align-center">
                            <a href="#" class="jSave button icon fa-sign-in small">저장하기</a>
                        </div>
                    </div>
                </form>
            </section>

        </div>
    </div>

    <!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>