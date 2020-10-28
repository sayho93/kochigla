<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/public/classes/BoardRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

$bRoute = new BoardRoute();
$boardId = $_REQUEST["id"];
$boardData = $bRoute->getBoard($boardId);
$attached = $bRoute->getAttachedFiles($boardId);

?>
    <script>
        $(document).ready(function(){
            /**
             * Filepond Area - Start
             */
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginImageExifOrientation,
                FilePondPluginFileValidateSize
            );


            FilePond.setOptions({
                maxFileSize: '10MB',
                // crop the image to a 1:1 ratio
//                imageCropAspectRatio: '1:1',
                // resize the image
//                imageResizeTargetWidth: 200,
                // upload to this server end point
                server: {
                    url: '/mygift/shared/public/route.php?',
                    process : {
                        url: 'F=FileRoute.processFilePond.raw',
                        method: 'POST',
                        withCredentials: false,
                        headers: {},
//                        timeout: 7000,
//                        onload: function(data){
//                            alert(data);
//                        },
                        onerror: null,
                        ondata: null
                    },
                    revert: 'F=FileRoute.revertFilePond',
                    restore: 'F=FileRoute.restoreFilePond.raw&fileSource=',
                    fetch: 'F=FileRoute.fetchFilePond',
                    load : 'F=FileRoute.loadFilePond.raw&fileSource='
                }
            });

            const pondInputElement = document.querySelector('input[type="file"]')
            pond = FilePond.create(pondInputElement, {
                files: [
                    <?foreach ($attached as $file){?>
                    {
                        source: '<?=$file["fileId"]?>',
                        options: {
                            type: 'local'
                        }
                    },
                    <?}?>
                ]
            });

            /**
             * Filepond Area - End
             */

            clickAction(".jCancel", function(){
                history.back();
            });

            clickAction(".jUpload", function(){
                submitForJson(
                    "/mygift/shared/public/route.php?F=BoardRoute.addBoard",
                    "#jDataForm",
                    function(data){
                        swal("알림", data.returnMessage, "info");
                    }
                );
            });
        });
    </script>

    <section id="" class="wrapper style2 special fade" style="padding:0;">
        <div class="container" style="padding: 10px;">
            <div class="col-12 align-left">
                <i class="icon fa-upload" style="font-style: normal;"> <?=$boardId == "" ? "작품등록" : "작품수정"?></i>
                <!--                <h5 style="margin:0;"><i class="button small jGroupShow" style="font-style: normal;"><i class="icon fa-upload"></i> 작품등록</i>&nbsp;&nbsp;-->
                <!--                    <i class="icon fa-list jGoPost" style="font-style: normal;"> 목록</i>&nbsp;|&nbsp;-->
                <!--                    <i class="icon fa-sign-out jGoOutChat" style="font-style: normal;"> 나가기</i>-->
                </h5>
            </div>
        </div>
    </section>

    <section class="wrapper">
        <h3>작품 정보</h3>
        <form method="post" action="#" id="jDataForm">
            <input type="hidden" name="id" value="<?=$boardData["id"]?>" />
            <div class="row gtr-uniform gtr-50">
                <p style="margin-bottom: 0;">제목</p>
                <div class="col-12">
                    <input type="text" name="title" id="title" value="<?=$boardData["title"]?>" placeholder="제목을 입력하세요" />
                </div>
                <p style="margin-bottom: 0;">공개 설정</p>
                <div class="col-12">
                    <div class="col-6">
                        <input type="radio" id="exposure_public" name="exposure" checked>
                        <label for="exposure_public">전체 공개</label>
                        <input type="radio" id="exposure_private" name="exposure">
                        <label for="exposure_private">비공개</label>
                    </div>
                </div>
                <p style="margin-bottom: 0;">카테고리</p>
                <div class="col-12">
                    <select name="category" id="category">
                        <option value="">카테고리를 선택하세요</option>
                        <option value="1">Manufacturing</option>
                        <option value="1">Shipping</option>
                        <option value="1">Administration</option>
                        <option value="1">Human Resources</option>
                    </select>
                </div>
                <p style="margin-bottom: 0;">작품</p>
                <div class="col-12 editable" id="content" contenteditable="true" placeholder="작품 설명을 입력하세요"><?=$boardData["content"]?>
                    <img src="./images/banner.jpg" alt="image" /></div>
                <p style="margin-bottom: 0;">첨부파일</p>
                <div class="col-12">
                    <input type="file" name="attachFiles[]" id="attachFiles" value="" placeholder="Name" multiple />
                </div>
                <div class="col-12 align-center">
                    <ul class="actions">
                        <li><a href="#" class="button primary icon fa-pencil jUpload" >등록하기</a></li>
                        <li><a href="#" class="button icon fa-times jCancel" >취소</a></li>
                    </ul>
                </div>
            </div>
        </form>
    </section>
    <!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>