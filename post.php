<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/ChatRoute.php"; ?>
<?

if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

$cRoute = new ChatRoute();
$groupList = $cRoute->getMyAuthGroupList();

?>

    <script>
        $(document).ready(function(){
            $(".float-button").hide();

            $(".jEnter").click(function(e){
                e.stopPropagation();
                e.preventDefault();

                var id = $(this).attr("groupId");
                location.href = "chat.php?groupId=" + id;
            });
        });
    </script>

    <section id="five" class="wrapper style2 special fade" style="padding:1.5em 1.5em 0.5em 1.5em;">
        <div class="container">
            <div class="col-12 align-left">
                <h5><i class="icon fa-comments"></i> 채팅목록(<?=sizeof($groupList)?>)</h5>
            </div>
        </div>
    </section>

    <div class="wrapper" style="padding:20px;">
        <div class="col-12">
            <?foreach ($groupList as $group){?>
                <div class="button fit jEnter" style="margin-bottom: 10px;" groupId="<?=$group["id"]?>">
                    <div class="fit align-left" style="font-size: 14px;">
                        <i class="icon fa-comment"></i> &nbsp;<?=$group["groupName"]?>
                        <i class="icon fa-users"
                           style="font-style:normal; font-size: 12px; position: absolute; right:50px;"> &nbsp;
                            <?=$group["members"]?> &nbsp;
                            <i class="icon fa-calendar" style="font-style:normal;"> &nbsp;<?=$group["regDate"]?></i>
                        </i>
                    </div>
                </div>
            <?}?>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>