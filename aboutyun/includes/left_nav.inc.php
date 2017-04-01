<?php
//防止恶意调用
if(!defined('IN_TG')){
  echo 'ACCESS DEFINED!';
  exit();
}
?>
<script type="text/javascript">
function $(id){return document.getElementById(id)}
window.onload = function(){
 $("nav_right").onclick = function(e){
  var src = e?e.target:event.srcElement;
  if(src.tagName == "H3"){
   var next = src.nextElementSibling || src.nextSibling;
   next.style.display = (next.style.display =="block")?"none":"block";
  }
 }
}
</script>
<div class="all_nav_right">
<div class="nav_top_web"><a href="#"><p>网站概况</p></a></div>
<div id="nav_right">
 <h3>流量分析</h3>
    <div>
        <a href="trend.php">趋势分析</a>
        <a href="detail.php">访问明细</a>
    </div>
    <h3>来源分析</h3>
    <div>
        <a href="fromHost.php">来源域名</a>
        <a href="fromPage.php">来源页面</a>
        <a href="searchword.php">搜索词</a>
    </div>
    <h3>受访分析</h3>
    <div>
        <a href="visitpage.php">受访页面</a>
        <a href="module.php">热点版块</a>
        <a href="visitspace.php">受访空间</a>
        <a href="guide.php">热点导航</a>
        <!-- <a href="#">热点博客</a>
        <a href="#">热点群组</a> -->
    </div>
    <h3>访客分析</h3>
    <div>
        <a href="place.php">地区/运营商</a>
        <a href="browser.php">终端详情</a>
    </div>
</div>
</div>
