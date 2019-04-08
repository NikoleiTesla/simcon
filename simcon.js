var serviceUri = 'http://samburu.at/App/simcon.php';
var appName = '';

function simconSetup(uri,name=''){
    serviceUri = uri;
    appName = name;
    console.log('New serviceUri: '+uri);
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
    
    
    
}

function disconnect(){
    
}

