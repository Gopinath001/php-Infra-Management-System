let global_limt = 0;
var table_update_del_device_pc = 0;
var table_update_del_other_device = 0;
let sql = "select * from ";
const loading_img = '<div style="margin-left:47%;" ><img src="loading.gif" border-radius="50%"</div>';

$(document).ready(function() {

    //for device category
    $('#what').click(function() {
    	var check_selection = $("#what option:selected").val();
    	if (check_selection == "device" && global_limt == 0) {
    		global_limt++;
    		$('#category_selector').append('<div><select name="type" id="type"><option value="" disabled selected>Type?</option><option value="device_pc">Device PC</option><option value="device_other">Other Devices</option></select></div>');
    	} else if (check_selection == "user") {
    		console.log('user click');
    		$("#type").remove();
    		global_limt = 0;
    	}

    });

    // $("#update-submit").on('click',function(){
    // 	$("#update-form")[0].reset();

    // 	$("#update-form").unbind('submit').bind('submit',function(){
    // 		var form = $(this);

    // 	});
    // });

});

function createTable(arr) {
	$('#table_here').DataTable({
		"ajax": {
			url: "worker.php",
			method: "POST",
			data: { get_all_device_pc: 1 },
			"dataSrc": ""
		},
		"columns": [
		{ "data": "id",className:"dt-body-center"  },
		{ "data": "device_id", className:"dt-body-center" },
		{ "data": "brand" },
		{ "data": "device_serial", className:"dt-body-center" },
		{ "data": "cpu" },
		{ "data": "ram" },
		{ "data": "charger_serial_number", className:"dt-body-center" },
		{ "data": "hard_disk_capacity", className:"dt-body-center" },
		{ "data": "model" },
		{ "data": "os" },
		{ "data": "used_by" }

		],
		"columnDefs": [
		{
			"targets": [ 0 ],
			"visible": false,
			"searchable": false
		}
		]
	});
}

function dashboardDisplay() {
	var temp = $("#type option:selected").val();
	if (temp == "device_pc") {
		sql = sql + "device_pc ";
        //ajaxcall to retrieve from databse
        $.ajax({
        	url: "worker.php",
        	method: "POST",
        	dataType: "json",
        	data: { get_all_columns: 1, },
        	success: function(data) {
        		var b = [];
        		for (a in data) {
        			if (!data.length - 1 == a) {
        				b.push('{"data":"' + data[a]["Field"] + '"},');
        			} else {
        				b.push('{"data":"' + data[a]["Field"] + '"}');
        			}
        		}
                // createTable(arr);
                //call the function to get all data ffrom databse
                console.log("inside the get all data");
                $('.table_container').show();
                createTable(b);

            }
        });

    }
}

function getAttributes_to_add(){
	var temp = $("#addDevice option:selected").val();
	if(temp == "pc"){
		$("#fields_container").empty();
		$("#fields_container").append('<div class="base"><div><form id="survey-form" method="post" action="worker.php"> <label id="deviceid-label">Device-id:</label> <input name="device_id" placeholder="Enter device id(unique)" autofocus required> <br> <br> <label id="brand-label">Brand:</label> <input type="text" name="brand" placeholder="Enter Brand" required> <br> <br> <label id="serial-label">Device Serial:</label> <input type="text" name="device_serial" placeholder="Device Serial(Unique)" required> <br> <br> <label id="cpu-label">Cpu:</label> <input type="text" name="cpu" placeholder="CPU" required> <br> <br> <label id="ram-label">Ram:</label> <input type="text" name="ram" placeholder="ram" required> <br> <br> <label id="charger_serial-label">Charger Serial:</label> <input type="text" name="charger_serial" placeholder="Charger Serial(Unique)" > <br> <br> <label id="HD-label">Hard Disk Capacity:</label> <input type="text" name="harddisk_capacity" placeholder="Hard Disk Capacity" required> <br> <br> <label id="model-label">Model:</label> <input type="text" name="model" placeholder="pc/lap model" required> <br> <br> <label id="os-label">OS:</label> <input type="text" name="os" placeholder="os installed" required> <br> <br> <button id="submit" type="submit" name="btn_submit_pc" value="submit" class="btn-submit">Submit</button></form></div></div>');	
	}
	else if(temp =="device"){
		$("#fields_container").empty();
		$("#fields_container").append('<div class="base"><div><form id="survey-form" action="worker.php" method="post"> <label id="deviceid-label">Device-id:</label> <input name="device_id" placeholder="Enter device id(unique)" autofocus required> <br> <br> <label id="Name-label">Name:</label> <input type="text" name="name" placeholder="Name" required> <br> <br> <label id="brand-label">Brand:</label> <input type="text" name="brand" placeholder="Enter Brand" required> <br> <br> <label id="serial-label">Device Serial:</label> <input type="text" name="device_serial" placeholder="Device Serial(Unique)" required> <br> <br> <label id="Otherinfo-label">Other info:</label> <input type="text" name="other_info" placeholder="Extra Info" required> <br> <br> <button id="submit" name="btn_submit_device" class="btn-submit">Submit</button></form></div></div>');
	}
}
//table.destroy();

function updateitemsDisplay(){
	var temp = $("#device_update_select option:selected").val();
	if (temp == "device_pc"){
		if(table_update_del_device_pc){
			table_update_del_device_pc.destroy();
			table_update_del_device_pc=0;
		}else if(table_update_del_other_device){
			table_update_del_other_device.destroy();
			table_update_del_other_device=0;
		}
		$("#table_here").empty().append('<thead><tr><th>ID</th><th>Device ID</th><th>Brand</th><th>Device Serial</th><th>CPU</th><th>RAM</th><th>Charger Serial</th><th>Model</th><th>Os</th><th>Edit/Delete</th></tr></thead>');	
		$("#table_here").dataTable().fnDestroy();
		$('.table_container').show();
		table_update_del_device_pc = $('#table_here').DataTable({
			"ajax":{
				url:"worker.php",
				method:"post",
				data:{getdata_updatepage:1}
			},order:[],
			columnDefs: [
			{ 
				orderable: false, 
				targets: -1 
			},
			{	targets:-1,
				className:"dt-body-center"
			},
			{
				"targets": [ 0 ],
				"visible": false,
				"searchable": false
			}
			]
		});
	}
	else if (temp=="other_device") {
		if(table_update_del_device_pc){
			table_update_del_device_pc.destroy();
			table_update_del_device_pc=0;
		}else if(table_update_del_other_device){
			table_update_del_other_device.destroy();
			table_update_del_other_device=0;
		}

		$("#table_here").empty().append('<thead><tr><th>ID</th><th>Device ID</th><th>Name</th><th>Brand</th><th>Serial</th><th>Other Info</th><th>Edit/Delete</th></tr></thead>');
		$("#table_here").dataTable().fnDestroy();
		$('.table_container').show();
		table_update_del_other_device = $('#table_here').DataTable({
			"ajax":{
				url:"worker.php",
				method:"post",
				data:{getdata_updatepage_od:1}
			},order:[],
			columnDefs: [
			{ 
				orderable: false, 
				targets: -1 
			},
			{	targets:'_all',
			className:"dt-body-center"
		},
		{
			"targets": [ 0 ],
			"visible": false,
			"searchable": false
		}
		]
	});
	}
}


function updateToserver(){
	var formdata = $("#update-form");
	//debugger;
	var submitdata = 'updaterow=1&'
	submitdata += formdata.serialize();
	console.log(submitdata);
	$("#dashboard-add").empty().append(loading_img);
	$.ajax({
		url:"worker.php",
		method:"post",
		data:submitdata,
		dataType:"json",
		success:function(data){
			if(data==1){
				table_update_del_device_pc.ajax.reload(null,false);
				$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Updated Successfully</span>');
			}
			else{
				$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Error Debug :(</span>');
			}
		}
	});
	return false;
}

function getFieldsForUpdate(id){
	$("#dashboard-add").empty().append(loading_img);
	$.ajax({
		url:"worker.php",
		method:"post",
		data:{"updateid":id},
		dataType:"json",
		success:function(data){
			console.log(data);
			$("#dashboard-add").empty().append('<br><br><div><form id="update-form"><div class="container_update-values"><div><label id="deviceid-label">Device-id:</label> <input name="device_id" id="device-id" value="'+data.device_id+'" required></div><div><label id="brand-label">Brand:</label> <input type="text" name="brand" id="device-brand" value="'+data.brand+'" required></div><div><label id="serial-label">Device-Serial:</label> <input type="text" name="device_serial" id="device-serial" value="'+data.device_serial+'" required></div><div><label id="cpu-label">Cpu:</label> <input type="text" name="cpu" id="device-cpu" value="'+data.cpu+'" required></div><div><label id="ram-label">Ram:</label> <input type="text" name="ram" id="device-ram" value="'+data.ram+'" required></div><div><label id="charger_serial-label">Charger Serial:</label> <input type="text" name="charger_serial" id="charger-serial" value="'+data.charger_serial_number+'" ></div><div><label id="HD-label">Hard Disk:</label> <input type="text" name="harddisk_capacity" id="device-hd" value="'+data.hard_disk_capacity+'" required></div><div><label id="model-label">Model:</label> <input type="text" name="model" id="device-model" value="'+data.model+'" required></div><div><label id="os-label">OS:</label> <input type="text" name="os" id="device-os" value="'+data.os+'" required></div><div><input type="hidden" value="'+data.id+'" name="id_seq" /></div></div> <button id="update-submit" onclick="return updateToserver()" class="update-submit">update</button></form></div>');
		}
	});

}
function deleteFieldsForUpdate(id){
	if (confirm("Are you sure to delete!!")) {
		$.ajax({
			url:"worker.php",
			method:"post",
			data:{"deleteid":id},
			dataType:"json",
			success:function(data){
				if(data==1){
					table_update_del_device_pc.ajax.reload(null,false);
					$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Deleted Successfully!!!</span>');
				}else{	
					console.log("error debug :( ");
				}
			}
		});

	} else {
		console.log("cancelled!");
	}
}

//submit onclick...
function updateToserver_od(){
	var formdata = $("#update-form");
	//debugger;
	var submitdata = 'updaterow_=1&'
	submitdata += formdata.serialize();
	console.log(submitdata);
	$("#dashboard-add").empty().append(loading_img);
	$.ajax({
		url:"worker.php",
		method:"post",
		data:submitdata,
		dataType:"json",
		success:function(data){
			if(data==1){
				table_update_del_other_device.ajax.reload(null,false);
				$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Updated Successfully</span>');
			}
			else{
				$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Error Debug :( <br> Most probably you didnt enter a unique id</span>');
			}
		}
	});
	return false;
}




function getFieldsForUpdate_od(id){
	$("#dashboard-add").empty().append(loading_img);
	$.ajax({
		url:"worker.php",
		method:"post",
		data:{"updateid_od":id},
		dataType:"json",
		success:function(data){
			console.log(data);
			$("#dashboard-add").empty().append('<br> <br><div><form id="update-form"><div class="container_update-values"><div> <label id="deviceid-label">Device-id:</label> <input name="device_id" id="device-id" value="'+data.device_id+'" required></div><div> <label id="name-label">Name:</label> <input type="text" name="name" id="device-name" value="'+data.name+'" required></div><div> <label id="brand-label">Brand:</label> <input type="text" name="brand" id="device-brand" value="'+data.brand+'" required></div><div> <label id="serial-label">Device-Serial:</label> <input type="text" name="device_serial" id="device-serial" value="'+data.serial+'" required></div><div> <label id="other_info-label">Other Info:</label> <input type="text" name="other_info" id="device-other_info" value="'+data.other_info+'" required></div><div> <input type="hidden" value="'+data.id+'" name="id_seq" /></div></div> <button id="update-submit" onclick="return updateToserver_od()" class="update-submit">update</button></form></div>');
		}
	});

}
function deleteFieldsForUpdate_od(id){
	if (confirm("Are you sure to delete!!")) {

		$.ajax({
			url:"worker.php",
			method:"post",
			data:{"deleteid_od":id},
			dataType:"json",
			success:function(data){
				if(data==1){
					table_update_del_other_device.ajax.reload(null,false);
					$("#dashboard-add").empty().append('<span style="background-color: yellow; color: black;">Deleted Successfully!!!</span>');
				}else{	
					console.log("error debug :(");
				}
			}
		});

	} else {
		console.log("cancelled!");
	}
}