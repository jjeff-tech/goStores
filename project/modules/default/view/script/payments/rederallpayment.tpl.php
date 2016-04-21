<table cellspacing="4" cellpadding="0" border="0" width="99%" class="card_options">
    <tr>
        <?php
        //  echopre(PageContext::$response->paymnetsEnabled);
        $paymentCount = 0;
        if(isset (PageContext::$response->paymnetsEnabled['paypal_enable']) && PageContext::$response->paymnetsEnabled['paypal_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypallogo.jpg" height="75" title="Paypal" width="100" onclick="setcurrentPaymnet('paypal')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';

        if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/paypalpro.jpg" height="75" width="100" title="Paypalpro" onclick="showPaymentdiv('paypalpro')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/icon-paypal-payflow.jpg" height="75" width="100" title="Paypalflow" onclick="showPaymentdiv('paypalflow')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypaladvanced_enable']) && PageContext::$response->paymnetsEnabled['paypaladvanced_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypaladvance.png" height="75" title="Paypaladvance" width="100" onclick="setcurrentPaymnet('paypaladvanced')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalexpress_enable']) && PageContext::$response->paymnetsEnabled['paypalexpress_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!-- <img src="<?php echo BASE_URL; ?>project/styles/images/btn_xpressCheckout.gif" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/btn_xpressCheckout.gif" height="75" title="Paypalexpress" width="100" onclick="setcurrentPaymnet('paypalxpress')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalflowlink_enable']) && PageContext::$response->paymnetsEnabled['paypalflowlink_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
          <!--  <img src="<?php echo BASE_URL; ?>project/styles/images/paylinkicon.jpg" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paylinkicon.jpg" height="75" width="100" title="Paypalflowlink" onclick="setcurrentPaymnet('paypalflowlink')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        ?>

        <?php
        if(isset (PageContext::$response->paymnetsEnabled['ogone_enable']) && PageContext::$response->paymnetsEnabled['ogone_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
            <!--<img src="<?php echo BASE_URL; ?>project/styles/images/logo_ogone.jpg" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/logo_ogone.jpg" height="75" width="100" title="Ogone" onclick="setcurrentPaymnet('ogone')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        ?>
        <?php
        if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/creditcard.jpg" height="75" width="100" title="Authorize" onclick="showPaymentdiv('authorize')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['twoco_enable']) && PageContext::$response->paymnetsEnabled['twoco_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100" title="Twocheckout" onclick="setcurrentPaymnet('twocheckout')">
        </td>
            <?php
        }if
        ($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['braintree_enable']) && PageContext::$response->paymnetsEnabled['braintree_enable'] == 'Y') {
            $paymentCount++;

    ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/braintree_logo.png" height="75" width="100" title="Braintree" onclick="setcurrentPaymnet('braintree')">
        </td>
            <?php
        }
?>

    </tr>
    <tr>
        <td colspan="5" class="cls_payment">
            <input type="hidden" name="currentpaymant"  id="currentpaymant" value="">
            <?php
            if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y') {
            ?>
            <div id="paypalpro" class="allpayment"  style="display:none">
            <?php PageContext::renderPostAction('personalinfo','payments'); ?>
            <?php  PageContext::renderPostAction('paypalpro','payments');?>
            </div>
                <?php
            }
            ?>
            <?php
            if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y') {
    ?>
            <div id="paypalflow" class="allpayment"  style="display:none">
    <?php PageContext::renderPostAction('personalinfo','payments'); ?>
    <?php  PageContext::renderPostAction('paypalflow','payments');?>
            </div>
                <?php
            }
            ?>
            <?php
            // if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y'){
?>
            <div id="authorize" class="allpayment" style="display:none" >
    <?php PageContext::renderPostAction('personalinfo','payments'); ?>
    <?php PageContext::renderPostAction('authorize','payments');?>
            </div>
            <?php
            //  }
?>
        </td>
    </tr></table>