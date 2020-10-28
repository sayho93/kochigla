<script src="../assets/js/jquery.min.js"></script>
<script type="text/javascript" src="../js/AjaxUtil.js"></script>
<script>
    callJson(
        "/mygift/shared/public/route.php?F=UserAuthRoute.requestLogout",
        null, function(data){
            if(data.returnCode == 1){
//                            alert(data.returnMessage);
                location.href = "index.php";
            }else{
                location.href = "index.php";
            }
        }
    );
</script>