<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="search-wrapper"
<ul class="slides">
  <!-- {foreach from=$show_list name=show item=show} -->
  <li><a href="{$show.show_link}" target="_blank" style="background-image:url({$show.show_img});"></a></li>
  <!-- {/foreach} -->
 </ul>
		<!-- <div class="wide-container search-layout">
			<div class="search-container">
				<form action="#" method="" class="search-form">
					<p>我有</p>
					<div class="form-row">
						<input type="text" placeholder="分數" name="grade" class="grade">
					</div>
					<div class="form-row">
						<input type="text" name="date" id="deadline" class="deadline lf" placeholder="截止日期">
						<select name="gradetype" class="gradetype rt">
							<option value="0">分數類型</option>
							<option value="1">高考</option>
							<option value="2">IB</option>
							<option value="3">GCEAL</option>
							<option value="4">SAT</option>
							<option value="5">TOFEL</option>
						</select>
					</div>
					<div class="form-row">
						<input type="text" placeholder="請輸入關鍵字" name="grade" class="keyword">
					</div>
					<div>
						<input type="submit" class="search" value="立即配對">
					</div>
				</form>
			</div>
		</div> -->
		<div class="home-ico"><div class="ico ico1"></div></div>
	</div>

<script type="text/javascript">
{literal}
$(document).ready(function(){
 $('.slides').bxSlider({
   mode: 'fade'
 });
})
{/literal}
</script>