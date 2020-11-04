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
$API_PATH = $uRoute->PF_API;
?>
    <script src="https://apis.openapi.sk.com/tmap/jsv2?version=1&appKey=l7xxdbde817571de4316a21c111fc7e3c35d"></script>
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

            var map, marker;
            var markerArr = [];

            function initTmap(){
                // 1. 지도 띄우기
                map = new Tmapv2.Map("map_div", {
                    center: new Tmapv2.LatLng(33.499583, 126.531251),
                    width : "100%",
                    height : "400px",
                    zoom : 15,
                    zoomControl : true,
                    scrollwheel : true

                });

                // 2. POI 통합 검색 API 요청
                $("#btn_select").click(function(){
                    doSearch();
                });

                function doSearch(){
                    var searchKeyword = $('#searchKeyword').val();
                    $.ajax({
                        method:"GET",
                        url:"https://apis.openapi.sk.com/tmap/pois?version=1&format=json&callback=result",
                        async:false,
                        data:{
                            "appKey" : "l7xxdbde817571de4316a21c111fc7e3c35d",
                            "searchKeyword" : searchKeyword,
                            "resCoordType" : "EPSG3857",
                            "reqCoordType" : "WGS84GEO",
                            "count" : 5
                        },
                        success:function(response){
                            var resultpoisData = response.searchPoiInfo.pois.poi;

                            // 기존 마커, 팝업 제거
                            if(markerArr.length > 0){
                                for(var i in markerArr){
                                    markerArr[i].setMap(null);
                                }
                            }
                            var innerHtml ="";	// Search Reulsts 결과값 노출 위한 변수
                            var positionBounds = new Tmapv2.LatLngBounds();		//맵에 결과물 확인 하기 위한 LatLngBounds객체 생성

                            for(var k in resultpoisData){
                                var noorLat = Number(resultpoisData[k].noorLat);
                                var noorLon = Number(resultpoisData[k].noorLon);
                                var name = resultpoisData[k].name;
                                console.log(k);

                                var pointCng = new Tmapv2.Point(noorLon, noorLat);
                                var projectionCng = new Tmapv2.Projection.convertEPSG3857ToWGS84GEO(pointCng);

                                var lat = projectionCng._lat;
                                var lon = projectionCng._lng;

                                var markerPosition = new Tmapv2.LatLng(lat, lon);

                                marker = new Tmapv2.Marker({
                                    position : markerPosition,
                                    //icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_a.png",
                                    icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_" + k + ".png",
                                    iconSize : new Tmapv2.Size(24, 38),
                                    title : name,
                                    map:map
                                });

                                innerHtml += "<li class='jRes' lat='" + lat + "' lng='" + lon + "' name='" + name + "' idx='" + k + "'><img src='http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_" + k + ".png' style='vertical-align:middle;'/><span>"+name+"</span></li>";

                                markerArr.push(marker);
                                positionBounds.extend(markerPosition);	// LatLngBounds의 객체 확장
                            }

                            $("#searchResult").html(innerHtml);	//searchResult 결과값 노출
                            map.panToBounds(positionBounds);	// 확장된 bounds의 중심으로 이동시키기
                            map.zoomOut();
                        },
                        error:function(request,status,error){
                            console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                        }
                    });
                }

                $(document).on("click", ".jRes", function(){
                    var lat = $(this).attr("lat");
                    var lon = $(this).attr("lng");
                    var name = $(this).attr("name");
                    var idx = $(this).attr("idx");

                    if(markerArr.length > 0){
                        for(var i in markerArr) markerArr[i].setMap(null);
                    }

                    var positionBounds = new Tmapv2.LatLngBounds();
                    var markerPosition = new Tmapv2.LatLng(lat, lon);

                    marker = new Tmapv2.Marker({
                        position : markerPosition,
                        icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_" + idx + ".png",
                        iconSize : new Tmapv2.Size(24, 38),
                        title : name,
                        map:map
                    });

                    markerArr.push(marker);
                    positionBounds.extend(markerPosition);
                    map.panToBounds(positionBounds);
                    map.zoomOut();
                    map.setZoom(15);

                    $("[name=rendezvousPoint]").val(name);
                    $("[name=latitude]").val(lat);
                    $("[name=longitude]").val(lon);
                    swal("info", "선택되었습니다.", "success");
                });

                $("#searchKeyword").keydown(function(key) {
                    if(key.keyCode === 13) doSearch();
                });
            }

            initTmap();

            $(".jSave").click(function(){
                // var data = new FormData($("#searchForm")[0]);
                // console.log(data);
                // console.log(data.get("rendezvousPoint"));
                // console.log(JSON.stringify(data));

                var data = $("#searchForm").serialize();

                callJsonBySerialize("<?="{$API_PATH}BoardRoute.upsertSearch"?>", data, function(data){
                        if(data.returnCode === 1){
                            swal("info", data.returnMessage, "success").then(() => {
                                location.href = "/mygift/search.php";
                            });
                        }
                        else swal("알림" ,  "오류가 발생하였습니다.\n관리자에게 문의하세요.", "error");
                    }
                );

            });
        });
    </script>

    <div id="main" class="wrapper style1">
        <div class="container">
            <header class="major">
                <h2>동행 찾기</h2>
            </header>

            <section id="content">
                <form id="searchForm" method="post">
                    <input type="hidden" name="id" value="<?=$_REQUEST["id"]?>"/>
                    <input type="hidden" name="rendezvousPoint" value=""/>
                    <input type="hidden" name="latitude" value=""/>
                    <input type="hidden" name="longitude" value=""/>

                    <div class="row gtr-uniform gtr-50">
                        <div class="col-12 col-12-xsmall">
                            <label>만날 위치</label>
                            <input class="" type="text" id="searchKeyword" value="" placeholder="주소/명칭/전화번호" />
                            <div class="align-center">
                                <button type="button" class="browse button primary icon fa-search" id="btn_select" style="margin-top: 1.0rem">위치 찾기</button>
                            </div>

                            <label>검색결과</label>
                            <ul id="searchResult"></ul>
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <div id="map_div" class="map_wrap"></div>
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <label for="jTitle">제목</label>
                            <input class=jTitle" type="text" name="title" value="" placeholder="제목"/>
                        </div>

                        <div class="col-12 col-12-xsmall">
                            <label for="jContent">내용</label>
                            <textarea class="jContent" type="text" name="content" rows="7" placeholder="여행 조건 등을 최대한 상세하게 적어주시기 바랍니다."></textarea>
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
                            <select class="jSex" name="sex">
                                <option value="-1" <?=$user["sex"] == -1 ? "selected" : ""?>>무관</option>
                                <option value="1" <?=$user["sex"] == 1 ? "selected" : ""?>>남자</option>
                                <option value="0" <?=$user["sex"] == 0 ? "selected" : ""?>>여자</option>
                            </select>
                        </div>

                        <div class="col-3 col-12-xsmall">
                            <label for="jCompanion">희망 동행 인원</label>
                            <select class="jCompanion" name="companion">
                                <?for($i=1; $i<=20; $i++){?>
                                    <option value="<?=$i?>"><?=$i?> 명</option>
                                <?}?>
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