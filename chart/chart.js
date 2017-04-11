google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawMaterial);

function load(){
    return {"dessimondo":{"omak":1,"merit":4},"dawnchen":{"omak":3,"merit":1},"sgnunnery":{"omak":0,"merit":1},"bwmyouth":{"omak":0,"merit":1},"ljk97":{"omak":1,"merit":0}}
}


/*
function loadFile(filePath) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            myObj = JSON.parse(this.responseText);
            //console.log(myObj);
        }
    };
    xmlhttp.open("GET", "omak.json", true);
    xmlhttp.send();
}
*/

/*
function AJAX_JSON_Req( url )
{
    var AJAX_req = new XMLHttpRequest();
    AJAX_req.open( "GET", url, true );
    AJAX_req.setRequestHeader("Content-type", "application/json");

    AJAX_req.onreadystatechange = function()
    {
        if( AJAX_req.readyState == 4 && AJAX_req.status == 200 )
        {
            var response = JSON.parse( AJAX_req.responseText );
            document.write( response.name );
        }
    }
    AJAX_req.send();
}
AJAX_JSON_Req( 'omak.json' );
*/

var data = load();

console.log(data);

// Total users
var Objcount = 1;
for (var key in data){
    Objcount++;
}

// Initialise array
var table = new Array(Objcount);
for (var i = 0; i < Objcount; i++) {
table[i] = new Array(3);
}

// Declare first row
table[0][0] = 'BWMY';
table[0][1] = 'OMAK';
table[0][2] = 'Merits';

var i = 1;
for (var key in data){
    table[i][0] = key;
    table[i][1] = data[key]["omak"];
    table[i][2] = data[key]["merit"];
    i++;	
}
console.log(table);

function drawMaterial() {
    var data = google.visualization.arrayToDataTable(table);
    /*
    var data = google.visualization.arrayToDataTable([
        ['BWMY', 'OMAK', 'Merits'],
        ['dessimondo', 1, 4],
        ['dawnchen', 3, 1],
        ['sgnunnery', 0, 1],
        ['bwmyouth', 0, 1],
        ['ljk97', 1, 0]
    ]);
    */

    var options = {
        chart: {
        title: 'OMAK Chart'
        },
        hAxis: {
        title: 'Total Counts',
        minValue: 0,
        },
        vAxis: {
        title: 'Members'
        },
        colors: ['#4ba0e5','#4be5c3'],
        backgroundColor: {
            stroke: 'black',
            strokeWidth: 0,
            fill: 'black'
        },
        bars: 'vertical'
    };
    var material = new google.charts.Bar(document.getElementById('chart_div'));
    material.draw(data, options);
}

    