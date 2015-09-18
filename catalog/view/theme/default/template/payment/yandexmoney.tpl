<form method="POST" id='YamoneyForm' action="<?php echo $action; ?>">
   <h3><?php echo $method_label; ?></h3>
   <table class="radio">
		<tbody>
   <?php if ($method_ym){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="PC" checked id="ym1"></td>
			<td><label for="ym1"><?php echo $method_ym_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_cards){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="AC" id="ym2"></td><td><label for="ym2"><?php echo $method_cards_text;?></label></td>
		</tr>
   <?php } 
	if ($org_mode){?>
   <?php if ($method_cash ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="GP" id="ym3"></td><td><label for="ym3"><?php echo $method_cash_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_mobile ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="MC" id="ym4"></td><td><label for="ym4"><?php echo $method_mobile_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_wm ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="WM" id="ym5"></td><td><label for="ym5"><?php echo $method_wm_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_ab ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="AB" id="ym6"></td><td><label for="ym6"><?php echo $method_ab_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_sb ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="SB" id="ym7"></td><td><label for="ym7"><?php echo $method_sb_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_ma ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="MA" id="ym8"></td><td><label for="ym8"><?php echo $method_ma_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_pb ){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="PB" id="ym9"></td><td><label for="ym9"><?php echo $method_pb_text;?></label></td>
		</tr>
		<?php } ?>
		<?php if ($method_mp){?>
		<tr class="highlight">
			<td><input type="radio" name="paymentType" value="MP" id="ym10"></td><td><label for="ym10"><?php echo $method_mp_text;?></label></td>
		</tr>
		<?php } ?>
		</tbody>
   </table>
   <input type="hidden" name="shopid" value="<?php echo $shop_id;?>">
   <input type="hidden" name="scid" value="<?php echo $scid;?>">
   <input type="hidden" name="orderNumber" value="<?php echo $order_id;?>">
   <input type="hidden" name="sum" value="<?php echo $sum;?>" data-type="number" >
   <input type="hidden" name="customerNumber" value="<?php echo $customerNumber; ?>" >
   <input type="hidden" name="shopSuccessURL" value="<?php echo $shopSuccessURL; ?>" >
   <input type="hidden" name="shopFailURL" value="<?php echo $shopFailURL; ?>" >
   <input type="hidden" name="cms_name" value="opencart" >
<?php }else{ ?>
		</tbody>
	</table>
   <input type="hidden" name="receiver" value="<?php echo $account; ?>">
	<input type="hidden" name="formcomment" value="<?php echo $formcomment;?>">
	<input type="hidden" name="short-dest" value="<?php echo $short_dest;?>">
	<input type="hidden" name="writable-targets" value="false">
	<input type="hidden" name="comment-needed" value="true">
	<input type="hidden" name="label" value="<?php echo $order_id;?>">
	<input type="hidden" name="successURL" value="<?php echo $shopSuccessURL; ?>" >
	<input type="hidden" name="quickpay-form" value="shop">
   <input type="hidden" name="targets" value="<?php echo $order_text;?> <?php echo $order_id;?>">
   <input type="hidden" name="sum" value="<?php echo $sum;?>" data-type="number" >
   <input type="hidden" name="comment" value="<?php echo $comment; ?>" >
   <input type="hidden" name="need-fio" value="true">
   <input type="hidden" name="need-email" value="true" >
   <input type="hidden" name="need-phone" value="false">
   <input type="hidden" name="need-address" value="false">
<?php } ?>
	<div class="buttons">
		<div class="right">
			<input type="submit" id="button-confirm" name="submit-button" value="<?php echo $button_confirm; ?>" class="button">
		</div>
	</div>
</form>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=payment/yandexmoney/confirm',
	});
});
$('input[name=paymentType]').bind('click', function() {
	if ($('input[name=paymentType]:checked').val()=='MP'){
		var textMpos='<?php echo $mpos_page_url; ?>';
		$("#YamoneyForm").attr('action', textMpos.replace(/&amp;/g, '&'));
		
	}else{
		$("#YamoneyForm").attr('action', '<?php echo $action; ?>');
	}
});
//--></script> 