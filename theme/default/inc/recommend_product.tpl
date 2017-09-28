<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- {if $recommend_product} -->
<div class="incBox tc">
 <div class="m3"></div>
 <h3><a href="{$url.product}">{$lang.product_news}</a></h3>
 <ul class="recommendProduct">
  <!-- {foreach from=$recommend_product name=recommend_product item=product} -->
  <li{if $smarty.foreach.recommend_product.iteration % 4 eq 0} class="clearBorder"{/if}>
  <p class="img"><a href="{$product.url}"><img src="{$product.thumb}" width="{$site.thumb_width}" height="{$site.thumb_height}" /></a></p>
  <p class="name"><a href="{$product.url}">{$product.name}</a></p>
  <p class="price">{$product.price}</p>
  </li>
  <!-- {/foreach} -->

 </ul>
  <div class="clear"></div>
 <a href="product_category.php" class="more"><img src="../images/jt.png" title="了解更多"></a>
</div>
<!-- {/if} -->