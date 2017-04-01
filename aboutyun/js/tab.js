// JavaScript Document

function setTab(m,n){
var tli=document.getElementById("menu"+m).getElementsByTagName("li");
var mli=document.getElementById("main"+m).getElementsByTagName("ul");
for(i=0;i<tli.length;i++){
tli[i].className=i==n?"hover":"";
mli[i].style.display=i==n?"block":"none";
}
}
function nowtab(m,n){
if(n!=0&&m3[0]=="")m3[0]=document.getElementById("main2").innerHTML;
document.getElementById("tip"+m).style.left=n*100+'px';
document.getElementById("main2").innerHTML=m3[n];
}
