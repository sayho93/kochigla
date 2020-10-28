<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019-01-16
 * Time: 오후 4:26
 */
?>

<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/header.php" ?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<?
    $bRoute = new BoardRoute();
    $boardId = $_REQUEST["id"];
    $boardData = $bRoute->getBoard($boardId);
    $attached = $bRoute->getAttachedFiles($boardId);
?>

<script type="text/babel">
    $(document).ready(() => {
        let currentPage = 1;
        let isFinal = false;
        let loadMore = (page) => {
            loadPageInto(
                "/mygift/ajaxPages/admin/ajaxNoticeList.php",
                {
                    type: 1,
                    page : page,
                    query : "<?=$_REQUEST["query"]?>"
                },
                ".jContainer",
                true,
                () => {
                    isFinal = true;
                    currentPage--;
                    $(".jLoadMore").hide();
                }
            );
        };
        loadMore(currentPage);

        $(".jLoadMore").click((e) => {
            e.preventDefault();
            loadMore(++currentPage);
        });

        $(document).on("click", ".jDetail", function(){
            let id = $(this).attr("noticeID");
            transition("pages/noticeDetail.php?id=" + id);
        });
    });
</script>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Notice List
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Notice</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notice List</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>제목 </th>
                                    <th>ID </th>
                                    <th>작성자 </th>
                                    <th>등록일시 </th>
                                    <th>조회수 </th>
                                </tr>
                                </thead>
                                <tbody class="jContainer">

                                </tbody>
                            </table>
                            <div class="col-12 text-center mt-4">
                                <a href="#" class="jLoadMore btn btn-dark"><i class="mdi mdi-reload"></i>더보기</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/footer.php" ?>
