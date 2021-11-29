$(document).ready(function() {
    document.getElementById('mtech_details_hidden').style.display = 'none';
    $("#fetch_id_btn").on('click',function() {
        add_data_to_table();
    });
});

function add_data_to_table()
{
	var fileInputXML = document.getElementById('fileInputXML');
	// parse as XML
    var file = e.target.files[0];
    var xmlParser = new SimpleExcel.Parser.XML();
    xmlParser.loadFile(file, function () {
        
        // draw HTML table based on sheet data
        var sheet = xmlParser.getSheet();
        var table = document.getElementById('result');
        table.innerHTML = "";
        sheet.forEach(function (el, i) {                    
            var row = document.createElement('tr');
            el.forEach(function (el, i) {
                var cell = document.createElement('td');
                cell.innerHTML = el.value;
                row.appendChild(cell);
            });
            table.appendChild(row);
        });
        // print to console just for quick testing
        console.log(xmlParser.getSheet(1));
        console.log(xmlParser.getSheet(1).getRow(1));
        console.log(xmlParser.getSheet(1).getColumn(2));
        console.log(xmlParser.getSheet(1).getCell(3, 1));
        console.log(xmlParser.getSheet(1).getCell(2, 3).value); 
    });
    document.getElementById('mtech_details_hidden').style.display = 'block';
}