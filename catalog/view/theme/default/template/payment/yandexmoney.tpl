<?php if ($org_mode){?>

	<form method="POST" action="<?php echo $action; ?>">
	     <div class="buttons">
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
		   <?php } ?>
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
		  
			<div class="right">
				 <input type="submit" name="submit-button" value="<?php echo $button_confirm; ?>" class="button">
			</div>
	   </div>
	</form>

<?php }else{ ?>
	<form method="POST" action="<?php echo $action; ?>">
	   <input type="hidden" name="receiver" value="<?php echo $account; ?>">
	   <input type="hidden" name="formcomment" value="<?php echo $formcomment;?>">
	   <input type="hidden" name="short-dest" value="<?php echo $short_dest;?>">
	   <input type="hidden" name="writable-targets" value="false">
	   <input type="hidden" name="comment-needed" value="true">
	   <input type="hidden" name="label" value="<?php echo $order_id;?>">
	   <input type="hidden" name="quickpay-form" value="shop">
	   <div class="buttons">
		   <h3><?php echo $method_label; ?></h3>
		   
		   <table class="radio">
			   <tbody>
			   <?php if ($method_ym){?>
				<tr class="highlight">
					<td><input type="radio" name="payment-type" value="PC" checked id="ym1"></td>
					<td><label for="ym1"><?php echo $method_ym_text;?></label></td>
				</tr>
			   <?php } ?>
			   <?php if ($method_cards){?>
			   <tr class="highlight">
					<td><input type="radio" name="payment-type" value="AC" id="ym2"></td><td><label for="ym2"><?php echo $method_cards_text;?></label></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		  
		   <input type="hidden" name="targets" value="<?php echo $order_text;?> <?php echo $order_id;?>">
		   <input type="hidden" name="sum" value="<?php echo $sum;?>" data-type="number" >
		   <input type="hidden" name="comment" value="<?php echo $comment; ?>" >
		   <input type="hidden" name="need-fio" value="true">
		   <input type="hidden" name="need-email" value="true" >
		   <input type="hidden" name="need-phone" value="false">
		   <input type="hidden" name="need-address" value="false">
	   
			<div class="right">
				 <input type="submit" name="submit-button" value="<?php echo $button_confirm; ?>" class="button">
			</div>
	   </div>
	</form>
<?php } ?>
