<?php

/**
 * Copyright (C) 2007 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
Class GoogleCheckOut{
	
 
	function GoogleCheckOut(){
	 
	 // chdir("..");
	  // Include all the required files
	  require_once('library/googlecart.php');
	  require_once('library/googleitem.php');
	  require_once('library/googleshipping.php');
	  require_once('library/googletax.php');
	}
   
  
  public function initiator($arrGoogleCheckOut= array()) {
      
  		// echo "<pre>";
  		 //print_r($arrGoogleCheckOut);
  		 
      $merchant_id 		= $arrGoogleCheckOut['merchant_id'];  // Assign Merchant ID
      $merchant_key 	= $arrGoogleCheckOut['merchant_key']; // Assign Merchant Key
      $server_type 		= $arrGoogleCheckOut['server_type'];  
      $currency 		= $arrGoogleCheckOut['currency']; 
      $cart 			= new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
      //$total_count = $arrGoogleCheckOut['items']['count'];
      
         	
      $item_1 = new GoogleItem($arrGoogleCheckOut['items']['item_name'],    // Item name
                               $arrGoogleCheckOut['items']['item_desc'], 	// Item description
                               $arrGoogleCheckOut['items']['count'], 	// Quantity
                               $arrGoogleCheckOut['items']['amount']); 	// Unit price
      $cart->AddItem($item_1);
      
      /*
      // Add shipping options
      if($total_count < 3){
             $ship_1 = new GoogleFlatRateShipping("USPS Priority Mail", 4.55);
      }else{
             $ship_1 = new GoogleFlatRateShipping("USPS Priority Mail", 6.2);
      }
      $Gfilter = new GoogleShippingFilters();
      $Gfilter->SetAllowedCountryArea('CONTINENTAL_48');
      
      $ship_1->AddShippingRestrictions($Gfilter);
      
      $cart->AddShipping($ship_1);
      */
      // Add tax rules
      $tax_rule = new GoogleDefaultTaxRule(0.05);
      $tax_rule->SetStateAreas(array("MA"));
      $cart->AddDefaultTaxRules($tax_rule);
      
      // Specify <edit-cart-url>
      $url_edit_cart = $arrGoogleCheckOut['url_edit_cart'];
      $cart->SetEditCartUrl($url_edit_cart);
      
      // Specify "Return to xyz" link
      
      $url_continue_shopping = $arrGoogleCheckOut['url_continue_shopping'];
      $cart->SetContinueShoppingUrl($url_continue_shopping);
      
      // Request buyer's phone number
      $cart->SetRequestBuyerPhone(true);
      
      // Display Google Checkout button
      $btn_checkout = $arrGoogleCheckOut['btn_checkout'];
      return $cart->CheckoutButtonCode($btn_checkout);
  }

 
}

?>
