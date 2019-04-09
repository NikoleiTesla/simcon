<?php
    /* siusco = Simulatnious Connection Count in a single php file 
     * to display the current simulationous connections or event handling such as basic load balancing 
     * features site or app count, automatic relase if not closed via event
     * uses SqlLite and  shall be triggered via jquery see example.html
     */

     $releaseTime = 3600; //in seconds (one day)
     /*var $db SQLite*/
     $db = new SQLite3("simcondb");
     $tableStructure = "CREATE TABLE IF NOT EXISTS simcon( ".
                       "id INTEGER PRIMARY KEY AUTOINCREMENT, ". 
                       "domain TEXT NOT NULL DEFAULT '', ".
                       "appname TEXT NOT NULL DEFAULT '', ".
                       "conGUID TEXT NOT NULL DEFAULT '', ".
                       "insertDT DATETIME NOT NULL )";
     $db->busyTimeout(2000);
     $db->exec('PRAGMA journal_mode = wal;');
     $db->exec($tableStructure);
     
     $action = '';
     $app = '';
     $guid = '';
     $domain = '';
     
     if(array_key_exists('action',$_REQUEST))
        $action = strip_tags($_REQUEST['action']);
     
     if(array_key_exists('app',$_REQUEST))
        $app = strip_tags($_REQUEST['app']);
     
     if(array_key_exists('domain',$_REQUEST))
        $app = strip_tags($_REQUEST['domain']);

     if(array_key_exists('guid',$_REQUEST))
        $guid = strip_tags($_REQUEST['guid']);
     
     if($action == 'getConnections'){            
        $select = "select count(id) from simcon where domain='".$domain."' and appname='".$app."' group by appname";
        //$select = "select * from simcon";
        $result = $db->query($select);
        $row = $result->fetchArray();
        if(!$row)
            jsonOutput("0");
        jsonOutput($row[0]);
     }
     
     if($action == 'connect'){
       $guid = createGuid();
       $dt = new DateTime();
       
       $statement = $db->prepare('insert into simcon(domain,appname,conGUID,insertDT) values(:domain, :app, :guid, :insertDT)');
       $statement->bindValue(':domain', $domain);
       $statement->bindValue(':app', $app);
       $statement->bindValue(':guid', $guid);
       $statement->bindValue(':insertDT', $dt->format('c'));
       $statement->execute();
       jsonOutput($guid);
     }
     
     if($action =='disconnect'){
       if($guid ==''){
           return '';
       }
       $statement = $db->prepare('delete from simcon where conGUID = :guid');  
       $statement->bindValue(':guid', $guid);
       $statement->execute();       
       jsonOutput("Disconnected: ".$guid);
     }
     
     if($action =='deleteAll'){
       $statement = $db->prepare('delete from simcon'); 
       $statement->execute();
       jsonOutput("all Deleted: ");
     }    

unset($db);

function jsonOutput($object){
  header("Access-Control-Allow-Origin: *");
  echo $object;
}    

function createGuid(){
    //if (function_exists('com_create_guid')){
    //    return com_create_guid();
    //}else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = ''
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);               
        return $uuid;
    //}
}

?>
