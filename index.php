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
            echo "0";
        echo $row[0];
        var_dump($row);
     }
     
     if($action == 'connect'){
       $guid = com_create_guid();
       $dt = new DateTime();
       
       $statement = $db->prepare('insert into simcon(domain,appname,conGUID,insertDT) values(:domain, :app, :guid, :insertDT)');
       $statement->bindValue(':domain', $domain);
       $statement->bindValue(':app', $app);
       $statement->bindValue(':guid', $guid);
       $statement->bindValue(':insertDT', $dt->format('c'));
       $statement->execute();
       echo $guid;

     }
     
     

function jsonOutput($object){
  $result = json_encode($object);
  DebugMessage("Json encode: ".$result);
  header('Content-type: application/json');
  echo $result;
}     
     
     

?>
