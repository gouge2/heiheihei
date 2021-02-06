$(document).ready(function(){
  $(".header .an").click(function(){
    $(this).toggleClass("n");
    $(".nav").toggleClass("n");
  });
  $(".nav").click(function(){
	$(this).removeClass("n");
	$(".header .an").removeClass("n");
  });
  $(".nav").on("click","a",function(e){
    var target = e.target;
	var head = $(".head_h").height();
    var id = $(target).data("to");
    $("html,body").animate({scrollTop:$("#"+id).offset().top - head}, 500);
  });
  $("a.a_n").on("click",function(e){
    var target = e.target;
	var head = $(".head_h").height();
    var id = $(target).data("to");
    $("html,body").animate({scrollTop:$("#"+id).offset().top - head}, 500);
  });
  $(".banner a.x_w").click(function(){
	$(".w_m img.w").slideToggle(0);
  });
  $("a.e_m").click(function(){
	$(".ewm").slideDown();
	$(".jw_ewm").slideUp();
  });
  $("a.j_w").click(function(){
	$(".jw_ewm").slideDown();
	$(".ewm").slideUp();
  });
  $(".ewm i").click(function(){
	$(".ewm").slideUp();
  });
  $(".jw_ewm i").click(function(){
	$(".jw_ewm").slideUp();
  });
  $(".xz_rj a").click(function(){
	$(this).siblings().slideToggle();
  });
  $(".jw_hy a").click(function(){
	$(this).siblings().slideToggle();
  });
});