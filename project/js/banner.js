    $(document).ready(function(){
               setInterval("slideShow()", 1000);
               $('.tooltip').tooltipster();
              
    });
    function slideShow()
    {

        var current = $(".current");
        if($(".current").prev('.bannerSub').length == 0)
        {
            $("#banner_container .bannerSub").not("#banner_container .bannerSub:last").animate({opacity:0},1000);
            $("#banner_container .bannerSub:last").addClass("current").css({opacity:1});
        }else{
            $(".current").prev('.bannerSub').animate({opacity:1},1000, function(){
                $(this).addClass("current");
                current.removeClass("current");
            });
        }
    }

    function setClickCount(id){
        data ="banner="+id;
        $.ajax({
        url: bannerUrl,
        type: "POST",
        data: data,
        cache: false,
        success: function (data) {
       
        }
    });
    return true;
    }
