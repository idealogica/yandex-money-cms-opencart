<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
	
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
	
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		<tr>
            <td><?php echo $entry_status; ?></td>
            <td>
			<select name="yandexmoney_status">
                <?php if ($yandexmoney_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
			 </td>
          </tr>
		 <tr>
            <td><span class="required">*</span> <?php echo $entry_testmode; ?></td>
            <td>
                <?php if ($yandexmoney_testmode) { ?>
					 <input type="radio" name="yandexmoney_testmode" value="1" checked="checked" />
					 <?php echo $text_yes; ?>
					 <input type="radio" name="yandexmoney_testmode" value="0" />
					  <?php echo $text_no; ?>
                <?php } else { ?>
					 <input type="radio" name="yandexmoney_testmode" value="1"  />
					 <?php echo $text_yes; ?>
					 <input type="radio" name="yandexmoney_testmode" value="0" checked="checked"/>
					 <?php echo $text_no; ?>
                <?php } ?>
              </td>
          </tr>
		  
		  <tr>
            <td><span class="required">*</span> <?php echo $entry_modes; ?></td>
            <td>
				<select name="yandexmoney_mode" id="yandexmoney_mode" onchange="yandex_validate_mode();">
					<option value="1"<?php if ($yandexmoney_mode == 1 or !$yandexmoney_mode){?> selected<?php } ?>><?php echo $entry_mode1; ?></option>
					<option value="2"<?php if ($yandexmoney_mode == 2){?> selected<?php } ?>><?php echo $entry_mode2; ?></option>
				</select>
			</td>
          </tr>

		   <tr>
            <td><span class="required">*</span> <?php echo $entry_methods; ?></td>
            <td>
				<input type="checkbox" name="yandexmoney_method_ym" value="1" id="ym_method_1"<?php if ($yandexmoney_method_ym){?> checked <?php } ?> /><label for="ym_method_1"><?php echo $entry_method_ym; ?></label> <br/>
				<input type="checkbox" name="yandexmoney_method_cards" value="1" id="ym_method_2" <?php if ($yandexmoney_method_cards){?> checked <?php } ?>/><label for="ym_method_2"><?php echo $entry_method_cards; ?></label> <br/>
				<div class="org">
					<input type="checkbox" name="yandexmoney_method_cash" value="1" id="ym_method_3" <?php if ($yandexmoney_method_cash){?> checked <?php } ?>/><label for="ym_method_3"><?php echo $entry_method_cash; ?></label> <br/>
					<input type="checkbox" name="yandexmoney_method_mobile" value="1" id="ym_method_4" <?php if ($yandexmoney_method_mobile){?> checked <?php } ?>/><label for="ym_method_4"><?php echo $entry_method_mobile; ?></label> <br/>
					<input type="checkbox" name="yandexmoney_method_wm" value="1" id="ym_method_5"<?php if ($yandexmoney_method_wm){?> checked <?php } ?> /><label for="ym_method_5"><?php echo $entry_method_wm; ?></label> <br/>
				</div>
				 <?php if ($error_methods) { ?>
					<span class="error"><?php echo $error_methods; ?></span>
				 <?php } ?>
			</td>
          </tr>
		
		 <tr class="individ">
			<td></td>
			<td><?php echo $text_welcome1;?></td>
		 </tr>

		  <tr class="org">
			<td></td>
			<td><?php echo $text_welcome2;?></td>
		 </tr>
	
		  <tr class="individ">
			<td></td>
			<td>
				<?php echo $text_params;?>:<br/>
				<table style="border: 1px black solid;">
				  <tr>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_param_name?></td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_param_value?></td>
				  </tr>
				  <tr>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_aviso1?></td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $callback_url?></td>
				   </tr>
				</table>
			</td>
		  </tr>

		   <tr class="org">
			<td></td>
			<td>
				<?php echo $text_params;?>:<br/>
				<table style="border: 1px black solid;">
				  <tr>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_param_name?></td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_param_value?></td>
				  </tr>
				  <tr>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $text_aviso2?></td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $callback_url?></td>
				   </tr>
				   <tr>
						<td style="border: 1px black solid; padding: 5px;">checkURL</td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $check_url?></td>
				   </tr>
				   <tr>
						<td style="border: 1px black solid; padding: 5px;">successURL</td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $shopSuccessURL?></td>
				   </tr>
				   <tr>
						<td style="border: 1px black solid; padding: 5px;">failURL</td>
						<td style="border: 1px black solid; padding: 5px;"><?php echo $shopFailURL?></td>
				   </tr>
				</table>
			</td>
		  </tr>

		  <tr class="individ">
            <td><span class="required">*</span> <?php echo $entry_account; ?></td>
            <td><input type="text" name="yandexmoney_account" value="<?php echo $yandexmoney_account; ?>" />
              <?php if ($error_account) { ?>
					<span class="error"><?php echo $error_account; ?></span>
              <?php } ?></td>
          </tr>

		  <tr>
            <td><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="text" name="yandexmoney_password" value="<?php echo $yandexmoney_password; ?>" />
              <?php if ($error_password) { ?>
					<span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr class="org">
            <td><span class="required">*</span> <?php echo $entry_shopid; ?></td>
            <td><input type="text" name="yandexmoney_shopid" value="<?php echo $yandexmoney_shopid; ?>" />
              <?php if ($error_shopid) { ?>
              <span class="error"><?php echo $error_shopid; ?></span>
              <?php } ?></td>
          </tr>
          <tr class="org">
            <td><span class="required">*</span> <?php echo $entry_scid; ?></td>
            <td><input type="text" name="yandexmoney_scid" value="<?php echo $yandexmoney_scid; ?>" />
              <?php if ($error_scid) { ?>
              <span class="error"><?php echo $error_scid; ?></span>
              <?php } ?></td>
          </tr>
         
          <tr>
            <td>
				<?php echo $entry_total; ?>
				<br/>
				<span class="help"><?php echo $entry_total2; ?></span>
			</td>
            <td><input type="text" name="yandexmoney_total" value="<?php echo $yandexmoney_total; ?>" /></td>
          </tr>          
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="yandexmoney_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $yandexmoney_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="yandexmoney_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $yandexmoney_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="yandexmoney_sort_order" value="<?php echo $yandexmoney_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
	function yandex_validate_mode(){
		var yandex_mode = $("#yandexmoney_mode").val();
		if (yandex_mode == 1){
			$(".individ").show();
			$(".org").hide();
		}else{
			$(".org").show();
			$(".individ").hide();
		}
	}
	$( document ).ready(function() {
		yandex_validate_mode();
	});
</script>

<?php echo $footer; ?>