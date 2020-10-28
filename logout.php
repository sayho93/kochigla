<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="js/AjaxUtil.js"></script>
<script>
    callJson(
        "/mygift/shared/public/route.php?F=UserAuthRoute.requestLogout",
        null, function(data){
            if(data.returnCode == 1){
//                            alert(data.returnMessage);
                location.href = "index.php";
            }else{
                alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
            }
        }
    );
</script>