<?php
// 防止恶意调用
// if(!defined('IN_TG')){
//   echo 'ACCESS DEFINED!';
//   exit();
// }


/**
*_url_len()求需要长度的字符（包括汉字）
*@return string
*@param $_str 指定输入的字符串
*@param $_strlen 指定最终输出字符串的长度
*/
function _url_len($_str,$_strlen){
	if(mb_strlen($_str,'UTF8') > $_strlen){
		return mb_substr($_str, 0, $_strlen,"UTF-8");//截取5个字符;
	}else
	{
		return $_str;
	}
}

/**
*
*
*/
function _privience($_city){
	$len = mb_strlen($_city,'UTF-8');
	if( $len == 3){
		return mb_substr($_city,0,2,'UTF-8');
	}elseif ($len == 4) {
		return mb_substr($_city,0,3,'UTF-8');
	}elseif ($len == 5) {
		return mb_substr($_city,0,2,'UTF-8');
	}elseif ($len == 6) {
		return mb_substr($_city,0,3,'UTF-8');
	}elseif($len == 7){
		return mb_substr($_city,0,2,'UTF-8');
	}elseif ($len == 8) {
		return mb_substr($_city,0,2,'UTF-8');
	}else{
		return null;
	}

	
}

/**
*
*求时间段
*/
function _time($_hour){
	switch ($_hour) {
		case '1':
			return "00:00-00:59";
			break;
		case '2':
			return "01:00-01:59";
			break;
		case '3':
			return "02:00-02:59";
			break;
		case '4':
			return "03:00-03:59";
			break;
		case '5':
			return "04:00-04:59";
			break;
		case '6':
			return "05:00-05:59";
			break;
		case '7':
			return "06:00-06:59";
			break;
		case '8':
			return "07:00-07:59";
			break;
		case '9':
			return "08:00-08:59";
			break;
		case '10':
			return "09:00-09:59";
			break;
		case '11':
			return "10:00-10:59";
			break;
		case '12':
			return "11:00-11:59";
			break;
		case '13':
			return "12:00-12:59";
			break;
		case '14':
			return "13:00-13:59";
			break;
		case '15':
			return "14:00-14:59";
			break;
		case '16':
			return "15:00-15:59";
			break;
		case '17':
			return "16:00-16:59";
			break;
		case '18':
			return "17:00-17:59";
			break;
		case '19':
			return "18:00-18:59";
			break;
		case '20':
			return "19:00-19:59";
			break;
		case '21':
			return "20:00-20:59";
			break;
		case '22':
			return "21:00-21:59";
			break;
		case '23':
			return "22:00-22:59";
			break;
		case '24':
			return "23:00-23:59";
			break;
		default:
			return "----";
			break;
	}
}
/**
*_datetime()函数的功能就是返回时分秒
*@return 返回的值就是时分秒
*@param $_str 传入的时间字符串
*/

function _datetime($_str){
	$_arr= explode(" ",$_str);
	return $_arr[1];
}
/**
*判断链接类型
*/
function _url_judge($_url){
	if($_url == "-")
		return "直接输入网址或书签";
	if($_url == "/ HTTP/1.0")
		return "来自本页面";
	return $_url;
}

/**
*_page()分页函数参数封装
*@param $_sql 此sql语句就是求出总共有多少条数据
*@param $_size 表示每页有多少条数据
*/
function _page($_sql,$_size){
	//将里面的所有参数取出来，外面可以访问
	global $_page,$_pagenum,$_pagesize,$_pageabsolute,$_num;
	//分页模块
	if(isset($_GET['page'])){
		$_page = $_GET['page'];
		if(empty($_page) || $_page < 0 || !is_numeric($_page)){
				$_page = 1;
			}else{
				$_page = intval($_page);
				}
	 }else{
			$_page = 1;
	}
	$_pagesize = $_size;
	//得到所有的数据总和
	$_num = _num_rows(_query($_sql));

		if($_num == 0){
			$_pageabsolute = 1;
		}else{
			$_pageabsolute  = ceil($_num / $_pagesize);
		}

		if($_page > $_pageabsolute){
			$_page = $_pageabsolute;
		}
	$_pagenum=($_page - 1) * $_pagesize;
}



/**
*_paging()分页函数
*@param $_type 分页类型
*@return 返回分页
*/
function _paging($_type){
	global $_page,$_pageabsolute,$_num;
	if ($_type == 1) {
		echo '<div id="page_num">';
		echo '<ul>';
				 for($i=0;$i<$_pageabsolute;$i++) {
					if($_page == ($i+1)){
						echo '<li><a href="'.SCRIPT.'.php?page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php?page='.($i+1).'">'.($i+1).'</a></li>';
					}
					
				}
		echo '</ul>';
		echo '</div>';
	}elseif ($_type == 2) {
	echo 	'<div id="page_text">';
	echo 	'<ul>';
			echo '<li>'.$_page.'/'.$_pageabsolute.'页&nbsp&nbsp|</li>';
			echo '<li>共有<strong>'.$_num.'</strong>条信息&nbsp&nbsp|</li>';
				if($_page == 1){
					echo '<li>首页&nbsp&nbsp|</li>';
					echo '<li>上一页&nbsp&nbsp|</li>';
				}else{
					echo '<li><a href="'.SCRIPT.'.php">首页</a>&nbsp&nbsp|</li>';
					echo '<li><a href="'.SCRIPT.'.php?page='.($_page-1).'">上一页</a>&nbsp&nbsp|</li>';
				}
				if ($_page == $_pageabsolute) {
					echo '<li>下一页&nbsp&nbsp|</li>';
					echo '<li>尾页&nbsp&nbsp|</li>';
				}else{
					echo '<li><a href="'.SCRIPT.'.php?page='.($_page+1).'">下一页</a>&nbsp&nbsp|</li>';
					echo '<li><a href="'.SCRIPT.'.php?page='.$_pageabsolute.'">尾页</a>&nbsp&nbsp|</li>';
				}
		echo '</ul>';
		echo '</div>';
	}
}





/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @return: array
 */
function _getAddress($ip = '')
{
    if($ip == ''){
        $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
        $ip=json_decode(file_get_contents($url),true);
        $data = $ip;
    }else{
        $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
        $ip=json_decode(file_get_contents($url));   
        if((string)$ip->code=='1'){
           return false;
        }
        $data = (array)$ip->data;
    }
    
    return $data;   
}


/**************************************************************
 *
 * 使用特定function对数组中所有元素做处理
 * @param string &$array  要处理的字符串
 * @param string $function 要执行的函数
 * @return boolean $apply_to_keys_also  是否也应用到key上
 * @access public
 *
 *************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
 
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

/**************************************************************
 *
 * 将数组转换为JSON字符串（兼容中文）
 * @param array $array  要转换的数组
 * @return string  转换得到的json字符串
 * @access public
 *
 *************************************************************/
function JSON($array) {
 arrayRecursive($array, 'urlencode', true);
 $json = json_encode($array);
 return urldecode($json);
}





?>