<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/BoardRoute.php"; ?>
<?
    if(!AuthUtil::isLoggedIn()){
        echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
    }
    $API_URL = $route->PF_API;
    $bRoute = new BoardRoute();
    $list = $bRoute->matchRequest();
//    echo json_encode($list);


?>
    <script>
        $(document).ready(function(){
            $(".jApply").click(function(){
                var searchId = $(this).attr("searchId");
                location.href = "/mygift/searchDetail.php?id=" + searchId;
            });

            $(".jReceive").click(function(){
                var userId = $(this).attr("userId");
                var searchId = $(this).attr("searchId");
                Swal.fire({
                    icon: "info",
                    title: '동행신청 수락',
                    showCloseButton: true,
                    showDenyButton: true,
                    confirmButtonText: '수락',
                    denyButtonText: '거절',
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if(result.isConfirmed) changeStatus(userId, searchId, 1);
                    else if(result.isDenied) changeStatus(userId, searchId, -1);
                });
            });

            function changeStatus(userId, searchId, status){
                callJson("<?="{$API_URL}BoardRoute.changeMatchStatus"?>", {
                        userId: userId,
                        searchId: searchId,
                        status: status
                    }, function(data){
                        if(data.returnCode === 1) Swal.fire("info", data.returnMessage, "success").then(() => location.reload());
                        else Swal.fire("info", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "erro");
                    }
                )
            }
        });
    </script>

<style>
    .buttonRed{
        background-color: #e64942;
    }
    .buttonPrimary{
        background-color: #3085d6;
    }
</style>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2><?=$_REQUEST["type"] == "R" ? "받은 동행신청" : "보낸 동행신청"?></h2>
						</header>

                        <?if($_REQUEST["type"] == "R"){?>
                            <section>
                                <div class="table-wrapper">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>동행 제목</th>
                                            <th style="white-space: nowrap;">이름</th>
                                            <th>지원일시</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?foreach($list as $item){?>
                                            <tr class="jReceive" userId="<?=$item["requestUserId"]?>" searchId="<?=$item["searchId"]?>">
                                                <td><?=$item["title"]?></td>
                                                <td style="white-space: nowrap;"><?=$item["name"]?></td>
                                                <td><?=$item["regDate"]?></td>
                                            </tr>
                                        <?}?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        <?}else{?>
                            <section>
                                <div class="table-wrapper">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th style="white-space: nowrap;">장소</th>
                                            <th>기간</th>
                                            <th>지원일시</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?foreach($list as $item){?>
                                            <tr class="jApply" searchId="<?=$item["searchId"]?>">
                                                <td><?=$item["rendezvousPoint"]?></td>
                                                <td><?="{$item["startDate"]} <br>~<br> {$item["endDate"]}"?></td>
                                                <td><?=$item["regDate"]?></td>
                                            </tr>
                                        <?}?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        <?}?>
					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>