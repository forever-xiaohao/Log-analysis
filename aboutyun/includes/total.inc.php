
<?php
$_sql_total="select TotalPV,TotalIP,TotalPeople,date from v_allstatistics order by date desc limit 1";
$_result_total=_fetch_array($_sql_total);
?>

		<div class="trend_cent_two">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td><p class="tr_c_p">浏览次数（PV）</p><p class="tr_c_t_p"><?php echo $_result_total['TotalPV'];?></p></td>
					<td><p class="tr_c_p">独立访客（UV）</p><p class="tr_c_t_p">----</p></td>
					<td><p class="tr_c_p">IP数</p><p class="tr_c_t_p">3<?php echo $_result_total['TotalIP'];?></p></td>
					<td><p class="tr_c_p">新独立访客</p><p class="tr_c_t_p">----</p></td>
					<td><p class="tr_c_p">访问次数</p><p class="tr_c_t_p"><?php echo $_result_total['TotalPeople'];?></p></td>
				</tr>
			</table>
		</div>