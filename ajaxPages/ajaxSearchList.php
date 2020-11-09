<? include_once $_SERVER["DOCUMENT_ROOT"] . "/mygift/shared/public/classes/BoardRoute.php" ?>
<?
$router = new BoardRoute();
//    $list = $router->getNoticeList();
$list = $router->searchList();
$idx = 0;
$target = mt_rand(1, 5);
?>
<?foreach($list as $item){
        $idx++;
    ?>
    <script src="assets/js/jquery.min.js"></script>
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
    </script>
    <style>
        svg {width: 2.0rem; height: 2.0rem;}
        svg path {fill: #FFFFFF !important;}

        .media{
            margin-top: 0;
            margin-bottom: 0 !important;
        }
        @media screen and (min-width: 770px) {
            .media{
                margin-top: 4.0rem;
                margin-bottom: 2.0em !important;
            }
        }
    </style>

    <div noticeID="<?=$item["id"]?>" class="col-twelve jDetail text-right"
         style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
        <div class="text-left">
            <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                <i class="fa fa-dot-circle"></i>&nbsp;<?=$item["title"]?></h5>
        </div>
        <hr style="margin:0;" />
        <div class="align-left">
            <p><?="[{$item["rendezvousPoint"]}]"?></p>
            <b><?=$item["content"]?></b>
        </div>
        <div class="align-right">
            <i class="fa fa-list"></i>&nbsp;<span><?=$item["id"]?></span>&nbsp;
            <i class="fa fa-calendar"></i>&nbsp;<span><?=$item["regDate"]?></span>&nbsp;
            <i class="fa fa-eye"></i>&nbsp;<span><?=$item["hit"]?></span>
            <p style="margin-bottom: 12px;"></p>
        </div>
    </div>
    <?if($idx == $target){?>
        <div class="col-twelve text-right" style="padding:0px 10px;font-size:13px;border: 1px #BBBBBB solid; border-radius:5px;margin-bottom: 10px;">
            <div class="text-left">
                <h5 style="margin-top: 12px; font-size: 15px; margin-bottom: 5px;">
                    <i class="fa fa-dot-circle"></i>&nbsp; (광고) 애월연어
                    <b style="float: right; font-size: 11px"><i class="fa fa-location-arrow"></i> 제주시 애월읍</b>
                </h5>
            </div>
            <hr style="margin:0;" />
            <div class="align-left">
                <p>
                    <span class="image left">
                        <img src="images/ad.jpg" alt="" style=""/>
                    </span>
                    애월 방어, 연어, 육사시미 맛집: 애월연어
                </p>
                <p class="media" style=""> 제주시 애월읍 하소로660 1층</p>
                <div class="align-right">
                    <img src="assets/css/images/ad.svg" class="svg" style="width: 2.0rem; color: white"/>
                </div>
            </div>
        </div>
    <?}?>
<?}?>
