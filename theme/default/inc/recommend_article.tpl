<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- {if $recommend_article} -->
<div class="artic incBox">
 <div class="m2"><a href="" class="rt"><img src="../images/more.jpg" title="更多"></a></div>
 <ul class="recommendArticle">
  <!-- {foreach from=$recommend_article name=recommend_article item=article} -->
  <li{if $smarty.foreach.recommend_article.last} class="last"{/if}><b>{$article.add_time_short}</b><a href="{$article.url}">{$article.title|truncate:26:"..."}</a></li>
  <!-- {/foreach} -->
 </ul>
</div>
<!-- {/if} -->