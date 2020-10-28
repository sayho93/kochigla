<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
    if(AuthUtil::isLoggedIn()){
        echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
    }
?>
    <script>
        $(document).ready(function(){
            $(".jJoin").click(function(){
                var rec = grecaptcha.getResponse();
                var jPrC = $(".jPr").prop("checked");
                var jAgC = $(".jAg").prop("checked");

                if($(".jEmailTxt").val() == ""
                    || $(".jPhoneTxt").val() == ""
                    || $(".jNameTxt").val() == ""
                    || $(".jPasswordTxt").val() == ""
                    ){
                    swal ( "알림" ,  "회원 정보를 모두 입력하세요.", "error" );
                    return;
                }
                if($(".jPasswordTxt").val() != $(".jPasswordCTxt").val()){
                    swal ( "알림" ,  "패스워드 확인이 일치하지 않습니다.", "error" );
                    return;
                }

                if(!jPrC){
                    swal ( "알림" ,  "개인정보처리방침에 동의하시기 바랍니다.", "error" );
                    return;
                }

                if(!jAgC){
                    swal ( "알림" ,  "서비스 이용 약관에 동의하시기 바랍니다.", "error" );
                    return;
                }

                if(rec == ""){
                    swal ( "알림" ,  "reCAPTCHA 인증을 완료하시기 바랍니다.", "error" );
                    return;
                }

                var data = new FormData($("#userForm")[0]);
                data.append("sex", $(".jSex option:selected").val());
                data.append("from", "N");
                data.append("recaptcha", rec);

                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url : "/mygift/shared/public/route.php?F=UserAuthRoute.joinUser",
                    cache : false,
                    async : true,
                    method : "post",
                    dataType : "json",
                    contentType: false,
                    processData: false,
                    data : data,
                    success : function(data){
                        console.log("[AJAX RESPONSE] " + data);
                        var retData = JSON.parse(data);
                        if(retData.returnCode > 0){
                            if(retData.returnCode > 1){
                            }else{
                                swal({
                                    title: "알림",
                                    text: retData.returnMessage,
                                    icon: "success",
                                    closeOnClickOutside: false,
                                }).then((result) => {
                                    if(result) location.href = "index.php";
                                });
                            }
                        }else{
                            swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                        }
                    },
                    error : function(req, stat, err){
                        console.log("[AJAX ERROR] REQUEST : " + req + " / STATUS : " + stat + " / ERROR : " + err);
                    }
                });

                // callJson(
                //     "/mygift/shared/public/route.php?F=UserAuthRoute.joinUser",
                //     {
                //         email : $(".jEmailTxt").val(),
                //         pwd : $(".jPasswordTxt").val(),
                //         phone : $(".jPhoneTxt").val(),
                //         name : $(".jNameTxt").val(),
                //         age: $(".jAge").val(),
                //         sex: $(".jSex option:selected").val(),
                //         from : 'N',
                //         recaptcha : rec
                //     }
                //     , function(data){
                //         if(data.returnCode > 0){
                //             alert(data.returnMessage);
                //             if(data.returnCode > 1){
                //             }else{
                //                 location.href = "index.php";
                //             }
                //         }else{
                //             swal ( "알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error" );
                //         }
                //     }
                // )
            });

            $(document).on("click", ".browse", function(){
                var idx = $(this).attr("idx");
                var file = $(this).parents().find(".file").eq(idx);
                file.trigger("click");
            });

            $(document).on("click", ".browseThumb", function(){
                $("#thumbFile").trigger("click");
            });

            $('input.imgFile[type=file]').change(function(e){
                var idx = $(this).attr("idx");
                var fileName = e.target.files[0].name;
                $("#file" + idx).val(fileName);

                var reader = new FileReader();
                reader.onload = function(e){
                    $("#preview" + idx).attr("src", e.target.result);
                    $("#preview" + idx).fadeIn();
                };
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>회원 가입</h2>
						</header>

						<!-- Content -->
							<section id="content">
                                <form id="userForm" method="post" enctype="multipart/form-data">
                                    <div class="row gtr-uniform gtr-50">

                                        <div class="col-4 col-12-xsmall text-center">
                                            <img src="" id="preview0" class="img-thumbnail text-center" style="width: 100%; display: none"/>
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="file0">프로필 사진</label>
                                            <input type="file" name="img[]" class="file imgFile" idx="0" accept="image/*" style="display: none;">
                                            <input type="text" class="form-control jImg" disabled placeholder="업로드 파일명" id="file0" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <div align="center">
                                                <button type="button" class="browse button primary" idx="0">파일 선택</button>
                                            </div>
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jNameTxt">성명</label>
                                            <input class="jNameTxt" type="text" name="name" placeholder="성명" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jEmailTxt">이메일</label>
                                            <input class="jEmailTxt" type="email" name="email" placeholder="이메일" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPhoneTxt">휴대전화 ('-' 없이 입력)</label>
                                            <input class="jPhoneTxt" type="text" name="phone" placeholder="휴대전화" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPasswordTxt">패스워드</label>
                                            <input class="jPasswordTxt" type="password" name="pwd" placeholder="패스워드" />
                                        </div>

                                        <div class="col-12 col-12-xsmall">
                                            <label for="jPasswordCTxt">패스워드 확인</label>
                                            <input class="jPasswordCTxt" type="password" placeholder="패스워드 확인" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jAge">나이</label>
                                            <input class="jAge" type="text" name="age" placeholder="나이" />
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <label for="jSex">성별</label>
                                            <select class="jSex">
                                                <option value="-1">선택</option>
                                                <option value="1">남자</option>
                                                <option value="0">여자</option>
                                            </select>
                                        </div>
                                        <div class="col-6 col-12-medium">
                                            <input type="checkbox" id="jPr" class="jPr" name="jPr">
                                            <label for="jPr"><a href="#">개인정보처리방침</a>에 동의합니다.</label>
                                        </div>
                                        <div class="col-6 col-12-medium">
                                            <input type="checkbox" id="jAg" class="jAg" name="jAg">
                                            <label for="jAg">본 서비스 이용에 따른 <a href="#">회원 약관</a>에 동의합니다.</label>
                                        </div>
                                        <div class="col-12 col-12-xsmall">
                                            <div class="g-recaptcha" data-sitekey="<?=REC_SITE_KEY?>"></div>
                                        </div>
                                        <div class="col-12 align-center">
                                            <a href="#" class="jJoin button icon fa-edit small">가입하기</a>
                                        </div>
                                    </div>
                                </form>
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>