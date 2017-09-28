$(function(){
	// 日期
	laydate.render({
		elem:"#deadline",
		theme:"grid"
	});
})

$(function(){//首页轮播js

	// 层叠轮播 大屏时显示
	var newopt={
		speed:"5000"
	};
	
	i_slide($("#image_scroll"),newopt);

	// 全屏轮播 小屏时显示
	$(".flexslider").flexslider({
		slideshowSpeed: 5000, //展示时间间隔ms
		animationSpeed: 400, //滚动时间ms
		touch: true, //是否支持触屏滑动
		pauseOnHover:true//鼠标悬停上去是否暂停
	});

});