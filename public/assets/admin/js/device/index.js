var url = window.location.origin;
$('#table-device').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/dispositivo/getDevices",
        "type": "GET"
    },
    "columns": [{
        "data": "picture"
    },{
        "data": "name"
    },
    {
        "data": "status"
        },
    
    {
        "data": "status"
    }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {


        $('td:eq(0)', row).html( '<div class="imagem-round"><img src="'+data['picture']+'" /></div>');
        $('td:eq(3)', row).html( '<a href="javascript:;" data-toggle="modal" onClick="configModalDelete(' + data["id"] + ')" data-target="#modalDelete" class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></a>');


    },
});