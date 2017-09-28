<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="clear"></div>
<!-- <div id="header">
 <div class="wrap">
  <ul class="logo">
   <a href="{$site.root_url}"><img src="../images/{$site.site_logo}" alt="{$site.site_name}" title="{$site.site_name}" /></a>
  </ul> 

 </div>
</div> -->
<div class="top-wrapper">
    <div class="top-container">
      <h1 class="logo lf">
        <a href="{$site.root_url}"><img src="../images/{$site.site_logo}" alt="{$site.site_name}" title="{$site.site_name}" /></a>
      </h1>
      <!-- nav 小屏 -->
      <div class="m-nav">
        <img class="menu" id="menu" src="images/m_menu.png" title="导航" alt="导航">
        <div class="m-cont" id="m_cont">
          <ul>
            <!-- {if $index eq 'index'} -->
             <li ><a href="{$site.root_url}" class="current">{$lang.home}</a></li>
            <!-- {else} -->
               <li><a href="{$site.root_url}" >{$lang.home}</a></li>
           <!-- {/if} -->

              <!-- {foreach from=$nav_middle_list name=nav_middle_list item=nav} --> 
              <!-- {if $nav.module eq $index} -->
             <li><a href="{$nav.url}" class="current">{$nav.nav_name}</a> </li>
               <!-- {else} -->
               <li><a href="{$nav.url}">{$nav.nav_name}</a> </li>
              <!-- {/if} -->
              
            <!-- {/foreach} -->
          </ul>
        </div>
      </div>
      <!-- nav 小屏 end -->
      <div class="login">
      <form name="action" method="post" action="entrance.php?ent=pass">
      <!-- {if $pass} -->
      <span>欢迎登陆</span><span><a href="entrance.php?ent=exit">退出</a></span>
      <!-- {else} -->
      <input type="password" placeholder="員工入口" name="password">
        <input type="submit" value="登入">
      <!-- {/if} -->
        
        <a href="uploading_system.php">資料上傳</a>
      </form>
        
      </div>
    </div>
  </div>
  <!-- top end -->
  <!-- nav 大屏 -->
  <div class="nav-wrapper">
    <div class="nav-container">
      <ul>
      <!-- {if $index eq 'index'} -->
         <li ><a href="{$site.root_url}" class="current">{$lang.home}</a></li>
        <!-- {else} -->
           <li><a href="{$site.root_url}" >{$lang.home}</a></li>
     <!-- {/if} -->
  <!-- {foreach from=$nav_middle_list name=nav_middle_list item=nav} --> 
    <!-- {if $nav.module eq $index} -->
             <li><a href="{$nav.url}" class="current">{$nav.nav_name}</a> </li>
               <!-- {else} -->
               <li><a href="{$nav.url}">{$nav.nav_name}</a> </li>
              <!-- {/if} -->
<!-- {/foreach} -->
      </ul>
    </div>      
  </div>
