<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019-01-30
 * Time: 오후 3:00
 */
?>

<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/header.php" ?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/StoreRoute.php" ?>
<?
    $sRoute = new StoreRoute();

?>

<script type="text/babel">
    $(document).ready(() => {
        let currentPage = 1;
        let isFinal = false;
        let loadMore = (page) => {
            loadPageInto(
                "/mygift/ajaxPages/admin/ajaxProductList.php",
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
                    $(".jLoadMoreArea").hide();
                }
            );
        };
        loadMore(currentPage);

        $(".jLoadMore").click((e) => {
            e.preventDefault();
            loadMore(++currentPage);
        });

        $(document).on("click", ".jDetail",(element) => {
            let id = element.currentTarget.getAttribute("productID");
            // let id = $(this).attr("productID");
            transition("pages/productDetail.php?id=" + id);
        });
    });
</script>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Product List
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product List</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive-xl">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr class="text-nowrap">
                                    <th class="text-center">카테고리</th>
                                    <th class="text-center">제품명</th>
                                    <th class="text-center">판매자</th>
                                    <th class="text-center">가격</th>
                                    <th class="text-center">적립률</th>
                                    <th class="text-center">수정일시</th>
                                    <th class="text-center">등록일시</th>
                                    <th class="text-center">조회수</th>
                                </tr>
                                </thead>
                                <tbody class="jContainer">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body jLoadMoreArea">
                        <div class="col-12 text-center mt-4">
                            <a href="#" class="jLoadMore btn btn-dark"><i class="mdi mdi-reload"></i>더보기</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/footer.php" ?>
