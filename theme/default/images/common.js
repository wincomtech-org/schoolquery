$(function(){
	// 大屏导航
	$("#nav-pc li a").click(function(){
		$("#nav-pc li a").removeClass("current");
		$(this).addClass("current");
	});

	// 小屏导航
	$("#m_cont a").click(function(){
		$("#m_cont a").removeClass("current");
		$(this).addClass("current");
	});
	
	var m_cont=$("#m_cont");
	$("#menu").click(function(){
		if(m_cont.is(":hidden")){
			m_cont.fadeIn();
		}
	});
	m_cont.click(function(){		
		if(m_cont.is(":visible")){
			m_cont.fadeOut();
		}
	})
	
});


$(document).delegate('.local_shool_list','click',function(){
	var self=$(this);
	var t=$(this).index();
	var con=$('.local_shool_content');
	self.addClass('active').siblings().removeClass('active');
	con.eq(t).show(600).siblings().hide(600);
})
$(function(){
	/***新闻滚动条**/
    var DivLi=$('.new_video_items');
    for(var i=0;i<DivLi.length;i++){
        var DivLiH=DivLi[i].clientHeight;
    }
    if(DivLi.length > 8){
    	$('#box').css('height',DivLiH*8+"px");
    }else{
    	
    		$('#box').css('height',DivLiH*DivLi.length+"px");
    	
    }

   
})


$('#deadline').on('foucus',function(){
	document.activeElement.blur();
})
$('#date').on('foucus',function(){
	document.activeElement.blur();
})

//切换国家和地区
$(document).delegate('.local_shool_list','click',function(){
	var self=$(this);
	var t=$(this).index();
	var con=$('.local_shool_content');
	self.addClass('active').siblings().removeClass('active');
	con.eq(t).show(600).siblings().hide(600);
})	
// $(document).delegate('.local_shool_list','click',function(){
// 			var self=$(this);
// 			var t=$(this).index();
// 			var con=$('.local_shool_content');
// 			self.addClass('active').siblings().removeClass('active');
// 			con.eq(t).show(600).siblings().hide(600);
// 		})





			
