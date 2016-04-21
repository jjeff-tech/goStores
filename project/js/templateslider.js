/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
	var autoPlayTime=5000;
	autoPlayTimer = setInterval( autoPlay, autoPlayTime);
	function autoPlay(){
		Slidebox('next');
	}
	$('#slidebox_template .next').click(function () {
		Slidebox('next','stop');
	});
	$('#slidebox_template .previous').click(function () {
		Slidebox('previous','stop');
	});
	var yPosition=($('#slidebox_template').height()-$('#slidebox_template .next').height())/2;
	$('#slidebox_template .next').css('top',yPosition);
	$('#slidebox_template .previous').css('top',yPosition);
	$('#slidebox_template .thumbs a:first-child').removeClass('thumb').addClass('selected_thumb');
	$("#slidebox_template .content").each(function(i){
		slideboxTotalContent=i*$('#slidebox_template').width();
		$('#slidebox_template .container').css("width",slideboxTotalContent+$('#slidebox_template').width());
	});
});

function Slidebox(slideTo,autoPlay){
    var animSpeed=1000; //animation speed
    var easeType='easeInOutExpo'; //easing type
	var sliderWidth=$('#slidebox_template').width();
	var leftPosition=$('#slidebox_template .container').css("left").replace("px", "");
	if( !$("#slidebox_template .container").is(":animated")){
		if(slideTo=='next'){ //next
			if(autoPlay=='stop'){
				clearInterval(autoPlayTimer);
			}
			if(leftPosition==-slideboxTotalContent){
				$('#slidebox_template .container').animate({left: 0}, animSpeed, easeType); //reset
				$('#slidebox_template .thumbs a:first-child').removeClass('thumb').addClass('selected_thumb');
				$('#slidebox_template .thumbs a:last-child').removeClass('selected_thumb').addClass('thumb');
			} else {
				$('#slidebox_template .container').animate({left: '-='+sliderWidth}, animSpeed, easeType); //next
				$('#slidebox_template .thumbs .selected_thumb').next().removeClass('thumb').addClass('selected_thumb');
				$('#slidebox_template .thumbs .selected_thumb').prev().removeClass('selected_thumb').addClass('thumb');
			}
		} else if(slideTo=='previous'){ //previous
			if(autoPlay=='stop'){
				clearInterval(autoPlayTimer);
			}
			if(leftPosition=='0'){
				$('#slidebox_template .container').animate({left: '-'+slideboxTotalContent}, animSpeed, easeType); //reset
				$('#slidebox_template .thumbs a:last-child').removeClass('thumb').addClass('selected_thumb');
				$('#slidebox_template .thumbs a:first-child').removeClass('selected_thumb').addClass('thumb');
			} else {
				$('#slidebox_template .container').animate({left: '+='+sliderWidth}, animSpeed, easeType); //previous
				$('#slidebox_template .thumbs .selected_thumb').prev().removeClass('thumb').addClass('selected_thumb');
				$('#slidebox_template .thumbs .selected_thumb').next().removeClass('selected_thumb').addClass('thumb');
			}
		} else {
			var slide2=(slideTo-1)*sliderWidth;
			if(leftPosition!=-slide2){
				clearInterval(autoPlayTimer);
				$('#slidebox_template .container').animate({left: -slide2}, animSpeed, easeType); //go to number
				$('#slidebox_template .thumbs .selected_thumb').removeClass('selected_thumb').addClass('thumb');
				var selThumb=$('#slidebox_template .thumbs a').eq((slideTo-1));
				selThumb.removeClass('thumb').addClass('selected_thumb');
			}
		}
	}
}
