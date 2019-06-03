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
//kefu
$(".kefu>div").click(function(){
	$('.kefu>div').find(".kefu_btn").removeClass("action");
	$(this).find(".kefu_btn").addClass("action");
	var index=$(this).index();
	$(".ewm").find("img").eq(index).show().siblings().hide()
})

//loan_detail
$(".guize-btn").click(function(){
	$(".guize-xl").slideToggle();
	$(".guize-xl").show();
})
$("#loanapply").click(function(){
	$(".mask").show();
	$(".loanapply-tc").show()
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
//vipPay
$(".pay_method li").click(function(){
	$(".pay_method li").find(".selec_y").removeClass("active");
	$(this).find(".selec_y").addClass("active");
	
})

//poster
$("#poster-guize").click(function(){
	$(".mask").show();
	$(".poster-tc").show();
})
var postBox=$(".poster-box");
var postImgWid=$(".poster-box").find("img").width();
var postImgLength=$(".poster-box").find("img").length;
var boxWid=parseInt(postImgWid*(postImgLength-1)) ;
var postListWid=$(".poster-list").find("img").width()+6.4;
function animate(offset){
	var newLeft=parseInt(postBox.css("left"))-offset;
	if(newLeft>0){
		postBox.css("left",0)
		
	}else{
		postBox.animate({left:newLeft+"px"})
	}
//	if(Math.abs(newLeft)>=boxWid){
//		postBox.css("left",-boxWid+"px")
//		console.log(-boxWid)
//	}else{
//		postBox.animate({left:newLeft+"px"})
//	}
}
$(".poster_r").click(function(){
	animate(postImgWid)
})
$(".poster_l").click(function(){
	animate(-postImgWid)
})

//$(".poster_r").click(function(){
//	num++;
//	if(num<postImgLength){
//		$(".poster-box").stop().animate({
//			left:-num*postImgWid+"px"
//		})
//		$(".poster-list").children("div").stop().animate({left:-num*postListWid+"px"})
//		$(".poster-list").find("img").eq(num).removeClass("bright-5").siblings().addClass("bright-5")
//	}else{
//		$(".poster-box").css("left","0");
//		$(".poster-list").children("div").stop().animate({left:0});
//		$(".poster-list").find("img").eq(0).removeClass("bright-5").siblings().addClass("bright-5")
//		num=0
//	}
//	
//})
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

//提现弹窗
$("#tixian-btn").click(function(){
	var mydate=new Date();
	var xingq=mydate.getDay();
	if(xingq=="6"||xingq=="0"){
		alert("对不起，节假日不能提现")
	}else{
		$(".mask").show();
		$(".tixian-tc").show();
	}	
})

//账户管理
$(".del_btn").click(function(){
	$(".mask").show();
	$(".del-bank").show();
})

//无卡支付
$(".select_card li").click(function(){
	if($(".money-top input").val() ==""){
		alert("请输入金额")
	}else{
		window.location.href="add_xinyong.html"
	}
})
//商户收款
$("#shPayBtn").click(function(){
	if($(".shskNum").val()<500 ){
		alert("请输入500元以上金额")
	}else{
		window.location.href="path.html"
	}
})

//帮助说明
$(".help li").click(function(){
	$(this).find("div").slideToggle().show()
})
//平台手册
$(".apiNav li").click(function(){
	$(this).addClass("action").siblings().removeClass("action");
	var index=$(this).index();
	$(".apiPic>div").eq(index).show().siblings().hide();
})

//智能还卡 -申请天数
$("#selecDay").click(function(){
	$(".mask").show()
	$("#dayBox").show()
})
$("#dayBox .day").children("p").click(function(){
	var txt=$(this).text();
	$("#selecDay").text(txt+'天');
	$(".mask").hide()
	$("#dayBox").hide()
})
//支持信用卡
$(".")

//公用
$(".close").click(function(){
	$(".mask").hide();
	$(".Bond-tc").hide();
	$(".tixian-tc").hide();
	$(".del-bank").hide();
	$(".loanapply-tc").hide()
})
