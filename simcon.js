var serviceUri = 'https://app.samburu.at/simcon/simcon.php';
var appName = '';
var guid = '';

function simconSetup(uri,name=''){
    serviceUri = uri;
    appName = name;
    console.log('New serviceUri: '+uri);
    registerOnload();
}

function getConnections(callback){
    
    $.get(serviceUri, {action: "getConnections", app:appName})
            .done(function (result) {
                console.log("Json: getConnections " + appName+' = '+result);               
                callback(result);
            })
            .fail(function (jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });    
}

function connect(){
    $.get(serviceUri, {action: "connect", app:appName})
            .done(function (result) {
                console.log("Json: connect " + appName+' guid = '+result);               
                guid = result;
            })
            .fail(function (jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });        
 }

function disconnect(){
    $.get(serviceUri, {action: "disconnect", guid:guid})
            .done(function (result) {
                console.log("Json: disconnect " + result);               
            })
            .fail(function (jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });   
     var i=0;
     for(i=1;i<1000;i++){
         console.log('idle a bit to give ajax time before browser destroys '+i);
     }    
}

function registerOnload(){
    console.log('Register unload eventListener');
    window.onbeforeunload = function(){
        disconnect();    
        return null;
    };
}
