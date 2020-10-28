<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019-01-17
 * Time: 오전 10:30
 */
?>

<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<?
    $bRoute = new BoardRoute();
    $item = $bRoute->getBoard($_REQUEST["id"]);
    $attached = $bRoute->getAttachedFiles($_REQUEST["id"]);
?>
<script type="text/babel">
    $(document).ready(() => {
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginFilePoster,
        );

        FilePond.setOptions({
            maxFileSize: '10MB',
            allowFilePoster: true,
            server: {
                url: '/mygift/shared/public/route.php?',
                process: {
                    url: 'F=FileRoute.processFilePond.raw',
                    method: 'POST',
                    withCredentials: false,
                    headers:{},
                    onerror: null,
                    ondata: null
                },
                revert: "F=FileRoute.revertFilePond",
                restore: "F=FileRoute.restoreFilePond",
                fetch: "F=FileRoute.fetchFilePond",
                load: "F=FileRoute.loadFilePond.raw&fileSource="
            }
        });

        //TODO attached file
        const pondInputElement = document.querySelector('input[type="file"]');
        pond = FilePond.create(pondInputElement, {
            files: [
                <?foreach($attached as $file){
                    $src = explode("/", $file["filePath"]);
                    $src = $src[sizeof($src) - 1];

                    $src = $route->PF_FILE_DISPLAY_PATH . "/" . $src;
                    ?>
                {
                    source: "<?=$file["fileId"]?>",
                    options: {
                        type: 'local',
                        load: true,
                        metadata: {
                            poster: "<?=$src?>"
                        }
                    }
                },
                <?}?>
            ]
        });

        clickAction(".jUpload", () => {
            submitForJson(
                "/mygift/shared/public/route.php?F=BoardRoute.addBoard",
                "#jDataForm",
                (data) => {
                    callBackSwal("알림", data.returnMessage, "info", "pages/noticeDetail.php?id=<?=$_REQUEST["id"]?>");
                }
            );
        });
    });
</script>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    Notice Form
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Notice</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notice Form</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form id="jDataForm">
                                <input type="hidden" name="id" value="<?=$_REQUEST["id"]?>"/>
                                <input type="hidden" name="type" value="1"/>
                                <div class="form-group">
                                    <label for="title">제목</label>
                                    <input type="text" class="form-control" name="title" placeholder="제목" value="<?=$item["title"]?>">
                                </div>
                                <div class="form-group">
                                    <label for="desc">내용</label>
                                    <textarea class="form-control" name="content" rows="10" placeholder="내용"><?=$item["content"]?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword4">파일</label>
                                    <input type="file" name="attachFiles[]" id="attachFiles" value="" placeholder="Name" multiple/>
                                </div>
                                <a class="btn btn-gradient-primary mr-2 jUpload">저장</a>
                                <a class="btn btn-light jLink" target="pages/notice.php">취소</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/admin/inc/footer.php"; ?>