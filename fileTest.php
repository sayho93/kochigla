<?php
/**
 * Created by PhpStorm.
 * User: 전세호
 * Date: 2019-01-09
 * Time: 오후 6:11
 */
?>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
    $info = $route->getBoardInfo();
    $filePath = $route->PF_FILE_PATH;
    $fileInfo = $info["fileInfo"]
?>

    <script>
        $(document).ready(function(){
            $(".jSubmit").click(function(){
                var ajax = new AjaxSubmit("/mygift/shared/public/route.php?F=WebRoute.upsertBoard", "post", true, "json", "#form");
                ajax.send(function(data){
                    if(data.returnCode === 1){
                        //location.href = "/admin/pages/recommend.php?appId=<?//=$info["id"]?>//";
                    }
                    else alert("이미지 저장 실패");
                });
            });

            $(".jFileName").hover(function(){

            });

            $(".jFileDel").click(function(){

            });
        });
    </script>

    <section id="content" class="wrapper">
        <div class="content">
            <div class="container">
                <header>
                </header>
                <form id="form">
                    <div class="row gtr-uniform gtr-50">
                        <input type="hidden" name="id" value="<?=$_REQUEST["id"]?>"/>
                        <input type="hidden" name="userId" value="1"/>
                        <input type="hidden" name="type" value="<?=$info["type"]?>"/>

                        <div class="col-12 col-12-xsmall">
                            <input type="text" name="title" placeholder="title" value="<?=$info["title"]?>"/>
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <textarea type="text" name="content" placeholder="content"><?=$info["content"]?></textarea>
                        </div>


                        <?for($i=0; $i<5; $i++){?>
                            <div class="wrapper" style="display: block;">
                                <div class="col-4 col-4-xsmall">
                                    <label class="button" style="margin: 1vw;">
                                        <input type="file" name="testFile[]" idx="<?=$i?>" style="display: none;"/>
                                        <input type="text" name="fileStatus" value="<?=$fileInfo[$i]["filePath"]?>"/>
                                        <span>파일 선택</span>
                                    </label>
                                    <span class="jFileName"><?=$fileInfo[$i]["fileName"]?></span>
                                    <a class="primary button jFileDel" idx="<?=$i?>" style="padding: 0 1em">X</a>
                                </div>
                                <div class="" style="position: absolute">

                                </div>
                            </div>
                        <?}?>

                        <!--                        <div class="wrapper">-->
                        <!--                            <div class="col-4 col-4-xsmall">-->
                        <!--                                <label class="button" style="margin: 1vw;">-->
                        <!--                                    <input type="file" name="testFile[]" style="display: none;"/>-->
                        <!--                                    <span>파일 선택</span>-->
                        <!--                                </label>-->
                        <!--                                <span class="jFileName">--><?//=$fileInfo[1]["fileName"]?><!--</span>-->
                        <!--                                <a class="primary button jFileDel" style="padding: 0 1em">X</a>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!---->
                        <!--                        <div class="wrapper">-->
                        <!--                            <div class="col-4 col-4-xsmall">-->
                        <!--                                <label class="button" style="margin: 1vw;">-->
                        <!--                                    <input type="file" name="testFile[]" style="display: none;"/>-->
                        <!--                                    <span>파일 선택</span>-->
                        <!--                                </label>-->
                        <!--                                <span class="jFileName">--><?//=$fileInfo[2]["fileName"]?><!--</span>-->
                        <!--                                <a class="primary button jFileDel" style="padding: 0 1em">X</a>-->
                        <!--                            </div>-->
                        <!--                        </div>-->

                        <div class="col-12 col-12-xsmall align-center">
                            <button class="primary button jSubmit">저장</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <a href="#one" class="goto-next scrolly">Next</a>
    </section>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>