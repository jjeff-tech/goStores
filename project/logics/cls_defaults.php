<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : cls_defaults.php                                         		  |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Defaults {

    //function to get plans & their features
     public static function getPlanFeatures() {
        $db     = new Db();
        
        $prefix          = $db->tablePrefix;
       /* $planFeatures        = $db->getAllData('PS.nServiceId , PS.vServiceName , PS.price , PS.vBillingInterval, PSF.vFeatureValue, SF.tFeatureName, PSF.nServiceFeatureId, PS.nQty',
                                           'ProductServices PS', 
                                           "LEFT JOIN {$prefix}ProductServiceFeatures PSF ON PS.nServiceId = PSF.nProductServiceId LEFT JOIN {$prefix}ServiceFeatures SF ON SF.nFeatureId = PSF.nServiceFeatureId", 
                                           "WHERE PS.nSCatId = 1 AND SF.eStatus = 'Active' AND PS.nStatus = 1 AND PS.price != 0 AND PS.vType != 'free' ORDER BY PS.price DESC ");
*/

     /*  $planFeatures        = $db->getAllData('PS.nServiceId , PS.vServiceName , PS.price , PS.vBillingInterval, SF.tValue as vFeatureValue, SF.tFeatureName, PSF.nServiceFeatureId, PS.nQty',
                                               'ProductServices PS',
                                               "LEFT JOIN {$prefix}ProductServiceFeatures PSF ON PS.nServiceId = PSF.nProductServiceId LEFT JOIN {$prefix}ServiceFeatures SF ON SF.nFeatureId = PSF.nServiceFeatureId",
                                               "WHERE PS.nSCatId = 1 AND SF.eStatus = 'Active' AND PS.nStatus = 1 AND PS.price != 0 AND PS.vType != 'free' ORDER BY PS.price DESC ");
*/
       $planName = $db->getAllData('PS.nServiceId , PS.vServiceName , PS.price,PS.vServiceDescription , PS.vBillingInterval , PS.nBillingDuration , PS.nQty,PS.vType',
                                               'ProductServices PS',
                                               "",
                                               "WHERE PS.nSCatId = 1 AND  PS.nStatus = 1   ORDER BY PS.nServiceId ASC ");

        $Services = $db->getAllData('SF.tValue as vFeatureValue, SF.tFeatureName, SF.nFeatureId',
                                               'ServiceFeatures SF',
                                               "",
                                               "WHERE SF.eStatus = 'Active' ORDER BY tFeatureName");

             $plans = array();
             $feacherArray =array();
             $feacherArray = self::getFeatures();
             $feachervalueArray =array();
           
             foreach ($Services as $key2 => $servicevalues) { 
               $feacherArray[$servicevalues->nFeatureId] = $servicevalues->tFeatureName;
                $feachervalueArray[$servicevalues->nFeatureId] = $servicevalues->vFeatureValue;
               
             }

    foreach ($planName as $key1 => $plan) {
            $plans[$plan->nServiceId]['plan_name'] = $plan->vServiceName;
            $plans[$plan->nServiceId]['plan_cost'] = $plan->price;
            $plans[$plan->nServiceId]['plan_type'] = $plan->vBillingInterval;
            $plans[$plan->nServiceId]['plan_duration'] = $plan->nBillingDuration;
            $plans[$plan->nServiceId]['vType'] = $plan->vType;
            $plans[$plan->nServiceId]['vServiceDescription'] = $plan->vServiceDescription;
            $plans[$plan->nServiceId]['permonth_price'] = $plan->permonth_price;
            $plans[$plan->nServiceId]['trasaction_fee'] = $plan->trasaction_fee;
            $plans[$plan->nServiceId]['savings'] = $plan->savings;
            $plans[$plan->nServiceId]['third_party_transaction'] = $plan->third_party_transaction;
            $plans[$plan->nServiceId]['makeready_payments'] = $plan->makeready_payments;
            $plans[$plan->nServiceId]['dropship_fee'] = $plan->dropship_fee;
            $plans[$plan->nServiceId]['plan_id'] = $plan->nServiceId;
          //  $plans[$plan->nServiceId]['feature_value'][0] = $plan->nQty;
            $plans[$plan->nServiceId]['plan_features'] = $feacherArray;
            $plans[$plan->nServiceId]['feature_value'] = $feachervalueArray;
            $plans[$plan->nServiceId]['feature_value'][0] = $plan->nQty;

            

        }

//echopre($plans);
/*$plans = array();

        if($planFeatures){
            foreach($planFeatures as $feature) {
                $plans[$feature->nServiceId]['plan_name'] = $feature->vServiceName;
                $plans[$feature->nServiceId]['plan_features'][$feature->nServiceFeatureId] = $feature->tFeatureName;
                $plans[$feature->nServiceId]['feature_value'][$feature->nServiceFeatureId] = $feature->vFeatureValue;
                $plans[$feature->nServiceId]['plan_cost'] = $feature->price;
                $plans[$feature->nServiceId]['plan_type'] = $feature->vBillingInterval;
                $plans[$feature->nServiceId]['plan_id'] = $feature->nServiceId;
                $plans[$feature->nServiceId]['feature_value'][0] = $feature->nQty;
            }
        }
echopre($plans);*/
        return $plans;
    } 
    
    //function to get list of plan features
    public static function getFeatures() {
        $db     = new Db();
        
        $serviceFeatures = $db->getAllData('*', 'ServiceFeatures', '', "WHERE eStatus = 'Active' ORDER BY tFeatureName");
        $services = array();
        $services[0] = 'Supported Products';
        if($serviceFeatures){
            foreach($serviceFeatures as $service) {
                $services[$service->nFeatureId] = $service->tFeatureName;
            }
        }
        
        return $services;
    }
    
} //End Class


?>