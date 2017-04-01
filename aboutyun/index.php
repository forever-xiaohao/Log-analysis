<?php
ini_set("max_execution_time", "1800");
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','index');
//引入公共文件
//转换成硬路径引入速度更快
require dirname(__FILE__).'/includes/common.inc.php';


    $_ipdetail_sql = "select time from ipdetail group by time order by time desc limit 1";
    $_ipstatistics_sql = "select ipaddress,visitNum,time from ipstatistical where time=(select time from ipstatistical group by time order by time desc limit 1 )";
    $_ipsta_sql = "select time from ipstatistical group by time order by time desc limit 1";
    $_num_result = _query($_ipdetail_sql);
    $_numrow = _num_rows($_num_result);
    $_det_time = _fetch_array($_ipdetail_sql);
    $_ip_time = _fetch_array($_ipsta_sql);
     if($_numrow == 0 || ($_det_time['time'] != $_ip_time['time']) ){
         $_ipde_result = _query($_ipstatistics_sql);
         while (!!$_rowip=_fetch_array_list($_ipde_result)) {
         $_ipdata = _getAddress($_rowip['ipaddress']);
          mysql_query("insert into ipdetail (ipaddress,visitNum,country,area,region,city,isp,time) values('{$_rowip['ipaddress']}',
                             '{$_rowip['visitNum']}',
                             '{$_ipdata['country']}',
                             '{$_ipdata['area']}',
                             '{$_ipdata['region']}',
                             '{$_ipdata['city']}',
                             '{$_ipdata['isp']}',
                             '{$_rowip['time']}'
                            )");
         }
         return false;          
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About云WEB日志分析</title>
<?php
require ROOT_PATH.'includes/link.inc.php';
?>
</head>

<script language="javascript">
   function mouseOver(obj){
      if(obj.className="tb_row")
         obj.className="tb_color";
   }
   function mouseOut(obj){
      if(obj.className="tb_color")
         obj.className="tb_row";
   }
</script> 

<body>

<?php 
require ROOT_PATH.'includes/header.inc.php';
?>





<div class="web_center">

<?php
require ROOT_PATH.'includes/left_nav.inc.php';
?>


<?php
$_sql_total="select TotalPV,TotalIP,TotalPeople,date from v_allstatistics order by date desc limit 2";
$_sql_avg = "select round(avg(TotalPV)) as avgPV,round(avg(TotalIP)) as avgIP,round(avg(TotalPeople)) as avgPeo from v_allstatistics order by date desc limit 30";
$_PV_High = "select TotalPV,date from v_allstatistics where TotalPV = (select max(TotalPV) from v_allstatistics)";
$_IP_High = "select TotalIP,date from v_allstatistics where TotalIP = (select max(TotalIP) from v_allstatistics)";
$_Peo_High = "select TotalPeople,date from v_allstatistics where TotalPeople = (select max(TotalPeople) from v_allstatistics)";

$_Sum_all = "select sum(TotalPV) as SumPV,sum(TotalIP) as SumIP from v_allstatistics";
//$_query=mysql_query($_sql);
//print_r(mysql_fetch_array($_query));
//$_row_1 = _fetch_array($_sql);
//求访问总数
$_result_total = _query($_sql_total);
$_fetch_total=array();
while (!!$_row = _fetch_array_list($_result_total)) {
  $_fetch_total[]=$_row;
}
//求30天平均数
$_rowAvg = _fetch_array($_sql_avg);

$_rowHigh_PV = _fetch_array($_PV_High);
$_rowHigh_IP = _fetch_array($_IP_High);
$_rowHigh_Peo = _fetch_array($_Peo_High);
$_rowSum = _fetch_array($_Sum_all);
?>

	<div class="center_time"><p>统计开通日期：2014-07-11</p></div>
	<div class="center_table">
      <table border="0"cellspacing="0" cellpadding="0">
         <tr>
            <td class="dd_one"><p></p></td>
            <td class="dd_oth"><p>浏览次数（PV）</p></td>
            <td class="dd_oth"><p>独立访客（UV）</p></td>
            <td class="dd_oth"><p>IP数</p></td>
            <td class="dd_oth"><p>新独立访客</p></td>
            <td class="dd_oths"><p>访问次数</p></td>
         </tr>
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)" >
            <td class="td_time"><p><?php echo $_fetch_total[0][date];?></p></td>
            <td class="td_data"><p><?php echo $_fetch_total[0][TotalPV];?></p></td>
            <td class="td_data"><p>----</p></td>
            <td class="td_data"><p><?php echo $_fetch_total[0][TotalIP];?></p></td>
            <td class="td_data"><p>----</p></td>
            <td class="td_datas"><p><?php echo $_fetch_total[0][TotalPeople];?></p></td>
         </tr>
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="td_time_y"><p><?php echo $_fetch_total[1][date];?></p></td>
            <td class="td_data_y"><p><?php echo $_fetch_total[1][TotalPV];?></p></td>
            <td class="td_data_y"><p>----</p></td>
            <td class="td_data_y"><p><?php echo $_fetch_total[1][TotalIP];?></p></td>
            <td class="td_data_y"><p>----</p></td>
            <td class="td_data_ys"><p><?php echo $_fetch_total[1][TotalPeople];?></p></td>
         </tr>
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="td_time_s"><p>近30日平均</p></td>
            <td class="td_data_s"><p><?php echo $_rowAvg[avgPV];?></p></td>
            <td class="td_data_s"><p>----</p></td>
            <td class="td_data_s"><p><?php echo $_rowAvg[avgIP];?></p></td>
            <td class="td_data_s"><p>----</p></td>
            <td class="td_data_ss"><p><?php echo $_rowAvg[avgPeo];?></p></td>
         </tr>
          <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="td_time_s"><p>历史最高</p></td>
            <td class="td_data_s"><p><?php echo $_rowHigh_PV[TotalPV];?></p><p><?php echo '('.$_rowHigh_PV[date].')';?></p></td>
            <td class="td_data_s"><p>----</p><p>----</p></td>
            <td class="td_data_s"><p><?php echo $_rowHigh_IP[TotalIP];?></p><p><?php echo '('.$_rowHigh_IP[date].')';?></p></td>
            <td class="td_data_s"><p>----</p><p>----</p></td>
            <td class="td_data_ss"><p><?php echo $_rowHigh_Peo[TotalPeople];?></p><p><?php echo '('.$_rowHigh_Peo[date].')';?></p></td>
         </tr>
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="td_time_s"><p>历史累计</p></td>
            <td class="td_data_s"><p><?php echo $_rowSum[SumPV];?></p></td>
            <td class="td_data_s"><p>----</p></td>
            <td class="td_data_s"><p><?php echo $_rowSum[SumIP];?></p></td>
            <td class="td_data_s"><p></p></td>
            <td class="td_data_ss"><p></p></td>
         </tr>
      </table>
	</div><!-- center_table_end-->
	<div class="data_show"><p>统计数据部件</p></div>
   <div class="date_ab"><p class="data_ab_lp">流量统计</p><p class="data_ab_rp"><a href="trend.php">>>>>查看全部</a></p></div>
<?php
$_grahp_line_sql = "select PVNum,IPNum,TotalNum,hours from allstatistics where date=(select date from allstatistics group by date order by date desc limit 1) order by hours asc";
$_result = _query($_grahp_line_sql);
$_line_param = array();
while ($_row=_fetch_array_list($_result)) {
  $_line_param['PVNum'][] = $_row['PVNum'];
  $_line_param['IPNum'][] = $_row['IPNum'];
  $_line_param['TotalNum'][] = $_row['TotalNum'];
}
$_PVNum = json_encode($_line_param['PVNum']);
$_IPNum = json_encode($_line_param['IPNum']);
$_TotalNum = json_encode($_line_param['TotalNum']);


$_blog_sql="select sum(visitnum) as blog from blogmodel where time =(select time from blogmodel group by time order by time desc limit 1)";

$_group_sql="select sum(visitnum) as gp from groupmodel where time =(select time from groupmodel group by time order by time desc limit 1)";
$_guide_sql="select sum(visitnum) as guide from guidemodel where time =(select time from guidemodel group by time order by time desc limit 1)";
$_radio_sql="select sum(visitnum) as radio from radiomodel where time =(select time from radiomodel group by time order by time desc limit 1)";
$_ranklist_sql="select sum(visitnum) as ranklist from ranklistmodel where time =(select time from ranklistmodel group by time order by time desc limit 1)";
$_read_sql="select sum(visitnum) as rds from readmodel where time =(select time from readmodel group by time order by time desc limit 1)";
$_share_sql="select sum(visitnum) as share from sharemodel where time =(select time from sharemodel group by time order by time desc limit 1)";
$_weixin_sql="select sum(visitnum) as wx from weixinmodel where time =(select time from weixinmodel group by time order by time desc limit 1)";
$_read=_fetch_array($_read_sql);
$_guide=_fetch_array($_guide_sql);
$_blog=_fetch_array($_blog_sql);
$_ranklist=_fetch_array($_ranklist_sql);
$_share=_fetch_array($_share_sql);
$_group=_fetch_array($_group_sql);
$_radio=_fetch_array($_radio_sql);
$_weixin=_fetch_array($_weixin_sql);
//print_r($_weixin);
$_arr = array();
$_arr[0]['value']=$_read['rds'];
$_arr[0]['name']='图文阅读';
$_arr[1]['value']=$_guide['guide'];
$_arr[1]['name']='导读';
$_arr[2]['value']=$_blog['blog'];
$_arr[2]['name']='博客';
$_arr[3]['value']=$_ranklist['ranklist'];
$_arr[3]['name']='排行榜';
$_arr[4]['value']=$_share['share'];
$_arr[4]['name']='分享';
$_arr[5]['value']=$_group['gp'];
$_arr[5]['name']='群组';
$_arr[6]['value']=$_radio['radio'];
$_arr[6]['name']='广播';
$_arr[7]['value']=$_weixin['wx'];
$_arr[7]['name']='微信';
$_re_bar = json_encode($_arr);
?>
<script src="chartjs/echarts.js"></script>
<script type="text/javascript">
    require.config({
        paths: {
            echarts: './chartjs'
        }
    });
    require(
           [ 
            'echarts',
            'echarts/chart/bar',//按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
            'echarts/chart/line',
            'echarts/chart/funnel',
            'echarts/chart/pie'

		   ],
            function(ec){
               //--折线图--
               // 基于准备好的dom，初始化echarts图表
                var myChartline = ec.init(document.getElementById('line_chart')); 
                
               var optionline = {

                   tooltip : {
                       trigger: 'axis'
                   },
                   legend: {
                       data:['浏览次数（PV）','IP数','站内访问总量']
                   },

                   toolbox: {
                       show : true,
                       feature : {
                           mark : {show: true},
                           dataView : {show: true, readOnly: false},
                           magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                           restore : {show: true},
                           saveAsImage : {show: true}
                       }
                   },
                   calculable : true,
                   xAxis : [
                       {
                           type : 'category',
                           boundaryGap : false,
                           data : ['00:00-00:59','01:00-01:59','02:00-02:59','03:00-03:59','04:00-04:59','05:00-05:59','06:00-06:59','07:00-07:59','08:00-08:59','09:00-09:59','10:00-10:59','11:00-11:59','12:00-12:59','13:00-13:59','14:00-14:59','15:00-15:59','16:00-16:59','17:00-17:59','18:00-18:59','19:00-19:59','20:00-20:59','21:00-21:59','22:00-22:59','23:00-23:59']
                       }
                   ],
                   yAxis : [
                       {
                           type : 'value'
                       }
                   ],
                   series : [
                       {
                           name:'浏览次数（PV）',
                           type:'line',
                           // stack: '总量',
                           data:<?php echo $_PVNum;?>
                       },
                       {
                           name:'IP数',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_IPNum;?>
                       },
                       {
                           name:'站内访问总量',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_TotalNum;?>
                       }
                   ]
               };
               // 为echarts对象加载数据 
                myChartline.setOption(optionline); 
                var myChartpie = ec.init(document.getElementById('pie_chart'));
                var optionpie = {
                            // title : {
                            //     text: '某站点用户访问来源',
                            //     subtext: '纯属虚构',
                            //     x:'center'
                            // },
                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },
                            legend: {
                                orient : 'vertical',
                                x : 'left',
                                data:['图文阅读','导读','博客','排行榜','分享','群组','广播','微信']
                            },
                            toolbox: {
                                show : true,
                                feature : {
                                    mark : {show: true},
                                    dataView : {show: true, readOnly: false},
                                    magicType : {
                                        show: true, 
                                        type: ['pie', 'funnel'],
                                        option: {
                                            funnel: {
                                                x: '25%',
                                                width: '50%',
                                                funnelAlign: 'left',
                                                max: 1548
                                            }
                                        }
                                    },
                                    restore : {show: true},
                                    saveAsImage : {show: true}
                                }
                            },
                            calculable : true,
                            series : [
                                {
                                    name:'访问来源',
                                    type:'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:<?php echo $_re_bar;?>
                                    // data:[
                                    //     {value:335, name:'图文阅读'},
                                    //     {value:310, name:'导读'},
                                    //     {value:234, name:'博客'},
                                    //     {value:135, name:'排行榜'},
                                    //     {value:548, name:'分享'},
                                    //     {value:48, name:'群组'},
                                    //     {value:68, name:'广播'},
                                    //     {value:8, name:'微信'}
                                    // ]
                                }
                            ]
                        };
                    

               // 为echarts对象加载数据 
                myChartpie.setOption(optionpie); 


            }
      );

</script>
<div class="line_chart_all">
   <div id="line_chart">
      
   </div><!-- lineChart_end -->
</div>

<div class="center_totall_all">

   <div class="pie_all">
      <div class="c_title"><p class="data_ab_lp">导航版块统计（按独立访客）</p><p class="data_ab_rp"><a href="guide.php">>>>>查看全部</a></p></div>
      <div id="pie_chart"></div>
   </div><!-- pie_all_end -->


<?php
$_from_host_sql = "select Host,sum(TotalNum) as TotalSum from fromhoststatistics group by Host order by TotalSum desc limit 10";
$_Sum_Total_sql = "select sum(TotalNum) as SumTotal from fromhoststatistics where date=(select date from fromhoststatistics  group by date order by date limit 1)";
$_Sum_Total = _fetch_array($_Sum_Total_sql);
$_result_from_url = _query($_from_host_sql);
?>
   <div class="From_all">
       <div class="c_title"><p class="data_ab_lp">来源域名TOP10（按来访次数）</p><p class="data_ab_rp"><a href="fromHost.php">>>>>查看全部</a></p></div>
       <div class="From_data">
          <table border="0"cellspacing="0" cellpadding="0">
         <tr>
            <td class="From_data_one"><p>来源</p></td>
            <td class="From_data_two"><p>来访次数</p></td>
            <td class="From_data_two"><p>占比</p></td>
         </tr>
         <?php while (!!$_row=_fetch_array_list($_result_from_url)) { //$_SComeurl_data = $_row['TotalSum'];?>
      
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="From_data_url"><a href="<?php echo 'http://'.$_row['Host'];?>" target="_blank"><p><?php echo $_row['Host'];?></p></a></td>
            <td class="From_data_dt"><p><?php echo $_row['TotalSum'];?></p></td>
            <td class="From_data_dt"><p><?php echo round($_row['TotalSum']/$_Sum_Total['SumTotal']*100,2).'%';?></p></td>
         </tr>
         <?php }?>
         
         </table>
       </div>
   </div>

</div><!-- center_totall_all_end -->
<?php
$_search_sql = "select SearchWord,SearchNum from searchstatistics order by SearchNum desc limit 10";
$_Search_Total_sql = "select sum(SearchNum) as NumTotal from searchstatistics where date=(select date from searchstatistics  group by date order by date limit 1)";
$_search_result=_query($_search_sql);
$_total_result = _fetch_array($_Search_Total_sql);
?>


<div class="center_totall_all">


   <div class="pie_all">
      <div class="c_title"><p class="data_ab_lp">搜索词TOP10（按来访次数）</p><p class="data_ab_rp"><a href="searchword.php">>>>>查看全部</a></p></div>
       <div class="From_data">
          <table border="0"cellspacing="0" cellpadding="0">
         <tr>
            <td class="From_data_one"><p>搜索词</p></td>
            <td class="From_data_two"><p>来访次数</p></td>
            <td class="From_data_two"><p>占比</p></td>
         </tr>
         <?php while(!!$_Search_row = _fetch_array_list($_search_result)){?>
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="From_data_url"><p><a href="#"><?php echo $_Search_row['SearchWord'] ;?></a></p></td>
            <td class="From_data_dt"><p><?php echo $_Search_row['SearchNum'] ;?></p></td>
            <td class="From_data_dt"><p><?php echo round($_Search_row['SearchNum']/$_total_result['NumTotal']*100,2).'%';?></p></td>
         </tr>
         <?php }?>
         </table>
       </div>
   </div><!-- pie_all_end -->
<?php
$_visit_page_sql = "select VisitUrl,sum(TotalNum) as TotalSum from visitpagestatistics group by VisitUrl order by TotalSum desc limit 10";
$_Sum_Total_sql = "select sum(TotalNum) as SumTotal from visitpagestatistics where date=(select date from visitpagestatistics  group by date order by date limit 1)";
$_result_visit_page = _query($_visit_page_sql);
$_Sum_Total = _fetch_array($_Sum_Total_sql);
?>
   <div class="From_all">
       <div class="c_title"><p class="data_ab_lp">受访页面TOP10（按来访次数）</p><p class="data_ab_rp"><a href="visitpage.php">>>>>查看全部</a></p></div>
       <div class="From_data">
          <table border="0"cellspacing="0" cellpadding="0">
         <tr>
            <td class="From_data_one"><p>受访页面</p></td>
            <td class="From_data_two"><p>浏览次数</p></td>
            <td class="From_data_two"><p>占比</p></td>
         </tr>
         <?php while (!!$_row = _fetch_array_list($_result_visit_page)) {
          $_result_str =explode('HTTP/1.0',$_row['VisitUrl']);?>
          
         <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
            <td class="From_data_url"><a href="<?php echo 'http://www.aboutyun.com'.$_row['VisitUrl'];?>" target="_blank"><p><?php echo _url_len($_result_str[0],35);?></p></a></td>
            <td class="From_data_dt"><p><?php echo $_row['TotalSum'];?></p></td>
            <td class="From_data_dt"><p><?php echo round($_row['TotalSum']/$_Sum_Total['SumTotal']*100,2).'%';?></p></td>
         </tr>
         <?php }?>
         </table>
       </div>
   </div>

</div><!-- center_totall_all_end -->

<div class="web_info">
   <dl>
      <dd><p>站点名称：about云</p></dd>
      <dd><p>站点首页：http://www.aboutyun.com</p></dd>
      <dd><p>站点类型：技术交流、学习</p></dd>
   </dl>
</div>


</div><!--web_center_end-->















<?php
require ROOT_PATH.'includes/footer.inc.php';
?>






</body>
</html>