<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<?
$router = new BoardRoute();
//    $list = $router->getNoticeList();
$list = $router->searchList();
//echo json_encode($list);
$idx = 0;
$target = mt_rand(1, 5);
$secondTarget = mt_rand(1, 5);
?>
<?foreach($list as $item){
        $idx++;
    ?>

    <script>
        jQuery('img.svg').each(function(){
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data){
                var $svg = jQuery(data).find('svg');
                if(typeof imgID !== 'undefined') $svg = $svg.attr('id', imgID);
                if(typeof imgClass !== 'undefined') $svg = $svg.attr('class', imgClass+' replaced-svg');
                $svg = $svg.removeAttr('xmlns:a');
                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                $img.replaceWith($svg);
            }, 'xml');
        });

        $(document).ready(function(){
            $(".jSlick").not(".slick-initialized").slick({
                dots: false,
                arrows:false,
                centerPadding: '0',
                centerMode: true,
                infinite: true,
                speed: 200,
                slidesToShow: 1,
                autoplay: true,
                variableWidth: true
            });

            $(".jSlick").each(function(idx){
                $(this).addClass("image left")
            })
        });
    </script>
    <style>
        svg {width: 2.0rem; height: 2.0rem;}
        svg path {fill: #FFFFFF !important;}

        .media{
            margin-top: 0;
            margin-bottom: 1.0rem !important;
        }

        .jSlick{
            margin-top: 0!important;
            width: 125.19px;
            height: 125.19px;
        }

        .cImg{
            width: 125.19px!important;
            height: 125.19px!important;
        }

        .mediaP{
            margin-top: 0;
            margin-bottom: 0 !important;
        }

        @media screen and (min-width: 770px) {
            .media{
                margin-top: 4.0rem;
                margin-bottom: 2.0em !important;
            }

            .mediaP{
                margin-top: 0;
                margin-bottom: 1.7rem !important;
            }

            .jSlick{
                margin-top: 0!important;
                width: 170px !important;
                height: 170px !important;
            }

            .cImg{
                width: 170px !important;
                height: 170px !important;
            }
        }

    </style>

    <div noticeID="<?=$item["id"]?>" class="col-twelve jDetail text-right" style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;" userID="<?=$item["userId"]?>">
        <div class="text-left">
            <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                <i class="fa fa-dot-circle"></i>&nbsp;<?=$item["title"]?></h5>
        </div>
        <hr style="margin:0;" />
        <div class="align-left" style="margin-top:0; padding-top: 0;">
            <div class="jSlick image left">
                <div>
                    <img src="<?="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=" . $item["thumbId"]?>" class="cImg" alt="" />
                </div>
                <?foreach(str_getcsv($item["additional"]) as $ids){?>
                    <div>
                        <img src="<?="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=" . $ids?>" class="cImg" alt="" />
                    </div>
                <?}?>
<!--                <div>-->
<!--                    <img src="images/profile/남자4/3.png" alt="" />-->
<!--                </div>-->
<!--                <div>-->
<!--                    <img src="images/profile/남자4/4.png" alt="" />-->
<!--                </div>-->
            </div>
            <p class="mediaP">
                <?if(false){?>
                <span class="image left">
                    <img src="<?="/mygift/shared/public/route.php?F=FileRoute.downloadFileById&id=" . $item["thumbId"]?>"  alt="" /><!--style="width: 10.65rem"-->
                </span>
                <?}?>
                <?="[{$item["rendezvousPoint"]}]"?>
            </p>
            <?if($item["isAuth"] == "1"){?>
                <button type="button" class="button small primary" style="margin-bottom: 0; background-color: forestgreen">인증 회원</button>
            <?}?>
            <button type="button" class="button small primary" style="margin-bottom: 0; background-color: darkred">ESFJ</button>
            <p>
                <?=$item["content"]?>
                <br/>
            </p>
        </div>
        <div class="align-right">
            <i style="color: orange;" class="fa fa-star"></i>&nbsp; <?=$item["score"] == null ? 0 : $item["score"]?>
            <i class="fa fa-list"></i>&nbsp;<span><?=$item["id"]?></span>&nbsp;
            <i class="fa fa-calendar"></i>&nbsp;<span><?=$item["regDate"]?></span>&nbsp;
<!--            <i class="fa fa-eye"></i>&nbsp;<span>--><?//=$item["hit"]?><!--</span>-->
            <p style="margin-bottom: 12px;"></p>
        </div>
    </div>
    <?if($idx == $target){?>
        <div class="col-twelve text-right jAd" style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
            <div class="text-left">
                <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                    <i class="fa fa-dot-circle"></i>&nbsp; (광고) 애월연어
                    <b style="float: right; font-size: 11px"><i class="fa fa-location-arrow"></i> 제주시 애월읍</b>
                </h5>
            </div>
            <hr style="margin:0;" />
            <div class="align-left">
                <div class="jSlick image left">
                    <div><img src="images/ad1.jpg" alt="" /></div>
                    <?for($i=2; $i<=4; $i++){?>
                        <div><img src="images/ad<?=$i?>.png" class="cImg" alt="" /></div>
                    <?}?>
                </div>
                <p>애월 방어, 연어, 육사시미 맛집: 애월연어</p>
                <p class="media" style=""> 제주시 애월읍 하소로660 1층</p>
                <div class="align-right">
                    <img src="assets/css/images/ad.svg" class="svg" style="width: 2.0rem; color: white"/>
                </div>
            </div>
        </div>
    <?}?>
    <?if($idx == $secondTarget){?>
        <div class="col-twelve text-right jAd2" style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
            <div class="text-left">
                <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                    <i class="fa fa-dot-circle"></i>&nbsp; (광고) 제주다이브
                    <b style="float: right; font-size: 11px"><i class="fa fa-location-arrow"></i> 서귀포시 강정동</b>
                </h5>
            </div>
            <hr style="margin:0;" />
            <div class="align-left">
                <div class="jSlick image left">
                    <?for($i=1; $i<=4; $i++){?>
                        <div><img src="images/adActivity<?=$i?>.png" class="cImg" alt="" /></div>
                    <?}?>
                </div>
                <p>스쿠버교육 / 체험다이빙 / 아쿠아 플라넷: 제주다이브</p>
                <p class="media" style=""> 제주특별자치도 서귀포시 강정동 월드컵로 84 씨월드펜션 </p>
                <div class="align-right">
                    <img src="assets/css/images/ad.svg" class="svg" style="width: 2.0rem; color: white"/>
                </div>
            </div>
        </div>
    <?}?>
<?}?>
