// JavaScript Document
<script type="text/javascript">
function $(id){
	}
	
	
	function $(id){
		return document.getElementById(id);
		}
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