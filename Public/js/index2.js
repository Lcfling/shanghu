//广告轮播
$(function(){
	var wid=$(".ban_list").find("img").width();
	var index=$(".ban_list").find("img").length;
	var num=0;
	setInterval(function(){
		num++;
		if(num<index){
			$(".ban_list").animate({
				left:-wid*num+"px"
			})
		}else{
			$(".ban_list").animate({left:0})
			num=0
		}
		
	},2000)
	
})

//footer
$(".foot").children("div").click(function(){
	$(".foot>div").find("div").addClass("color-gray")
	$(".foot>div").find("img").addClass("filter-gray")
	$(this).find("div").removeClass("color-gray");
	$(this).find("img").removeClass("filter-gray");
})

//loan_detail
$(".guize-btn").click(function(){
	$(".guize-xl").slideToggle();
	$(".guize-xl").show();
})

//team_manage
$(".team-nav div").click(function(){
	var index=$(this).index();
	$(".team-box").children().eq(index).show().siblings().hide()
})

//upVIP
$(".Bond-btn").click(function(){
	$(".mask").show();
	$(".Bond-tc").show();
})

//poster
$("#poster-guize").click(function(){
	$(".mask").show();
	$(".poster-tc").show();
})
var postImgWid=$(".poster-box").find("img").width();
var postImgLength=$(".poster-box").find("img").length;
var postListWid=$(".poster-list").find("img").width()+6.4;
var num=0;
$(".poster_r").click(function(){
	num++;
	if(num<postImgLength){
		$(".poster-box").stop().animate({
			left:-num*postImgWid+"px"
		})
		$(".poster-list").children("div").stop().animate({left:-num*postListWid+"px"})
		$(".poster-list").find("img").eq(num).removeClass("bright-5").siblings().addClass("bright-5")
	}else{
		$(".poster-box").css("left","0");
		$(".poster-list").children("div").stop().animate({left:0});
		$(".poster-list").find("img").eq(0).removeClass("bright-5").siblings().addClass("bright-5")
		num=0
	}
	
})
//$(".poster_l").click(function(){
//	num--;
//	if(num<postImgLength){
//		$(".poster-box").animate({
//			left:num*postImgWid+"px"
//		})
//		$(".poster-list").children("div").animate({left:num*postListWid+"px"})
//		$(".poster-list").find("img").eq(num).removeClass("bright-5").siblings().addClass("bright-5")
//	}else{
//		$(".poster-box").css("left","0");
//		$(".poster-list").children("div").animate({left:0});
//		num=0
//	}
//	
//})

//mycenter
$("#tixian-btn").click(function(){
	$(".mask").show();
	$(".tixian-tc").show();
})
//账户管理
$(".del_btn").click(function(){
	$(".mask").show();
	$(".del-bank").show();
})

//公用
$(".close").click(function(){
	$(".mask").hide();
	$(".Bond-tc").hide();
	$(".tixian-tc").hide();
	$(".del-bank").hide()
})
