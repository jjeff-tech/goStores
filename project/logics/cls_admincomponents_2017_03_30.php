<?php
/*
* All Admincomponents Entity Logics should come here.
* Author : Meena Susan Joseph <meena.s@armiasystems.com>
* Created On : 25 AUG 2012
*/

class Admincomponents {

    public static $dbObj = null;

    /*
     * Method : Get Module Name By ID <M>
    */
    
    public static function getModuleName($moduleID) {
        Admincomponents::$dbObj = new Db();
        $module = Admincomponents::$dbObj->selectRecord("Module","vModuleName","nMId = '".$moduleID."' AND nStatus = '1'");
        $moduleName = NULL;
        if(!empty($module)) {
            $moduleName = $module->vModuleName;
        }
        return $moduleName;
    } // End Function

    public static function getActiveModules() {
        Admincomponents::$dbObj = new Db();
        $module = Admincomponents::$dbObj->selectResult("Module","nMId, vModuleName","nStatus = '1' ORDER BY vModuleName");
        return $module;
    } // End Function

    public static function getAdminUserModules($roleID) {
        Admincomponents::$dbObj = new Db();
        $moduleArr = array();
        $access = Admincomponents::$dbObj->selectRecord("Permission","nRid, vPermission","nRid = '".$roleID."'");
        if(!empty($access)) {
            $acessArr = explode(",", $access->vPermission);
            foreach($acessArr as $module) {
                $moduleName = NULL;
                $moduleName = Admincomponents::getModuleName($module);
                if(!empty($moduleName)) {
                    $moduleArr[] = $moduleName;
                }
            } // End Foreach
        }

        return $moduleArr;
    } // End Function

    public static function getRoleById($roleID) {
        Admincomponents::$dbObj = new Db();
        $selRole = "SELECT r.nRid, r.vRoleName, r.nStatus, r.dCreatedOn, p.vPermission FROM ".Admincomponents::$dbObj->tablePrefix."Role r LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Permission p ON r.nRid = p.nRid WHERE r.nRid = '".$roleID."'";
        $role = Admincomponents::$dbObj->selectQuery($selRole);
        return $role;
    } // End Function

    public static function getRoleData($roleID) {
        $roleData = array();
        $roleData['nRid'] = $roleData['vRoleName'] = $roleData['nStatus'] = NULL;
        if(!empty($roleID)) {
            $role = Admincomponents::getRoleById($roleID);
            $roleData['nRid'] = $role[0]->nRid;
            $roleData['vRoleName'] = $role[0]->vRoleName;
            $roleData['nStatus'] = $role[0]->nStatus;
            $roleData['vPermission'] = $role[0]->vPermission;
        }
        return $roleData;
    } // End Function

    public static function getRoles($search = NULL, $limit = NULL, $filterArr = NULL, $orderArr=NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter = (!empty($search)) ? "vRoleName LIKE '".addslashes($search)."%'" : "";
        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item
        $filter .= (!empty($filter)) ? "" : " 1";
        // FILTER WITH ORDER BY
        if(!empty($orderArr)) {
            $sortBy = (!empty($orderArr['sort'])) ? $orderArr['sort'] : 'ASC';
            foreach($orderArr['fields'] as $orderItem) {
                $order .= (!empty($order)) ? ', ' : '';
                $order .= (!empty($orderItem)) ? $orderItem : '';
            } // End Foreach
            $filter .= (!empty($order)) ? " ORDER BY ".$order." ".$sortBy : NULL;
        } // End If
        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;

        $role = Admincomponents::$dbObj->selectResult("Role","nRid, vRoleName, nStatus, dCreatedOn",$filter);
        return $role;
    } // End Function

    public static function saveRole($roleData) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;
        $roleDet = array();
        if(!empty($roleData)) {
            $duplicateRole = (empty($roleData['nRid'])) ? Admincomponents::$dbObj->selectRecord("Role","nRid, vRoleName, nStatus","LOWER(vRoleName) = '".strtolower($roleData['vRoleName'])."'") : Admincomponents::$dbObj->selectRecord("Role","nRid, vRoleName, nStatus","LOWER(vRoleName) = '".strtolower($roleData['vRoleName'])."' AND nRid != '".$roleData['nRid']."'") ;
            $modulePermission = join(",", $roleData['moduleAccess']);
            if(empty($duplicateRole)) {
                if(empty($roleData['nRid'])) {
                    //Insert new role
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Role SET nRid = NULL, vRoleName = '".addslashes($roleData['vRoleName'])."', nStatus = '1', dCreatedOn= NOW()";
                    Admincomponents::$dbObj->execute($itemQry);
                    $roleID = Admincomponents::$dbObj->lastInsertId();
                    $item2Qry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Permission SET nId = NULL, nRid ='".$roleID."', vPermission = '".$modulePermission."', dCreatedOn= NOW(), dLastUpdated = NULL";
                    Admincomponents::$dbObj->execute($item2Qry);
                } else {
                    //Update role
                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Role SET vRoleName = '".addslashes($roleData['vRoleName'])."' WHERE nRid='".$roleData['nRid']."'";
                    Admincomponents::$dbObj->execute($itemQry);
                    $roleID = $roleData['nRid'];

                    // Select Permission
                    $permission = Admincomponents::$dbObj->selectRecord("Permission","vPermission","nRid='".$roleData['nRid']."'");
                    if(empty($permission)) {
                        $item2Qry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Permission SET nId = NULL, nRid ='".$roleData['nRid']."', vPermission = '".$modulePermission."', dCreatedOn= NOW(), dLastUpdated = NULL";
                    } else {
                        $item2Qry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Permission SET vPermission = '".$modulePermission."', dLastUpdated = NOW() WHERE nRid='".$roleData['nRid']."'";
                    }
                    Admincomponents::$dbObj->execute($item2Qry);

                }
            } else {
                $errMsg = 'Role name already exists! Try another role name.';
            }
            $roleDet['id']= $roleID;
            $roleDet['errMsg']= $errMsg;
        } // End If
        return $roleDet;

    } // End Function

    public static function createCoupon($noOfCoupons,$expiryDate,$discountRate,$description, $couponCode, $pricingMode = NULL) {
        //  $couponCode = substr(strtoupper(md5($noOfCoupons.$expiryDate)),0,6);
        Admincomponents::$dbObj = new Db();
        $postedArray    	= array("vCouponCode"		=> $couponCode,
                "vCouponDescription"	=> $description,
                "nCouponValue"		=> $discountRate,
                "dCreatedOn"            => date("Y-m-d H:i:s", time()),
                "dExpireOn"		=> date("Y-m-d H:i:s", strtotime($expiryDate)),
                "nCouponCount"		=> $noOfCoupons,
                "nCouponUsed"		=> 0,
                "vPricingMode"		=> $pricingMode);
        $status = Admincomponents::$dbObj->addFields("Coupon",$postedArray);
        return $status;
    }

    public static function updateCoupon($noOfCoupons,$expiryDate,$discountRate,$description,$couponId, $couponName, $pricingMode = NULL) {
        $couponCode = substr(strtoupper(md5($noOfCoupons.$expiryDate)),0,6);
        Admincomponents::$dbObj = new Db();
        $postedArray    	= array("vCouponDescription"	=> $description,
                "nCouponValue"		=> $discountRate,
                "dExpireOn"		=> date("Y-m-d H:i:s", strtotime($expiryDate)),
                "nCouponCount"		=> $noOfCoupons,
                "vCouponCode"		=> $couponName,
                "vPricingMode"		=> $pricingMode);
        $status = Admincomponents::$dbObj->updateFields("Coupon",$postedArray,"nCouponId='$couponId'");
        return $status;
    }

    /*
     * function to fetch coupon details
    */
    public static function getCoupon($search = NULL, $orderArr = NULL, $limit) {
        Admincomponents::$dbObj = new Db();
        $filter = (!empty($search)) ? "vCouponCode LIKE '".addslashes($search)."%'" : "";
        $filter .= (!empty($filter)) ? "" : " 1";
        // FILTER WITH ORDER BY
        if(!empty($orderArr)) {
            $sortBy = (!empty($orderArr['sort'])) ? $orderArr['sort'] : 'ASC';
            foreach($orderArr['fields'] as $orderItem) {
                $order .= (!empty($order)) ? ', ' : '';
                $order .= (!empty($orderItem)) ? $orderItem : '';
            } // End Foreach
            $filter .= (!empty($order)) ? " ORDER BY ".$order." ".$sortBy : NULL;
        } // End If

        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
        $coupon = Admincomponents::$dbObj->selectResult("Coupon","nCouponId,vCouponCode,nCouponValue, dExpireOn, nCouponCount, nCouponUsed, vPricingMode",$filter);
        return $coupon;
    } // End Function

    public static function deleteCoupon($couponId) {
        Admincomponents::$dbObj = new Db();
        $status = Admincomponents::$dbObj->deleteRecord("Coupon",$couponId);
        return $status;
    }

    public static function getCouponDetails($couponId = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = (!empty($couponId)) ? "nCouponId= '".$couponId."'" : "";
        $coupon = Admincomponents::$dbObj->selectResult("Coupon","*",$filter);
        return $coupon;
    } // End Function

    public static function getPlans($search = NULL, $limit = NULL, $filterArr = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter .= (!empty($search)) ? "vPlanName LIKE '".$search."%'" : NULL;

        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item

        $filter .= (!empty($filter)) ? " AND " : NULL;
        $filter .= "nDeleteStatus != '1'"; // List items that are not deleted

        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
        $data = Admincomponents::$dbObj->selectResult("Plans","nPlanId, vPlanName, nStatus, dCreatedOn",$filter);
        return $data;
    } // End Function

    public static function getPlanById($planId = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $data = array();
        if(!empty($planId)) {
            $filter .= (!empty($planId)) ? "nPlanId = '".$planId."'" : NULL;
            $data = Admincomponents::$dbObj->selectRecord("Plans","nPlanId, vPlanName, vDescription, dCreatedOn, nStatus",$filter);
        }

        return $data;
    } // End Function

    public static function savePlan($data) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;
        $itemDet = array();
        if(!empty($data)) {
            $duplicateItem = (empty($data['nPlanId'])) ? Admincomponents::$dbObj->selectRecord("Plans","nPlanId, vPlanName, nStatus","LOWER(vPlanName) = '".strtolower($data['vPlanName'])."'") : Admincomponents::$dbObj->selectRecord("Plans","nPlanId, vPlanName, nStatus","LOWER(vPlanName) = '".strtolower($data['vPlanName'])."' AND nPlanId != '".$data['nPlanId']."'") ;

            if(empty($duplicateItem)) {
                if(empty($data['nPlanId'])) {
                    //Insert new Plans
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Plans SET nPlanId = NULL, vPlanName = '".addslashes($data['vPlanName'])."', vDescription = '".addslashes($data['vDescription'])."', nStatus = '1', dCreatedOn= NOW()";
                    Admincomponents::$dbObj->execute($itemQry);

                } else {
                    //Update Plans
                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Plans SET vPlanName = '".addslashes($data['vPlanName'])."', vDescription = '".addslashes($data['vDescription'])."' WHERE nPlanId='".$data['nPlanId']."'";
                    Admincomponents::$dbObj->execute($itemQry);
                    $PlansID = $data['nPlanId'];

                }
            } else {
                $errMsg = 'Plan name already exists! Try another name.';
            }
            $itemDet['id']= $PlansID;
            $itemDet['errMsg']= $errMsg;
        } // End If

        return $itemDet;

    } // End Function

    public static function updatePlanPackages($itemData, $field, $value) {
        Admincomponents::$dbObj = new Db();
        $data = Admincomponents::$dbObj->selectResult("PlanPackages","nPlanId, vPlanName, nStatus, dCreatedOn",$field."='".$value."'");
        if(!empty($data)) {
            Admincomponents::$dbObj->updateFields("PlanPackages",$itemData,$field."='".$value."'");
        } // End Data
    } // End Function

    public static function adminPaginationContent($pageInfo, $navigationUrl) {
        $content = $previous = $next = NULL;
        
        if($pageInfo['page']!=1) {
            $previous = $pageInfo['page']-1;
            $content .='<a class="first" href="'.$navigationUrl.'1">&laquo; First</a>';
            $content .='<a class="previouspostslink" href="'.$navigationUrl.$previous.'">&laquo;</a>';
        } else {
            $content .='<span class="current">&laquo; First</span>';
            $content .='<span class="current">&laquo;</span>';
        }
        //<a class="page smaller" href="#">2</a>
        //<a class="page smaller" href="#">3</a>
        $content .='<span class="current">Page '.$pageInfo['page'].' / '.$pageInfo['maxPages'].'</span>';
        //<a class="page larger" href="#">5</a>
        //<a class="page larger" href="#">6</a>
        if($pageInfo['page']!=$pageInfo['maxPages']) {
            $next = $pageInfo['page']+1;
            $content .='<a class="nextpostslink" href="'.$navigationUrl.$next.'">&raquo;</a>';
            $content .='<a class="last" href="'.$navigationUrl.$pageInfo['maxPages'].'">Last &raquo;</a>';

        } else {
            $content .='<span class="current">&raquo;</span>';
            $content .='<span class="current">Last &raquo;</span>';
        }

        return $content;
    } //End Function
    
    public static function adminApiPaginationContent($pageInfo, $navigationUrl,$extra_parameters=NULL) {
        $content = $previous = $next = NULL;
        //echo "<pre>"; print_r($pageInfo); echo "</pre>"; 
        
        if($pageInfo['page']!=1) {
            $previous = $pageInfo['page']-1;
            $content .='<a class="first" href="'.$navigationUrl.'&page=1'.$extra_parameters.'">&nbsp;&laquo;&nbsp;First&nbsp;</a>';
            if($previous){
                $content .='<a class="previouspostslink" href="'.$navigationUrl."&page=".$previous.$extra_parameters.'">&nbsp;&laquo;&nbsp;Previous&nbsp;</a>';
            }else{
                $content .='<a class="previouspostslink" href="'.$navigationUrl.$previous.$extra_parameters.'">&nbsp;&nbsp;&nbsp;&laquo;&nbsp;Previous&nbsp;&nbsp;&nbsp;</a>';
            }
        } else {
            $content .='<span class="current">&nbsp;&laquo;&nbsp;First&nbsp;</span>';
            $content .='<span class="current">&nbsp;&laquo;&nbsp;Previous&nbsp;</span>';
        }
        //<a class="page smaller" href="#">2</a>
        //<a class="page smaller" href="#">3</a>
        $content .='<span class="current" style="margin-left:20px;margin-right:20px;">Page '.$pageInfo['page'].' / '.$pageInfo['maxPages'].'</span>';
        //<a class="page larger" href="#">5</a>
        //<a class="page larger" href="#">6</a>
        if($pageInfo['page'] != $pageInfo['maxPages']) {
            $next = $pageInfo['page']+ 1;
            $content .='<a class="nextpostslink" href="'.$navigationUrl."&page=".$next.$extra_parameters.'">&nbsp;Next&nbsp;&raquo;&nbsp;</a>';
            $content .='<a class="last" href="'.$navigationUrl."&page=".$pageInfo['maxPages'].$extra_parameters.'">Last &raquo;</a>';
        }else{
            $content .='<span class="current">&nbsp;Next&nbsp;&raquo;&nbsp;</span>';
            $content .='<span class="current">Last &raquo;</span>';
        }
        return $content;
    } //End Function

    public static function getPlanPurchaseCategoryById($itemId = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $data = array();
        if(!empty($itemId)) {
            $filter .= (!empty($itemId)) ? "nSCatId = '".$itemId."'" : NULL;
            $data = Admincomponents::$dbObj->selectRecord("ServiceCategories","nSCatId, vCategory, vDescription, vInputType, vOrderofDisplay, dCreatedOn, nStatus",$filter);
        }

        return $data;
    } // End Function

    public static function deletePlanPurchaseItem($itemId, $action = NULL) {
        Admincomponents::$dbObj = new Db();
        if(!empty($itemId)) {
            if(empty($action)) {
                //Delete from plan purchase Category
                Admincomponents::$dbObj->deleteRecord("ServiceCategories","nSCatId = '".$itemId."'");
                //Delete from plan purchase Category Details
                Admincomponents::$dbObj->deleteRecord("PlanPurchaseCategoryDetails","nPlanPurchaseCategoryId = '".$itemId."'");

            } else {
                //Delete from plan purchase Category Details
                Admincomponents::$dbObj->deleteRecord("PlanPurchaseCategoryDetails","nPlanPurchaseCategoryId = '".$itemId."'");
            }


        } // End If

    } // End Function

    public static function getPlanPurchaseCategory($search = NULL, $limit = NULL, $filterArr = NULL, $orderArr=NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter .= (!empty($search)) ? "vCategory LIKE '".addslashes($search)."%'" : NULL;

        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item

        $filter .= (!empty($filter)) ? "" : " 1";
        // FILTER WITH ORDER BY
        if(!empty($orderArr)) {
            $sortBy = (!empty($orderArr['sort'])) ? $orderArr['sort'] : 'ASC';
            foreach($orderArr['fields'] as $orderItem) {
                $order .= (!empty($order)) ? ', ' : '';
                $order .= (!empty($orderItem)) ? $orderItem : '';
            } // End Foreach
            $filter .= (!empty($order)) ? " ORDER BY ".$order." ".$sortBy : NULL;
        } // End If

        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
        $data = Admincomponents::$dbObj->selectResult("ServiceCategories","nSCatId, vCategory, vDescription, vOrderofDisplay, nStatus, dCreatedOn",$filter);

        return $data;
    } // End Function

    public static function updateListItem($itemData, $tableName, $field, $value) {
        Admincomponents::$dbObj = new Db();
        Admincomponents::$dbObj->updateFields($tableName,$itemData, $field."='".$value."'");

    } // End Function

    public static function savePlanPurchaseCategory($data) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;

        $itemDet = array();
        if(!empty($data)) {
            $duplicateItem = (empty($data['nId'])) ? Admincomponents::$dbObj->selectRecord("ServiceCategories","nSCatId, vCategory, nStatus","LOWER(vCategory) = '".strtolower($data['vCategory'])."'") : Admincomponents::$dbObj->selectRecord("ServiceCategories","nSCatId, vCategory, nStatus","LOWER(vCategory) = '".strtolower($data['vCategory'])."' AND nSCatId != '".$data['nId']."'") ;

            if(empty($duplicateItem)) {
                if(empty($data['nId'])) {
                    //Insert new Plans
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ServiceCategories SET nSCatId = NULL, vCategory = '".addslashes($data['vCategory'])."', vDescription = '".addslashes($data['vDescription'])."', vInputType='".$data['vInputType']."', vOrderofDisplay = NULL, nStatus = '1', dCreatedOn= NOW()";
                    Admincomponents::$dbObj->execute($itemQry);

                } else {
                    //Update Plans
                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ServiceCategories SET vCategory = '".addslashes($data['vCategory'])."', vDescription = '".addslashes($data['vDescription'])."', vInputType='".$data['vInputType']."' WHERE nSCatId='".$data['nId']."'";
                    Admincomponents::$dbObj->execute($itemQry);
                    $itemID = $data['nId'];

                }
            } else {
                $errMsg = 'Service Category name already exists! Try another Service Category name.';
            }
            $itemDet['id']= $itemID;
            $itemDet['errMsg']= $errMsg;
        } // End If

        return $itemDet;

    } // End Function

    public static function getListItemById($itemId = NULL, $itemField = NULL, $fieldArr = NULL, $table = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = $fieldList = NULL;

        $data = array();
        if(!empty($itemId)) {
            $filter .= (!empty($itemId)) ? $itemField."= '".$itemId."'" : NULL;
            if(!empty($fieldArr)) {
                foreach($fieldArr as $field) {
                    $fieldList .= (!empty($fieldList)) ? ', ' : '';
                    $fieldList .= (!empty($field)) ? $field : '';
                }

                $data = Admincomponents::$dbObj->selectRecord($table,$fieldList,$filter);

            } // End FieldArr

        }

        return $data;
    } // End Function

    public static function getPlanPurchaseCategoryDetails($search = NULL, $limit = NULL, $filterArr = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter .= (!empty($search)) ? "vDescription LIKE '".$search."%'" : NULL;

        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item

        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
        $data = Admincomponents::$dbObj->selectResult("PlanPurchaseCategoryDetails","nId, nPlanPurchaseCategoryId, vDescription, nAmount, nIsMandatory, nStatus",$filter);

        return $data;
    } // End Function

    public static function savePlanPurchaseCategoryDetail($data) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;
        $itemDet = array();
        if(!empty($data)) {
            $duplicateItem = (empty($data['nId'])) ? Admincomponents::$dbObj->selectRecord("PlanPurchaseCategoryDetails","nId, nPlanPurchaseCategoryId, nStatus","LOWER(vDescription) = '".strtolower($data['vDescription'])."'") : Admincomponents::$dbObj->selectRecord("PlanPurchaseCategoryDetails","nId, nPlanPurchaseCategoryId, nStatus","LOWER(vDescription) = '".strtolower($data['vDescription'])."' AND nId != '".$data['nId']."'") ;

            if(empty($duplicateItem)) {
                if(empty($data['nId'])) {
                    //Insert new Plans
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."PlanPurchaseCategoryDetails SET nId = NULL, nPlanPurchaseCategoryId = '".addslashes($data['nPlanPurchaseCategoryId'])."', vDescription = '".addslashes($data['vDescription'])."', nAmount = '".$data['nAmount']."', nIsMandatory = '".$data['nIsMandatory']."', nStatus = '1', dCreatedOn= NOW()";
                    Admincomponents::$dbObj->execute($itemQry);

                } else {
                    //Update Plans
                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."PlanPurchaseCategoryDetails SET nPlanPurchaseCategoryId = '".addslashes($data['nPlanPurchaseCategoryId'])."', vDescription = '".addslashes($data['vDescription'])."', nAmount = '".$data['nAmount']."', nIsMandatory = '".$data['nIsMandatory']."' WHERE nId='".$data['nId']."'";
                    Admincomponents::$dbObj->execute($itemQry);
                    $itemID = $data['nId'];

                }
            } else {
                $errMsg = 'Plan Purchase Category name already exists! Try another name.';
            }
            $itemDet['id']= $itemID;
            $itemDet['errMsg']= $errMsg;
        } // End If

        return $itemDet;

    } // End Function

    public static function getPlanPackages($search = NULL, $limit = NULL, $filterArr = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter .= (!empty($search)) ? "vDescription LIKE '".$search."%'" : NULL;

        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item

        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
        $data = Admincomponents::$dbObj->selectResult("PlanPackages","nPPId, nPlanId, vDescription, nPlanAmount, dCreatedOn, nStatus, nDeleteStatus",$filter);

        return $data;
    } // End Function

    public static function savePlanPackages($data) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;
        $itemDet = array();
        if(!empty($data)) {
            $duplicateItem = (empty($data['nPPId'])) ? Admincomponents::$dbObj->selectRecord("PlanPackages","nPPId, nPlanId, nStatus","LOWER(vDescription) = '".strtolower($data['vDescription'])."'") : Admincomponents::$dbObj->selectRecord("PlanPackages","nPPId, nPlanId, nStatus","LOWER(vDescription) = '".strtolower($data['vDescription'])."' AND nPPId != '".$data['nPPId']."'") ;

            if(empty($duplicateItem)) {
                if(empty($data['nPPId'])) {
                    //Insert new Plans
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."PlanPackages SET nPPId = NULL, nPlanId = '".addslashes($data['nPlanId'])."', vDescription = '".addslashes($data['vDescription'])."', nPlanAmount = '".$data['nPlanAmount']."', nStatus = '1', dCreatedOn= NOW()";
                    Admincomponents::$dbObj->execute($itemQry);

                } else {
                    //Update Plans
                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."PlanPackages SET nPlanId = '".addslashes($data['nPlanId'])."', vDescription = '".addslashes($data['vDescription'])."', nPlanAmount = '".$data['nPlanAmount']."' WHERE nPPId='".$data['nPPId']."'";
                    Admincomponents::$dbObj->execute($itemQry);
                    $itemID = $data['nPPId'];

                }
            } else {
                $errMsg = 'Plan Package name already exists! Try another name.';
            }
            $itemDet['id']= $itemID;
            $itemDet['errMsg']= $errMsg;
        } // End If

        return $itemDet;

    } // End Function

    /*
     * Function : getListItem
     * Input : @table <table name to select>
     * Input : @fieldArr <field names as an array to select, eg : array('a', 'b', 'c')>
     * Input : @filterArr <field names as an array to supply in WHERE clause, eg : array('a', 'b', 'c')>
     * Input : @orderArr <array input 1 : sort order, array input 2: sort fields as an array eg : array('sort' => 'ASC', 'fields' => array('a', 'b', 'c'))>
     * Input : @limit <base,indent eg : 0,5 -> generate the query as LIMIT 0,5))>
    */
    public static function getListItem($table = NULL, $fieldArr = NULL, $filterArr = NULL, $orderArr = NULL, $limit = NULL, $searchArr = NULL, $join = NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = $fieldList = $order = $searchCode = $searchList = NULL;
        
        $data = array();
        // FIELD LIST generation like a, b, c
        if(!empty($fieldArr)) {
            foreach($fieldArr as $field) {
                $fieldList .= (!empty($fieldList)) ? ', ' : '';
                $fieldList .= (!empty($field)) ? $field : '';
            }

            // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
            if(!empty($filterArr)) {
                foreach($filterArr as $filterItem) {
                    $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                    $filterInputQuotes = (isset($filterItem['inputQuotes']) && $filterItem['inputQuotes']=='N') ? $filterItem['value'] : "'".$filterItem['value']."'";
                    $filter .= (!empty($filter)) ? ' AND ' : '';
                    $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." ".$filterInputQuotes : NULL;
                } // End Foreach
            } // End If

            // FILTER fields for where condition generation like a LIKE 'xx%' AND b LIKE 'xx%' AND c LIKE 'xx%' generally for search cases
            if(!empty($searchArr)) {
                $filter .= (!empty($filter)) ? ' AND (' : ' (';
                foreach($searchArr as $searchItem) {
                    $searchList .= (!empty($searchList)) ? ' OR ' : '';
                    $searchList .= (!empty($searchItem)) ? $searchItem['field']." LIKE '".addslashes($searchItem['value'])."%'" : NULL;
                } // End Foreach
                $filter .= $searchList;
                $filter .= (!empty($filter)) ? ' )' : '';
            } // End If

            // FILTER WITH WHERE 1 for blank entries
            $filter .= (!empty($filter)) ? "" : " 1";

            // FILTER WITH ORDER BY
            if(!empty($orderArr)) {
                $sortBy = (!empty($orderArr['sort'])) ? $orderArr['sort'] : 'ASC';
                foreach($orderArr['fields'] as $orderItem) {
                    $order .= (!empty($order)) ? ', ' : '';
                    $order .= (!empty($orderItem)) ? $orderItem : '';
                } // End Foreach
                $filter .= (!empty($order)) ? " ORDER BY ".$order." ".$sortBy : NULL;
            } // End If

            // FILTER WITH LIMIT WHERE 1
            $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;
            //echo $filter;
            $data = Admincomponents::$dbObj->selectResult($table,$fieldList,$filter);

        } // End FieldArr


        return $data;
    } // End Function

    public static function getLatestInvoice() {
        Admincomponents::$dbObj = new Db();
        $data = array();
        $selItem = "SELECT i.nInvId, i.dGeneratedDate, i.nTotal, u.vUsername FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId ORDER BY i.dGeneratedDate DESC LIMIT 0,5";
        $data = Admincomponents::$dbObj->selectQuery($selItem);
        return $data;
    } // End Function

    public static function getLatestPayments() {
        Admincomponents::$dbObj = new Db();
        $data = array();
        $selItem = "SELECT p.nPaymentId, p.nAmount, u.vUsername FROM ".Admincomponents::$dbObj->tablePrefix."Payments p LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON p.nUId = u.nUId ORDER BY p.dPaymentDate DESC LIMIT 0,5";
        $data = Admincomponents::$dbObj->selectQuery($selItem);
        return $data;
    } // End Function

    public static function dropListItem($table = NULL, $fieldArr = NULL) {
        Admincomponents::$dbObj = new Db();
        $fieldList = NULL;

        // FIELD LIST generation like a, b, c
        if(!empty($fieldArr)) {
            foreach($fieldArr as $fieldItem) {
                $fieldList .= (!empty($fieldList)) ? ' AND ' : '';
                $fieldList .= (!empty($fieldItem)) ? $fieldItem['field']." = '".$fieldItem['value']."'" : '';
            } // End Foreach
            $drop = Admincomponents::$dbObj->deleteRecord($table, $fieldList);
        } // End FieldArr

    } // End Function

    public static function getSiteSettings() {
        $data   =   array();
        $settingsArr = Admincomponents::getListItem("Settings", array('settingfield', 'value'));
        foreach($settingsArr as $settingItem) {
            //echo '<pre>'; print_r($settingItem); echo '</pre>';
            //$data[$settingItem['settingfield']] = $settingItem['value'];
            $data[$settingItem->settingfield] = $settingItem->value;
        } // End Foreach

        return $data;
    } // End Function

    public static function getModules($search = NULL, $limit = NULL, $filterArr = NULL, $orderArr=NULL) {
        Admincomponents::$dbObj = new Db();
        $filter = NULL;
        $filter = (!empty($search)) ? "vModuleName LIKE '".addslashes($search)."%'" : "";
        // Filter Items / WHERE condition fields
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filter .= (!empty($filter)) ? " AND " : "";
                $filter .= (!empty($filterItem)) ? $filterItem['field']."= '".$filterItem['value']."'" : NULL;
            }
        } // End Filter Item
        $filter .= (!empty($filter)) ? "" : " 1";
        // FILTER WITH ORDER BY
        if(!empty($orderArr)) {
            $sortBy = (!empty($orderArr['sort'])) ? $orderArr['sort'] : 'ASC';
            foreach($orderArr['fields'] as $orderItem) {
                $order .= (!empty($order)) ? ', ' : '';
                $order .= (!empty($orderItem)) ? $orderItem : '';
            } // End Foreach
            $filter .= (!empty($order)) ? " ORDER BY ".$order." ".$sortBy : NULL;
        } // End If

        $filter .= (!empty($filter)) ? "" : " 1";
        $filter .= (!empty($limit)) ? " LIMIT ".$limit : NULL;

        $role = Admincomponents::$dbObj->selectResult("Module","nMId, vModuleName, vDescription, dLastModifiedDate, nStatus",$filter);
        return $role;
    } // End Function

    public static function sendPassword($email = NULL) {
        if(!empty($email)) {

            $adminArr = Admincomponents::getListItem('Admin', array('vEmail'), array(array('field' => 'nSuperAdmin', 'value' => '1')));
            $userArr = Admincomponents::getListItem('Admin', array('nAId','vEmail','vUsername','vFirstName','vLastName'), array(array('field' => 'vEmail', 'value' => addslashes($email)))); // ;
            $MailArr = Admincomponents::getListItem('Cms', array('cms_title', 'cms_desc', 'cms_status'), array(array('field' => 'cms_name', 'value' => 'forgotpassword'),array('field' => 'cms_type', 'value' => 'email')));

            $adminEmail = $adminArr[0]->vEmail;
            $userEmail = $userArr[0]->vEmail;
            $mailSubject = $MailArr[0]->cms_title;
            $mailSubject = str_replace("{SITE_NAME}", SITE_NAME, $mailSubject); // Replace the Site Name

            $userName = $userArr[0]->vUsername;
            $password = Utils::rand_string(6);

            $loginLink = BASE_URL.'admin/login';

            $msgBody = $MailArr[0]->cms_desc;
            $msgBody = str_replace("{MEMBER_NAME}", $username, $msgBody); // Replace the Member Name
            $msgBody = str_replace("{SITE_NAME}", SITE_NAME, $msgBody); // Replace the Site Name
            $msgBody = str_replace("{SITE_URL}", BASE_URL, $msgBody); // Replace the Site Url
            $msgBody = str_replace("{LoginName}", $userName, $msgBody); // Replace the Login Name
            $msgBody = str_replace("{Password}", $password, $msgBody); // Replace the Login Password
            $msgBody = str_replace("{LOGIN_LINK}", $loginLink, $msgBody);
            $msgBody = Utils::bindEmailTemplate($msgBody);

            PageContext::includePath('email');

            $emailObj    = new Emailsend();
            $emailData   = array("from"		=> $adminEmail,
                    "subject"	=> $mailSubject,
                    "message"	=> $msgBody,
                    "to"           => $userEmail);
            $emailObj->email_senderNow($emailData);

            // Update password
            Admincomponents::$dbObj->updateFields("Admin",array('vPassword' => md5($password)),"nAId='".$userArr[0]->nAId."'");

        } // End If
    } // End Function

    public static function defaultProductServices() {
        $dataS = array();
        $dataS[]=array('nServiceId' => NULL,
                'vServiceName' => 'Free Trial',
                'vServiceDescription' => 'Free Trial',
                'price' => '0.00',
                'nSCatId' => '10',
                'vBillingInterval' => 'M',
                'nBillingDuration' => '14',
                'vInputType' => 'C');
        $dataS[]=array('nServiceId' => NULL,
                'vServiceName' => 'Product Purchase',
                'vServiceDescription' => 'Product Purchase',
                'price' => '300.00',
                'nSCatId' => '6',
                'vBillingInterval' => 'Y',
                'nBillingDuration' => '1',
                'vInputType' => 'C');

        return $dataS;

    } // End Function

    public static function getPlanStatus($planId){
        Admincomponents::$dbObj = new Db();
        $planStatus = Admincomponents::$dbObj->fetchSingleRow("SELECT nStatus FROM ".Admincomponents::$dbObj->tablePrefix."ProductServices WHERE nServiceId = ".$planId);
        return $planStatus->nStatus;
    }
    
    public static function savePlanGostores($data) {
        Admincomponents::$dbObj = new Db();
        $itemDet = array();
        if(!empty($data)) {
            
            if($data['nServiceId'] <> ''){
                $planStatus = Admincomponents::getPlanStatus($data['nServiceId']);
                $itemServiceQry =  "UPDATE ".Admincomponents::$dbObj->tablePrefix."productservices SET vServiceName = '".$data['vServiceName']."',
                                    vServiceDescription = '".trim($data['vServiceDescription'])."',
                                    nSCatId = '".$data['nSCatId']."',
                                    nPId = '".$data['nPId']."',
                                    nQty = '".$data['nQty']."',
                                    price = '".$data['price']."',
                                        trasaction_fee = '".$data['trasaction_fee']."',
                                            trasaction_fee = '".$data['trasaction_fee']."',
                                            savings = '".$data['savings']."',
                                                third_party_transaction = '".$data['third_party_transaction']."',
                                                    makeready_payments = '".$data['makeready_payments']."',
                                    vBillingInterval = '".$data['vBillingInterval']."',
                                    nBillingDuration = '".$data['nBillingDuration']."',
                                    nStatus = '".$planStatus."' WHERE nServiceId = ".$data['nServiceId'];
            }
            else{
                $itemServiceQry =  "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."productservices SET nServiceId = NULL,
                                    vServiceName = '".$data['vServiceName']."',
                                    vServiceDescription = '".trim($data['vServiceDescription'])."',
                                    nSCatId = '".$data['nSCatId']."',
                                    nPId = '".$data['nPId']."',
                                    nQty = '".$data['nQty']."',
                                    vType = 'paid',
                                    price = '".$data['price']."',
                                         trasaction_fee = '".$data['trasaction_fee']."',
                                            savings = '".$data['savings']."',
                                                third_party_transaction = '".$data['third_party_transaction']."',
                                                    makeready_payments = '".$data['makeready_payments']."',
                                    vBillingInterval = '".$data['vBillingInterval']."',
                                    nBillingDuration = '".$data['nBillingDuration']."',
                                    nStatus ='".$data['nStatus']."'";
            }      

            $saveQry = Admincomponents::$dbObj->execute($itemServiceQry);   
            
        }
        
        if($saveQry){
            $itemDet['success'] = true;
            $itemDet['plan_id'] = $data['nServiceId'] == '' ? Admincomponents::$dbObj->lastInsertId() : $data['nServiceId'];
            
            //delete current list of features
            Admincomponents::$dbObj->execute("DELETE FROM ".Admincomponents::$dbObj->tablePrefix."ProductServiceFeatures WHERE nProductServiceId = ".$itemDet['plan_id']);  
            
            //get list of active service features            
            $serviceFeatures = Admincomponents::$dbObj->selectResult("ServiceFeatures", "*", "eStatus = 'Active'");
            if($serviceFeatures){
                foreach($serviceFeatures as $features){
                    $itemFeatureQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ProductServiceFeatures SET nProductServiceId = '".$itemDet['plan_id']."',
                                       nServiceFeatureId = '".$features->nFeatureId."',
                                       vFeatureValue = '".$features->tValue."'";
                    
                    Admincomponents::$dbObj->execute($itemFeatureQry);  
                }
            }
        }
        else{
            $itemDet['success'] = false;
        }
        
        
        return $itemDet;
    }

    public static function getPlansCount(){
        Admincomponents::$dbObj = new Db();
        $planCount = Admincomponents::$dbObj->fetchSingleRow("SELECT count(nServiceId) as planCount FROM ".Admincomponents::$dbObj->tablePrefix."ProductServices WHERE vType='paid' AND nStatus=1");
        return $planCount->planCount;
    }

    public static function changePlanStatus($status_id,$plan_id){
        Admincomponents::$dbObj = new Db();
        if($plan_id > 0){
            $statusRealVal =  ($status_id==1)?0:1;
            $updateQry =  "UPDATE ".Admincomponents::$dbObj->tablePrefix."ProductServices
                           SET nStatus = '".$statusRealVal."'
                           WHERE nServiceId = ".$plan_id;
            $updateStatus = Admincomponents::$dbObj->execute($updateQry);
            if($updateStatus){
                $planStatus = Admincomponents::getPlanStatus($plan_id);
            }
        }
        return $planStatus;
    }

    public static function saveProduct($data) {
        Admincomponents::$dbObj = new Db();
        $errMsg = NULL;
        $itemDet = array();
        if(!empty($data)) {
            if(empty($data['nPId'])) {
                //Insert new Product
                $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Products SET nPId = NULL,
                                                                                                vPName = '".addslashes($data['vPName'])."',
                                                                                                vProductCaption = '".addslashes($data['vProductCaption'])."',
                                                                                                vProductDescription = '".addslashes($data['vProductDescription'])."',
                                                                                                vProductPack = '".addslashes($data['vProductPack'])."',
                                                                                                vProductlogoSmall = '".addslashes($data['vProductlogoSmall'])."',
                                                                                                vProductlogo = '".addslashes($data['vProductlogo'])."',
                                                                                                vProductScreens = '".addslashes($data['vProductScreens'])."',
                                                                                                nPRId = NULL,
                                                                                                nStatus = '1'";

                Admincomponents::$dbObj->execute($itemQry);
                $productID = Admincomponents::$dbObj->lastInsertId();
                //
                // Insert Product Permissions
                $itemPermissionQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ProductPermission SET nId = NULL,
                                                                                                nPId = '".$productID."',
                                                                                                vPermissions = '".addslashes($data['vPermissions'])."'";
                Admincomponents::$dbObj->execute($itemPermissionQry);

                //
                // Insert Product Release
                $itemReleaseQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ProductReleases SET nPRId = NULL,
                                                                                                nPId = '".$productID."',
                                                                                                vVersion = '".addslashes($data['vVersion'])."',
                                                                                                dLastUpdated = NOW()";

                Admincomponents::$dbObj->execute($itemReleaseQry);
                $releaseID = Admincomponents::$dbObj->lastInsertId();

                Admincomponents::$dbObj->execute("UPDATE ".Admincomponents::$dbObj->tablePrefix."Products SET nPRId = '".$releaseID."' WHERE nPId='".$productID."'");

                // Services
                if(!empty($data['productServices'])) {
                    $serArr = $data['productServices'];
                    foreach($serArr as $serItem) {
                        $itemServiceQry = NULL;
                        $itemServiceQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ProductServices SET nServiceId = NULL,
                                                                                                vServiceName = '".addslashes($serItem['vServiceName'])."',
                                                                                                vServiceDescription = '".addslashes($serItem['vServiceDescription'])."',
                                                                                                nSCatId = '".addslashes($serItem['nSCatId'])."',
                                                                                                nPId = '".addslashes($productID)."',
                                                                                                price = '".addslashes($serItem['price'])."',
                                                                                                vBillingInterval = '".addslashes($serItem['vBillingInterval'])."',
                                                                                                nBillingDuration = '".addslashes($serItem['nBillingDuration'])."',
                                                                                                nStatus = '1'";
                        Admincomponents::$dbObj->execute($itemServiceQry);

                    } // End foreach
                }
                // End Services

            } else {

                //Update Product
                $productID = $data['nPId'];
                $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Products SET vPName = '".addslashes($data['vPName'])."', vProductCaption = '".addslashes($data['vProductCaption'])."',vProductDescription = '".addslashes($data['vProductDescription'])."' WHERE nPId = '".$data['nPId']."'";
                Admincomponents::$dbObj->execute($itemQry);

                // Update Item
                if(isset($data['vProductPack'])) {
                    Admincomponents::$dbObj->updateFields("Products", array('vProductPack' => addslashes($data['vProductPack'])), "nPId='".$data['nPId']."'");
                }

                // Update Item
                if(isset($data['vProductlogo'])) {
                    Admincomponents::$dbObj->updateFields("Products", array('vProductlogo' => addslashes($data['vProductlogo'])), "nPId='".$data['nPId']."'");
                }

                // Update Item
                if(isset($data['vProductlogoSmall'])) {
                    Admincomponents::$dbObj->updateFields("Products", array('vProductlogoSmall' => addslashes($data['vProductlogoSmall'])), "nPId='".$data['nPId']."'");
                }

                // Update Item
                if(isset($data['vProductScreens'])) {
                    Admincomponents::$dbObj->updateFields("Products", array('vProductScreens' => addslashes($data['vProductScreens'])), "nPId='".$data['nPId']."'");
                }

                //
                // Update Product Permissions
                $itemPermissionQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ProductPermission SET vPermissions = '".addslashes($data['vPermissions'])."' WHERE nPId ='".$data['nPId']."'";
                Admincomponents::$dbObj->execute($itemPermissionQry);

                //
                // Insert Product Release
                $itemReleaseQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ProductReleases SET vVersion = '".addslashes($data['vVersion'])."' WHERE nPRId ='".$data['nPRId']."'";
                Admincomponents::$dbObj->execute($itemReleaseQry);

                // Services
                if(!empty($data['productServices'])) {
                    $serArr = $data['productServices'];
                    foreach($serArr as $serItem) {
                        $itemServiceQry = NULL;
                        if(empty($serItem['nServiceId'])) {
                            $itemServiceQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."ProductServices SET nServiceId = NULL,
                                                                                                vServiceName = '".addslashes($serItem['vServiceName'])."',
                                                                                                vServiceDescription = '".addslashes($serItem['vServiceDescription'])."',
                                                                                                nSCatId = '".addslashes($serItem['nSCatId'])."',
                                                                                                nPId = '".addslashes($productID)."',
                                                                                                price = '".addslashes($serItem['price'])."',
                                                                                                vBillingInterval = '".addslashes($serItem['vBillingInterval'])."',
                                                                                                nBillingDuration = '".addslashes($serItem['nBillingDuration'])."',
                                                                                                nStatus = '1'";
                        } else {

                            $itemServiceQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ProductServices SET vServiceName = '".addslashes($serItem['vServiceName'])."',
                                                                                                vServiceDescription = '".addslashes($serItem['vServiceDescription'])."',
                                                                                                nSCatId = '".addslashes($serItem['nSCatId'])."',
                                                                                                nPId = '".addslashes($productID)."',
                                                                                                price = '".addslashes($serItem['price'])."',
                                                                                                vBillingInterval = '".addslashes($serItem['vBillingInterval'])."',
                                                                                                nBillingDuration = '".addslashes($serItem['nBillingDuration'])."' WHERE nServiceId = '".$serItem['nServiceId']."'";

                        }
                        Admincomponents::$dbObj->execute($itemServiceQry);

                    } // End foreach
                }
                // End Services

            }
            $itemDet['id']= $productID;

        } // End If

        return $itemDet;

    } // End Function

    //Functionality to fetch products for reports
    public static function fetchProductsList() {
        $db = new Db();
        return $db->selectResult("Products", "vPName,nPId", "nStatus =1");
    }

    public static function generateReports($startDate, $endDate, $product = NULL, $subscriptionType = NULL, $limit = NULL) {

        $db = new Db();

       
        $filterStatus = FALSE;
        if($startDate && $endDate) {
            $filter = "IP.dCreatedoN between '".date('Y-m-d H:i:s',strtotime($startDate))."' AND
            '".date('Y-m-d 23:59:59',strtotime($endDate))."'";
            $filterStatus = TRUE;
        }

        if($product && $product!='all') {
            IF($filterStatus)
                $filter.= " AND P.nPId=".intval($product);
            else {
                $filter.= "  P.nPId=".intval($product);
                $filterStatus = TRUE;
            }
        }

        if($subscriptionType && $subscriptionType!='all') {
            if($filterStatus)
                $filter.= " AND I.vSubscriptionType='".$subscriptionType."' ";
            else
                $filter.= "  I.vSubscriptionType='".$subscriptionType."' ";
        }
        if(empty($filter))
            $filter = "1";
        $filter.= " GROUP BY P.vPName,vSubscriptionType ORDER BY P.vPName";

        if($limit)
            $filter.=" LIMIT $limit";

        $reports = $db->selectResult("Invoice I INNER JOIN  ".$db->tablePrefix."InvoicePlan  IP ON(I.nInvId =IP.nInvId)
            INNER JOIN  ".$db->tablePrefix."ProductServices PS  ON(IP.nServiceId = PS.nServiceId)
            INNER JOIN  ".$db->tablePrefix."Products P ON (PS.nPId=P.nPId)", "P.vPName ,SUM(IP.nAmount) as toal_amount,count(I.nInvId) as invoice_count,I.vSubscriptionType",
                $filter);

        return $reports;
        



    }

    public static function getProductDetails($idProduct) {
        $dataArr = array();
        if(!empty($idProduct)) {
            //
            $prdArr = Admincomponents::getListItem("Products", array('nPId','vPName','vProductCaption','vProductPack','vProductlogoSmall','vProductlogo','vProductDescription','vProductScreens','nPRId'), array(array('field' => 'nPId', 'value' => $idProduct)));
            // Product Release
            $prdReleaseArr = Admincomponents::getListItem("ProductReleases", array('nPRId','nPId','vVersion','dLastUpdated'), array(array('field' => 'nPRId', 'value' => $prdArr[0]->nPRId)));
            $prdPermissionArr = Admincomponents::getListItem("ProductPermission", array('nId','nPId','vPermissions'), array(array('field' => 'nPId', 'value' => $idProduct)));
            $prdSerArr = Admincomponents::getListItem("ProductServices", array('nServiceId','vServiceName','vServiceDescription','nSCatId','nPId','price','vBillingInterval','nBillingDuration','nStatus'), array(array('field' => 'nPId', 'value' => $idProduct)));

            $dataArr['nPId'] = $idProduct;
            $dataArr['nPRId'] = $prdArr[0]->nPRId;
            $dataArr['vPName'] = $prdArr[0]->vPName;
            $dataArr['vProductCaption'] = $prdArr[0]->vProductCaption;
            $dataArr['vProductDescription'] = $prdArr[0]->vProductDescription;
            $dataArr['vProductCaption'] = $prdArr[0]->vProductCaption;

            // Product Release
            $dataArr['vVersion'] = $prdReleaseArr[0]->vVersion;

            // Product Permission
            $dataArr['vPermissions'] = $prdPermissionArr[0]->vPermissions;

            //Product Pack
            $dataArr['vProductPack'] = $prdArr[0]->vProductPack;

            //Product Logo
            $dataArr['vProductlogo'] = $prdArr[0]->vProductlogo;

            //Product Logo Small
            $dataArr['vProductlogoSmall'] = $prdArr[0]->vProductlogoSmall;

            //Product Screens
            $dataArr['vProductScreens'] = $prdArr[0]->vProductScreens;

            //Product Services
            $serviceArr = array();

            if(!empty($prdSerArr)) {
                $i=0;
                foreach($prdSerArr as $item) {
                    $serviceArr[$i]['nServiceId']  = $item->nServiceId;
                    $serviceArr[$i]['vServiceName']  = $item->vServiceName;
                    $serviceArr[$i]['vServiceDescription']  = $item->vServiceDescription;
                    $serviceArr[$i]['nSCatId']  = $item->nSCatId;
                    $serviceArr[$i]['price']  = $item->price;
                    $serviceArr[$i]['vBillingInterval']  = $item->vBillingInterval;
                    $serviceArr[$i]['nBillingDuration']  = $item->nBillingDuration;
                 //   $serviceArr[$i]['vInputType']  = $item->vInputType;
                    $i++;
                } // End Foreach
            } //

            $dataArr['productServices'] = $serviceArr;


        } // End If
        return $dataArr;
    } //End Function


    public static function getFreeTrialsAcrossLastYear() {
        $db = new Db();

        $query = "SELECT EXTRACT(MONTH FROM dGeneratedDate) as month,count(nInvId) as trial_count,
            dGeneratedDate FROM ".$db->tablePrefix."Invoice WHERE vSubscriptionType='FREE' GROUP BY month ORDER BY month ASC";
        $result = $db->execute($query);

        while($row = mysql_fetch_array($result)) {
            $freeTrialArray[$row[0]]['trial_count']= $row[1];
        }


        return $freeTrialArray;
    }

    public static function getSubscriptionAcrossLastYear() {
        $db = new Db();
        $query = "SELECT EXTRACT(MONTH FROM dGeneratedDate) as month,count(nInvId) as subscription_count
            FROM ".$db->tablePrefix."Invoice WHERE vSubscriptionType='PAID' GROUP BY month ORDER BY month ASC";
        $result = $db->execute($query);
        while($row = mysql_fetch_array($result)) {
            $subscriptionsArray[$row[0]]['trial_count']= $row[1];
        }

        return $subscriptionsArray;
    }

    public static function getUpgradationsAcrossLastYear() {
        $db = new Db();

        $query = "SELECT EXTRACT(MONTH FROM dGeneratedDate) as month,count(nInvId) as trial_count,
            dGeneratedDate FROM ".$db->tablePrefix."Invoice WHERE upgraded=1 GROUP BY month ORDER BY month ASC";
        $result = $db->execute($query);

        if(!empty($result)) {
            while($row = mysql_fetch_array($result)) {
                $freeTrialArray[$row[0]]['trial_count']= $row[1];
            }
        }


        return $freeTrialArray;
    }

    public static function getTrialsOverRange() {
        $db = new Db();

        $query = "SELECT count(DISTINCT I.nInvId) as trial_count,
            P.vPName FROM ".$db->tablePrefix."Invoice I LEFT JOIN
            ".$db->tablePrefix."InvoicePlan IP on (I.nInvId=IP.nInvId)
            INNER JOIN ".$db->tablePrefix."ProductServices PS ON (IP.nServiceId=PS.nServiceId)
            INNER JOIN ".$db->tablePrefix."Products P ON(P.nPId=PS.nPId)WHERE vSubscriptionType='FREE'
                GROUP BY vPName";

        $result = $db->execute($query);

        while($row = mysql_fetch_array($result)) {
            $freeTrialArray[$row[1]]['trial_count']= $row[0];
        }

        return $freeTrialArray;
    }

    public static function getSubscriptionsOverRange() {
        $db = new Db();
        Logger::info($freeTrialArray);
        $query = "SELECT count(DISTINCT I.nInvId) as trial_count,
            P.vPName FROM ".$db->tablePrefix."Invoice I LEFT JOIN
            ".$db->tablePrefix."InvoicePlan IP on (I.nInvId=IP.nInvId)
            INNER JOIN ".$db->tablePrefix."ProductServices PS ON (IP.nServiceId=PS.nServiceId)
            INNER JOIN ".$db->tablePrefix."Products P ON(P.nPId=PS.nPId)WHERE vSubscriptionType='PAID'
                GROUP BY vPName";
        $result = $db->execute($query);
        while($row = mysql_fetch_array($result)) {
            $subscriptionsArray[$row[1]]['trial_count']= $row[0];
        }

        return $subscriptionsArray;
    }

    public static function getInvoices($dataArr, $limit = NULL) {

        Admincomponents::$dbObj = new Db();
        $reportArr = array();
        $filter = $filterSub = NULL;

        $sel = "SELECT i.nInvId, i.vInvNo, i.dGeneratedDate, i.dDueDate, i.nAmount, i.nDiscount,
             i.nTotal, i.vCouponNumber, i.vTerms, i.vNotes, i.vMethod, i.vSubscriptionType,
             i.vTxnId, i.dPayment, NOW() as currentDate, p.vPName, u.vUsername, u.vFirstName, u.vLastName,
             u.vEmail, u.vInvoiceEmail FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Products p ON ps.nPId = p.nPId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId";

        if(!empty($dataArr)) {
            if(!empty($dataArr['reportStartDate']) && !empty($dataArr['reportEndDate'])) {
                $filter .= "(i.dGeneratedDate BETWEEN '".date('Y-m-d H:i:s',strtotime($dataArr['reportStartDate']))."' AND '".date('Y-m-d 23:59:59',strtotime($dataArr['reportEndDate']))."')";
            } // Date filter

            if($dataArr['product']!='all') {
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= "p.nPId = '".addslashes($dataArr['product'])."'";
            } // Product Filter

            if($dataArr['userEmail']!='all') {
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= "i.nUId = '".addslashes($dataArr['userEmail'])."'";
            } // User Filter

            //vSubscriptionType
            if($dataArr['paid_subscription']== 'PAID'){
                $filterSub .= "i.vSubscriptionType = '".addslashes($dataArr['paid_subscription'])."'";
            } // Paid

            if($dataArr['free_subscription']== 'FREE'){
                $filterSub .= (!empty($filterSub)) ? ' OR ' : '';
                $filterSub .= "i.vSubscriptionType = '".addslashes($dataArr['free_subscription'])."'";
            } // Free

            if(!empty($filterSub)) {

                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= (!empty($filterSub)) ? '('.$filterSub.')' : '';
            }

            if($dataArr['invDue']=='DUE') {
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= "i.dDueDate < NOW() AND (i.dPayment IS NULL OR UNIX_TIMESTAMP(i.dPayment)=0) AND i.vSubscriptionType !='FREE'";
            } // Due


        }

        $filter =(!empty($filter)) ? ' WHERE '.$filter : $filter;
        $sel .= $filter." GROUP BY i.nInvId ORDER BY i.dGeneratedDate,i.dDueDate DESC";
        $sel .= (!empty($limit)) ? ' LIMIT '.$limit : '';
        //echo $sel;
        $reportArr = Admincomponents::$dbObj->selectQuery($sel);
        return $reportArr;

    } // End Function

    public static function getInvoicePaymentStatus($currentDate, $dueDate, $paymentDate) {
        $status = '--';       
        if(Utils::checkDateTime($paymentDate)){
           
            $status = (strtotime($currentDate) >= strtotime($paymentDate) || date("Y-m-d", strtotime($currentDate)) == date("Y-m-d", strtotime($paymentDate))) ? 'Paid' : '--';
            
        } else {
            $status = (strtotime($currentDate) > strtotime($dueDate)) ? 'Due' : '--';
        }
        
        return $status;
    } //End Function

    public static function getUserEmailList() {
        $dataArr = array();
        $userArr = Admincomponents::getListItem('User', array('nUId','vEmail'), array(array('field' => 'nStatus', 'value' => '1')), array('sort' => 'ASC', 'fields' => array('vEmail')));
        if(!empty($userArr)) {
            foreach($userArr as $userItem) {
                $dataArr[$userItem->nUId] = $userItem->vEmail;
            } //End
        } // End Function
        return $dataArr;
    } //End Function

    public static function getInvoiceDetails($idInvoice=NULL, $filterArr = NULL, $groupBy= NULL) {
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT i.nInvId, i.nUId, i.vInvNo,i.nPLId, i.dGeneratedDate, i.dDueDate, i.nAmount, i.nDiscount,
             i.nTotal, i.vCouponNumber, i.vTerms, i.vNotes, i.vMethod, i.vSubscriptionType,
             i.vTxnId, i.dPayment, NOW() as currentDate,i.nPLId, ip.nSpecialCost, ip.vSpecials, ip.nAmount as ipAmount, ip.nAmtNext, ip.nDiscount as ipDiscount,ip.vType,ip.vBillingInterval as ipBillingInterval, ip.nBillingDuration as ipBillingDuration,ip.dDateStart, ip.dDateStop, ps.vServiceName, ps.vServiceDescription,
             ps.price as servicePrice, ps.vBillingInterval as serviceBillingInterval,
             ps.nBillingDuration as serviceBillingDuration, ps.nSCatId, pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus, u.vUsername, u.vFirstName, u.vLastName, CONCAT(u.vFirstName,' ', u.vLastName) as vFullName,
             u.vEmail, u.vInvoiceEmail, u.vAddress, u.vCountry, u.vState, u.vZipcode, u.vCity FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl ON i.nPLId = pl.nPLId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId";

        $filter = NULL;
        
        if(!empty($idInvoice)){
            $filter .="i.nInvId='".$idInvoice."'";
        }
        
        // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." '".$filterItem['value']."'" : NULL;             
            } // End Foreach
        } // End If


        //$sel .= (!empty($filter)) ? ' AND ' : '';
        $sel .=" WHERE ".$filter;
        $sel .=(!empty($groupBy))?" GROUP BY $groupBy":"";
        $sel .= " ORDER BY i.dGeneratedDate DESC,i.dDueDate DESC";
        //echo '<br/>'.$sel;
//        echo '<br/>';
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        return $dataArr;

    } // End Function

    public static function getInvoiceDomainDetails($idInvoice) {

        Admincomponents::$dbObj = new Db();

        $sel = "SELECT id.nIDId, id.nSCatId, id.nPLId, id.vDescription, id.nRate, id.nAmount, id.nAmtNext,
             id.vType, id.vBillingInterval, id.nDiscount, id.dDateStart, id.dDateStop, id.dDateNextBill,
             id.dCreatedOn, id.nPlanStatus, NOW() as currentDate, pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus FROM ".Admincomponents::$dbObj->tablePrefix."InvoiceDomain id
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl ON id.nPLId = pl.nPLId
             WHERE id.nInvId='".$idInvoice."' ORDER BY id.dCreatedOn DESC";

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        return $dataArr;

    } // End Function

    public static function billingType($type) {
        $bType ='--';
        if(!empty($type)) {
            $bType = ucwords($type);
        }
        return $bType;
    } // End Function

    public static function billingInterval($type) {
        $bType ='--';
        if(!empty($type)) {
            switch($type) {
                case 'M':
                    $bType = 'Monthly';
                    break;
                case 'Y':
                    $bType = 'Yearly';
                    break;
                case 'L':
                    $bType = 'One-time';
                    break;
            }
        }
        return $bType;
    } // End Function

    public static function planInterval($type) {
        $bType ='--';
        if(!empty($type)) {
            switch($type) {
                case 'M':
                    $bType = 'Month';
                    break;
                case 'Y':
                    $bType = 'Year';
                    break;
                case 'L':
                    $bType = 'One-time';
                    break;
            }
        }
        return $bType;
    } // End Function


    public static function fetchXmlData() {
        $strXML = "<chart caption='Factory Output report' subCaption='By Quantity' pieSliceDepth='30' showBorder='1' formatNumberScale='0' numberSuffix=' Units' >";
        $freeTiral = self::getTrialsOverRange($startDate, $endDate);
        $subscription = self::getSubscriptionsOverRange($startDate, $endDate);
        $products = self::fetchProductsList();
        $arrData[0][0] = "Free Trial";
        $arrData[0][1] = "";
        $arrData[1][0] = "Subscription";
        $arrData[1][1] = "";
        foreach($products as $product) {
            $productNames[] = $product->vPName;
        }

        for($i=0;$i<=count($productNames);$i++) {
            $freeTrialName = $productNames[$i].' Free Trial';
            // $subscriptionName = $productNames[$i].' Free Trial';
            $arrData[0][$i+1] = $freeTiral[$productNames[$i]]['trial_count']?$freeTiral[$productNames[$i]]['trial_count']:NULL;
            // $arrData[1][$i+1] = $subscription[$productNames[$i]]['trial_count']?$subscription[$productNames[$i]]['trial_count']:NULL;
            $strXML .="<set label='" .$freeTrialName. "' value='" .$freeTiral[$productNames[$i]]['trial_count'] . "' />";
            // $strXML .="<set label='" .$subscriptionName. "' value='" .$subscription[$productNames[$i]]['trial_count'] . "' />";
        }
        $strXML .="</chart>";

        return $strXML;

    }

    //This function generates coupon code for user
    public static function generateCouponCode() {
        return substr(strtoupper(md5()),0,6);
    }

    //Functionality to check coupon name
    public static function checkCouponName($name, $id = NULL) {
        $db = new Db();
        $filter = " vCouponCode='$name'";
        if($id)
            $filter.= " nCouponId='$id'";
        if($db->checkExists("Coupon", "nCouponId", $filter))
            return TRUE;
        else
            return FALSE;
    }

    public static function generateInvoice($dataArr) {
        
        Admincomponents::$dbObj = new Db();

        /* Example
        array('nUId'=>1,
            'nPLId'=>9,
            'services'=>array('2'),
            'domainService'=>array('appendDescription' => '','rate' => '100', 'year' => '1'),
            'couponNo'=>'',
            'terms'=>'',
            'notes'=>'',
            'paymentstatus'=>'paid',
            'vMethod'=>'CC',
            'vTxnId'=>'xacadafadadad',
            'upgrade'=>'',
            'subscriptionType'=>'FREE'); subscriptionType = FREE / PAID
       */
                        
        $dataDomainArr = array();
        $dataServiceArr = array();
        $dataBillingArr = array();
        $validCouponArr = array();
        $invDomArr = array();
        $invArr = array();
        
        $totalAmount = 0;
        $discount = $couponDiscount = $walletDiscount = $walletBalance = $walletNewBalance = 0;
        $grandTotal = 0;
        $domainAmount = $domainAmountNext = 0;
        $error = NULL;
        $couponNo = $appendDescription = NULL;
        $planTypeDomain = $billingIntervalDomain = $billingDuration = NULL;


        // Domain Service
        if(isset($dataArr['domainService']) && count($dataArr['domainService']) > 0) {

            $planTypeDomain = 'recurring';
            $billingIntervalDomain = 'Y';
            $billingDuration = 1; //.. next auto renewal would be for another 1 year plan basis

            $domainAmount = round(($dataArr['domainService']['rate'] * $dataArr['domainService']['year']), 2);
            $domainAmountNext = round(($dataArr['domainService']['rate'] * 1), 2); // probably domain renewal would be on 1 year basis => 1 year plan
            $totalAmount+=$domainAmount;          

            $appendDescription .= 'Domain Registration'.$dataArr['domainService']['appendDescription'];
            $appendDescription .=(!empty($appendDescription)) ? '<br>' : '';
            $appendDescription .= Admincomponents::getStoreHost($dataArr['nPLId']);

            //Domain registration plan is on yearly basis
            //calculations are turned on for a yearly basis plan
           
            //Domain Plan Period
            $bStartDate = date("Y-m-d");
            $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . " +".$dataArr['domainService']['year']." year");
            $bStopDate = date("Y-m-d", $bStopDate);
            //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
            //$bNextDate = date("Y-m-d", $bNextDate);
            $bNextDate = $bStopDate;

            $dataDomainArr = array('nUId' => $dataArr['nUId'],
                                    'nPLId' => $dataArr['nPLId'],
                                    'vDescription' => $appendDescription,
                                    'nAmount' => $domainAmount,
                                    'nAmtNext' => $domainAmountNext,
                                    'vType' =>$planTypeDomain,
                                    'nRate' => $dataArr['domainService']['rate'],
                                    'vBillingInterval' => $billingIntervalDomain,
                                    'nBillingDuration' => $dataArr['domainService']['year'],
                                    'nDiscount' => NULL,
                                    'dDateStart' => $bStartDate,
                                    'dDateStop' => $bStopDate,
                                    'dDateNextBill' => $bNextDate,
                                    'nPlanStatus' => NULL);

            // Billing Main Data as Domain registration is also recurring plan
            //nSCatId - leave it as blank, it is not required.
            //nServiceId - keep this as NULL for domain registration
            //vDomain - keep vDomain log as 1
            
            $dataBillingArr [] = array('nUId' => $dataArr['nUId'],
                    'nServiceId' => NULL,
                    'vDomain' => 1,
                    'nDiscount' => NULL,
                    'nAmount' => $domainAmount,
                    'vType' => $planTypeDomain,
                    'vBillingInterval' => $billingIntervalDomain,
                    'nBillingDuration' => $billingDuration,
                    'dDateStart' => $bStartDate,
                    'dDateStop' => $bStopDate,
                    'dDateNextBill' => $bNextDate,
                    'dDatePurchase' => $bStartDate,
                    'vDelStatus' => '0');
            // End Billing Main Data

        }
        // End Domain Service

      

        // Service
        if(!empty($dataArr['services'])) {
            foreach($dataArr['services'] as $serviceId) {
                $serItemArr = Admincomponents::getListItem("ProductServices", array("nServiceId","vServiceName","vServiceDescription","nPId","price","vBillingInterval","nBillingDuration"), array(array('field' => 'nServiceId', 'value'=> $serviceId)));
                Logger::info($serItemArr);
                if(!empty($serItemArr)) {
                    $totalAmount += $serItemArr[0]->price;
                    $productSpanArr['productBillingInterval']; // productBillingInterval
                    $productSpanArr['productBillingDuration']; // productBillingDuration
                    $serItemArr[0]->vBillingInterval;
                    $serItemArr[0]->nBillingDuration;
                    $bStartDate = $bStopDate = $bNextDate = $planType = $amountNext= NULL;
                    $planPrice = $serItemArr[0]->price;
                    $serDiscount = 0;
                    switch($serItemArr[0]->vBillingInterval) {
                        case 'M':
                        // recurring                       
                            $addDays = NULL;
                            if($serItemArr[0]->nBillingDuration ==1) {
                                $addDays = " +".$serItemArr[0]->nBillingDuration." day";
                            }else if($serItemArr[0]->nBillingDuration > 1) {
                                $addDays = " +".$serItemArr[0]->nBillingDuration." days";
                            }
                            $bStartDate = date("Y-m-d");
                            $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addDays);
                            $bStopDate = date("Y-m-d", $bStopDate);
                            //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                            //$bNextDate = date("Y-m-d", $bNextDate);
                            $bNextDate = $bStopDate;
                            $planType = 'recurring';
                            $amountNext = $serItemArr[0]->price;

                            break;
                        case 'Y':                       
                        // recurring
                            $addYear = NULL;                           
                            $addYear = " +".$serItemArr[0]->nBillingDuration." years";                          
                            $bStartDate = date("Y-m-d");
                            $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                            $bStopDate = date("Y-m-d", $bStopDate);
                            //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                            //$bNextDate = date("Y-m-d", $bNextDate);
                            $bNextDate = $bStopDate;
                            $planType = 'recurring';
                            $amountNext = $serItemArr[0]->price;                         
                            break;
                        case 'L':
                        // one-time;
                            $planType = 'one time';
                            break;
                    } // End Switch

                    $dataServiceArr[] = array('nUId'=>$dataArr['nUId'],
                            'nServiceId'=>$serviceId,                           
                            'nAmount' => $serItemArr[0]->price,
                            'nAmtNext' => $amountNext,
                            'vType'=>$planType,
                            'vBillingInterval' => $serItemArr[0]->vBillingInterval,
                            'nBillingDuration' => $serItemArr[0]->nBillingDuration,
                            'nDiscount' => $serDiscount,
                            'dDateStart'=>$bStartDate,
                            'dDateStop'=>$bStopDate,
                            'dDateNextBill'=>$bNextDate);

                    // Billing Main Data
                    if($planType=='recurring') {
                        // Recurring
                        //nSCatId - leave it as blank, it is not required.
                        //vDomain - keep vDomain log as NULL in case of service plans
                        $dataBillingArr [] = array('nUId' => $dataArr['nUId'],
                                'nServiceId' => $serviceId,                                
                                'vDomain' => NULL,
                                'nDiscount' => $serDiscount,
                                'nAmount' => $serItemArr[0]->price,
                                'vType' => $planType,
                                'vBillingInterval' => $serItemArr[0]->vBillingInterval,
                                'nBillingDuration' => $serItemArr[0]->nBillingDuration,
                                'dDateStart' => $bStartDate,
                                'dDateStop' => $bStopDate,
                                'dDateNextBill' => $bNextDate,
                                'dDatePurchase' => $bStartDate,
                                'vDelStatus' => '0');
                    } // End Billing Main Data

                }
                // FREE SERVICE

                // PRICED SERVICE
            } // End Foreach
        } // End Service
        // End Service

        // Coupon Validate
        if(isset($dataArr['couponNo']) && !empty($dataArr['couponNo'])) {            
            $validCouponArr = Admincomponents::couponValidate($dataArr['couponNo']);           
            if($validCouponArr['valid']==1) {
                $couponDiscount += ($validCouponArr['pricemode']=='rate') ? $validCouponArr['value'] : $totalAmount*($validCouponArr['value']/100); // pricemode => rate or percentage
                $discount += $couponDiscount;
                $couponNo = $dataArr['couponNo'];
                
                //updateCouponNo coupon used number
                Admincomponents::updateCouponNo($validCouponArr['couponId']);
            } else {
                $error .= $validCouponArr['msg'];
            }
        }
        // End Coupon Validate


        // Upgrade Area
        // Update the Free Invoice as Upgraded
        if(isset($dataArr['upgrade']) && $dataArr['upgrade']==1){
            Admincomponents::updateInvoiceOnUpgrade($dataArr['nPLId']);
        } // End If
        // End Upgrade Area
        
        // Invoice Creation
        $grandTotal = $totalAmount - $discount;
        $grandTotal = round($grandTotal, 2);
        $vSubscriptionType = ($dataArr['subscriptionType']=='FREE') ? 'FREE' : 'PAID';
        $paymentDate = ($dataArr['paymentstatus']=='paid') ? date('Y-m-d H:i:s') : NULL;   //

        $invQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Invoice SET nInvId=NULL, nUId='".$dataArr['nUId']."', nPLId = '".$dataArr['nPLId']."', dGeneratedDate = NOW(),
                            dDueDate = NOW(), nAmount='".$totalAmount."', nDiscount ='".$discount."', nTotal ='".$grandTotal."',
                                vCouponNumber ='".$couponNo."', vTerms ='".$dataArr['terms']."', vNotes = '".$dataArr['notes']."',
                                    vSubscriptionType = '".$vSubscriptionType."', vMethod='".$dataArr['vMethod']."', vTxnId = '".$dataArr['vTxnId']."', dPayment='".$paymentDate."'";

        Admincomponents::$dbObj->execute($invQry);
        $invoiceID = Admincomponents::$dbObj->lastInsertId();

        // Update Invoice Number
        $invUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET vInvNo='".$invoiceID."' WHERE nInvId='".$invoiceID."'";
        Admincomponents::$dbObj->execute($invUpdateQry);

        // End Invoice Creation

        // ****************** Invoice Plan Creation *************************
        // Plan Creation Against Domain
        if(count($dataDomainArr) > 0) {
            $invPlanDQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoiceDomain SET nIDId=NULL, nUId='".$dataDomainArr['nUId']."',
                        nInvId='".$invoiceID."', nPLId='".$dataDomainArr['nPLId']."', vDescription='".$dataDomainArr['vDescription']."', nAmount='".$dataDomainArr['nAmount']."', nAmtNext='".$dataDomainArr['nAmtNext']."', vType='".$dataDomainArr['vType']."', vBillingInterval='".$dataDomainArr['vBillingInterval']."', nBillingDuration = '".$dataDomainArr['nBillingDuration']."', nRate='".$dataDomainArr['nRate']."',
                        nDiscount='".$dataDomainArr['nDiscount']."', dDateStart='".$dataDomainArr['dDateStart']."', dDateStop='".$dataDomainArr['dDateStop']."', dDateNextBill='".$dataDomainArr['dDateNextBill']."', dCreatedOn=NOW(), nPlanStatus='1'";
            Admincomponents::$dbObj->execute($invPlanDQry);
        }// End If
        // End Plan Creation Against Domain

        // Plan Creation Against Service
        if(count($dataServiceArr) > 0) {
            foreach($dataServiceArr as $itemSP) {
                $invSerQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoicePlan SET nIPId=NULL, nUId='".$itemSP['nUId']."',
                       nInvId='".$invoiceID."', nServiceId='".$itemSP['nServiceId']."', nAmount='".$itemSP['nAmount']."', nAmtNext='".$itemSP['nAmtNext']."', vType='".$itemSP['vType']."', vBillingInterval='".$itemSP['vBillingInterval']."', nBillingDuration ='".$itemSP['nBillingDuration']."',
                       nDiscount='".$itemSP['nDiscount']."', dDateStart='".$itemSP['dDateStart']."', dDateStop='".$itemSP['dDateStop']."', dDateNextBill='".$itemSP['dDateNextBill']."', dCreatedOn=NOW(), nPlanStatus='1'";
                Admincomponents::$dbObj->execute($invSerQry);
            } //End Foreach
        } // End If Service
        // End Plan Creation Against Service

        // ****************** Invoice Plan Creation End *********************

        // ****************** Billing Main Entry ****************************
        if($vSubscriptionType=='PAID'){ // Billing auto pay entry only for PAID subscription
            if(count($dataBillingArr) > 0) {
                foreach($dataBillingArr as $itemBill) {
                    $invBillQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."BillingMain SET nBmId=NULL, nUId='".$itemBill['nUId']."',
                            nServiceId='".$itemBill['nServiceId']."', vInvNo='".$invoiceID."', vDomain='".$itemBill['vDomain']."', nDiscount='".$itemBill['nDiscount']."', nAmount='".$itemBill['nAmount']."', vType='".$itemBill['vType']."', vBillingInterval='".$itemBill['vBillingInterval']."', nBillingDuration ='".$itemBill['nBillingDuration']."',
                            dDateStart='".$itemBill['dDateStart']."', dDateStop='".$itemBill['dDateStop']."', dDateNextBill='".$itemBill['dDateNextBill']."', dDatePurchase='".$itemBill['dDatePurchase']."', vPaymentMethod='".$dataArr['vMethod']."', vDelStatus='".$itemBill['vDelStatus']."'";
                    Admincomponents::$dbObj->execute($invBillQry);

                } // End Foreach
            } // End If
        } // End If
        // ****************** Billing Main Entry End ************************

        // Send Invoice Mail
        self::sendInvoiceMail($invoiceID);                
        
    } // End Function

    public static function couponValidate($couponCode) {
        //$couponCode = 'D41D8C'; /* Example */
        $dataArr = array();
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT nCouponId,vCouponCode,nCouponValue,vPricingMode, (CASE WHEN DATEDIFF(CURDATE(), DATE(dExpireOn))<=0 THEN 0 ELSE 1 END) as expired, dCreatedOn, dExpireOn, (CASE WHEN (nCouponCount-nCouponUsed) <=0 THEN 1 ELSE 0 END) as usageClosed, (nCouponCount-nCouponUsed) as couponLeft FROM ".Admincomponents::$dbObj->tablePrefix."Coupon WHERE BINARY vCouponCode = '".$couponCode."'";
        $couponArr = Admincomponents::$dbObj->selectQuery($sel);
        // Valid =>  0: Invalid , 1: valid, 2: expired, 3: usage closed
        $valid = 0; //
        $couponId = NULL;
        $msg = "Invalid Coupon Code";
        if(!empty($couponArr)) {
            $expired = $couponArr[0]->expired; // 1: expired, 0: Not expired
            $closed = $couponArr[0]->usageClosed; // 1: usage closed, 0: usage left
            $usageLeft = $couponArr[0]->couponLeft; // No of coupons left for use
            $couponId = $couponArr[0]->nCouponId; // Coupon Id to update the coupon usage
            if($expired==1) {
                $valid = 2;
                $msg = "Coupon expired";
            } else if($closed==1) {
                $valid = 3;
                $msg = "Coupon usage closed";
            } else if($usageLeft > 0) {
                $valid = 1;
                $msg = "Coupon code applied successfully";;
            }
        }
        
        $dataArr['couponId'] = $couponId;
        $dataArr['valid'] = $valid;
        $dataArr['pricemode'] = $couponArr[0]->vPricingMode;
        $dataArr['value'] = $couponArr[0]->nCouponValue;
        $dataArr['msg'] = $msg;

        return $dataArr;
    } // End Function

    public static function getProductPurchaseSpan($idProduct) {
        // Product Purchase Category Default
        $serviceCategory = 6;
        $dataArr = array();
        $productBillingInterval = $productBillingDuration = NULL;
        Admincomponents::$dbObj = new Db();
        $productArr = Admincomponents::getListItem("ProductServices", array("vBillingInterval","nBillingDuration"), array(array('field' => 'nPId', 'value' => $idProduct),array('field' => 'nSCatId', 'value'=> $serviceCategory)));
        Logger::info($productArr);
        $dataArr['productBillingInterval'] = (!empty($productArr)) ? $productArr[0]->vBillingInterval : PRODUCT_BILLING_INTERVAL;
        $dataArr['productBillingDuration'] = (!empty($productArr)) ? $productArr[0]->nBillingDuration : PRODUCT_PURCHASE_SPAN;

        return $dataArr;
    } // End Function

    public static function getInvoiceLoopingTemplate() {
        $content = '<tr>
                        <td style="width: 8%; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">{SL_NO}</td>
                        <td style="padding-left: 10px;text-align: left; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;">{PURCHASE_DESCRIPTION}</td>
                        <td class="mono" style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;">{BILLING_TYPE}</td>
                        <td style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;" class="mono">{RATE}</td>
			<td style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;" class="mono">{AMOUNT}</td>
                    </tr>';

        return $content;
    } // End Function
    
    public static function getInvoiceInventoryLoopingTemplate() {
        $content = '<tr>
                        <td style="width: 8%; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">{SL_NO}</td>
                        <td style="padding-left: 10px;text-align: left; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;">{PURCHASE_DESCRIPTION}</td>
                        <td class="mono" style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;">{START_DATE}</td>
                        <td class="mono" style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;">{EXPIRT_DATE}</td>
                        <td style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;" class="mono">{RATE}</td>
			<td style="width: 15%; font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;" class="mono">{AMOUNT}</td>
                    </tr>';

        return $content;
    } // End Function
    
   
    public static function getUserWalletBalance($userId){
        $walletBalance = 0;
        $dataArr=Admincomponents::getListItem("Wallet", array('nBalanceAmount'), array(array('field' => 'nUId', 'value'=>$userId)));
        if(!empty($dataArr)){
            $walletBalance = $dataArr[0]->nBalanceAmount;
        }
        return $walletBalance;
    } // End Function

    public static function updateWallet($dataArr){
        Admincomponents::$dbObj = new Db();
        $walletUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Wallet SET nBalanceAmount='".$dataArr['newBalance']."' WHERE nUId='".$dataArr['nUId']."'";
        Admincomponents::$dbObj->execute($walletUpdateQry);
    } // End Function

    public static function getFreePlanInvoiceForUpgrade($productLookUpID) {
        Admincomponents::$dbObj = new Db();
        $freeplanInvoiceId = NULL;
        $sel = "SELECT i.nInvId, i.nUId, i.vInvNo, i.dGeneratedDate, i.dDueDate, i.nAmount, i.nDiscount,
             i.nTotal, i.vCouponNumber, i.vTerms, i.vNotes, i.vMethod, i.vSubscriptionType,
             i.vTxnId, i.dPayment, NOW() as currentDate,i.nPLId FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             WHERE i.nPLId ='".$productLookUpID."' AND i.vSubscriptionType='FREE'";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            $freeplanInvoiceId = $dataArr[0]->nInvId;
        }
        
        return $freeplanInvoiceId;

    } // End Function

    public static function getPreviousInvoiceForUpgrade($productLookUpID) {
        Admincomponents::$dbObj = new Db();
        $previousInvoiceId = NULL;
        $sel = "SELECT i.nInvId FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             WHERE i.nPLId ='".$productLookUpID."' GROUP BY i.nPLId ORDER BY i.nInvId DESC";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            $previousInvoiceId = $dataArr[0]->nInvId;
        }

        return $previousInvoiceId;

    } // End Function
    
     public static function updateInvoiceOnUpgrade($productLookUpID){
        Admincomponents::$dbObj = new Db();
        $planInvoiceId = Admincomponents::getPreviousInvoiceForUpgrade($productLookUpID);
        if(!empty($planInvoiceId)) {
            $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET upgraded='1' WHERE nInvId='".$planInvoiceId."'";
            Admincomponents::$dbObj->execute($updateQry);
            Admincomponents::updatePreviousDomain($productLookUpID, $planInvoiceId);
        }
     } // End Function

     public static function updatePreviousDomain($productLookUpID,$planInvoiceId){
        Admincomponents::$dbObj = new Db();
        $data = NULL;
        if(!empty($planInvoiceId)){
           $data = Admincomponents::getStoreHost($productLookUpID);
           if(!empty($data)){
            $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET previousDomain='".$data."' WHERE nInvId='".$planInvoiceId."'";
            Admincomponents::$dbObj->execute($updateQry);
           }
        }
     } // End Function

    public static function getStoreHost($productLookUpID){
        Admincomponents::$dbObj = new Db();
        $data = NULL;
        
        // vSubDomain, nSubDomainStatus, vDomain, nDomainStatus
        $sel = "SELECT pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,s.vserver_name FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory h ON pl.nPLId = h.nPLId
                    LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id
                        WHERE pl.nPLId ='".$productLookUpID."'";
        
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        
        if(!empty($dataArr)){           
            if($dataArr[0]->nSubDomainStatus == 1){
                $data = $dataArr[0]->vSubDomain.'.'.$dataArr[0]->vserver_name;
            } else if($dataArr[0]->nDomainStatus == 1){
                $data = $dataArr[0]->vDomain;
            }
        }

        return $data;
     } // End Function

    /*
     * Method : Get Product Name Through Invoice Id - CMS <M>
    */
    public static function getProductName($invoiceId) {
       
        Admincomponents::$dbObj = new Db();

        $sel = " SELECT p.vPName
                 FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Products p ON ps.nPId = p.nPId
                 WHERE i.nInvId ='".$invoiceId."'";

        $productData = Admincomponents::$dbObj->fetchSingleRow($sel);
        
        if(!empty($productData)) {
            $productName = $productData->vPName;
        }
        return $productName;
    } // End Function


   
    /*
     * Method : Get Plan Details Through Invoice Id - CMS <M>
    */
    public static function getPlanDetails($search,$orderField='',$orderType='DESC',$limit='',$page='1') {
       
        Admincomponents::$dbObj = new Db();

        $sel = " SELECT i.nInvId, i.nPLId, p.vSubDomain,p.vDomain,ps.vServiceName,
        		CONCAT(u.vFirstName,' ',u.vLastName)as user,u.nUId,ip.dDateStart,ip.dDateStop,
        		ip.nPlanStatus,s.vserver_name 
                 FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup p ON i.nPLId = p.nPLId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory h ON p.nPLId = h.nPLId
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id
                 LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId ";
       $sel .=  $search;
       $sel .=  "GROUP BY i.nPLId ";

        

       if($orderField!=''){
           $sel .= "ORDER BY $orderField $orderType";
       }else{
           $sel .= "ORDER BY i.nInvId DESC";
       }
    
       if($limit > 0){
           $limitStart = ($page==1)? 0 :(($page-1)*$limit);
           $sel .= " LIMIT $limitStart,$limit";
       }
      
        $productData = Admincomponents::$dbObj->selectQuery($sel);       

        return $productData;
    } // End Function    
    

    /*
     * Method : change status by  themeId - CMS <M>
    */
    public static function statusUpdate($themeID) {       
        Admincomponents::$dbObj = new Db();
        if(!empty($themeID)) {
            $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."themes SET theme_status=0 WHERE theme_id!='".$themeID."'";
            Admincomponents::$dbObj->execute($updateQry);
        }
       
    } // End Function   
    /*
     * Method : change status by  themeId - CMS <M>
    */
    public static function showthumbnail($themeID) {       
        Admincomponents::$dbObj = new Db();
        if(!empty($themeID)) {
            $sel = "SELECT theme_thumbnail FROM ".Admincomponents::$dbObj->tablePrefix."themes 
                WHERE theme_id ='".$themeID."'";
           $themeData = Admincomponents::$dbObj->fetchSingleRow($sel);            
        }
        return $themeData->theme_thumbnail;
    } // End Function   
    
    /*
     * Method : Get Payment Status Through Invoice Id - CMS <M>
    */
    public static function getPaymentStatus($invoiceId) {

        Admincomponents::$dbObj = new Db();

        $sel = "SELECT vSubscriptionType, dPayment, dDueDate, NOW() as currentDate
                FROM ".Admincomponents::$dbObj->tablePrefix."Invoice 
                WHERE nInvId ='".$invoiceId."'";

        $invoiceData = Admincomponents::$dbObj->fetchSingleRow($sel);
        $status = '--';
        if(!empty($invoiceData)) {
            $currentDate    = $invoiceData->currentDate;
            $dueDate        = $invoiceData->dDueDate;
            $paymentDate    = $invoiceData->dPayment;
            if($invoiceData->vSubscriptionType == 'PAID'){
                $status = self::getInvoicePaymentStatus($currentDate, $dueDate, $paymentDate);
            }
            
        }
        return $status;
    } // End Function

     public static function updateCouponNo($couponID){
        Admincomponents::$dbObj = new Db();
        if(!empty($couponID)) {
            $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Coupon SET nCouponUsed=IFNULL(nCouponUsed, 0) + 1 WHERE nCouponId='".$couponID."'";
            Admincomponents::$dbObj->execute($updateQry);
        }
     } // End Function
    
   public static function getUserPalnCount($userId) {
        Admincomponents::$dbObj     = new Db();       
        $sel = "SELECT count(nServiceId) AS planCount FROM " . Admincomponents::$dbObj->tablePrefix . "BillingMain WHERE nUId = '".$userId."'";
        $res = Admincomponents::$dbObj->selectQuery($sel);

        $data = NULL;
        if(!empty($res)) {
            $data = $res[0]->planCount;
           
        }
        return $data;
    } // End Function

    public static function getUserPlansForCmsListing($userId){
        
        $planCount = Admincomponents::getUserPalnCount($userId);
        $data = $planCount;
        if($planCount > 0) {
            $data .='&nbsp;&nbsp;<a href="'.BASE_URL.'cms?section=user_plans&parent_id='.$userId.'" class="cms_list_operation">Manage</a>';
        }

        return $data;
    } // End Function

    public static function getUserBillingPlans($filterArr,$searchArr){
        Admincomponents::$dbObj = new Db();
        $dataArr = array();
        $filter = $filterSub = NULL;
        
        $sel = "SELECT b.nBmId, b.nUId, b.nDiscount, b.nAmount, IFNULL(b.nSpecialCost,0) as specialCost, b.vSpecials, b.vType, b.vBillingInterval, b.nBillingDuration, b.dDateStart, b.dDateStop, b.dDateNextBill, b.dDatePurchase,
                pl.nPLId,pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,
                ps.vServiceName, ps.vServiceDescription,
                u.vUsername, u.vFirstName, u.vLastName,u.vEmail, u.vInvoiceEmail FROM ".Admincomponents::$dbObj->tablePrefix."BillingMain b
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Invoice i ON i.nInvId = b.vInvNo
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl ON i.nPLId = pl.nPLId
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON b.nServiceId = ps.nServiceId
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId";

        //$filter = "b.nUId ='".$userId."'";

        // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
            if(!empty($filterArr)) {
                foreach($filterArr as $filterItem) {
                    $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                    $filterInputQuotes = (isset($filterItem['inputQuotes']) && $filterItem['inputQuotes']=='N') ? $filterItem['value'] : "'".$filterItem['value']."'";
                    $filter .= (!empty($filter)) ? ' AND ' : '';
                    $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." ".$filterInputQuotes : NULL;
                } // End Foreach
            } // End If
        
        // FILTER fields for where condition generation like a LIKE 'xx%' AND b LIKE 'xx%' AND c LIKE 'xx%' generally for search cases
            if(!empty($searchArr)) {
                $filter .= (!empty($filter)) ? ' AND (' : ' (';
                foreach($searchArr as $searchItem) {
                    $searchList .= (!empty($searchList)) ? ' OR ' : '';
                    $searchList .= (!empty($searchItem)) ? $searchItem['field']." LIKE '".addslashes($searchItem['value'])."%'" : NULL;
                } // End Foreach
                $filter .= $searchList;
                $filter .= (!empty($filter)) ? ' )' : '';
            } // End If

         $sel .= (!empty($filter)) ? ' WHERE '.$filter : '';

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        //echopre($dataArr);
        return $dataArr;
        
    } // End Function

    public static function getPlanBillingType($dataArr){
        $data = NULL;
        
        $data .=$dataArr['vType'];
        
        switch($dataArr['vBillingInterval']){
            case 'M':
                $data .=' - Monthly';
                $data .=' ['.$dataArr['nBillingDuration'].' day(s)]';
                break;
            case 'Y':
                $data .=' - Yearly';
                $data .=' ['.$dataArr['nBillingDuration'].' year(s)]';
                break;
        }

        return $data;
        
    } // End Function

    public static function updateBilling($billingID, $dataArr){
        Admincomponents::$dbObj = new Db();
        $specialcost = NULL;
        $specials = NULL;
        $msg = NULL;        
        if(!empty($dataArr)) {
            $specials = json_encode($dataArr);
            $specialcost = 0.00;
            foreach($dataArr as $item){
                $specialcost += $item['cost'];
            }
            
        }
        $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET nSpecialCost='".$specialcost."', vSpecials='".$specials."' WHERE nBmId='".$billingID."'";
            Admincomponents::$dbObj->execute($updateQry);
            $msg = 'success';
        return $msg;
     } // End Function

    public static function downloadTemplateZip($templateID){
        //$templateID;
        //echo FILE_UPLOAD_DIR;
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT pt.vTemplateZipId,f.file_path FROM ". Admincomponents::$dbObj->tablePrefix . "PaidTemplates pt LEFT JOIN ". Admincomponents::$dbObj->tablePrefix . "files f ON pt.vTemplateZipId = f.file_id WHERE nTemplateId = '".$templateID."'";
        $res = Admincomponents::$dbObj->selectQuery($sel);
        $zip = NULL;
        $data = NULL;
        if(!empty($res)) {
            $zip = $res[0]->file_path;
            if(is_file(FILE_UPLOAD_DIR.$zip)){
                $data .='<a href="'.BASE_URL.'admin/products/template/'.$zip.'" class="cms_list_operation">Download</a>';
            }
        }
        return $data;
     } // End Functions

     public static function downloadFile($file, $fileType='application/zip') {
        header('Content-disposition: attachment; filename='.$file);
        header('Content-type: '.$fileType);
        readfile(FILE_UPLOAD_DIR.$file);
        exit();
     } //End

    public static function getStoreInvoices($idProductLookUp) {
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT i.nInvId FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             WHERE i.nPLId='".$idProductLookUp."'";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            
        } //End If

        
        return $dataArr;

    } // End Function

    public static function getStoreDomains($idProductLookUp) {
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT i.previousDomain, i.dGeneratedDate,
            pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus,
            si.vserver_name FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl ON i.nPLId = pl.nPLId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory sh ON sh.nPLId = pl.nPLId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo si ON si.nserver_id = sh.nserver_id 
                 WHERE i.nPLId='".$idProductLookUp."' ORDER BY i.nInvId ASC";

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        //$recentDomainArr =
        $contentArr = array();
        if(!empty($dataArr)){
            $i=0;
            foreach($dataArr as $item){                
                //$item->
                ++$i;
                $host = NULL;
                $registeredOn = NULL;

                // Populating the different domains registed for a particular store
                if($item->previousDomain==''){
                    if($i==1){
                        $host = Admincomponents::getStoreHost($idProductLookUp);
                        $registeredOn = $item->dGeneratedDate;
                    }

                } else {
                    $host = $item->previousDomain;
                    if($i==1){
                       $registeredOn = $item->dGeneratedDate;
                    }
                    $i=0;
                }

                if(!empty($host) && !empty($registeredOn)) {
                    $contentArr[] = array('domain' => $host, 'registeredOn' => $registeredOn, 'location' => $item->vserver_name);
                }
                // End Populating the different domains registed for a particular store
      
            }
            

        } //End If

        return $contentArr;

    } // End Function

    public static function getActiveServer(){
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT s.nserver_id FROM ".Admincomponents::$dbObj->tablePrefix."ServerInfo s
             WHERE s.vmakethisserver_default='1'";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        $data = NULL;
        if(!empty($dataArr)){
           $data = $dataArr[0]->nserver_id;
        } //End If
        return $data;
    }

    public static function logServerMapped($idProductLookUp){
        $serverId = Admincomponents::getActiveServer();
        Admincomponents::$dbObj = new Db();
        $sel = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."serverHistory SET id=NULL, nPLId='".$idProductLookUp."', nserver_id='".$serverId."'";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);       
    } // End Function

    public static function getSpecialsCaptureType($type=NULL){
        $capture = NULL;
        switch($type){
            case 'recurring':
                $capture = ' [charged with every bill]';
                break;
            case 'one-time':
                $capture = ' [charged with initial bill]';
                break;
        }
        return $capture;
           
    } //End Function

     //Function fetch username for cms
     public static function getUsername($plId){
         $dbh = new Db();
         $userDetails = $dbh->selectRecord("ProductLookup PL INNER JOIN  ".$dbh->tablePrefix."User U ON (PL.nUId  = U.nUId )"," U.nUId, CONCAT_WS(' ', vFirstName, vLastName) as Name", " PL.nPLId = ".$plId);
         $data = Admincomponents::cmsUserPopup($userDetails);
         return $data;
     }
          //Function fetch username for cms
     public static function getUserInfo($userID){
         $dataArr = array();
         $dbh = new Db();
         $dataArr = $dbh->selectRecord("User"," CONCAT_WS(' ', vFirstName, vLastName) as Name, vEmail, vInvoiceEmail ", " nUId = ".$userID);
         return $dataArr;
     }

     //Function fetch username for cms from invoice
     public static function getUsernameFromInvoice($invoiceId){
         $dbh = new Db();
         $userDetails = $dbh->selectRecord("Invoice Inv INNER JOIN ".$dbh->tablePrefix."User U ON (Inv.nUId  = U.nUId )"," U.nUId, CONCAT_WS(' ', vFirstName, vLastName) as Name", " Inv.nInvId = ".$invoiceId);
         $data = Admincomponents::cmsUserPopup($userDetails);
         return $data;
     }

     //Function fetch user details for cms
     public static function getUserdetails($userId){
         $dbh = new Db();
         $userDetails = $dbh->selectRecord("User "," * ", " nUId = ".$userId);
        
         if(!empty($userDetails))
            return $userDetails;
     }

     public static function cmsUserPopup($userDetails){
          $content = '--';
         
          if(!empty($userDetails)){            
            $content = '<a href="javascript:void(0)" class="userDetails" name= "'.$userDetails->nUId.'" >'.ucfirst($userDetails->Name).'</a>';
          }
         
          return $content;
     } // End Function

     public static function getCmsInvoiceDetails($invoiceId){
         $dbh = new Db();
         $invoiceDetails = $dbh->selectRecord("Invoice "," nInvId,vInvNo ", " nInvId = ".$invoiceId);
         return '<a href="javascript:void(0)" class="invoiceDetails" name="'.$invoiceDetails->nInvId.'" >'.$invoiceDetails->vInvNo.'</a>';
     }

     public static function getCmsUserPlanDetails($userId){ 
         $dbh = new Db();
         $planCount = Admincomponents::getUserPalnCount($userId);
         if($planCount > 0){
            $billingUrl = '<a href="'.BASE_URL.'cms?section=user_plans&parent_id='.$userId.'" >Manage Billing</a>';
            $returnUrl = '<a href="javascript:void(0)" class="userPlanDetails" name="'.$userId.'" >'.$planCount.'</a> | '.$billingUrl;
         }
         else
            $returnUrl  = $planCount;
         return $returnUrl;
     }

     public static function getCmsBannerImage($bannerId){
         $dbh = new Db();
         $bannerDetails = Admincomponents::getBannerDetails($bannerId);
         $imgStr = '<img src="'.IMAGE_FILE_URL.'/'.$bannerDetails->file_path.'" width="60" height="60" />';
         return '<a href="javascript:void(0)" class="viewBanner" name="'.$bannerId.'" >'.$imgStr.'</a>';
     }

     public static function getBannerDetails($bannerId){
         Admincomponents::$dbObj = new Db();
         $bannerDetails = Admincomponents::$dbObj->selectRecord("Banners b INNER JOIN ".Admincomponents::$dbObj->tablePrefix."files f ON b.vBannerImageId = f.file_id "," f.file_path,b.vBannerImageId,b.vBannerText,b.clickcount,b.eType ", " nBannerId = ".$bannerId);
         return $bannerDetails;
     }

     //Function used to update domain status for cms
     public static function updateDomainStatus($domainId){ 
        Admincomponents::$dbObj = new Db();
        if(!empty($domainId)) {
            $domainData = Admincomponents::$dbObj->fetchSingleRow("SELECT vmakethisserver_default,whmpass FROM ".Admincomponents::$dbObj->tablePrefix."ServerInfo WHERE nserver_id=".$domainId);
            if($domainData->vmakethisserver_default==1){
                $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ServerInfo SET vmakethisserver_default=0 WHERE nserver_id!='".$domainId."'";
                Admincomponents::$dbObj->execute($updateQry);
            }else{
                $updateQry=0;
            }

            // Encrypt Password
            $currentPassword = $domainData->whmpass;
            $cipher = User::encrytCreditCardDetails($currentPassword);
            $updateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ServerInfo SET whmpass='".$cipher."' WHERE nserver_id='".$domainId."'";
            Admincomponents::$dbObj->execute($updateQry);
        }
     }
     
     //Function used to de - ciper WHM password for cms
     public static function cipherPassword($domainId=NULL){
        $cipher = NULL;
        Admincomponents::$dbObj = new Db();
        
        
        if(!empty($domainId)) {
            $domainData = Admincomponents::$dbObj->fetchSingleRow("SELECT whmpass FROM ".Admincomponents::$dbObj->tablePrefix."ServerInfo WHERE nserver_id=".$domainId);
            if(!empty($domainData->whmpass)){
              // Decrypt Password
                $currentPassword = $domainData->whmpass;
                $cipher = User::decrytCreditCardDetails($currentPassword);
            }

            
        }
        return $cipher;
     }

     public static function getPaidTemplates($filterArr = array()){
         Admincomponents::$dbObj = new Db();
         $dataArr = array();
         $filter = NULL;

         $sel = "SELECT t.nTemplateId, t.vTemplateName,t.vTemplateDisplayName, t.vDescription, t.nCost, t.vTemplateZipId, t.vHomeScreenshotId, t.vInnerScreenshot1Id, t.vInnerScreenshot2Id,
                f1.file_path as zipFile,
                f2.file_path as homeScreenshot,
                f3.file_path as innerScreenshot1,
                f4.file_path as innerScreenshot2
                FROM ".Admincomponents::$dbObj->tablePrefix."PaidTemplates t
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."files f1 ON t.vTemplateZipId = f1.file_id
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."files f2 ON t.vHomeScreenshotId = f2.file_id
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."files f3 ON t.vInnerScreenshot1Id = f3.file_id
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."files f4 ON t.vInnerScreenshot2Id = f4.file_id";

         // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
         if(!empty($filterArr)) {
                foreach($filterArr as $filterItem) {
                    $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                    $filterInputQuotes = (isset($filterItem['inputQuotes']) && $filterItem['inputQuotes']=='N') ? $filterItem['value'] : "'".$filterItem['value']."'";
                    $filter .= (!empty($filter)) ? ' AND ' : '';
                    $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." ".$filterInputQuotes : NULL;
                } // End Foreach
         } // End If

         $sel .= (!empty($filter)) ? ' WHERE '.$filter : '';
        
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
     
        return $dataArr;
     } // End Function

     public static function getUserStore($userID){         
         $sel = "SELECT pl.nPLId FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Invoice i ON i.nPLId = pl.nPLId 
                 WHERE pl.nUId='".$userID."' AND i.vSubscriptionType!='FREE' AND pl.nStatus='1' GROUP BY i.nPLId ORDER BY i.dGeneratedDate DESC";

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
         $storeArr = array();
         if(!empty($dataArr)){
            foreach($dataArr as $item){
                $item->nPLId;
                $storeArr[] = array('nPLId'=>$item->nPLId,
                                    'host'=> Admincomponents::getStoreHost($item->nPLId));                   
            }
         }
         return $storeArr;
         
     } // End Function

     public static function getStoreServerInfo($idProductLookUp){

        Admincomponents::$dbObj = new Db();
        $sel = "SELECT pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus,pl.vAccountDetails,
            si.vserver_name,si.whmuser,si.whmpass FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory sh ON sh.nPLId = pl.nPLId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo si ON si.nserver_id = sh.nserver_id
                 WHERE pl.nPLId='".$idProductLookUp."'";

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
       
        $cpanelAccount=array('c_host' => '', 'c_user' => '', 'c_pass' => '');

        $hostArr = array();

        $serverFlag = true;

        if(!empty($dataArr)){
            if(empty($dataArr[0]->vserver_name)){
                $serverFlag = false;
            }
            if(empty($dataArr[0]->whmuser)){
                $serverFlag = false;
            }
            if(empty($dataArr[0]->whmpass)){
                $serverFlag = false;
            }
            if(!empty($dataArr[0]->vAccountDetails)){
              
              $cpanelAccount = unserialize($dataArr[0]->vAccountDetails);
             
              if(empty($cpanelAccount['c_host'])){
                $serverFlag = false;
              }
              if(empty($cpanelAccount['c_user'])){
                $serverFlag = false;
              }
              if(empty($cpanelAccount['c_pass'])){
                $serverFlag = false;
              }              
            }
            
        } else {
            $serverFlag = false;
        }
        
        if($serverFlag){          
            $hostArr=array('whm_user_host' => $dataArr[0]->vserver_name,
            'whm_user_password' => $dataArr[0]->whmpass,
            'whm_user_login' => $dataArr[0]->whmuser,
            'c_host' => $cpanelAccount['c_host'],
            'c_user' => $cpanelAccount['c_user'],
            'c_pass' => $cpanelAccount['c_pass']);
        }
           
        return $hostArr;
     } // End Function

     // get invoice detail url for cms
     public static function getInvoiceDetailUrl($invoiceId){
         return $invoiceId;         
     }

     public static function logTemplatePurchase($dataArr = array()){
         /*Expected input
         $dataArr=array('nTemplateId' => '',
                        'nUId' => '',
                        'amount' => '',
                        'paymentMethod' => '',
                        'transactionId' => '',
                        'comments' => '');
          
          */
         
        Admincomponents::$dbObj = new Db();
        $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."paidtemplatepurchase SET id=NULL, nTemplateId='".$dataArr['nTemplateId']."',
                            nUId='".$dataArr['nUId']."', nPLId = '".$dataArr['nPLId']."', amount='".$dataArr['amount']."', paymentMethod='".$dataArr['paymentMethod']."', transactionId='".$dataArr['transactionId']."', comments='".$dataArr['comments']."', paidOn = NOW()";
        Admincomponents::$dbObj->execute($itemQry);
        
     } // End Function

    public static function getFreePlanId() {
        Admincomponents::$dbObj = new Db();
        $freeplanId = NULL;
        $sel = "SELECT ps.nServiceId FROM ".Admincomponents::$dbObj->tablePrefix."ProductServices ps
             WHERE ps.vType ='free' AND ps.nStatus=1";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            $freeplanId = $dataArr[0]->nServiceId;
        }

        return $freeplanId;

    } // End Function
           
     //Function fetch username for cms
     public static function getUsernameOfSettledRequest($uid){
         Admincomponents::$dbObj = new Db();
         $userDetails = Admincomponents::$dbObj->selectRecord("User AS U LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."BillingSettlement AS B ON U.nUId = B.nUId"," U.nUId,CONCAT_WS(' ', U.vFirstName, U.vLastName) as Name", " B.nId = ".$uid);
         $data = Admincomponents::cmsUserPopup($userDetails);
         return $data;
     }

     public static function getSiteLogoName() {
         Admincomponents::$dbObj = new Db();
         $siteLogoName = Admincomponents::$dbObj->fetchSingleRow("SELECT file_path FROM ".Admincomponents::$dbObj->tablePrefix."files f INNER JOIN  ".Admincomponents::$dbObj->tablePrefix."Settings s ON s.value=f.file_id");
         return $siteLogoName->file_path;
    } // End Function

    public static function deletePlanGostores($planId) {
         Admincomponents::$dbObj = new Db();
         $deletePlan = Admincomponents::$dbObj->deleteRecord('ProductServices','nServiceId='.$planId);
         return $deletePlan;
    } // End Function

    public static function getSiteLogo(){
        Admincomponents::$dbObj = new Db();
        $siteLogoFile = SITE_LOGO_FILE;
        $siteLogo = IMAGE_URL.'gostores_logo.jpg';
        if(!empty($siteLogoFile)){
            $logoArr = Admincomponents::$dbObj->selectResult("files", "file_path", "file_id=".$siteLogoFile);
            $logoArr[0]->file_path;
            if(is_file(FILE_UPLOAD_DIR.$logoArr[0]->file_path)){
            $siteLogo = IMAGE_FILE_URL.SITE_LOGO_PREFIX.$logoArr[0]->file_path;
            }
        }
        return $siteLogo;
    } // End Function

    public static function bindSiteLogoCms(){
    	 
        $content = '<img alt="'.SITE_NAME.'" src="'.SITE_LOGO.'" />';
        return $content;
    }
    
    public static function fetchDomaindata($request)
    {    	    	    	
        Admincomponents::$dbObj = new Db();
        $data = $orderField = $orderBy = NULL;
        $start = 0;
        $perPageSize = $request['perPageSize'];
        if(PageContext::$request['page'] != '')
        	$start = (PageContext::$request['page'] * $perPageSize) - $perPageSize;
         
        if(PageContext::$request['searchText'] != ''){
        	$search = " WHERE pl.vSubDomain LIKE '%".PageContext::$request['searchText']."%' OR pl.vDomain LIKE '%".PageContext::$request['searchText']."%' OR U.vFirstName LIKE '%".PageContext::$request['searchText']."%' OR U.vLastName LIKE '%".PageContext::$request['searchText']."%' OR inp.dDateStop LIKE '%".PageContext::$request['searchText']."%'";
                $search .= " OR CONCAT_WS('.',pl.vSubDomain,s.vserver_name) LIKE '%".PageContext::$request['searchText']."%'";
                $search .= " OR CONCAT_WS(' ',U.vFirstName,U.vLastName) LIKE '%".PageContext::$request['searchText']."%'";
        }

        if(PageContext::$request['orderField'] != '' && PageContext::$request['orderType'] != ''){

            switch(PageContext::$request['orderField']){
                case 'vDomain':                    
                    $orderBy .="pl.".PageContext::$request['orderField']." ".PageContext::$request['orderType'];
                    $orderBy .=", pl.vSubDomain ".PageContext::$request['orderType'];
                    break;
                case 'nUId':
                    $orderBy .="U.vFirstName ".PageContext::$request['orderType'];
                    $orderBy .=", U.vLastName ".PageContext::$request['orderType'];
                    break;
                case 'dDateStop':
                    $orderBy .="inp.dDateStop ".PageContext::$request['orderType'];
                    break;

            }
            
        }
        if(!empty($orderBy)){
            $orderBy .= ", ";
        }
        $orderBy .= "inv.nInvId DESC";
                
        $sel = "SELECT pl.nPLId,pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.dPlanExpiryDate,pl.nDomainStatus,s.vserver_name,U.vFirstName,U.vLastName,inp.dDateStop
            FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory h ON pl.nPLId = h.nPLId 
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id 
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Invoice inv ON inv.nPLId = pl.nPLId
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan inp ON inp.nInvId = inv.nInvId
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User AS U ON U.nUId = pl.nUId ".$search." GROUP BY pl.nPLId ORDER BY ".$orderBy." LIMIT ".$start.','.$perPageSize;
                

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        return $dataArr;



    }
    
    public static function fetchDomaindataCount()
    {
        Admincomponents::$dbObj = new Db();
        $data = $orderField = $orderBy = NULL;
       
        if(PageContext::$request['searchText'] != ''){
        	$search = " WHERE pl.vSubDomain LIKE '%".PageContext::$request['searchText']."%' OR pl.vDomain LIKE '%".PageContext::$request['searchText']."%' OR U.vFirstName LIKE '%".PageContext::$request['searchText']."%' OR U.vLastName LIKE '%".PageContext::$request['searchText']."%' OR inp.dDateStop LIKE '%".PageContext::$request['searchText']."%'";
                $search .= " OR CONCAT_WS('.',pl.vSubDomain,s.vserver_name) LIKE '%".PageContext::$request['searchText']."%'";
                $search .= " OR CONCAT_WS(' ',U.vFirstName,U.vLastName) LIKE '%".PageContext::$request['searchText']."%'";
        }

        if(PageContext::$request['orderField'] != '' && PageContext::$request['orderType'] != ''){

            switch(PageContext::$request['orderField']){
                case 'vDomain':
                    $orderBy .="pl.".PageContext::$request['orderField']." ".PageContext::$request['orderType'];
                    $orderBy .=", pl.vSubDomain ".PageContext::$request['orderType'];
                    break;
                case 'nUId':
                    $orderBy .="U.vFirstName ".PageContext::$request['orderType'];
                    $orderBy .=", U.vLastName ".PageContext::$request['orderType'];
                    break;
                case 'dDateStop':
                    $orderBy .="inp.dDateStop ".PageContext::$request['orderType'];
                    break;

            }

        }
        if(!empty($orderBy)){
            $orderBy .= ", ";
        }
        $orderBy .= "inv.nInvId DESC";

         $sel = "SELECT COUNT(pl.nPLId) AS cntDomain FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
        LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory h ON pl.nPLId = h.nPLId
        LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id
            LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Invoice inv ON inv.nPLId = pl.nPLId
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan inp ON inp.nInvId = inv.nInvId
                    LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User AS U ON U.nUId = pl.nUId ".$search." GROUP BY pl.nPLId ORDER BY ".$orderBy;

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
         $count = count($dataArr);
        // $count = $dataArr[0]->cntDomain;
        return $count;
        
    }
        
    public static function writeStoreConfiguration($lookupID, $productRestriction = 0){
        $status = NULL;
        $serverInfoArr = Admincomponents::getStoreServerInfo($lookupID);    // getStoreServerInfo
        if(!empty($serverInfoArr)) {
            PageContext::includePath('cpanel');
            $cpanelObj = new cpanel();
           
            $configurationFileContent = User::setXmlData($productRestriction, md5(SECRET_SALT.$lookupID));
            $operationArgArr = array(
                        'dir'      => '/public_html/app/webroot/',
                        'filename' => CONFIG_FILE_NAME,
                        'content' => $configurationFileContent,
                        );

            $status = $cpanelObj->doCpanelOperation($serverInfoArr['c_host'], $serverInfoArr['c_user'], $serverInfoArr['c_pass'], $operationArgArr, $module='Fileman', $function='savefile');
            //
        }

       return $status;
    }

    public static function getStoreHostWithAdminNav($productLookUpID){
        $returnUrl = NULL;
            $storeHost = Admincomponents::getStoreHost($productLookUpID);

            if(!empty($storeHost)){
                $returnUrl = '<a href="http://'.$storeHost.'/admins/login/n/'.md5(SECRET_SALT.$productLookUpID).'" target="_blank">'.$storeHost.'</a>';
            }

        return $returnUrl;
     } // End Function

    public static function sendInvoiceMail($invoiceID, $appendMsg = NULL){
        // Status - initially 0
        $status = $discount = 0;
        // Invoice Mail Generation
        $invDomArr = Admincomponents::getInvoiceDomainDetails($invoiceID);
        $invArr=Admincomponents::getInvoiceDetails($invoiceID);       
        
        // User Info
        $userName = $userAddress = $userAddDetails = $userEmail = $vSubscriptionType = $paymentStatus = NULL;
        // Invoice payment status
        if(Utils::checkDateTime($invArr[0]->dPayment, 'timestamp')){
            $paymentStatus = 'Paid';
        } else {
            $paymentStatus = 'Due';
        }

        if(count($invArr) > 0) {
            $userName = stripslashes($invArr[0]->vFirstName);
            $userName .= (!empty($invArr[0]->vLastName)) ? '&nbsp;'.stripslashes($invArr[0]->vLastName) : NULL;
            $userAddress = nl2br(stripslashes($invArr[0]->vAddress));
            $userAddDetails .=(!empty($invArr[0]->vCity)) ? stripslashes($invArr[0]->vCity) : NULL;
            $userAddDetails .=(!empty($userAddDetails)) ? '&nbsp;'.stripslashes($invArr[0]->vState) : NULL;
            $userAddDetails .=(!empty($userAddDetails)) ? '&nbsp;-&nbsp;'.stripslashes($invArr[0]->vZipcode) : NULL;
            $userAddDetails .=(!empty($userAddDetails)) ? '&nbsp;'.stripslashes($invArr[0]->vCountry) : NULL;
            $userEmail = (!empty($invArr[0]->vInvoiceEmail)) ? $invArr[0]->vInvoiceEmail : $invArr[0]->vEmail;
            // End User Details

            // Subscription Type
            $vSubscriptionType = $invArr[0]->vSubscriptionType;
            // End Subscription Type

            // Invoice payment status
            $paymentStatus = ($vSubscriptionType=='FREE') ? '--' : $paymentStatus;

            //Discount
            $discount = $invArr[0]->nDiscount;

            // Mail Generation
            $invoiceMailTemplate = BASE_PATH.'project/lib/emailtemplates/invoice.html';
            $mailContent = file_get_contents($invoiceMailTemplate);
            // Mail Message
            $mailMsgArr = Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('invoice'))));
            Logger::info($mailMsg);

            $subject = $mailMsg = NULL;

            if(count($mailMsgArr) > 0) {
                $subject = $mailMsgArr[0]->cms_title;
                $mailMsg = $mailMsgArr[0]->cms_desc;
            } // End If

            if(!empty($appendMsg)){
               $mailMsg .= $appendMsg;
            }

            $subject = str_replace("{SITE_NAME}", SITE_NAME, $subject);
            $mailMsg = str_replace("{MEMBER_NAME}", $userName, $mailMsg);
            $mailMsg = str_replace("{COMPANY_NAME}", COMPANY_NAME, $mailMsg);

            $mailContent = str_replace("{MAIL_CONTENT}", $mailMsg, $mailContent); // Mail message to member
            $mailContent = str_replace("{COMPANY_NAME}", COMPANY_NAME, $mailContent);
            $mailContent = str_replace("{COMPANY_ADDRESS}", COMPANY_ADDRESS, $mailContent);
            $mailContent = str_replace("{COMPANY_WEBSITE}", COMPANY_WEBSITE, $mailContent);
            $mailContent = str_replace("{COMPANY_EMAIL}", COMPANY_EMAIL, $mailContent);
            $mailContent = str_replace("{COMPANY_PHONE}", COMPANY_PHONE, $mailContent);
            // Customer Details
            $mailContent = str_replace("{CLIENT_NAME}", $userName, $mailContent);
            $mailContent = str_replace("{CLIENT_ADDRESS}", $userAddress, $mailContent);
            $mailContent = str_replace("{CLIENT_CITY}", $userCity, $mailContent);
            $mailContent = str_replace("{CLIENT_ADD_DETAILS}", $userAddDetails, $mailContent);
            // INVOICE Details
            $mailContent = str_replace("{INVOICE_NUMBER}", stripslashes($invArr[0]->vInvNo), $mailContent);
            $mailContent = str_replace("{INVOICE_DATE}", Utils::formatDateUS($invArr[0]->dGeneratedDate), $mailContent);
            //
            $invLoopingContent = NULL;

            $i=0;

            $invTotalAmount = $invDiscount = $invGrandTotal = 0;

            if(count($invDomArr) > 0) {
                foreach($invDomArr as $itemD) {
                    $invTotal = 0;
                    ++$i;
                    $purchaseDescription = NULL;

                    $purchaseDescription = nl2br(stripslashes($itemD->vDescription));
                    $invDiscount +=$itemD->nDiscount;
                    $invTotal =($itemD->nAmount - $itemD->nDiscount);
                    $invTotalAmount += $invTotal;

                    // Mail content Loop Area
                    $itemLoop = NULL;
                    $itemLoop = Admincomponents::getInvoiceLoopingTemplate();
                    $itemLoop = str_replace("{SL_NO}", $i , $itemLoop);
                    $itemLoop = str_replace("{PURCHASE_DESCRIPTION}", $purchaseDescription, $itemLoop);
                    $itemLoop = str_replace("{BILLING_TYPE}", Admincomponents::billingInterval($itemD->vBillingInterval) , $itemLoop);
                    $itemLoop = str_replace("{RATE}", CURRENCY_SYMBOL.Utils::formatPrice($itemD->nRate), $itemLoop);
                    $itemLoop = str_replace("{AMOUNT}", CURRENCY_SYMBOL.Utils::formatPrice($invTotal) , $itemLoop);

                    $invLoopingContent .=$itemLoop;
                } // End Foreach
            } // End If

            if(!empty($invArr)) {
                foreach($invArr as $item) {
                    $invTotal = 0;
                    ++$i;
                    $purchaseDescription = NULL;
                    $storeHost = Admincomponents::getStoreHost($item->nPLId);
                    $purchaseDescription .= (!empty($item->vServiceName)) ? nl2br(stripslashes($item->vServiceName)).'<br>' : NULL;
                    $purchaseDescription .= (!empty($storeHost)) ? $storeHost.'<br><br>' : '';
                    $purchaseDescription .= (!empty($item->vServiceDescription)) ? nl2br(stripslashes($item->vServiceDescription)) : NULL;
                    $invDiscount +=$item->ipDiscount;
                    $invTotal =($item->ipAmount - $item->ipDiscount);
                    $invTotalAmount += $invTotal;

                    // Mail content Loop Area
                    $itemLoop = NULL;
                    $itemLoop = Admincomponents::getInvoiceLoopingTemplate();
                    $itemLoop = str_replace("{SL_NO}", $i , $itemLoop);
                    $itemLoop = str_replace("{PURCHASE_DESCRIPTION}", $purchaseDescription, $itemLoop);
                    $itemLoop = str_replace("{BILLING_TYPE}", Admincomponents::billingInterval($item->ipBillingInterval) , $itemLoop);
                    $itemLoop = str_replace("{RATE}", CURRENCY_SYMBOL.Utils::formatPrice($item->ipAmount), $itemLoop);
                    $itemLoop = str_replace("{AMOUNT}",  CURRENCY_SYMBOL.Utils::formatPrice($invTotal) , $itemLoop);

                    $invLoopingContent .=$itemLoop;
                } // End Foreach
            } // End If

            $invGrandTotal = ($invTotalAmount-$invDiscount-$discount);

            $mailContent = str_replace("{LOOPING_TEMPLATE}", $invLoopingContent, $mailContent);
            $mailContent = str_replace("{DISCOUNT_AMOUNT}", CURRENCY_SYMBOL.Utils::formatPrice($invDiscount+$discount), $mailContent);
            $mailContent = str_replace("{TOTAL_AMOUNT}", CURRENCY_SYMBOL.Utils::formatPrice($invTotalAmount), $mailContent);
            $mailContent = str_replace("{FIGURE_AMOUNT}", ucfirst(Utils::number_to_words($invGrandTotal)).' only', $mailContent);
            $mailContent = str_replace("{GRAND_TOTAL}", CURRENCY_SYMBOL.Utils::formatPrice($invGrandTotal), $mailContent);
            $mailContent = str_replace("{PAYMENT_STATUS}", ucfirst($paymentStatus), $mailContent);
            $mailContent = Utils::bindEmailTemplate($mailContent);
            // End Mail Generation

            PageContext::includePath('email');
            
            $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
            $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
            $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

            $emailObj    = new Emailsend();
            $emailData   = array("from"		=> $senderMailID,
                    "subject"	=> $subject,
                    "message"	=> $mailContent,
                    "to"           => $userEmail);
            if($vSubscriptionType=='PAID'){ // Avoid sending invoice for free trial                
                $emailObj->email_senderNow($emailData);
                $status = 1;
            }
             // Send a copy to admin
             $mailContent = preg_replace('/Dear \w+ \w+ \w+,/','Dear Administrator,', $mailContent);
             $mailContent = preg_replace('/Dear \w+&nbsp;\w+&nbsp;\w+,/','Dear Administrator,', $mailContent);
             $mailContent = preg_replace('/Dear \w+ \w+,/','Dear Administrator,', $mailContent);
             $mailContent = preg_replace('/Dear \w+&nbsp;\w+,/','Dear Administrator,', $mailContent);
             $mailContent = str_replace("Thank you for the interest shown in .", "", $mailContent);
             $emailData["to"] = SITE_NAME . '<' . ADMIN_EMAILS . '>';
             $emailData["message"] = $mailContent ;
             $emailObj->email_senderNow($emailData);
        } // End Invoice Array
        
        return $status;
    } //End Function

    public static function getCmsScreenImage($screenId){
         $dbh = new Db();
         $screenDetails = Admincomponents::getScreenDetails($screenId);
         $imgStr = '<img src="'.IMAGE_FILE_URL.'/'.$screenDetails->file_path.'" width="60px" height="60px" style="width:60px!important;height:60px!important;"/>';
         return '<a href="javascript:void(0)" class="viewScreenshot" name="'.$screenId.'" >'.$imgStr.'</a>';
    } //End Function

     public static function getScreenDetails($screenId=NULL){
         Admincomponents::$dbObj = new Db();
         if(!empty($screenId)){
            $screenDetails = Admincomponents::$dbObj->selectRecord("DemoScreenshots s INNER JOIN ".Admincomponents::$dbObj->tablePrefix."files f ON s.vScreenImageId = f.file_id "," f.file_path,s.vScreenImageId,s.vActive, s.eType ", " nScreenId = ".$screenId);
         } else {
            $screenDetails = Admincomponents::$dbObj->selectResult("DemoScreenshots s INNER JOIN ".Admincomponents::$dbObj->tablePrefix."files f ON s.vScreenImageId = f.file_id"," f.file_path,s.vScreenImageId,s.vActive, s.eType ","s.vActive = '1' ORDER BY s.vScreenImageId ASC");
         }
         return $screenDetails;
     } //End Function

     public static function getAccountSuspendLink($lookupID){
         
         $itemArr = self::getListItem("ProductLookup", array("nStatus"), array(array("field" => "nPLId", "value" => $lookupID)));
         $returnUrl = NULL;
         
         if(isset($itemArr[0]->nStatus)){
             if($itemArr[0]->nStatus==2){
                  $returnUrl .= 'Account cancelled';
             }else if($itemArr[0]->nStatus==1){
                  $returnUrl = '<a href="'.BASE_URL.'admin/service/changeAccountSetting/'.$lookupID.'/0" class="accountAction">Suspend Account</a>';
                   $returnUrl .= ' / <a href="'.BASE_URL.'admin/service/doAccountCancellation/'.$lookupID.'" class="accountAction">Cancel Account</a>';
             }elseif($itemArr[0]->nStatus==0){
                 $returnUrl = '<a href="'.BASE_URL.'admin/service/changeAccountSetting/'.$lookupID.'/1" class="accountAction">Activate Account</a>';
                 $returnUrl .= ' / <a href="'.BASE_URL.'admin/service/doAccountCancellation/'.$lookupID.'" class="accountAction">Cancel Account</a>';
             }
             
            /* if($itemArr[0]->nStatus==0){
                
                  $returnUrl = '<a href="'.BASE_URL.'admin/service/changeAccountSetting/'.$lookupID.'/1" class="accountAction">Activate Account</a>';
             } else if($itemArr[0]->nStatus==0){
                 
                 $returnUrl = '<a href="'.BASE_URL.'admin/service/changeAccountSetting/'.$lookupID.'/0" class="accountAction">Suspend Account</a>';
             }

             $returnUrl .= ' / <a href="'.BASE_URL.'admin/service/doAccountCancellation/'.$lookupID.'" class="accountAction">Cancel Account</a>'; */
             
         }
         return $returnUrl;

     } // End Function
     
     public static function getAccountProductsLink($lookupID){ 
        $returnUrl = '<a href="'.BASE_URL.'cms?section=products&storeid='.trim($lookupID).'" class="accountAction">View Products</a>';
        return $returnUrl;
     } // End Function      

    //update status of store lookup
    public static function updateExpiredDomain($lookupID, $status) {
        Admincomponents::$dbObj = new Db();
        $query = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PLU SET PLU.nStatus = $status WHERE PLU.nPLId = $lookupID";
       echo $query;
        Admincomponents::$dbObj->execute($query);
    } //End Function

    //suspend the invoice
    public static function updateInvoice($plid, $invstatus = 1) {
        Admincomponents::$dbObj = new Db();
        $lastBillArray = "SELECT max( BMN.nBmId ) as nBmId
                                  FROM " . Admincomponents::$dbObj->tablePrefix . "BillingMain BMN
                            INNER JOIN " . Admincomponents::$dbObj->tablePrefix . "Invoice INV  ON INV.nInvId = BMN.vInvNo
                                 WHERE INV.nPLId='" . $plid . "'
                              GROUP BY INV.nPLId
                              ORDER By INV.nInvId DESC";
        $lastBillArray = Admincomponents::$dbObj->selectQuery($lastBillArray);

        $arrUpdate = array();
        $arrUpdate["vDelStatus"] = $invstatus;
        if ($invstatus == 0) {
            //update the next billing date of the plan with today's date
            $arrUpdate["dDateNextBill"] = date("Y-m-d", strtotime("+1 day", time()));
        }

        if (!empty($lastBillArray)) {
            Admincomponents::$dbObj->updateFields("BillingMain", $arrUpdate, "nBmId='" . $lastBillArray[0]->nBmId . "'");
        }
    } // End Function


    public static function getPlanExpiryCms($lookupID){
       $expiryDate = NULL;
       Admincomponents::$dbObj = new Db();
        
       $lastBill = "SELECT IP.dDateStop
                        FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PL
                        INNER JOIN " . Admincomponents::$dbObj->tablePrefix . "Invoice INV ON INV.nPLId = PL.nPLId
                        INNER JOIN " . Admincomponents::$dbObj->tablePrefix . "InvoicePlan IP ON IP.nInvId = INV.nInvId
                        WHERE INV.nPLId='".$lookupID."'
                            GROUP BY INV.nPLId
                            ORDER By INV.nInvId DESC";
        
        $lastBillArray = Admincomponents::$dbObj->selectQuery($lastBill);
        
        if(isset($lastBillArray[0]->dDateStop)){
            $expiryDate = Utils::formatDateUS($lastBillArray[0]->dDateStop, FALSE, 'date');
        }
        return $expiryDate;

    } // End Function

    
    //get list of expired domains
    public static function getExpiredDomains() {
        $dataArr = array();
        Admincomponents::$dbObj = new Db();

        $query = "SELECT PLU.nPLId FROM " . Admincomponents::$dbObj->tablePrefix . "Invoice I
                  LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "InvoicePlan IP ON I.nInvId = IP.nInvId
                  LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PLU ON I.nPLId = PLU.nPLId
                  WHERE I.vSubscriptionType = 'FREE' AND I.upgraded = 0 AND IP.dDateNextBill <= CURDATE() AND PLU.nStatus != 0";

        $result = Admincomponents::$dbObj->execute($query);
        $dataArr = Admincomponents::$dbObj->fetchAll($result);
               
        return $dataArr;
    }

    public static function getFreePlanPeriod() {
        Admincomponents::$dbObj = new Db();
        $freeplanId = $freeplanPeriod = NULL;
        $sel = "SELECT ps.nServiceId, ps.vBillingInterval, ps.nBillingDuration FROM ".Admincomponents::$dbObj->tablePrefix."ProductServices ps
             WHERE ps.vType ='free' AND ps.nStatus=1";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            $freeplanId = $dataArr[0]->nServiceId; // Free Paln Id
            $freeplanPeriod = $dataArr[0]->nBillingDuration;
            switch($dataArr[0]->vBillingInterval){
                case 'M':                    
                    $freeplanPeriod .= ($dataArr[0]->nBillingDuration > 1) ?  ' days' : ' day';
                    break;
                case 'Y':
                    $freeplanPeriod .= ($dataArr[0]->nBillingDuration > 1) ?  ' years' : ' year';
                    break;
                case 'L':
                    $freeplanPeriod ='';
                    break;
            }
            
        }

        return $freeplanPeriod;

    } // End Function

    public static function getFreeTrialSpan() {
        Admincomponents::$dbObj = new Db();
        $freeplanId = $freeplanPeriod = NULL;
        $sel = "SELECT ps.nServiceId, ps.vBillingInterval, ps.nBillingDuration FROM ".Admincomponents::$dbObj->tablePrefix."ProductServices ps
             WHERE ps.vType ='free' AND ps.nStatus=1";
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            $freeplanId = $dataArr[0]->nServiceId; // Free Paln Id
            $freeplanPeriod = $dataArr[0]->nBillingDuration;
        }

        return $freeplanPeriod;

    } // End Function

     public static function getCmsBannerClickCount($bannerId){
         $content = 'N.A';
         $bannerDetails = Admincomponents::getBannerDetails($bannerId);
         if(!empty($bannerDetails)){
             if($bannerDetails->eType=='Footer'){
                $content = $bannerDetails->clickcount;
             }
         }         
         return $content;
         
     } //End Function


     public static function getCurrencySymbolForCms(){
         return "(".CURRENCY_SYMBOL.")";
     } //End Function

     public static function getPendingPaymentList($dateRange) {
        Admincomponents::$dbObj = new Db();
        $query = "SELECT CONCAT_WS(' ', USR.vFirstName, USR.vLastName) as Name, USR.vEmail, USR.vInvoiceEmail, PLU.nPLId, PLU.vSubDomain, PLU.vDomain, INV.vInvNo, INV.dGeneratedDate FROM  " . Admincomponents::$dbObj->tablePrefix . "Invoice INV
                   LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "User USR ON INV.nUId = USR.nUId
                   LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PLU ON INV.nPLId = PLU.nPLId
                   WHERE INV.vSubscriptionType NOT LIKE 'FREE' AND CURDATE() > INV.dDueDate AND CURDATE() < PLU.dPlanExpiryDate AND INV.dPayment LIKE '0000-00-00' AND DATEDIFF(CURDATE(), INV.dGeneratedDate) = $dateRange";

        $result = Admincomponents::$dbObj->execute($query);
        $resultSet = Admincomponents::$dbObj->fetchAll($result);
        $userArray = array();       
        if ($resultSet) {
            foreach ($resultSet as $key => $val) {
                $userArray[] = array("user_name" => $val->Name,
                                     "productlookupID" => $val->nPLId,
                                     "subdomain" => $val->vSubDomain,
                                     "user_mail" => $val->vEmail,
                                     "invoice_mail" => $val->vInvoiceEmail,
                                     "domain" => $val->vDomain,
                                     "inv_no" => $val->vInvNo,
                                     "inv_date" => $val->dGeneratedDate);
            } // Foreach
        } //End If

        return $userArray;
    } //End Function

    public static function getProductLookUpIDFromInvoice($invoiceID){
       $productLookUpID = NULL;
       $dataArr = array();
       Admincomponents::$dbObj = new Db();
       $dataArr = Admincomponents::$dbObj->selectRecord("Invoice INV INNER JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup LP ON INV.nPLId = LP.nPLId "," LP.nPLId ", " INV.nInvId = ".$invoiceID." GROUP BY LP.nPLId");
       
       if(!empty($dataArr)) {
          $productLookUpID =  $dataArr->nPLId;
       }
            
       return $productLookUpID;
    } //End Function

    public static function logDomainRenewal($productLookUpID,$status, $logID = NULL){
        Admincomponents::$dbObj = new Db();

        $storeHost = $logStatus = $comments = NULL;

            if(!empty($productLookUpID)){
                //... store host
                $storeHost = Admincomponents::getStoreHost($productLookUpID);
                                
                switch($status){
                    case '0':// Scenario - Domain renewal not yet attempted
                            $logStatus = 0; //... log status
                            $comments = 'Payment Received, Domain Renewal Failed'; //... log comments
                            break;
                    case '1': // Scenario - Domain renewed
                            $logStatus = 1; //... log status
                            $comments = 'Domain Renewed'; //... log comments
                            break;
                    case '2': // Scenario - Not yet attempted to renew Domain due to the payment failure
                            $logStatus = 0; //... log status
                            $comments = 'Payment Due'; //... log comments
                            break;
                } //End switch

                if(empty($logID)){
                    $logID = Admincomponents::getDomainRenewalLogID($productLookUpID);
                }

                                          
                // ... log domain renewal
                if(!empty($logID)){

                    $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."DomainRenewalLog SET status='".$logStatus."',
                                    comments='".$comments."',
                                    lastModified=NOW() WHERE id='".$logID."'";

                } else {
                    
                    $itemQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."DomainRenewalLog SET id=NULL,
                                    nPLId='".$productLookUpID."',
                                    nUId=(SELECT nUId FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup WHERE nPLId='".$productLookUpID."'),
                                    vDomain='".$storeHost."',
                                    status='".$logStatus."',
                                    comments='".$comments."',
                                    expireOn=(SELECT dDateStop FROM ".Admincomponents::$dbObj->tablePrefix."InvoiceDomain WHERE nPLId='".$productLookUpID."' ORDER BY dDateStop DESC LIMIT 0,1),
                                    createdOn=NOW(),
                                    lastModified=NOW()";
                    
                }

                Admincomponents::$dbObj->execute($itemQry);
                               
            } // End If

        return true;
        
    } // end function

    public static function doDomainRenewal($productLookUpID){
            $status = false;
            $accountArr = $serverInfoArr = array();
            $domainRenewalDuration = 1; // by default the renewal period is considered as 1 year
            $errorMsg = NULL;

            Admincomponents::$dbObj          = new Db();
            if(!empty($productLookUpID)){
                // ... do domain renewal process
                // get the domain registrar
                $domain_registrar   = Admincomponents::$dbObj->selectRow("Settings","value","settingfield='domain_registrar'");
                $userAccountInfoArr = Admincomponents::getStoreWithUserDetailsFromProductLookupID($productLookUpID);

                //Expected Response
                $userAccountInfoArr->vSubDomain;
                $userAccountInfoArr->nSubDomainStatus;
                $userAccountInfoArr->vDomain;
                $userAccountInfoArr->nStatus;
                $userAccountInfoArr->vAccountDetails;
                $userAccountInfoArr->nTransactionSession;
                $userAccountInfoArr->nUId;
                $userAccountInfoArr->vFirstName;
                $userAccountInfoArr->vLastName;
                $userAccountInfoArr->vEmail;
                $userAccountInfoArr->userStatus;
                $userAccountInfoArr->vPhoneNumber;
                $userAccountInfoArr->vZipcode;
                //Expected Response Ends

                $serverInfoArr = Admincomponents::getServerInfoForStore($productLookUpID);

                //Expected Response
                 $serverInfoArr->vSubDomain;
                 $serverInfoArr->nSubDomainStatus;
                 $serverInfoArr->vDomain;
                 $serverInfoArr->nDomainStatus;
                 $serverInfoArr->vserver_name;
                 $serverInfoArr->whmip;
                 $serverInfoArr->vserver_hosting_plan;
                 $serverInfoArr->whm_port;
                 $serverInfoArr->cpanel_port;
                //Expected Response Ends

                if(!empty($domain_registrar) && !empty($userAccountInfoArr) && !empty($serverInfoArr)){

                    $accountArr = unserialize($userAccountInfoArr->vAccountDetails);

                    /* Sample AccountArr Info
                     $accountArr["user_name"] = "James";
                     $accountArr["user_email"] = "jamessmith121212@gmail.com";
                     $accountArr["store_name"] = "plansmsj004md.com";
                     $accountArr["userpassw"] = "d52e32";
                     $accountArr["user_lname"] = "Smith";
                     $accountArr["c_user"] = "jamc0e";
                     $accountArr["c_pass"] = "d41d8c";
                     $accountArr["c_host"] = "plansmsj004md.com";
                     $accountArr["sld"] = "plansmsj004md";
                     $accountArr["tld"] = "com";
                     $accountArr["tempdispurl"] = "http://plansmsj004md.clients.gostores.com/";
                     */

                    switch($domain_registrar){
                        case 'ENOM':
                            //Do the domain renewal for ENOM
                            PageContext::includePath('enom');

                            // Set account username and password
                            $enom_username  =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_user'");
                            $enom_password  =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_password'");
                            $enom_mode      =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_testmode'");

                            /* Input Data */

                                $sld = $accountArr["sld"]; // PLANSMSJ009A.COM
                                $tld = $accountArr["tld"];
                                $username = $enom_username;
                                $password = $enom_password;
                                //$password = User::decrytCreditCardDetails($password);
                                $duration = $domainRenewalDuration; // renewal duration
                                $enduserip = $serverInfoArr->whmip;
                                $enommode = $enom_mode;
                            /* Input Data Ends */

	
                            // Create URL Interface class
                            $Enom = new Enominterface();

                            $Enom->NewRequest();
                            // Set TLD and SLD of domain to register
                            $Enom->AddParam( "tld", $tld );
                            $Enom->AddParam( "sld", $sld );
                            // Set account username and password
                            $Enom->AddParam( "uid", $username );
                            $Enom->AddParam( "pw", $password );
                            // Set number of years to extend
                            if ( $duration != "" ) {
                                    $Enom->AddParam( "NumYears", $duration );
                            }

                            $Enom->AddParam( "EndUserIP", $enduserip );


                            $Enom->AddParam( "command", "extend" );
                            // All the info has been entered, now register the name
                            $Enom->DoTransaction($enom_mode);
                            //echopre($Enom->Values);
                            
                            // Were there errors?
                            if ( $Enom->Values[ "ErrCount" ] != "0" ) {
                                    // Yes, get the first one
                                    $errorMsg = $Enom->Values[ "Err1" ];

                            } else {
                                //domain renewal processed successfully
                                /* Sample response for a successful transaction
                                 Array
                                    (
                                        [Extension] => successful
                                        [DomainName] => plansmsj006md.com
                                        [OrderID] => 157919807
                                        [RRPCode] => 200
                                        [RRPText] => Command completed successfully
                                        [RegistryExpDate] => 2015-07-09 09:26:26.000
                                        [Command] => EXTEND
                                        [Language] => eng
                                        [ErrCount] => 0
                                        [ResponseCount] => 0
                                        [MinPeriod] => 1
                                        [MaxPeriod] => 10
                                        [Server] => SJL0VWRESELL_T1
                                        [Site] => eNom
                                        [IsLockable] => True
                                        [IsRealTimeTLD] => True
                                        [TimeDifference] => +08.00
                                        [ExecTime] => 0.610
                                        [Done] => true
                                        [RequestDateTime] => 7/9/2013 3:03:51 AM
                                    )
                                 */
                                $status = true;

                            }

                            break;
                        case 'GODADDY':
                            //Do the domain renewal for GODADDY
                            PageContext::includePath('parsexmls');
                            PageContext::includePath('godaddy');

                            $goDaddyObj = new goDaddy();

                            $parse  = new parsexmls();

                            // Product ID
                            $productID = User::getDomainProductIdForGodaddy(array("tld" => $accountArr["tld"],"years" => '1'),'Renewal');

                            /* Input Data */
                            $email = $userAccountInfoArr->vEmail;
                            $fname = $userAccountInfoArr->vFirstName;
                            $lname = $userAccountInfoArr->vLastName;
                            $phone = $userAccountInfoArr->vPhoneNumber;
                            $productid = $productID;
                            
                            $duration = $domainRenewalDuration;
                            $sld = $accountArr["sld"]; // PLANSMSJ009A.COM
                            $tld = $accountArr["tld"];
                            /* Input Data Ends */

                            $opStatus = $goDaddyObj->domainrenewal($email, $fname, $lname, $phone, $productid, $duration, $sld, $tld);
                            //echopre($opStatus);
                            $opParse = $parse->parseDomainRenewalResultXML($opStatus);
                            
                            /* Sample response for a successful transaction
                             /* sample response
                                 SimpleXMLElement Object
                            (
                                [@attributes] => Array
                                    (
                                        [user] => 835756
                                        [svTRID] => order.157899
                                        [clTRID] => reseller1372933192
                                    )

                                [result] => SimpleXMLElement Object
                                    (
                                        [@attributes] => Array
                                            (
                                                [code] => 1000
                                            )

                                        [msg] => processed 1 item
                                    )

                                [resdata] => SimpleXMLElement Object
                                    (
                                        [orderid] => 157899
                                    )

                            )

                             */
                            //Code No
                            // Success Code : 1000
                            // Error Code : 1001
                            $status = ($opParse["code"]=='1000') ? true : false;
                            $errorMsg = ($opParse["code"]=='1000') ? '' : $opParse["msg"]; //Error message
                            
                            break;
                    } // End Switch
                }
            } // End If                       

        return $status;
    } // end function

    public static function getDomainRenewalLink($logID){

         $itemArr = self::getListItem("DomainRenewalLog", array("status","comments"), array(array("field" => "id", "value" => $logID)));
         $returnUrl = '--';

         if(isset($itemArr[0]->status)){
             if($itemArr[0]->status==0){
                 
                  $returnUrl = '<a href="'.BASE_URL.'admin/service/doDomainRenewal/'.$logID.'" class="accountAction">Renew Domain</a>';
                  
             }             
         }
         return $returnUrl;

     } // End Function

     public static function updateDomainRenewalAttempt($logID){    
         //...update domain renewal attempt
         Admincomponents::$dbObj = new Db();
         $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "DomainRenewalLog SET cronAttempt= IFNULL(cronAttempt, 0) + 1 WHERE id='" . $logID . "'";
         Admincomponents::$dbObj->execute($itemUpdateQry);
     } // End Function

    public static function getTransactionSessionID(){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        $resData = Admincomponents::$dbObj->execute("SELECT CASE nTransactionSession WHEN(MAX(nTransactionSession) < 1) THEN 1 ELSE IFNULL(MAX(nTransactionSession), 0) + 1 END as sessID FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup");
        $listData = Admincomponents::$dbObj->fetchOne($resData);

        return $listData;

    } // End Function

    public static function getInvoicePaidDateForCMS($invoiceID) {
        Admincomponents::$dbObj     = new Db();
        $sel = "SELECT dPayment  FROM " . Admincomponents::$dbObj->tablePrefix . "Invoice WHERE nInvId = '".$invoiceID."'";
        $res = Admincomponents::$dbObj->selectQuery($sel);

        $datePaid = $paidOn = NULL;

        if(!empty($res)) {
            $datePaid = $res[0]->dPayment;
        }
        return $paidOn = Utils::formatDateUS($datePaid, false);


    } // End Function

    public static function saveTransactionSessionID($transactionSession, $productLookUpID){
         Admincomponents::$dbObj     = new Db();
         $response = 0;
         if(!empty($transactionSession)){
             $itemQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."ProductLookup SET nTransactionSession = '".$transactionSession."' WHERE nPLId='".$productLookUpID."'";
             $response = Admincomponents::$dbObj->execute($itemQry);
         }
         return $response;
    } // End Function

    public static function mapTransactionSessionWithBill($transactionSession){
        Admincomponents::$dbObj     = new Db();
        $listDataArr = array();

        if(!empty($transactionSession)) {

            $productLookUpID = Admincomponents::getProductLookupIDwithTransactionSessionID($transactionSession);
            if(!empty($productLookUpID)) {
                $invoiceList = Admincomponents::groupInvoicewithProductLookupID($productLookUpID);
                if(!empty($invoiceList)){
                    $resData = Admincomponents::$dbObj->execute("SELECT nBmId, nUId, nServiceId, vInvNo, vDomain, nDiscount, nAmount, nSpecialCost, vSpecials, vType, vBillingInterval, dDateStart, dDateStop, dDateNextBill, dDatePurchase, vDelStatus, cronAttempt FROM " . Admincomponents::$dbObj->tablePrefix . "BillingMain WHERE vInvNo IN(".$invoiceList.") AND vDelStatus !=1 GROUP BY nBmId");
                    $listDataArr = Admincomponents::$dbObj->fetchAll($resData);
                    
                }

            }

         }
        return $listDataArr;

    }

     public static function getProductLookupIDwithTransactionSessionID($transactionSession){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        $resData = Admincomponents::$dbObj->execute("SELECT nPLId FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup WHERE nTransactionSession=".$transactionSession);
        $listData = Admincomponents::$dbObj->fetchOne($resData);

        return $listData;

    } // End Function

    public static function groupInvoicewithProductLookupID($productLookUpID){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($productLookUpID)){
        $resData = Admincomponents::$dbObj->execute("SELECT GROUP_CONCAT(nInvId) FROM " . Admincomponents::$dbObj->tablePrefix . "Invoice WHERE nPLId=".$productLookUpID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }
        return $listData;

    } // End Function

    public static function getUserEmailwithProductLookupID($productLookUpID){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($productLookUpID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT vEmail FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PL LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "User U ON PL.nUId = U.nUId WHERE PL.nPLId=".$productLookUpID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }
        return $listData;

    } // End Function

    public static function getUserwithProductLookupID($productLookUpID){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($productLookUpID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT CONCAT_WS(' ',U.vFirstName,U.vLastName) as Name FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PL LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "User U ON PL.nUId = U.nUId WHERE PL.nPLId=".$productLookUpID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }
        return $listData;

    } // End Function

    public static function updateBillingAttempt($billMainID) {
        // To do : update the billing attempt and increment
        Admincomponents::$dbObj     = new Db();

        $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=1) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
        $response = Admincomponents::$dbObj->execute($itemUpdateQry);

        return $response;
    } // End Function

    public static function getTransactionSessionIDFromInvoice($invoiceID){
       $productLookUpID = NULL;
       $dataArr = array();
       Admincomponents::$dbObj = new Db();
       $dataArr = Admincomponents::$dbObj->selectRecord("Invoice INV INNER JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup LP ON INV.nPLId = LP.nPLId "," LP.nTransactionSession ", " INV.nInvId = ".$invoiceID." GROUP BY LP.nPLId");

       if(!empty($dataArr)) {
          $productLookUpID =  $dataArr->nTransactionSession;
       }

       return $productLookUpID;
    } //End Function

    public static function getStoreWithUserDetailsFromProductLookupID($productLookUpID){
        Admincomponents::$dbObj     = new Db();
        $listDataArr = array();
        if(!empty($productLookUpID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT PL.vSubDomain, PL.nSubDomainStatus, PL.vDomain, PL.nDomainStatus, PL.nStatus, PL.vAccountDetails, PL.nTransactionSession, U.nUId, U.vFirstName, U.vLastName, U.vEmail, U.vInvoiceEmail, U.nStatus as userStatus, U.vPhoneNumber, U.vZipcode,PL.bluedogdetails FROM " . Admincomponents::$dbObj->tablePrefix . "ProductLookup PL LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "User U ON PL.nUId = U.nUId WHERE PL.nPLId=".$productLookUpID." AND PL.nStatus=1 AND U.nStatus='1'");
        
        $listDataArr = Admincomponents::$dbObj->fetchRow($resData);
        }
        return $listDataArr;
    } // End Function

    public static function getServerInfoForStore($productLookUpID){

        Admincomponents::$dbObj     = new Db();
        $listDataArr = array();
        if(!empty($productLookUpID)){ // User
            $sel = "SELECT pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,s.vserver_name,s.whmip,s.vserver_hosting_plan,s.whm_port,s.cpanel_port FROM ".Admincomponents::$dbObj->tablePrefix."ProductLookup pl
                LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."serverHistory h ON pl.nPLId = h.nPLId
                    LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id
                        WHERE pl.nPLId ='".$productLookUpID."'";
            $resData = Admincomponents::$dbObj->execute($sel);

            $listDataArr = Admincomponents::$dbObj->fetchRow($resData);

        }
        return $listDataArr;
    }

    public static function getDomainRenewalLogID($productLookUpID){
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($productLookUpID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT id FROM " . Admincomponents::$dbObj->tablePrefix . "DomainRenewalLog WHERE nPLId=".$productLookUpID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }
        return $listData;
    }
    
    public static function getSoldTemplatePurchaseDateCms($orderID){
        $purchaseDate = NULL;
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($orderID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT paidOn FROM " . Admincomponents::$dbObj->tablePrefix . "PaidTemplatePurchase WHERE id=".$orderID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }

        if(!empty($listData)){
            $purchaseDate = Utils::formatDateUS($listData, FALSE);
        } else {
            $purchaseDate = '--';
        }
        return $purchaseDate;

    } // End Function

    public static function getUsernameOfSoldTemplateCMS($uid){
         Admincomponents::$dbObj = new Db();
         $data = "--";
        
         if(!empty($uid)){
          $userDetails = Admincomponents::$dbObj->selectRecord("User U LEFT JOIN " . Admincomponents::$dbObj->tablePrefix . "PaidTemplatePurchase P ON P.nUId = U.nUId","U.nUId, CONCAT_WS(' ',U.vFirstName,U.vLastName) as Name", " P.id = ".$uid);          
          $data = Admincomponents::cmsUserPopup($userDetails);
          
         }
         return $data;
     }

     public static function getStoreHostForSoldTemplatePurchaseCms($orderID){
        $storeHost = $productLookUpID = NULL;
        Admincomponents::$dbObj     = new Db();
        $listData = NULL;
        if(!empty($orderID)){ // User
        $resData = Admincomponents::$dbObj->execute("SELECT nPLId FROM " . Admincomponents::$dbObj->tablePrefix . "PaidTemplatePurchase WHERE id=".$orderID);
        $listData = Admincomponents::$dbObj->fetchOne($resData);
        }

        if(!empty($listData)){
            $productLookUpID = $listData;
            $storeHost = Admincomponents::getStoreHost($productLookUpID);
        } else {
            $storeHost = '--';
        }
        return $storeHost;

    } // End Function

    public static function getDefaultPackLocation(){
        
            $location = dirname($_SERVER["SCRIPT_FILENAME"]);
            $location .="/project/products/";

        
        return $location;
    }

    public static function getDefaultLandingLocation(){
           
            $location ="/public_html/";
        return $location;
    }


} //End Class


?>