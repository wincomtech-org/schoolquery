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
   $('#box').css('height',DivLiH*8+"px");
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
// 验证中文名称
function isChinaName(name) {
	 var pattern = /^[\u4E00-\u9FA5]{1,6}$/;
	 return pattern.test(name);
}
 
// 验证手机号
function isPhoneNo(phone) { 
	 var pattern = /^1[34578]\d{9}$/; 
	 return pattern.test(phone); 
}
 
// 验证身份证 
function isCardNo(card) { 
	 var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; 
	 return pattern.test(card); 
} 
 
// 验证函数
	function formValidate() {
		 // var str = '';
		 
		 // 判断名称
		 // if($.trim($('input[name="zh_name"]').val()).length == 0) {
			//   str += '名称没有输入\n';
			//   $('input[name="zh_name"]').focus();
		 // }else  {
			//   if(isChinaName($.trim($('input[name="zh_name"]').val())) == false) {
			//    str += '名称不合法\n';
			//    $('#name').focus();
			//   }
		 // }
		 
		 // 判断手机号码
		 // if ($.trim($('#phone').val()).length == 0) { 
			//   str += '手机号没有输入\n';
			//   $('#phone').focus();
		 // } else {
			//   if(isPhoneNo($.trim($('#phone').val()) == false)) {
			//    str += '手机号码不正确\n';
			//    $('#phone').focus();
		 //  }
		 // }
		 
		
		 // if($.trim($('#identity').val()).length == 0) { 
		 // 	 // 验证身份证
			//   str += '身份证号码没有输入\n';
			//   $('#identity').focus();
		 // } else {
		 //  if(isCardNo($.trim($('#identity').val())) == false) {
			//    str += '身份证号不正确；\n';
			//    $('#identity').focus();
		 //  }
		 // }
		 
		 
		 // if($.trim($('#address').val()).length == 0) { 
			// // 验证地址		 		
			//   str += '地址没有输入\n';
			//   $('#address').focus();
		 // }
		 
		 // 如果没有错误则提交
		 // if(str != '') {
			//   alert(str);
			//   return false;
		 // } else {
			//   $('.auth-form').submit();
		 // }
	}
 function formValidate(){
	/*上传资料验证***/
 	if($.trim($('input[name="zh_name"]').val()).length == 0 || isChinaName($.trim($('input[name="zh_name"]').val())) == false ){
			alert('请填写正确中文名');
			return false;
			$('input[name="zh_name"]').focus();
		}else if($('input[name="en_name"]').val()==""){
			alert('请填写英文名');
			$('input[name="en_name"]').focus();
		}else if($('input[name="nation"]').val()==""){
			alert('请填写国籍');
			$('input[name="nation"]').focus();
		}else if($('input[name="register"]').val()==""){
			alert('请填写户籍');
			$('input[name="register"]').focus();
		}else if($.trim($('input[name="shenfen"]').val()).length == 0 || isCardNo($.trim($('input[name="shenfen"]').val())) == false){
			alert('请填写正确身份证');
			$('input[name="shenfen"]').focus();
		}else if($('input[name="tongxingzheng"]').val()==""){
			alert('请填写通行证号码')
		}else if($.trim($('input[name="neidi_tel"]').val()).length == 0  || isPhoneNo($.trim($('input[name="neidi_tel"]').val())) == false){
			alert('请填写正确手机号码');
			$('input[name="neidi_tel"]').focus();
		}else if($('input[name="tongxingzheng"]').val()==""){
			alert('请填写通行证号码')
			$('input[name="tongxingzheng"]').focus();
		}else if($('.education tbody tr input[type="text"]').val()==""){
			alert("请完善学历信息");
			$('.education tbody tr input[type="text"]').foucus();
		}else if($('.tutorDatas').css('display')=="block"){
			if($.trim($('input[name="tutor_zh_name"]').val()).length == 0 || isChinaName($.trim($('input[name="tutor_zh_name"]').val())) == false ){
				alert('请填写监护人正确中文名')
				$('input[name="tutor_zh_name"]').focus();
			}else if($('input[name="tutor_en_name"]').val()==""){
				alert('请填写监护人英文名');
				$('input[name="tutor_en_name"]').focus();
			}else if($.trim($('input[name="tutor_shenfen"]').val()).length == 0 || isCardNo($.trim($('input[name="tutor_shenfen"]').val())) == false){
				alert('请填写正确监护人身份证');
				$('input[name="tutor_shenfen"]').focus();
			}else if($('input[name="tutor_tongxingzheng"]').val()==""){
				alert('请填写监护人通行证号码')
				$('input[name="tutor_tongxingzheng"]').focus();
			}else  if($('input[name="tutor_work"]').val()==""){
				alert('请填写监护人职业')
				$('input[name="tutor_tongxingzheng"]').focus();
			}else if($('input[name="tutor_relation"]').val()==""){
				alert('请填写与监护人关系')
				$('input[name="tutor_relation"]').focus();
			}
		}
 }

$('.sub_button button').on('click',function(){
		formValidate();
})


百度地图API功能
var map = new BMap.Map('allmap');
var poi = new BMap.Point(114.172061,22.285733);
map.centerAndZoom(poi, 16);
map.enableScrollWheelZoom();

var content = '<div style="margin:0;line-height:20px;padding:2px;">' +
                '地址：<br/>電話：<br/>簡介：<br/>' +
              '</div>';

//创建检索信息窗口对象
var searchInfoWindow = null;
searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
		title  : "香港升學中心",      //标题
		width  : 290,             //宽度
		height : 60,              //高度
		panel  : "panel",         //检索结果面板
		enableAutoPan : true,     //自动平移
		searchTypes   :[
			BMAPLIB_TAB_SEARCH,   //周边检索
			BMAPLIB_TAB_TO_HERE,  //到这里去
			BMAPLIB_TAB_FROM_HERE //从这里出发
		]
	});
var marker = new BMap.Marker(poi); //创建marker对象
marker.enableDragging(); //marker可拖拽
searchInfoWindow.open(marker);
marker.addEventListener("click", function(e){
searchInfoWindow.open(marker);
	 })
map.addOverlay(marker); //

			
