<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php"?>
<?
    if(AuthUtil::getLoggedInfo() == ""){
        echo "<script>Swal.fire('info', '로그인 후 이용바랍니다.', 'error').then(() => {location.href = '/mygift'})</script>";
    }
    $route->updateNoticeHit();
    //    $item = $route->getNotice();
    $API_URL = $route->PF_API;
    $bRoute = new BoardRoute();
    $item = $bRoute->searchInfo();

    $revInfo = $bRoute->revInfo();

    $attached = $bRoute->getAttachedFiles($_REQUEST["id"]);
?>

<script src="https://apis.openapi.sk.com/tmap/jsv2?version=1&appKey=l7xxdbde817571de4316a21c111fc7e3c35d"></script>
<script>
    $(document).ready(function(){
        buttonLink(".jBack", "search.php");
        var id = "<?=$_REQUEST["id"]?>";
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

                        if(markerArr.length > 0){
                            for(var i in markerArr) markerArr[i].setMap(null);
                        }
                        var innerHtml ="";
                        var positionBounds = new Tmapv2.LatLngBounds();

                        for(var k in resultpoisData){
                            var noorLat = Number(resultpoisData[k].noorLat);
                            var noorLon = Number(resultpoisData[k].noorLon);
                            var name = resultpoisData[k].name;

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

                            innerHtml += "<li class='jRes' lat='" + lat + "' lng='" + lon + "' name='" + name + "' idx='" + k + "'><img src='http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_" + k +
                                ".png' style='vertical-align:middle;'/><span>"+name+"</span></li>";

                            markerArr.push(marker);
                            positionBounds.extend(markerPosition);
                        }

                        $("#searchResult").html(innerHtml);
                        map.panToBounds(positionBounds);
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

                setMarker(lat, lon, name, idx);
                swal("info", "선택되었습니다.", "success");
            });

            if(id != null && id != ""){
                var lat = $("[name=latitude]").val();
                var lng = $("[name=longitude]").val();
                var name = $("[name=rendezvousPoint]").val();
                setMarker(lat, lng, name, 0);
            }

            function setMarker(lat, lon, name, idx){
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
            }

            $("#searchKeyword").keydown(function(key) {
                if(key.keyCode === 13) doSearch();
            });
        }

        initTmap();

        $(".jApply").click(function(){
            $(this).attr("disabled", true);
            callJson("<?="{$API_URL}"?>BoardRoute.applyMatch", {
                    id: id
                }, function(data){
                    if(data.returnCode === 1){
                        Swal.fire("info", data.returnMessage, "success").then(() => {



                            callJson("<?="{$API_URL}"?>ChatRoute.createGroupAjaxK",{
                                    userId: "<?=$item["userId"]?>"
                                }, function(data){
                                    if(data.returnCode === 1){
                                        swal.fire("info", "채팅방이 생성되었습니다! <br/>우측 하단의 말풍선 버튼을 눌러 대화를 나눠보세요!", "success").then(() => {
                                            history.back();
                                        })
                                    }
                                    else Swal.fire("info", "채팅방 생성중 오류가 발생했습니다.\n관리자에게 문의하세요", "error");
                                }
                            )

                        });
                    }
                    else Swal.fire("info", data.returnMessage, "error");
                }
            )
            $(this).attr("disabled", false);
        });

        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: 0,
            symbols:{
                fontawesome_star: {
                    base: '<i style="color: grey;" class="fa fa-lg fa-star"></i>',
                    hover: '<i style="color: orange;" class="fa fa-lg fa-star"></i>',
                    selected: '<i style="color: orange;" class="fa fa-lg fa-star"></i>',
                },
            },
            selected_symbol_type: 'fontawesome_star',
            cursor: 'default',
            readonly: false,
            change_once: false,
        }

        let rating = $(".rating");
        rating.rate(options);

        let tmp = "<?=$revInfo == "" ? 0 : $revInfo["score"]?>";
        rating.rate("setValue", tmp);

        $(".jRev").click(() => {
            callJson(
                "/mygift/shared/public/route.php?F=BoardRoute.sendReview", {
                    score: rating.rate("getValue"),
                    searchId: "<?=$_REQUEST["id"]?>",
                    applierId: "<?=$_REQUEST["applierId"]?>"
                }, (data) => {
                    if(data.returnCode === 1){
                        swal.fire("info", data.returnMessage, "success").then(() => {
                            history.back();
                        })
                    }
                    else swal.fire("info", data.returnMessage, "error")
                }
            )
        });
    });
</script>
<!-- Main -->
<div id="main" class="wrapper style1">
    <div class="container">
        <input type="hidden" name="id" value="<?=$_REQUEST["id"]?>"/>
        <input type="hidden" name="rendezvousPoint" value="<?=$item["rendezvousPoint"]?>"/>
        <input type="hidden" name="latitude" value="<?=$item["latitude"]?>"/>
        <input type="hidden" name="longitude" value="<?=$item["longitude"]?>"/>
        <header class="major">
            <h2>동행 상세</h2>
            <p><?="\"{$item["title"]}\""?></p>
        </header>
        <div class="col-12 align-right">
            <i class="icon fa-eye"></i> <?=$item["hit"]?> &nbsp;&nbsp;
            <i class="icon fa-calendar"></i> <?=$item["regDate"]?>
        </div>

        <section id="content">
            <hr/>
            <div class="row gtr-uniform gtr-50">
                <?if($_REQUEST["type"] == "my"){?>
                    <div class="col-12 col-12-xsmall align-center">
                        <label>상대를 평가해 주세요!</label>
                        <div>
                            <div class="rating" data-rate-value=6 style="display: inline-block"></div>
                            <br/>
                            <button class="jRev button primary icon fa-star small">평가하기</button>
                        </div>
                    </div>
                <?}?>

                <div class="col-12 col-12-xsmall">
                    <label>상세 내용</label>
                    <p><?=$item["content"]?></p>
                </div>

                <div class="col-6 col-6-xsmall">
                    <label>시작일시</label>
                    <p><?=$item["startDate"]?></p>
                </div>

                <div class="col-6 col-6-xsmall">
                    <label>종료일시</label>
                    <p><?=$item["endDate"]?></p>
                </div>

                <div class="col-6 col-6-xsmall">
                    <label>선호 성별</label>
                    <p>
                        <?
                            switch($item["sex"]){
                                case -1:
                                    echo "무관";
                                    break;
                                case 0:
                                    echo "여자";
                                    break;
                                case 1:
                                    echo "남자";
                                    break;
                            }
                        ?>
                    </p>
                </div>

                <div class="col-6 col-6-xsmall">
                    <label>사전 구성 인원</label>
                    <p><?="{$item["originCompanion"]} 명"?></p>
                </div>

                <div class="col-6 col-6-xsmall">
                    <label>필요한 동행 수</label>
                    <p><?="{$item["companion"]} 명"?></p>
                </div>

                <div class="col-12 col-12-xsmall">
                    <label>만날 위치</label>
                    <p><?=$item["rendezvousPoint"]?></p>
                    <div id="map_div" class="map_wrap"></div>
                </div>

                <div class="col-12 align-center">
                    <?if($_REQUEST["type"] != "my"){?>
                        <button class="jApply button primary icon fa-sign-in small" >지원하기</button>
                    <?}?>
                    <a href="#" class="jBack button icon fa-list small">목록으로</a>
                </div>
            </div>
        </section>

    </div>
</div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>
