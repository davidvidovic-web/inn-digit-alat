<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<!-- <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/r-3.0.1/datatables.min.css" rel="stylesheet"> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/r-3.0.1/datatables.min.js"></script>
<table class="table table-striped" style="width:100%"></table>
</div>
<script>
    jQuery(document).ready(function($) {
        let ajaxUrl = '<?php echo admin_url('admin-ajax.php') ?>';
        let tableData;

        $.post(
            ajaxUrl, {
                action: "get_quiz_data_db"
            },
            function(response) {
                if (response.success) {
                    $('.table').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel',
                            'print'
                        ],
                        data: response.data,
                        columns: [{
                                data: 'naziv_privrednog_drustva',
                                title: 'Naziv privrednog drustva'
                            },
                            {
                                data: 'email',
                                title: 'Email'
                            },
                            {
                                data: 'ocjena',
                                title: 'Nivo digitalizacije'
                            },
                            {
                                data: 'datum',
                                title: 'Datum'
                            },
                        ],
                        select: true
                    });

                    console.log(response.data);
                } else {
                    console.log(response.data); // error message
                }
            }
        );






    })
</script>