<style>
    .float-button{
        position : fixed;
        bottom: 15px;
        right: 15px;
        z-index: 9997;
    }

    .float-button img{
        width: 75px;
        height: 75px;
    }

</style>

<script>
    $(document).ready(function(){
        $(".jGoMsg").click(function(e){
            e.stopPropagation();
            e.preventDefault();
            location.href = "post.php";
        });
    });
</script>

<div class="float-button">
    <img src="images/icon_message.png" class="jGoMsg" />
</div>