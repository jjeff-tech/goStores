<?php 
// +----------------------------------------------------------------------+
// | File name : CMS	                                          		  |
// |(AUTOMATED CUSTOM CMS LOGIC)					 	  |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems Ã¯Â¿Â½ 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Cms extends Filehandler {


    public static function loadMenu() {

        $dbh 	 = new Db();
        $session    =   new LibSession();

        if($session->get("admin_type")=="developer")
            $privileges    =   "  ";
        else
            $privileges    =   " AND cs.user_privilege='all' AND cg.user_privilege='all' ";

        $res = $dbh->execute("SELECT cs.*,cg.group_name FROM cms_sections cs  LEFT JOIN cms_groups cg ON cs.group_id=cg.id WHERE cs.visibilty!='0' AND cg.published=1 $privileges  ORDER BY position,display_order ASC");
        $groups = $dbh->fetchAll($res);
        $menu = array();
        foreach($groups as $group) {
            $menu[$group->group_id]->name = $group->group_name;
            $menu[$group->group_id]->sections[] = $group;
        }
        return $menu;
    }
    public static function getParentRoleList($roleId,$parentRoleIDArray) {
        return $parentRoleIDArray= Cms::getParentRoleIdArray($roleId,$parentRoleIDArray);
        //echopre($parentRoleIDArray);
    }

    public static function  getPrivileges($parentRoleIDArray) {
        $parentRoleIDString  =  "" ;
        for($loop=0;$loop<count($parentRoleIDArray);$loop++) {
            $parentRoleIDString  .=  $parentRoleIDArray[$loop].",";
        }
        $parentRoleIDString = substr($parentRoleIDString, 0, -1);

        $privilegedGroups = Cms::getPrivilegedGroups($parentRoleIDString);

        $privilegedSections = Cms::getPrivilegedSections($parentRoleIDString);


        if($privilegedGroups!="")
            $privileges   .=   " AND cg.id NOT IN($privilegedGroups) ";
        if($privilegedSections!="")
            $privileges   .=   "  AND cs.id NOT IN($privilegedSections)";

        return $privileges;


    }

    public static function getAllRoles($roleId) {

        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT role_name,role_id,parent_role_id   FROM cms_roles  ");
        $roles = $dbh->fetchAll($res);


        $loop = 0;
        foreach($roles as $role) {
            $rolesArry[$loop]->value = $role->role_id;
            $rolesArry[$loop]->text = $role->role_name;
            $loop++;
        }
        return $rolesArry;
    }

    public static function getAllRolesArray($page,$perPageSize) {
        if($page)
            $startPage=($page-1)*$perPageSize;
        else
            $startPage =0;
        $limit=" LIMIT $startPage,$perPageSize";
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT role_name,role_id,parent_role_id   FROM cms_roles $limit ");
        $roles = $dbh->fetchAll($res);



        return $roles;
    }
    public static function getAllUsers() {

        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT username,id,role_id   FROM cms_users  where status='active'");
        $users = $dbh->fetchAll($res);



        return $users;
    }
    public static function getAllUserArray($page,$perPageSize) {
        if($page)
            $startPage=($page-1)*$perPageSize;
        else
            $startPage =0;
        $limit=" LIMIT $startPage,$perPageSize";
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT username,email,id,role_id   FROM cms_users where status='active' $limit ");

        $users = $dbh->fetchAll($res);



        return $users;
    }
    public static function getUserDetails($userId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_users WHERE id= '$userId' ");
        $users = $dbh->fetchRow($res);

        return $users;
    }
    public static function getRoleDetails($roleId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_roles WHERE role_id= '$roleId' ");
        $roles = $dbh->fetchRow($res);

        return $roles;
    }
    public static function getSectionId($sectionName = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT id   FROM cms_sections WHERE section_alias= '$sectionName' ");
        $sections = $dbh->fetchOne($res);

        return $sections;
    }

    public static function getprivilegedMenuList($roleId,$parentRoleIDArray) {

        $dbh 	 = new Db();
        $session    =   new LibSession();

        if($session->get("admin_type")=="developer")

            $privileges    =   "  ";
        else {

            $privileges = Cms::getPrivileges($parentRoleIDArray);
            $userprivilege  = " AND cs.user_privilege!='dev'";
        }


        $res = $dbh->execute("SELECT cs.*,cg.group_name FROM cms_sections cs  LEFT JOIN cms_groups cg ON cs.group_id=cg.id WHERE cs.visibilty!='0' AND cg.published=1 $privileges  $userprivilege ORDER BY position,display_order ASC");
        $groups = $dbh->fetchAll($res);
        $menu = array();
        foreach($groups as $group) {
            $menu[$group->group_id]->name = $group->group_name;
            $menu[$group->group_id]->sections[] = $group;
        }
        return $menu;

    }
    public static function loadDefaultMenu($roleId='',$parentRoleIDArray='') {

        if($roleId) {
            $dbh 	 = new Db();
            $session    =   new LibSession();

            if($session->get("admin_type")=="developer")
                $privileges    =   "  ";
            else {

                $privileges = Cms::getPrivileges($parentRoleIDArray);
            }


            $res = $dbh->execute("SELECT cs.*,cg.group_name FROM cms_sections cs  LEFT JOIN cms_groups cg ON cs.group_id=cg.id WHERE cs.visibilty!='0' AND cg.published=1 $privileges  ORDER BY position,display_order ASC");
            return $groups = $dbh->fetchRow($res);
        }
        else {
            $dbh 	 = new Db();
            $session    =   new LibSession();

            if($session->get("admin_type")=="developer")

                $privileges    =   "  ";
            else
                $privileges    =   " AND cs.user_privilege='all' AND cg.user_privilege='all' ";

            $res = $dbh->execute("SELECT cs.*,cg.group_name FROM cms_sections cs  LEFT JOIN cms_groups cg ON cs.group_id=cg.id WHERE cs.visibilty!='0' AND cg.published=1 $privileges  ORDER BY position,display_order ASC");
            return $groups = $dbh->fetchRow($res);
        }
    }
    //function to get parent categoies from a category id
    public static function getPrivilegedSections($parentRoleIDString = "") {
        if($parentRoleIDString!="") {
            $dbh 	 = new Db();
            $res = $dbh->execute("SELECT group_concat(entity_id)   FROM cms_privileges WHERE entity_type= 'section' AND view_role_id   IN($parentRoleIDString) ");
            $sections = $dbh->fetchOne($res);
        }
        return $sections;
    }

    public static function savePrivileges($privilegeId, $postAarray) {
        $dbh    =   new Db();
        if(!empty($postAarray)) {
            if($privilegeId > 0) {
                $updateQuery = "UPDATE  cms_privileges set  view_role_id ='".$postAarray['view_role_id']."', add_role_id ='".$postAarray['add_role_id']."', edit_role_id='".$postAarray['edit_role_id']."', delete_role_id ='".$postAarray['delete_role_id']."', publish_role_id='".$postAarray['publish_role_id']."' WHERE privilege_id=$privilegeId";
                $res = $dbh->execute($updateQuery);
                return $res;
            }else {
                $insertQuery = "INSERT INTO cms_privileges (entity_type,entity_id, view_role_id , add_role_id , edit_role_id, delete_role_id , publish_role_id ) values('".$postAarray['entity_type']."','".$postAarray['entity_id']."','".$postAarray['view_role_id']."','".$postAarray['add_role_id']."','".$postAarray['edit_role_id']."','".$postAarray['delete_role_id']."','".$postAarray['publish_role_id']."') ";
                $res = $dbh->execute($insertQuery);
                $insert_id = mysql_insert_id();
                return $insert_id;
            }
        }

    }
    public static function saveRoles($roleId, $postAarray) {
        $dbh    =   new Db();
        if(!empty($postAarray)) {
            if($roleId > 0) {
                $updateQuery = "UPDATE  cms_roles set  role_name ='".$postAarray['role_name']."', parent_role_id ='".$postAarray['parent_role_id']."' WHERE role_id=$roleId";
                $res = $dbh->execute($updateQuery);
                return $res;
            }else {
                $insertQuery = "INSERT INTO cms_roles (role_name,parent_role_id ) values('".$postAarray['role_name']."','".$postAarray['parent_role_id']."') ";
                $res = $dbh->execute($insertQuery);
                $insert_id = mysql_insert_id();
                return $insert_id;
            }
        }

    }
    public static function saveUser($id, $postAarray) {
        $dbh    =   new Db();
        if(!empty($postAarray)) {
            if($id > 0) {
                $updateQuery = "UPDATE  cms_users set  username ='".$postAarray['username']."',email='".$postAarray['email']."',role_id ='".$postAarray['role_id']."' ,type ='".$postAarray['type']."' WHERE id=$id";
                $res = $dbh->execute($updateQuery);
                return $res;
            }else {
                $insertQuery = "INSERT INTO cms_users (username,password,email,role_id,type ) values('".$postAarray['username']."','".md5($postAarray['password'])."','".$postAarray['email']."','".$postAarray['role_id']."','".$postAarray['type']."') ";
                $res = $dbh->execute($insertQuery);
                $insert_id = mysql_insert_id();
                return $insert_id;
            }
        }

    }
    public static function changeUserPassword($id, $postAarray) {
        $dbh    =   new Db();
        if(!empty($postAarray)) {
            if($id > 0) {
                $updateQuery = "UPDATE  cms_users set  password ='".md5($postAarray['newpassword'])."' WHERE id=$id";
                $res = $dbh->execute($updateQuery);
                return $res;
            }
        }

    }

    public static function getPrivilegeDetails($privilegeId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_privileges WHERE privilege_id=$privilegeId ");
        $sections = $dbh->fetchRow($res);

        return $sections;
    }
    public static function getPrivilegedSectionsArray($parentRoleIDString = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT entity_id   FROM cms_privileges WHERE entity_type= 'section' AND view_role_id   IN($parentRoleIDString) ");
        $sections = $dbh->fetchAll($res);

        return $sections;
    }
    public static function getSectionRoles($sectionId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_privileges WHERE entity_type= 'section' AND entity_id=$sectionId ");
        $sections = $dbh->fetchAll($res);

        return $sections[0];
    }
    //function to get parent categoies from a category id
    public static function getPrivilegedGroups($parentRoleIDString = "") {
        if($parentRoleIDString!="") {
            $dbh 	 = new Db();
            $res = $dbh->execute("SELECT group_concat(entity_id) as entity_id   FROM cms_privileges WHERE entity_type= 'group' AND view_role_id   IN($parentRoleIDString) ");
            $sections = $dbh->fetchOne($res);
        }
        return $sections;
    }
    //function to get parent categoies from a category id

    public static function getParentRoleIdArray($roleId = "",  $parentRoleIdArray) {
        $parentRoleId           =   Cms::getParentRoleId($roleId);
        if($parentRoleId!="")
            $parentRoleIdArray[]    =   $parentRoleId;
        //$parentIdArray[]    =   $parentId;

        if($parentRoleId != 0 ) {
            return  $parentRoleId       =   Cms::getParentRoleIdArray($parentRoleId,$parentRoleIdArray);
        }
        else {

            return $parentRoleIdArray;
        }

    }
    //function to get parent categoies from a category id
    public static function getParentRoleId($roleId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT parent_role_id  FROM cms_roles WHERE role_id = $roleId");
        $user = $dbh->fetchOne($res);

        return $user;
    }
    public static function getprivilege($sectionId = "") {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_privileges ");
        $privileges = $dbh->fetchAll($res);

        return $privileges;
    }
    public static function  deleteprivilege($privilegeId) {
        $dbh 	 = new Db();
        $res = $dbh->execute("delete    FROM cms_privileges where privilege_id=$privilegeId");
        return ;


    }
    public static function  deleteRole($roleId) {

        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT   role_id FROM cms_roles WHERE  parent_role_id = $roleId");
        $roleExist = $dbh->fetchAll($res);
        if(count($roleExist))
            return "roleExist";

        $res = $dbh->execute("SELECT   id FROM cms_users WHERE  role_id = $roleId");
        $userExist = $dbh->fetchAll($res);
        if(count($userExist))
            return "userExist";
        $res = $dbh->execute("delete    FROM cms_roles where role_id=$roleId");
        return ;


    }
    public static function  checkUserExist($username,$id) {

        $dbh 	 = new Db();
        if($id)
            $res = $dbh->execute("SELECT   id FROM cms_users WHERE  username='$username' and id!= $id");
        else
            $res = $dbh->execute("SELECT   id FROM cms_users where username='$username'  ");
        $userExist = $dbh->fetchAll($res);
        return count($userExist);






    }
    public static function  deleteUser($userId) {

        $dbh 	 = new Db();

        $res = $dbh->execute("delete    FROM cms_users where id=$userId");
        return ;


    }

    public static function getprivilegeList($page,$perPageSize) {
        if($page)
            $startPage=($page-1)*$perPageSize;
        else
            $startPage =0;
        $limit=" LIMIT $startPage,$perPageSize";
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_privileges $limit ");
        $privileges = $dbh->fetchAll($res);

        return $privileges;
    }
    public static function getAllPrivileges() {

        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT *   FROM cms_privileges  ");
        $privileges = $dbh->fetchAll($res);

        return $privileges;
    }

    public static function getNewSections($addedSections) {
        $dbh 	 = new Db();
        if($addedSections!="")
            $in = " where id not in ($addedSections)";
        $res = $dbh->execute("SELECT section_name,id   FROM cms_sections $in");
        $sections = $dbh->fetchAll($res);

        return $sections;
    }
    public static function getSections() {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT section_name,id   FROM cms_sections ");
        $sections = $dbh->fetchAll($res);

        return $sections;
    }
    public static function getGroupId($sectionId) {
        if($sectionId) {
            $dbh 	 = new Db();
            $res = $dbh->execute("SELECT group_id 	   FROM cms_sections where id=$sectionId ");
            $sections = $dbh->fetchOne($res);
        }
        return $sections;
    }
    public static function getNewGroups($addedGroups) {
        $dbh 	 = new Db();
        if($addedGroups!="")
            $in = " where id not in ($addedGroups)";
        $res = $dbh->execute("SELECT group_name,id   FROM cms_groups $in");
        $groups = $dbh->fetchAll($res);

        return $groups;
    }
    public static function getGroups() {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT group_name,id   FROM cms_groups ");
        $groups = $dbh->fetchAll($res);

        return $groups;
    }
    public static function getEntityName($entityId,$entityType) {

        $dbh 	 = new Db();
        if($entityType=='group') {
            $res = $dbh->execute("SELECT group_name    FROM cms_groups where id= $entityId");
            $name = $dbh->fetchOne($res);

            return $name;
        }
        if($entityType=='section') {
            $dbh 	 = new Db();
            $res = $dbh->execute("SELECT section_name    FROM cms_sections where id= $entityId");
            $name = $dbh->fetchOne($res);

            return $name;
        }
    }
    public static function getRoleName($roleId = "") {
        if($roleId==0)
            return "sadmin";
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT role_name    FROM cms_roles where role_id= $roleId");
        $role = $dbh->fetchOne($res);

        return $role;
    }

    public static function hasSectionPrivileges($section) {
        $session    =   new LibSession();

        $dbh 	 = new Db();
        if($session->get("admin_type")=="sadmin")
            $privileges    =   "  ";
        if($session->get("admin_type")=="admin")
            $privileges    =   " AND user_privilege='all'  ";

        $res = $dbh->execute("SELECT count(id) FROM cms_sections  WHERE 1  AND section_alias='".$section."'   $privileges  ");

        $count = $dbh->fetchOne($res);

        return $count;
    }

    public static function loadSection($request) {

        $section_alias  = $request['section'];
        $dbh 	 = new Db();
        $res 			= $dbh->execute("SELECT * FROM cms_sections where section_alias='".addslashes($section_alias)."' limit 1");
        $section_data 	= $dbh->fetchRow($res);

        if(!$section_data->section_config || $section_data->section_config=='')return $section_data;
//$listData 		= Cms::listData($section_data,$request);
//$listRenderData = Cms::renderSectionListing($listData, $section_data->section_config,$request);
        return $section_data;
    }
    public static function checkLogin($username='',  $password='',$roleEnabled='') {

        $dbh 	 = new Db();
        if($roleEnabled)
            $res = $dbh->execute("SELECT id,type,role_id FROM cms_users WHERE username='".addslashes($username)."' AND password='".addslashes($password)."' AND status='active' ");
        else
            $res = $dbh->execute("SELECT id,type FROM cms_users WHERE username='".addslashes($username)."' AND password='".addslashes($password)."' AND status='active' ");

        $user = $dbh->fetchRow($res);
        return $user;
    }
    public static function getSectionData($request) {

        $section_alias  = $request['section'];
        $dbh 	 = new Db();
        $res 			= $dbh->execute("SELECT * FROM cms_sections where section_alias='$section_alias' limit 1");
        $section_data 	= $dbh->fetchRow($res);

        if(!$section_data->section_config || $section_data->section_config=='')return $section_data;
//$listData 		= Cms::listData($section_data,$request);
//$listRenderData = Cms::renderSectionListing($listData, $section_data->section_config,$request);
        return $section_data;
    }
    public static function getlayoutSectionData() {

        $section_alias  = 'cms_layout';
        $dbh 	 = new Db();
        $res 			= $dbh->execute("SELECT * FROM cms_sections where section_alias='$section_alias' limit 1");
        $section_data 	= $dbh->fetchRow($res);

        if(!$section_data->section_config || $section_data->section_config=='')return $section_data;
//$listData 		= Cms::listData($section_data,$request);
//$listRenderData = Cms::renderSectionListing($listData, $section_data->section_config,$request);
        return $section_data;
    }
    public  static function getParentSectionData($request) {

        $section_alias  = $request['parent_section'];
        $dbh 	 = new Db();
        $res 			= $dbh->execute("SELECT * FROM cms_sections where section_alias='$section_alias' limit 1");
        $section_data 	= $dbh->fetchRow($res);

        if(!$section_data->section_config || $section_data->section_config=='')return $section_data;
//$listData 		= Cms::listData($section_data,$request);
//$listRenderData = Cms::renderSectionListing($listData, $section_data->section_config,$request);
        return $section_data;
    }
// Function to perform join opertaions

    public static function getJoinResult($section_data,$joinConfig,$listData) {

        $dbh 	 = new Db();
        $parentColumn=json_decode($section_data->section_config);
        $key   =   $parentColumn->keyColumn;
        $val        =   $listData->$key;
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " count(*) AS count";
//from table (joins?)
        $from = " FROM ".$section_data->table_name." AS parent ";
//join clause
        $join="  JOIN $joinConfig->child_table AS child ON parent.".$joinConfig->parent_join_column."=child.".$joinConfig->child_join_column;

//where condition
        $where = " WHERE 1 AND parent.".$parentColumn->keyColumn."=$val  ";
//Final Query
        $query = $query.$columns.$from.$join.$where;
// resultset
        $res = $dbh->execute($query);
        $listData = $dbh->fetchOne($res);
// returning count of result
//         return $listData;
        return $listData['count'];
    }

//function for returning section listing
    public  static function listData($section_data,$request,$perPageSize) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        $refVar=1;
        $combineFlag    =   0;




        foreach($section_config->combineTables as $combineTable => $combineOptions) {
            $combineFlag=0;
            foreach($combineOptions->combineColumns as $column) {
                foreach($section_config->columns as $key => $col) {
                    if($column==$key) {

                        $columns    .= " combine$refVar.".$column." AS ".$column.",";
                        $combineFlag=1;
                        if($combineOptions->isPrimaryKey) {
                            $combineReferenceColumn =$combineOptions->combineReferenceColumn;
                            $combineTableForiegnKey =$combineOptions->combineTableForiegnKey;
                        }
                        else {
                            $combineReferenceColumn =$combineOptions->combineTableForiegnKey;
                            $combineTableForiegnKey =$combineOptions->combineReferenceColumn;
                        }
                        if($request['searchField']==$column) {

                            $searchText=addslashes($request['searchText']);
                            $search=" AND combine$refVar.".$request['searchField']." LIKE '%".$searchText."%'";

                        }


                    }
                }
            }
            if($combineFlag ==1) {
                $join       .=  " LEFT JOIN ". $combineTable." AS combine$refVar ON ".$section_data->table_name.".".$combineTableForiegnKey."=combine$refVar.".$combineReferenceColumn;

                if($groupBy =="")
                    $groupBy    .= " GROUP BY ";
                $groupBy    .= "  combine$refVar.".$combineReferenceColumn." ,";

            }




            $refVar++;



        }
        foreach($section_config->detailColumns as $col) {
            $columnInserted=0;
// one to one relation for combininf two tables
            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {
                    $combineFlag=0;
                    foreach($combineOptions->combineColumns as $column) {

                        if($column==$col) {
                            $columnInserted =1;

                        }
                    }

                }

            }
            if($section_config->combineReferenceColumn) {
                foreach($section_config->combineReferenceColumn as $combineCol) {

                    if($combineCol==$col) {


                        $externalOptions    =   $section_config->columns->$col->externalCombineOptions;
                        $columns    .= " externalCombine$refVar.".$externalOptions->externalCombineShowColumn." AS ".$col.",";
                        $join       .=  " LEFT JOIN ". $externalOptions->externalCombineTable." AS externalCombine$refVar ON ".$section_data->table_name.".".$section_config->keyColumn."=externalCombine$refVar.".$externalOptions->externalCombineForeigenKey;


                        $combineReferenceFlag    =   1;


                    }
                }
            }




// if column is a foreign key
            if($section_config->columns->$col->external) {
                $externalOptions    =   $section_config->columns->$col->externalOptions;
                $columns    .= " external$refVar.".$externalOptions->externalShowColumn." AS ".$col.",".$section_data->table_name.".".$col." as external_$col,";
                $join       .=  " LEFT JOIN ". $externalOptions->externalTable." AS external$refVar ON external$refVar.".$externalOptions->externalColumn."=".$section_data->table_name.".".$col;
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND external$refVar.".$externalOptions->externalShowColumn." LIKE '%".$searchText."%'";

                }

            }

// primary table colummn
            else if($section_config->reference->referenceColumn==$col ) {

                $columns    .= $section_data->table_name.".".$col." ,";
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";

                }

            }

            else if($columnInserted==0 && !$section_config->columns->$col->customColumn  ) { //&& !$section_config->columns->$col->customColumn
            
                $columns    .= $section_data->table_name.".".$col.",";
                if($request['searchField']==$col) {

//                     $searchText=mysql_real_escape_string($request['searchText']);
//                     if($request['searchField']==$section_config->keyColumn)
//                         $search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
//                     else
//                         $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";
                	$searchText=addslashes($request['searchText']);
                	if($request['searchField']==$section_config->keyColumn)
                		$search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."' ";
                	else {
                	
                		if($section_config->columns->$col->listoptions) {
                			$listEnumvalues = json_decode($section_config->columns->$col->listoptions->enumvalues);
                	
                			foreach($section_config->columns->$col->listoptions->enumvalues as $lisOptionKey=>$listOptionValue) {
                				if(strtolower($listOptionValue)==strtolower($searchText))
                					$search=" AND ".$section_data->table_name.".".$col." LIKE '".$lisOptionKey."' ";
                			}
                	
                		}
                		else {
                	
                			$search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%' ";
                		}
                	}

                }
            }
            else if($columnInserted==0 && $section_config->columns->$col->customColumn  ) {
            
            	if($request['searchField']==$col) {
            
            		$columns    .= $section_data->table_name.".".$col.",";
            
            
            		$searchText=addslashes($request['searchText']);
            		if($request['searchField']==$section_config->keyColumn)
            			$search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
            		else
            			$search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";
            	}
            }
            $refVar++;

        }

        if($section_config->publishColumn) { //publish column
            $columns .= $section_data->table_name.".".$section_config->publishColumn;
        }
        if(!substr_count($columns, $section_data->table_name.".".$section_config->keyColumn.","))
            $columns   .= " ".$section_data->table_name.".".$section_config->keyColumn.",";
        $columns = rtrim($columns,",");
//from table (joins?)
        if($section_config->reference)
            $from = " FROM ".$section_config->reference->referenceTable." AS ".$section_config->reference->referenceTable ;
        if($section_config->filter && isset($request['parent_id']))
            $from = " FROM ".$section_config->filter->filterTable ;
        else
            $from = " FROM $section_data->table_name ";
// join

        if($section_config->reference) {

            $join .= "  JOIN ".$section_config->reference->referenceTable." AS reference ON ".$section_data->table_name.".".$section_config->reference->referenceColumn."=reference.".$section_config->reference->referenceTableForiegnKey;


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $join .= " LEFT JOIN ".$section_data->table_name." AS ".$section_data->table_name." ON ".$section_data->table_name.".".$section_config->keyColumn."=".$section_config->filter->filterTable.".".$section_config->filter->filterColumn;


        }

//        if($combineFlag ==  1) {
//            $combineOptions =   $section_config->combineOptions;
//            $join       .=  " LEFT JOIN ". $combineOptions->combineTable." AS combine ON combine.".$combineOptions->combineColumn."=".$section_data->table_name.".".$combineOptions->combineTableForiegnKey;
//
//            $groupBy    = "GROUP BY  $combineOptions->combineColumn";
//
//        }

//where condition //publish, page limit, page offset //sort
        $where = " WHERE 1   ";

//        if($section_config->where) {
//            foreach($section_config->where as $wherekey=>$whereval) {
//
//                for($whrLoop=0;$whrLoop<count($whereval);$whrLoop++) {
//                    $where .= " AND ".$section_data->table_name.".".$wherekey.$whereval[$whrLoop]."";
//                }
//
//            }
//        }
        if($section_config->where) {
            $where .= " AND ".$section_config->where." ";
        }
        if($section_config->reference) {

            $where .= " AND ".$section_data->table_name.".".$section_config->reference->referenceColumn."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $where .= " AND ".$section_data->table_name.".".$section_config->filter->filterTableForiegnKey."=".$request['parent_id'];


        }
        if($groupBy !="") {
            $groupBy    .=" ".$section_data->table_name.".".$section_config->keyColumn.",";
            $groupBy    = substr($groupBy, 0, -1);
        }
        $section_config=json_decode($section_data->section_config);
// default ORDER BY clause
        foreach($section_config->orderBy as  $key => $value)
            $orderby=" ORDER BY ".$key." ".$value;
//  ORDER BY clause from the $_GET params
        if(isset($request['orderField']))
            $orderby=" ORDER BY ".$request['orderField']." ".$request['orderType'];
//logic for pagination
        $page=$request['page'];
// if page is not set
        if($page=="")
            $page=1;
//finding start page
        $startPage=($page-1)*$perPageSize;
        $limit=" LIMIT $startPage,$perPageSize";
//combine and execute query

        $query = $query.$columns.$from.$join.$where.$search.$groupBy.$orderby.$limit;
        Logger::info($query);
        $res = $dbh->execute($query);

        $listData = $dbh->fetchAll($res);
//return result
        return $listData;


    }
//function for get report
    public function getReport($section_data,$request,$reportStartDate,$reportEndDate) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        $refVar=1;
        $combineFlag    =   0;




        foreach($section_config->combineTables as $combineTable => $combineOptions) {
            $combineFlag=0;
            foreach($combineOptions->combineColumns as $column) {
                foreach($section_config->columns as $key => $col) {
                    if($column==$key) {

                        $columns    .= " combine$refVar.".$column." AS ".$column.",";
                        $combineFlag=1;
                        if($combineOptions->isPrimaryKey) {
                            $combineReferenceColumn =$combineOptions->combineReferenceColumn;
                            $combineTableForiegnKey =$combineOptions->combineTableForiegnKey;
                        }
                        else {
                            $combineReferenceColumn =$combineOptions->combineTableForiegnKey;
                            $combineTableForiegnKey =$combineOptions->combineReferenceColumn;
                        }
                        if($request['searchField']==$column) {

                            $searchText=addslashes($request['searchText']);
                            $search=" AND combine$refVar.".$request['searchField']." LIKE '%".$searchText."%'";

                        }


                    }
                }
            }
            if($combineFlag ==1) {
                $join       .=  " LEFT JOIN ". $combineTable." AS combine$refVar ON ".$section_data->table_name.".".$combineTableForiegnKey."=combine$refVar.".$combineReferenceColumn;

                if($groupBy =="")
                    $groupBy    .= " GROUP BY ";
                $groupBy    .= "  combine$refVar.".$combineReferenceColumn." ,";

            }




            $refVar++;



        }
        foreach($section_config->detailColumns as $col) {
            $columnInserted=0;
// one to one relation for combininf two tables
            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {
                    $combineFlag=0;
                    foreach($combineOptions->combineColumns as $column) {

                        if($column==$col) {


                            $columnInserted =1;


                        }
                    }



                }



            }
            if($section_config->combineReferenceColumn) {
                foreach($section_config->combineReferenceColumn as $combineCol) {

                    if($combineCol==$col) {


                        $externalOptions    =   $section_config->columns->$col->externalCombineOptions;
                        $columns    .= " externalCombine$refVar.".$externalOptions->externalCombineShowColumn." AS ".$col.",";
                        $join       .=  " LEFT JOIN ". $externalOptions->externalCombineTable." AS externalCombine$refVar ON ".$section_data->table_name.".".$section_config->keyColumn."=externalCombine$refVar.".$externalOptions->externalCombineForeigenKey;


                        $combineReferenceFlag    =   1;


                    }
                }
            }




// if column is a foreign key
            if($section_config->columns->$col->external) {
                $externalOptions    =   $section_config->columns->$col->externalOptions;
                  $columns    .= " external$refVar.".$externalOptions->externalShowColumn." AS ".$col.",".$section_data->table_name.".".$col." as external_$col,";
               $join       .=  " LEFT JOIN ". $externalOptions->externalTable." AS external$refVar ON external$refVar.".$externalOptions->externalColumn."=".$section_data->table_name.".".$col;
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND external$refVar.".$externalOptions->externalShowColumn." LIKE '%".$searchText."%'";

                }

            }

// primary table colummn
            else if($section_config->reference->referenceColumn==$col ) {

                $columns    .= $section_data->table_name.".".$col." ,";
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";

                }

            }

            else if($columnInserted==0 && !$section_config->columns->$col->customColumn) {
                $columns    .= $section_data->table_name.".".$col.",";
                if($request['searchField']==$col) {

//                     $searchText=mysql_real_escape_string($request['searchText']);
//                     if($request['searchField']==$section_config->keyColumn)
//                         $search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
//                     else
//                         $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";
                	$searchText=addslashes($request['searchText']);
                	if($request['searchField']==$section_config->keyColumn)
                		$search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
                	else {
                	
                		if($section_config->columns->$col->listoptions) {
                			$listEnumvalues = json_decode($section_config->columns->$col->listoptions->enumvalues);
                	
                			foreach($section_config->columns->$col->listoptions->enumvalues as $lisOptionKey=>$listOptionValue) {
                				if(strtolower($listOptionValue)==strtolower($searchText))
                					$search=" AND ".$section_data->table_name.".".$col." LIKE '".$lisOptionKey."' ";
                			}
                	
                		}
                		else {
                	
                			$search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%' ";
                		}
                	}

                }
            }

            $refVar++;

        }

        if($section_config->publishColumn) { //publish column
            $columns .= $section_data->table_name.".".$section_config->publishColumn;
        }
        if(!substr_count($columns, $section_data->table_name.".".$section_config->keyColumn.","))
            $columns   .= " ".$section_data->table_name.".".$section_config->keyColumn.",";
        $columns = rtrim($columns,",");
//from table (joins?)
        if($section_config->reference)
            $from = " FROM ".$section_config->reference->referenceTable." AS ".$section_config->reference->referenceTable ;
        if($section_config->filter && isset($request['parent_id']))
            $from = " FROM ".$section_config->filter->filterTable ;
        else
            $from = " FROM $section_data->table_name ";
// join

        if($section_config->reference) {

           $join .= "  JOIN ".$section_config->reference->referenceTable." AS reference ON ".$section_data->table_name.".".$section_config->reference->referenceColumn."=reference.".$section_config->reference->referenceTableForiegnKey;


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $join .= " LEFT JOIN ".$section_data->table_name." AS ".$section_data->table_name." ON ".$section_data->table_name.".".$section_config->keyColumn."=".$section_config->filter->filterTable.".".$section_config->filter->filterColumn;


        }

//        if($combineFlag ==  1) {
//            $combineOptions =   $section_config->combineOptions;
//            $join       .=  " LEFT JOIN ". $combineOptions->combineTable." AS combine ON combine.".$combineOptions->combineColumn."=".$section_data->table_name.".".$combineOptions->combineTableForiegnKey;
//
//            $groupBy    = "GROUP BY  $combineOptions->combineColumn";
//
//        }

//where condition //publish, page limit, page offset //sort
        $where = " WHERE 1   ";
//        if($section_config->where) {
//            foreach($section_config->where as $wherekey=>$whereval) {
//
//                for($whrLoop=0;$whrLoop<count($whereval);$whrLoop++) {
//                    $where .= " AND ".$section_data->table_name.".".$wherekey.$whereval[$whrLoop]."";
//                }
//
//            }
//        }
        if($section_config->where) {
            $where .= " AND ".$section_config->where." ";
        }
//         if($section_config->report) {

//             $where .= " AND   DATE_FORMAT(".$section_data->table_name.".".$section_config->report->dateColumn.",'%m-%d-%Y')>= '".$reportStartDate."' AND DATE_FORMAT(".$section_data->table_name.".".$section_config->report->dateColumn.",'%m-%d-%Y')< '".$reportEndDate."'";
//         }
        if($section_config->report) {
        	if(GLOBAL_DATE_FORMAT_SEPERATOR)
        		$date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
        	else {
        		$date_separator = "-";
        	}
        
        
        	$where .= " AND   DATE_FORMAT(".$section_data->table_name.".".$section_config->report->dateColumn.",'%Y-%m-%d')>= '".$reportStartDate."' AND DATE_FORMAT(".$section_data->table_name.".".$section_config->report->dateColumn.",'%Y-%m-%d')<= '".$reportEndDate."'";
        }
        if($section_config->reference) {

            $where .= " AND ".$section_data->table_name.".".$section_config->reference->referenceColumn."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $where .= " AND ".$section_data->table_name.".".$section_config->filter->filterTableForiegnKey."=".$request['parent_id'];


        }
        if($groupBy !="") {
            $groupBy    .=" ".$section_data->table_name.".".$section_config->keyColumn.",";
            $groupBy    = substr($groupBy, 0, -1);
        }
        $section_config=json_decode($section_data->section_config);
// default ORDER BY clause
        foreach($section_config->orderBy as  $key => $value)
            $orderby=" ORDER BY ".$key." ".$value;
//  ORDER BY clause from the $_GET params
        if(isset($request['orderField']))
            $orderby=" ORDER BY ".$request['orderField']." ".$request['orderType'];

//combine and execute query

        $query = $query.$columns.$from.$join.$where.$search.$groupBy.$orderby;
        Logger::info($query);
        $res = $dbh->execute($query);

        $listData = $dbh->fetchAll($res);
//return result
        return $listData;


    }
//function for returning section listing
    public function listItem($section_data,$request) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        foreach($section_config->detailColumns as $col) {
            $columnInserted=0;
// one to one relation for combininf two tables
            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {
                    $combineFlag=0;
                    foreach($combineOptions->combineColumns as $column) {

                        if($column==$col) {

                            $columns    .= " combine$refVar.".$column." AS ".$column.",";
                            $combineFlag=1;
                            $combineReferenceColumn =$combineOptions->combineReferenceColumn;
                            $columnInserted =1;


                        }
                    }
                    if($combineFlag ==1) {
                        $join       .=  " LEFT JOIN ". $combineTable." AS combine$refVar ON ".$section_data->table_name.".".$section_config->keyColumn."=combine$refVar.".$combineReferenceColumn;

                        if($groupBy =="")
                            $groupBy    .= " GROUP BY ";
                        $groupBy    .= "  combine$refVar.".$combineReferenceColumn." ,";

                    }


                }



            }
            if($section_config->combineReferenceColumn) {
                foreach($section_config->combineReferenceColumn as $combineCol) {

                    if($combineCol==$col) {


                        $externalOptions    =   $section_config->columns->$col->externalCombineOptions;
                        $columns    .= " externalCombine$refVar.".$externalOptions->externalCombineShowColumn." AS ".$col.",";
                        $join       .=  " LEFT JOIN ". $externalOptions->externalCombineTable." AS externalCombine$refVar ON ".$section_data->table_name.".".$section_config->keyColumn."=externalCombine$refVar.".$externalOptions->externalCombineForeigenKey;


                        $combineReferenceFlag    =   1;


                    }
                }
            }




// if column is a foreign key
            if($section_config->columns->$col->external) {
                $externalOptions    =   $section_config->columns->$col->externalOptions;
                $columns    .= " CONCAT(external".$refVar.".".$externalOptions->externalShowColumn.",'{valSep}', external".$refVar.".".$externalOptions->externalColumn.") AS ".$col.",";
                $join       .=  " LEFT JOIN ". $externalOptions->externalTable." AS external$refVar ON external$refVar.".$externalOptions->externalColumn."=".$section_data->table_name.".".$col;
            }

// primary table colummn
            else if($section_config->reference->referenceColumn==$col ) {

                $columns    .= $section_data->table_name.".".$col." ,";

            }

            else if($columnInserted==0 && !$section_config->columns->$col->customColumn)
                $columns    .= $section_data->table_name.".".$col.",";

            $refVar++;

        }

        $columns = rtrim($columns,",");
//from table (joins?)
        $from = " FROM $section_data->table_name ";

//where condition //publish, page limit, page offset //sort
        $where = " WHERE ".$section_data->table_name.".".$section_config->keyColumn."='".$request[$section_config->keyColumn]."'";

//combine and execute query
        $query = $query.$columns.$from.$join.$where.$search.$orderby.$limit;
        $res = $dbh->execute($query);
        $listData = $dbh->fetchAll($res);
//return result
        return $listData;


    }
//function for returning section listing
    public function listDataNumRows($section_data,$request) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        $refVar=1;
        $combineFlag    =   0;
        foreach($section_config->combineTables as $combineTable => $combineOptions) {
            $combineFlag=0;
            foreach($combineOptions->combineColumns as $column) {
                foreach($section_config->columns as $key => $col) {
                    if($column==$key) {

                        $columns    .= " combine$refVar.".$column." AS ".$column.",";
                        $combineFlag=1;
                        if($combineOptions->isPrimaryKey) {
                            $combineReferenceColumn =$combineOptions->combineReferenceColumn;
                            $combineTableForiegnKey =$combineOptions->combineTableForiegnKey;
                        }
                        else {
                            $combineReferenceColumn =$combineOptions->combineTableForiegnKey;
                            $combineTableForiegnKey =$combineOptions->combineReferenceColumn;
                        }
                        if($request['searchField']==$column) {

                            $searchText=addslashes($request['searchText']);
                            $search=" AND combine$refVar.".$request['searchField']." LIKE '%".$searchText."%'";

                        }


                    }
                }
            }
            if($combineFlag ==1) {
                $join       .=  " LEFT JOIN ". $combineTable." AS combine$refVar ON ".$section_data->table_name.".".$combineTableForiegnKey."=combine$refVar.".$combineReferenceColumn;

                if($groupBy =="")
                    $groupBy    .= " GROUP BY ";
                $groupBy    .= "  combine$refVar.".$combineReferenceColumn." ,";

            }




            $refVar++;



        }
        foreach($section_config->detailColumns as $col) {
            $columnInserted=0;
// one to one relation for combininf two tables
            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {
                    $combineFlag=0;
                    foreach($combineOptions->combineColumns as $column) {

                        if($column==$col) {


                            $columnInserted =1;


                        }
                    }



                }



            }
            if($section_config->combineReferenceColumn) {
                foreach($section_config->combineReferenceColumn as $combineCol) {

                    if($combineCol==$col) {


                        $externalOptions    =   $section_config->columns->$col->externalCombineOptions;
                        $columns    .= " externalCombine$refVar.".$externalOptions->externalCombineShowColumn." AS ".$col.",";
                        $join       .=  " LEFT JOIN ". $externalOptions->externalCombineTable." AS externalCombine$refVar ON ".$section_data->table_name.".".$section_config->keyColumn."=externalCombine$refVar.".$externalOptions->externalCombineForeigenKey;


                        $combineReferenceFlag    =   1;


                    }
                }
            }




// if column is a foreign key
            if($section_config->columns->$col->external) {
                $externalOptions    =   $section_config->columns->$col->externalOptions;
                $columns    .= " external$refVar.".$externalOptions->externalShowColumn." AS ".$col.",";
                $join       .=  " LEFT JOIN ". $externalOptions->externalTable." AS external$refVar ON external$refVar.".$externalOptions->externalColumn."=".$section_data->table_name.".".$col;
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND external$refVar.".$externalOptions->externalShowColumn." LIKE '%".$searchText."%'";

                }

            }

// primary table colummn
            else if($section_config->reference->referenceColumn==$col ) {

                $columns    .= $section_data->table_name.".".$col." ,";
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";

                }

            }

            else if($columnInserted==0 && !$section_config->columns->$col->customColumn) {
                $columns    .= $section_data->table_name.".".$col.",";
                if($request['searchField']==$col) {

                    $searchText=addslashes($request['searchText']);
                    if($request['searchField']==$section_config->keyColumn)
                        $search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
                    else
                        $search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";

                }
            }
            else if($columnInserted==0 && $section_config->columns->$col->customColumn) {
            
            	if($request['searchField']==$col) {
            		$columns    .= $section_data->table_name.".".$col.",";
            		$searchText=addslashes($request['searchText']);
            		if($request['searchField']==$section_config->keyColumn)
            			$search=" AND ".$section_data->table_name.".".$col." LIKE '".$searchText."'";
            		else
            			$search=" AND ".$section_data->table_name.".".$col." LIKE '%".$searchText."%'";
            
            	}
            }

            $refVar++;

        }

        if($section_config->publishColumn) { //publish column
            $columns .= $section_data->table_name.".".$section_config->publishColumn;
        }
        $columns = rtrim($columns,",");
//from table (joins?)
        if($section_config->reference)
            $from = " FROM ".$section_config->reference->referenceTable." AS ".$section_config->reference->referenceTable ;
        if($section_config->filter && isset($request['parent_id']))
            $from = " FROM ".$section_config->filter->filterTable ;
        else
            $from = " FROM $section_data->table_name ";
// join

        if($section_config->reference) {

            $join .= "  JOIN ".$section_config->reference->referenceTable." AS reference ON ".$section_data->table_name.".".$section_config->reference->referenceColumn."=reference.".$section_config->reference->referenceTableForiegnKey;


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $join .= " LEFT JOIN ".$section_data->table_name." AS ".$section_data->table_name." ON ".$section_data->table_name.".".$section_config->keyColumn."=".$section_config->filter->filterTable.".".$section_config->filter->filterColumn;


        }

//        if($combineFlag ==  1) {
//            $combineOptions =   $section_config->combineOptions;
//            $join       .=  " LEFT JOIN ". $combineOptions->combineTable." AS combine ON combine.".$combineOptions->combineColumn."=".$section_data->table_name.".".$combineOptions->combineTableForiegnKey;
//
//            $groupBy    = "GROUP BY  $combineOptions->combineColumn";
//
//        }

//where condition //publish, page limit, page offset //sort
        $where = " WHERE 1   ";
//        if($section_config->where) {
//            foreach($section_config->where as $wherekey=>$whereval) {
//
//                for($whrLoop=0;$whrLoop<count($whereval);$whrLoop++) {
//                    $where .= " AND ".$section_data->table_name.".".$wherekey.$whereval[$whrLoop]."";
//                }
//
//            }
//        }
        if($section_config->where) {
            $where .= " AND ".$section_config->where." ";
        }
        if($section_config->reference) {

            $where .= " AND ".$section_data->table_name.".".$section_config->reference->referenceColumn."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $where .= " AND ".$section_data->table_name.".".$section_config->filter->filterTableForiegnKey."=".$request['parent_id'];


        }
        if($groupBy !="") {
            $groupBy    .=" ".$section_data->table_name.".".$section_config->keyColumn.",";
            $groupBy    = substr($groupBy, 0, -1);
        }
        $section_config=json_decode($section_data->section_config);


        $query = $query.$columns.$from.$join.$where.$search.$groupBy;
        Logger::info($query);
        $res = $dbh->execute($query);

        $listData = $dbh->fetchAll($res);

//returning count of results
        return count($listData);




    }
//function for returning parent section listing
    public function listParentItem($section_data,$request) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        if($section_config->breadCrumbColumn!="")
            $columns = $section_config->breadCrumbColumn;
        else
            $columns = "*";

//from table (joins?)
        $from = " FROM $section_data->table_name ";

//where condition //publish, page limit, page offset //sort
        $where = " WHERE $section_config->keyColumn='".$request['parent_id']."'";

//combine and execute query
        $query = $query.$columns.$from.$where.$search.$orderby.$limit;
        $res = $dbh->execute($query);
        $listData = $dbh->fetchOne($res);
//return result
        return $listData;


    }
// finding count of resultset
    public function listDataNumRows_old($section_data,$request) {

        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " count(*) AS count ";
//from table (joins?)
//from table (joins?)
        if($section_config->reference)
            $from = " FROM ".$section_config->reference->referenceTable ;
        if($section_config->filter && isset($request['parent_id']))
            $from = " FROM ".$section_config->filter->filterTable ;
        else
            $from = " FROM $section_data->table_name ";
// join
        $where = " WHERE 1   ";
        if($section_config->reference) {

            $where .= " AND reference.".$section_config->reference->referenceTableForiegnKey."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $join .= " LEFT JOIN ".$section_data->table_name." AS ".$section_data->table_name." ON ".$section_data->table_name.".".$section_config->keyColumn."=".$section_config->filter->filterTable.".".$section_config->filter->filterColumn;


        }
// search parameters
        $search=" ";
        if($request['searchField']) {
            $searchText=addslashes($request['searchText']);
            $search=" AND ".$request['searchField']." LIKE '%".$searchText."%'";
        }

//where condition //publish, page limit, page offset //sort

        if($section_config->reference) {

            $where .= " AND ".$section_data->table_name.".".$section_config->reference->referenceColumn."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $where .= " AND ".$section_config->filter->filterTable.".".$section_config->filter->filterTableForiegnKey."=".$request['parent_id'];


        }
//combine and execute query
        $query = $query.$columns.$from.$join.$where.$search;
        $res = $dbh->execute($query);
        $rowNum = $dbh->fetchOne($res);

        Logger::info($rowNum);
//returning count of results
        return $rowNum;


    }
// finding count of resultset
    public function listDataNumRowsOld($section_data,$request) {
        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        $refVar=1;
        foreach($section_config->listColumns as $col) {

// if column is a foreign key
            if($section_config->columns->$col->external) {
                $externalOptions    =   $section_config->columns->$col->externalOptions;
                $columns    .= " external$refVar.".$externalOptions->externalShowColumn." AS ".$col.",";
                $join       .=  " LEFT JOIN ". $externalOptions->externalTable." AS external$refVar ON external$refVar.".$externalOptions->externalColumn."=".$section_data->table_name.".".$col;
            }
            else if($section_config->reference->referenceColumn==$col) {

                $columns    .= $section_data->table_name.".".$col.",";

            }
            else
                $columns    .= $section_data->table_name.".".$col.",";
            $refVar++;

        }
        if($section_config->publishColumn) { //publish column
            $columns .= $section_data->table_name.".".$section_config->publishColumn;
        }
        $columns = rtrim($columns,",");
//from table (joins?)
        if($section_config->reference)
            $from = " FROM ".$section_config->reference->referenceTable." AS ".$section_config->reference->referenceTable ;
        if($section_config->filter && isset($request['parent_id']))
            $from = " FROM ".$section_config->filter->filterTable ;
        else
            $from = " FROM $section_data->table_name ";
// join

        if($section_config->reference) {

            $join .= "  JOIN ".$section_config->reference->referenceTable." reference ON ".$section_data->table_name.".".$section_config->reference->referenceColumn."=reference.".$section_config->keyColumn;


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $join .= " LEFT JOIN ".$section_data->table_name." AS ".$section_data->table_name." ON ".$section_data->table_name.".".$section_config->keyColumn."=".$section_config->filter->filterTable.".".$section_config->filter->filterColumn;


        }
// search parameters
        $search=" ";
        if($request['searchField']) {

            $searchText=addslashes($request['searchText']);
            $search=" AND ".$request['searchField']." LIKE '%".$searchText."%'";

        }
//where condition //publish, page limit, page offset //sort
        $where = " WHERE 1   ";
        if($section_config->reference) {

            $where .= " AND ".$section_data->table_name.".".$section_config->reference->referenceColumn."=".$request['parent_id'];


        }
        if($section_config->filter && isset($request['parent_id'])) {

            $where .= " AND ".$section_data->table_name.".".$section_config->filter->filterTableForiegnKey."=".$request['parent_id'];


        }
        $section_config=json_decode($section_data->section_config);
// default ORDER BY clause
        foreach($section_config->orderBy as  $key => $value)
            $orderby=" ORDER BY ".$key." ".$value;
//  ORDER BY clause from the $_GET params
        if(isset($request['orderField']))
            $orderby=" ORDER BY ".$request['orderField']." ".$request['orderType'];


        $query = $query.$columns.$from.$join.$where.$search.$orderby;
        Logger::info($query);
        $res = $dbh->execute($query);
        $listData = $dbh->fetchAll($res);
//return result
        return count($listData);


    }
// function to get section config values
    public function getSectionListingData($section_data,$request) {
        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//select
        $query = " SELECT ";
//get columns to retreive
        $columns = " ";
        foreach($section_config->columns as $key => $val) $columns .= $key.",";

        $columns = rtrim($columns,",");
//from table (joins?)
        $from = " FROM $section_data->table_name ";
//where condition //publish, page limit, page offset //sort
        $where = " LIMIT 10";
//combine and execute query
        $query = $query.$columns.$from.$where;

        $res = $dbh->execute($query);
        $listData = $dbh->fetchAll($res);
        Logger::info($query);
        return $listData;
    }
    public static function getSearchableColumns($section_data) {
        $searchableColumns  =   array();
        $section_config = json_decode($section_data->section_config);
        foreach($section_config->detailColumns as $col) {

            foreach($section_config->columns as $key => $value) {
                if($key==$col) {
                    if($section_config->columns->$col->searchable) {
                        $searchableColumns[$key]=$section_config->columns->$col->name;
                    }
                }
            }

        }
        return $searchableColumns;

    }

// function to get current url using GET params
    public static function formUrl($request,$sectionConfig) {

        $url   = BASE_URL."cms?section=".$request['section'];
        if($request['page']!="")  $url    .=  "&page=".$request['page'];
        if($request['orderField']!="")  $url    .=  "&orderField=".$request['orderField']."&orderType=".$request['orderType'];
        if($request['searchField']!="") $url    .=  "&searchField=".$request['searchField']."&searchText=".$request['searchText'];
        if($request['action']=="edit")  $url     .=  "&action=edit&".$sectionConfig->keyColumn."=".$request[$sectionConfig->keyColumn];
        if($request['action']=="add")  $url     .=  "&action=add";
        if($request['parent_section']!="") $url .=  "&parent_section=".$request['parent_section'];
        if($request['parent_section']!="") $url .=  "&parent_id=".$request['parent_id'];

        return $url;
    }
    // function to get current url using GET params
    public static function formPagingUrl($request) {

        $url   = BASE_URL."cms?section=".$request['section'];
        if($request['page']!="")  $url    .=  "&page=".$request['page'];
        if($request['orderField']!="")  $url    .=  "&orderField=".$request['orderField']."&orderType=".$request['orderType'];
        if($request['searchField']!="") $url    .=  "&searchField=".$request['searchField']."&searchText=".$request['searchText'];
        if($request['parent_section']!="") $url .=  "&parent_section=".$request['parent_section'];
        if($request['parent_section']!="") $url .=  "&parent_id=".$request['parent_id'];

        return $url;
    }
    // function to get search url using GET params
    public static function formSearchUrl($request) {

        $url   = BASE_URL."cms?section=".$request['section'];


        if($request['parent_section']!="") $url .=  "&parent_section=".$request['parent_section'];
        if($request['parent_section']!="") $url .=  "&parent_id=".$request['parent_id'];

        return $url;
    }

// function to delete an entry from listing
    public function deleteEntry($section_data,$request) {
        $dbh 	 = new Db();
        $section_config = json_decode($section_data->section_config);
//delete
        $query = " DELETE ";

//from table ()
        $from = " FROM $section_data->table_name ";
//where condition //publish, page limit, page offset //sort
        $where = " WHERE ".$section_config->keyColumn."=".$request[$section_config->keyColumn];
//combine and execute query
        $query = $query.$columns.$from.$where;

        $res = $dbh->execute($query);
//delete from referance table
//TODO
        if($section_config->reference) {
            $referanceQuery = " DELETE ";

//from table ()
            $referanceFrom = " FROM ".$section_config->reference->referenceTable ;
//where condition //publish, page limit, page offset //sort
            $referanceWhere = " WHERE ".$section_config->reference->referenceTableForiegnKey."=".$request['parent_id']." AND ".$section_config->reference->referenceColumn."=".$request[$section_config->keyColumn];
//combine and execute query
            $referanceQuery = $referanceQuery.$referanceFrom.$referanceWhere;

// $res = $dbh->execute($referanceQuery);

        }
        return ;

    }

//function to change publish status
    public function changePublishStatus($sectionData,$request) {
        $dbh 	 = new Db();
        $section_config = json_decode($sectionData->section_config);
        $query = " UPDATE ".$sectionData->table_name." SET ";
        if($request['action']=="publish")
            $status=1;
        if($request['action']=="unpublish")
            $status=0;
        $columns .= $section_config->publishColumn."=".$status;
        $where=" WHERE ".$section_config->keyColumn."=".$request[$section_config->keyColumn];
        $query=$query.$columns.$where;
        $res = $dbh->execute($query);
    }
    public function formValidation($sectionData,$params,$request) {

        $section_config = json_decode($sectionData->section_config);
        foreach($section_config->showColumns as $col) {
            $key = $col;
            $val = $section_config->columns->$col;
            if($key!=$section_config->keyColumn) {
                foreach($val->editoptions->validations as $validations) {
                    echopre($validations);
                }

            }
        }

    }
    public static  function before($params) {

        return $params;
    }
//function to save
    public function saveForm($sectionData,$params,$request) {

        $dbh 	 = new Db();
        $section_config = json_decode($sectionData->section_config);
        $globalutils      = new Globalutils;
        $aliasColumn     =   $section_config->alias;
        $table           =  $sectionData->table_name;

        $customActions  =   $section_config->customActions;



//update

        if($request['action']=="edit") {
            //pre submit action
            if($customActions->beforeEditRecord) {
                $params        =   call_user_func($customActions->beforeEditRecord,$params);
            }



            if($aliasColumn!="") {
                $exclude_id = ($params[$section_config->keyColumn]>0)?$params[$section_config->keyColumn]:'';
                $alias = $globalutils->checkAndValidateEntityAlias($table,$params[$aliasColumn], $section_config->keyColumn,$exclude_id, $alias_column="alias");

            }
            $query = " UPDATE ".$sectionData->table_name." SET ";
            $loop   =   0;

            foreach($section_config->columns as $key => $val) {
                foreach($section_config->showColumns as $showColumn) {
                    if($showColumn == $key ) {

                        $combineColumn=0;
// creating alias
                        if($loop==0 && $aliasColumn!="") {
                            //$columns .=     "alias="."'".$alias."',";
                            $loop++;

                        }
                        foreach($section_config->combineTables as $combineTable => $combineOptions) {

                            foreach($combineOptions->combineColumns as $column) {

                                if($column==$key) {

                                    $combineColumn=1;
                                }
                            }
                        }
                        if($combineColumn==0) {
                            if($key!=$section_config->keyColumn) {
                                if($section_config->reference->referenceColumn==$key) {
                                    $columns .= $section_config->reference->referenceColumn."=". $request['parent_id'].",";


                                }
                                else {


                                    if($val->editoptions->type=="password" && key_exists($key, $params)) {
                                    	$columns .= $key."="."'".addslashes(md5($params[$key]))."',";
                                    	if($val->editoptions->noEncryption) {
                                    		$columns .= $key."="."'".addslashes($params[$key])."',";
                                    	
                                    	}
                                    	else
                                        	$columns .= $key."="."'".addslashes(md5($params[$key]))."',";

                                    }
                                    else if($val->editoptions->type=="file" ) {
                                        if($_FILES[$key]['tmp_name']!="") {
                                            $fileHandler = new Filehandler();


//                                             $fileHandler->allowed_extensions=  array("gif","jpg", "jpeg", "png","bmp");
                                            $fileHandler->allowed_extensions=  array("gif","jpg", "jpeg", "png","bmp","zip");
                                            
                                            $fileDetails = $fileHandler->handleUpload($_FILES[$key]); 
                                            $fileArray["fileId"] = $fileDetails->file_id;
                                            $columns .= $key."=".addslashes($fileArray["fileId"]).",";

                                        }

                                    }
                                    else if($val->editoptions->type=="autocomplete") {
                                        $autoCompleteId =   explode(":",$params["selected_".$key]);
                                        $autoCompleteId =   $autoCompleteId[0];
                                        $columns .= $key."="."'".addslashes($autoCompleteId)."',";

                                    }
                                    else if($val->editoptions->type=="checkbox") {
                                        if($params[$key]=="")
                                            $params[$key]   =   0;
                                        $columns .= $key."="."'".addslashes($params[$key])."',";


                                    }
                                    else if($val->editoptions->type=="datepicker") {

                                        if($params[$key]=="" && $val->dbFormat=="date")
                                            $params[$key]           =   date();
                                        else {
                                            $datePickerDate         =   $params[$key];
                                            $date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
                                            if($date_separator=="")
                                                $date_separator = "-";
                                            $datePickerDateArray    =   explode($date_separator, $datePickerDate);


                                            if($val->dbFormat=="date")
                                                $params[$key]   =  $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
                                            if($val->dbFormat=="datetime")
                                                $params[$key]   =   $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
//                                             if($val->editoptions->dbFormat=="date")
//                                             	$params[$key]   =   $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
//                                             if($val->editoptions->dbFormat=="datetime")
//                                             	$params[$key]   =   $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
                                            
                                        }

                                        $columns .= $key."="."'".addslashes($params[$key])."',";


                                   }
                                    else if($val->editoptions->type=="hidden") {
                                        $columns  .=  $key."="."'".addslashes($params[$key])."',";
                                    }
                                    else if($key!="alias" && key_exists($key, $params))

                                        $columns .= $key."="."'".addslashes($params[$key])."',";
                                }
                            }
                        }
                    }
                }
            }
            $columns = rtrim($columns,",");
            $where=" WHERE ".$section_config->keyColumn."='".$request[$section_config->keyColumn]."'";
            $query=$query.$columns.$where;


            Logger::info($query);
            $res = $dbh->execute($query);
//$lastInsertedId =   $dbh->lastInsertId();

// insert into comine table



            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {

                    $query      =   " update  ".$combineTable;

                    $columns    =   " SET ";


                    foreach($combineOptions->combineColumns as $column) {
                        foreach($section_config->showColumns as $showColumn) {
                            if($showColumn==$column)

                                foreach($section_config->columns as $key => $val) {
                                    if($column==$key) {
                                        $updateCombineTableFlag =   1;
                                        $columns    .=  $key."="."'".addslashes($params[$key])."',";
//$values  .=   "'".mysql_real_escape_string($params[$key])."',";
                                        $combineColumn=$combineOptions->combineReferenceColumn;


                                    }
                                }
                        }

                        $where=" WHERE ".$combineColumn."='".$request[$section_config->keyColumn]."'";

                        $columns = rtrim($columns,",");

                        $query=$query.$columns.$where;
                        Logger::info($query);
                        if($updateCombineTableFlag)
                            $res = $dbh->execute($query);



                    }

                }
            }
            // post submit action
            if($customActions->afterEditRecord) {
                $params        =   call_user_func($customActions->afterEditRecord,$request[$section_config->keyColumn],$params);
            }

        }
        else {
//insert
// check for alias
//pre submit action
            if($customActions->beforeAddRecord) {
                $params        =   call_user_func($customActions->beforeAddRecord,$params);
            }
            $query      =   " INSERT INTO ".$sectionData->table_name;
            $values     =   " VALUES(";
            $columns    =   " ( ";
            $loop       =   0;
            if($aliasColumn!="") {
                $alias = $globalutils->checkAndValidateEntityAlias($table,$params[$aliasColumn], $section_config->keyColumn,"", $alias_column="alias");

            }
            foreach($section_config->columns as $key => $val) {
                foreach($section_config->showColumns as $showColumn) {
                    if($showColumn == $key ) {
                        $combineColumn=0;
// creating alias
                        if($loop==0 && $aliasColumn!="") {

                            $columns .=     "alias,";
                            $values  .=     "'".$alias."',";
                            $loop++;

                        }

                        foreach($section_config->combineTables as $combineTable => $combineOptions) {

                            foreach($combineOptions->combineColumns as $column) {

                                if($column==$key) {

                                    $combineColumn=1;
                                }
                            }
                        }
                        if($combineColumn==0) {
                            if($key!=$section_config->keyColumn) {
                                if($section_config->reference->referenceColumn==$key) {
                                    $columns .= $section_config->reference->referenceColumn.",";
                                    $values  .=   $request['parent_id'].",";

                                }
                                else {
                                    if( $key!="alias")
                                        $columns .= $key.",";
                                    if($val->editoptions->type=="password")
                                    	if($val->editoptions->noEncryption)
                                    		$values  .=   "'".addslashes($params[$key])."',";
                                    	else                                    		 
                                        	$values  .=   "'".addslashes(md5($params[$key]))."',";
                                    else if($val->editoptions->type=="file" && ($_FILES[$key]['tmp_name']!="") ) {
                                        $fileHandler = new Filehandler();
                                        $fileHandler->allowed_extensions=  array("gif","jpg", "jpeg", "png","bmp","zip");
                                        $fileDetails = $fileHandler->handleUpload($_FILES[$key]);
                                        $fileArray["fileId"] = $fileDetails->file_id;
                                        $values  .=  addslashes($fileArray["fileId"]).",";
                                    }
                                    else if($val->editoptions->type=="autocomplete") {
                                        $autoCompleteId =   explode(":",$params["selected_".$key]);
                                        $autoCompleteId =   $autoCompleteId[0];
                                        $values  .=   "'".addslashes($autoCompleteId)."',";
                                    }
                                    else if($val->editoptions->type=="checkbox") {
                                        if($params[$key]=="")
                                            $params[$key]=0;
                                        $values  .=   "'".addslashes($params[$key])."',";
                                    }
                                    else if($val->editoptions->type=="datepicker") {

                                        if($params[$key]=="" && $val->dbFormat=="date")
                                            $params[$key]           =   date();
                                        else {
                                            $datePickerDate         =   $params[$key];
                                            $date_separator =  GLOBAL_DATE_FORMAT_SEPERATOR;
                                            if($date_separator=="")
                                                $date_separator = "-";
                                            $datePickerDateArray    =   explode($date_separator, $datePickerDate);


                                            if($val->dbFormat=="date")
                                                $params[$key]   =  $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
                                            if($val->dbFormat=="datetime")
                                                $params[$key]   =   $datePickerDateArray[2]."-".$datePickerDateArray[0]."-".$datePickerDateArray[1];
                                        }
                                        $values  .=   "'".addslashes($params[$key])."',";


                                    }
                                    else if($val->editoptions->type=="hidden") {
                                        $values  .=   "'".addslashes($params[$key])."',";
                                    }
                                    else if($key!="alias" ) {
                                        $values  .=   "'".addslashes($params[$key])."',";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $columns = rtrim($columns,",");
            $values = rtrim($values,",");
            $values     .=  " )";
            $columns    .=  ")";
            $query=$query.$columns.$values;
            Logger::info($query);
            $res = $dbh->execute($query);
            $lastInsertedId =   $dbh->lastInsertId();

// insert into referance table



            if($section_config->combineTables) {

                foreach($section_config->combineTables as $combineTable => $combineOptions) {
                    $query      =   " INSERT INTO ".$combineTable;
                    $values     =   " VALUES(";
                    $columns    =   " ( ";


                    foreach($combineOptions->combineColumns as $column) {

                        foreach($section_config->columns as $key => $val) {
                            if($column==$key) {

                                $columns    .=  $key.",";
                                $values  .=   "'".addslashes($params[$key])."',";
                                $combineColumn=$combineOptions->combineReferenceColumn;


                            }
                        }
                    }
                    $columns .=$combineColumn.",";
                    $values     .=$lastInsertedId.",";
                    $columns = rtrim($columns,",");
                    $values = rtrim($values,",");
                    $values     .=  " )";
                    $columns    .=  ")";
                    $query=$query.$columns.$values;

                    Logger::info($query);
                    $res = $dbh->execute($query);





                }
            }
            // after submit action
            //pre submit action
            if($customActions->afterAddRecord) {
                $params        =   call_user_func($customActions->afterAddRecord,$lastInsertedId,$params);
            }
        }

        return ;
    }
//function to get getBreadCrumb from url
    public function getBreadCrumb($request) {
        $html   =   '<ul class="breadcrumb">';

        $html   .=    '</ul>';
        if($request['section'])
            $html   .=   '<li class="active"><?php echo PageContext::$request["section"]; ?></li>' ;
        if($request['parent_section'])
            $html   .=   '<li><a href="#">'.PageContext::$request["parent_section"].'</a> <span class="divider">&raquo;</span></li>' ;
    }
//function to get thumb image of file
    public static function getThumbImage($fileId,$width,$height) {
        $fileName       =   Cms::getImageName($fileId);
        $filePath       =   BASE_URL. 'project/files/'.$fileName;
        if($fileId=="")
            $filePath  =   BASE_URL. 'project/images/cms/'."missing-image.png";
        if(!file_exists('project/files/'.$fileName))
            $filePath  =   BASE_URL. 'project/images/cms/'."noImagePlaceholder.JPG";
        return  '   <ul class="thumbnails">
                    <li class="span">
                    <a href="#" class="thumbnail">
                    <img src="'.$filePath.'" style="width:'.$width.'px; height:'.$height.'px; ">
                    </a>
                    </li>
                    </ul>';
    }
//function to get image file path from fileid
    public static function getImageName($fileId) {

        $dbh 	 = new Db();
        $tableprefix    =   $dbh->tablePrefix;
        $res        = $dbh->execute("SELECT file_path FROM  ".$tableprefix."files where file_id='$fileId' ");
        $filePath   =   $dbh->fetchRow($res);
        return $filePath->file_path;

    }
// function to display pagination
    public static function pagination($total, $perPage  =   5, $url  =   '',$page) {



        $adjacents          =   "2";
        $page               =   ($page == 0 ? 1 : $page);
        $start              =   ($page - 1) * $perPage;
        $prev               =   $page - 1;
        $next               =   $page + 1;
        $lastPage           =   ceil($total/$perPage);
        $lpm1               =   $lastPage - 1;
        $pagination         =   "";
        if($lastPage > 1) {
            $pagination     .=  "<ul class='pagination'>";
            if($page>1)
                $pagination .=  "<li><a href='{$url}page=$prev'>&laquo;</a></li>";
            if ($lastPage < 5 + ($adjacents * 2)) {
                for ($counter = 1;
                $counter <= $lastPage;
                $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
            }
            elseif($lastPage > 5 + ($adjacents * 2)) {
                if($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1;
                    $counter < 4 + ($adjacents * 2);
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li><a class='current'>..</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lastPage'>$lastPage</a></li>";
                }
                elseif($lastPage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li><a href='#'>..</a></li>";
                    for ($counter = $page - $adjacents;
                    $counter <= $page + $adjacents;
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li><a class='current'>..</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lastPage'>$lastPage</a></li>";
                }
                else {
                    $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li><a href='#'>..</a></li>";
                    for ($counter = $lastPage - (2 + ($adjacents * 2));
                    $counter <= $lastPage;
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                }
            }
            if ($page < $counter - 1) {
                $pagination .= "<li><a href='{$url}page=$next'>&raquo;</a></li>";

            }

            $pagination .='<li class="is-padded">Page<input type="text" name="goto" class="input goto is-padded" value="'.$page.'"> of  '.$lastPage.'</li>';
            $pagination .= "</ul>\n";
        }
//echo $pagination;exit;

        return $pagination;


    }
// function to display format
    public static function getTimeFormat($date,$dbFormat,$displayFormat) {

        if($dbFormat=="date") {
            $timeArray=explode("-",$date);

            $time=mktime(0,0,0,$timeArray[1],$timeArray[2],$timeArray[0]);
            return date($displayFormat,$time);
        }
        if($dbFormat=="time") {

            return date($displayFormat,$date);
        }
        if($dbFormat=="timestamp") {
            list($date,$time)=explode(" ", $date);
            list($year,$month,$day)=explode("-",$date);
            list($hour,$minute,$second)=explode(":",$time);

            $time=mktime($hour,$minute,$second,$month,$day,$year);
            return date($displayFormat,$time);
            //return   $newdate=$month."-".$day."-".$year." ".$time;

        }
        if($dbFormat=="datetime") {
            list($date,$time)=explode(" ", $date);
            list($year,$month,$day)=explode("-",$date);
            list($hour,$minute,$second)=explode(":",$time);

            $time=mktime($hour,$minute,$second,$month,$day,$year);
            return date($displayFormat,$time);
            //return   $newdate=$month."-".$day."-".$year." ".$time;

        }

    }
    //function to get image file path from fileid
    public static function getCmsSettings() {

        $dbh 	 = new Db();
        $tableprefix    =   $dbh->tablePrefix;
        $res        = $dbh->execute("SELECT cms_set_name,cms_set_value from  ".$tableprefix."cms_settings  ");
        $settings   =   $dbh->fetchAll($res);
        $cmsSettings = array();
        foreach($settings as $setting) {
            $cmsSettings[$setting->cms_set_name] = $setting->cms_set_value;

        }
        return $cmsSettings;

    }

    //function to get image file path from fileid
    public static function link($params) {
        $href = "www.googlec.om";
        return $href;


    }

    public static function getSettingsTableData($groupLabel,$tablename) {

        $dbh 	 = new Db();


        $res = $dbh->execute("SELECT settingfield,settinglabel ,value,groupLabel,type FROM  $tablename  where groupLabel='$groupLabel'");
        $settings = $dbh->fetchAll($res);

        return $settings;
    }
    public static function getTabContent($settingsArray) {
        echopre($settingsArray);

    }

    public static function getSettingsTabs($tablename) {


        $dbh    =   new db();
        $tableprefix    =   $dbh->tablePrefix;
        $tablename =    str_replace($tableprefix, "", $tablename);
        $settingstabs = $dbh->selectResult(trim($tablename),"distinct groupLabel",$where);
        return $settingstabs;



    }
    public static function getSettingsTabId($groupLabel) {
        //format alias
        $groupId = str_replace("&amp;", "and", $groupLabel);
        $groupId = htmlspecialchars_decode($groupId, ENT_QUOTES);
        $groupId = str_replace("-", " ", $groupId);
        $groupId = preg_replace("/[^a-zA-Z0-9\s]/", "", $groupId);
        $groupId = preg_replace('/[\r\n\s]+/xms', ' ', trim($groupId));
        $groupId = strtolower(str_replace(" ", "-", $groupId));
        return $groupId;
    }
    public static function saveSettings($postArray,$tablename) {

        $dbh    =   new db();

        foreach($postArray as $key=>$val) {
            $updateQuery   =   "UPDATE ".$tablename." SET ";
            $updateQuery   .=  "value='".  addslashes($val)."' where settingfield='$key'";
            $dbh->execute($updateQuery);
        }


    }
    public static function test($id) {

        $array['status'] = "success";
        return  $array;
        $array['status'] = "error";
        $array['message'] = "nothing";
        return $array;

    }
    public static function getAllGroups() {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT group_name,id   FROM cms_groups ");
        $groupsArray = $dbh->fetchAll($res);
        foreach($groupsArray as $group) {
            $groupList = new stdClass();
            $groupList->value= $group->id;
            $groupList->text= $group->group_name;
            $groups[]=$groupList;
        }
        return $groups;
    }
    public static function checkPassword($currentPassword,$userId) {
        $dbh 	 = new Db();
        $res = $dbh->execute("SELECT   password FROM cms_users WHERE  id = $userId");
        $pass = $dbh->fetchOne($res);
        return $pass;
    }
   
}




?>