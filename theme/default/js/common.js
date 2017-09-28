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



/**判断是否成年*/
$(document).delegate('#date','click',function(){
	var self=$(this);
	$('#layui-laydate1').on("click",function(){
		
		if(!$('#layui-laydate1').is(":visible")){
			var bir=$('#date').val();
			var birDat=bir.split('-');
			var Year=parseInt(birDat[0]);
			var Mon=parseInt(birDat[1]);
			var Day=parseInt(birDat[2]);
			var d = new Date()
			var vYear = d.getFullYear()
			var vMon = d.getMonth() + 1
			var vDay = d.getDate();
			if(vYear - Year >18){
				$('.tutorDatas').remove();
				alert(123)
			}else if(vYear - Year == 18){	
				if(vMon - Mon >0 ){
					$('.tutorDatas').remove();	
				}else if(vMon - Mon == 0 ){
					if(vDay -Day >=0){$('.tutorDatas').remove();}
				}
			}
		}
				

		
	})
})

			
