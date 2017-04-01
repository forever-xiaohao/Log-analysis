
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>www.webdm.cn</title>
<dody>
 <!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
    <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
    <div id="main" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
    <div id="mainMap" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
    
    <!--Step:2 Import echarts.js-->
    <!--Step:2 引入echarts.js-->
    <script src="chartjs/echarts.js"></script>
    <script type="text/javascript">
    require.config({
        paths: {
            echarts: './chartjs'
        }
    });
    
    // Step:4 require echarts and use it in the callback.
    // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/pie',
            'echarts/chart/line',
            'echarts/chart/map'
        ],
        function (ec) {
            //--- 折柱 ---
            var myChart = ec.init(document.getElementById('main'));
            
            myChart.setOption(
 {
    tooltip : {
        show: true,
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient : 'vertical',
        x : 'left',
        data:['直达','营销广告','搜索引擎','邮件营销','联盟广告','视频广告','百度','谷歌','必应','其他']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    series : [
        {
            name:'访问来源',
            type:'pie',
            center : ['35%', 200],
            radius : 80,
            itemStyle : {
                normal : {
                    label : {
                        position : 'inner',
                        formatter : function (params) {                         
                          return (params.percent - 0).toFixed(0) + '%'
                        }
                    },
                    labelLine : {
                        show : false
                    }
                },
                emphasis : {
                    label : {
                        show : true,
                        formatter : "{b}\n{d}%"
                    }
                }
                
            },
            data:[
                {value:335, name:'直达'},
                {value:679, name:'营销广告'},
                {value:1548, name:'搜索引擎'}
            ]
        },
        {
            name:'访问来源',
            type:'pie',
            center : ['35%', 200],
            radius : [110, 140],
            data:[
                {value:335, name:'直达'},
                {value:310, name:'邮件营销'},
                {value:234, name:'联盟广告'},
                {value:135, name:'视频广告'},
                {
                    value:1048,
                    name:'百度',
                    itemStyle : {
                        normal : {
                            color : (function (){
                                var zrColor = require('zrender/tool/color');
                                return zrColor.getRadialGradient(
                                    300, 200, 110, 300, 200, 140,
                                    [[0, 'rgba(255,255,0,1)'],[1, 'rgba(30,144,250,1)']]
                                )
                            })(),
                            label : {
                                textStyle : {
                                    color : 'rgba(30,144,255,0.8)',
                                    align : 'center',
                                    baseline : 'middle',
                                    fontFamily : '微软雅黑',
                                    fontSize : 30,
                                    fontWeight : 'bolder'
                                }
                            },
                            labelLine : {
                                length : 40,
                                lineStyle : {
                                    color : '#f0f',
                                    width : 3,
                                    type : 'dotted'
                                }
                            }
                        }
                    }
                },
                {value:251, name:'谷歌'},
                {
                    value:102,
                    name:'必应',
                    itemStyle : {
                        normal : {
                            label : {
                                show : false
                            },
                            labelLine : {
                                show : false
                            }
                        },
                        emphasis : {
                            label : {
                                show : true
                            },
                            labelLine : {
                                show : true,
                                length : 50
                            }
                        }
                    }
                },
                {value:147, name:'其他'}
            ]
        },
        {
            name:'访问来源',
            type:'pie',
            clockWise:true,
            startAngle: 135,
            center : ['75%', 200],
            radius : [80, 120],
            itemStyle :　{
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                },
                emphasis : {
                    color: (function (){
                        var zrColor = require('zrender/tool/color');
                        return zrColor.getRadialGradient(
                            650, 200, 80, 650, 200, 120,
                            [[0, 'rgba(255,255,0,1)'],[1, 'rgba(255,0,0,1)']]
                        )
                    })(),
                    label : {
                        show : true,
                        position : 'center',
                        formatter : "{d}%",
                        textStyle : {
                            color : 'red',
                            fontSize : '30',
                            fontFamily : '微软雅黑',
                            fontWeight : 'bold'
                        }
                    }
                }
            },
            data:[
                {value:335, name:'直达'},
                {value:310, name:'邮件营销'},
                {value:234, name:'联盟广告'},
                {value:135, name:'视频广告'},
                {value:1548, name:'搜索引擎'}
            ],
            markPoint : {
                symbol: 'star',
                data : [
                    {name : '最大', value : 1548, x:'80%', y:50, symbolSize:32}
                ]
            }
        }
    ]
}
            	);
            
            // --- 地图 ---
            var myChart2 = ec.init(document.getElementById('mainMap'));
            myChart2.setOption({
                tooltip : {
                    trigger: 'item',
                    formatter: '{b}'
                },
                series : [
                    {
                        name: '中国',
                        type: 'map',
                        mapType: 'china',
                        selectedMode : 'multiple',
                        itemStyle:{
                            normal:{label:{show:true}},
                            emphasis:{label:{show:true}}
                        },
                        data:[
                            {name:'广东',selected:true}
                        ]
                    }
                ]
            });
        }
    );
    </script>
</body>
</html>