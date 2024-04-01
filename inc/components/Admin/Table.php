<link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-html5-3.0.1/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-html5-3.0.1/datatables.min.js"></script>
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
                    console.log(response.data);
                    tableData = response.data; // log the field value
                    let columns = [];
                    for (let i in tableData) {
                        columns[i] = [];
                        columns[i].push(tableData[i].naziv_privrednog_drustva);
                        columns[i].push(tableData[i].email);
                        for (let j in tableData[i].finansije_q) {
                            // let finansije_question = tableData[i].finansije_q[j];
                            const answer = Object.values(tableData[i].finansije_a[j]);
                            columns[i].push(answer);
                            // finansije.push(answer);
                        }
                        for (let j in tableData[i].marketing_q) {
                            // let marketing_question = tableData[i].marketing_q[j];
                            const answer = Object.values(tableData[i].marketing_a[j]);
                            columns[i].push(answer);
                        }
                        console.log(columns[i]);
                        for (let j in tableData[i].ljudski_resursi_q) {
                            // let ljudski_resursi_question = tableData[i].ljudski_resursi_q[j];
                            columns[i].push(tableData[i].ljudski_resursi_a[j]);
                        }
                        for (let j in tableData[i].proces_q) {
                            // let proces_question = tableData[i].proces_q[j];
                            const answer = Object.values(tableData[i].proces_a[j]);
                            columns[i].push(answer);
                        }
                        for (let j in tableData[i].strategija_q) {
                            // let strategija_question = tableData[i].strategija_q[j];
                            const answer = Object.values(tableData[i].strategija_a[j]);
                            columns[i].push(answer);
                        }

                        columns[i].push(tableData[i].datum);
                    }

                    console.log(columns);

                    let table = new DataTable('.table', {
                        responsive: true,
                        columns: [{
                                title: 'Naziv privrednog drustva'
                            },
                            {
                                title: 'Email'
                            },
                            {
                                title: 'Šta sprečava veća ulaganja u digitalnu transformaciju u kompaniji? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Da li ste učestvovali u nekim od programa podrške digitalnoj transformaciji privrednih društava? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Koje od navedenih digitalnih kanala koristite za prodaju, komunikaciju i/ili građenje odnosa sa klijentima? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'U kojoj mjeri generalno prikupljate i koristite podatke (o kupcima, dobavljačima i drugim partnerima) u svrhu razvoja i transformacije poslovanja kompanije?  (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Kako se provodi obuka za pripremu zaposlenih za digitalnu transformaciju? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Koje digitalne tehnologije trenutno koristite u kompaniji? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Koje od usluga eUprave koristite? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Na koji način pratite i upravljate inovacijama? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Da li su procesi automatizovani? (uzmite u obzir proizvodnju/pružanje usluga i/ili administraciju i/ili građevinske objekte) (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Da li pratite stanje mašina i kako provodite održavanje? (Možete izabrati jedan odgovor)'
                            },
                            {
                                title: 'Kako dizajnirate proizvode? (Možete izabrati jedan ili više odgovora) '
                            },
                            {
                                title: 'Ukoliko preduzeće koristi 3D projektovanje u razvoju novog proizvoda, da li imate vlastite uređaje za projekotvanje? (Možete izabrati jedan odgovor)'
                            },
                            {
                                title: 'Koju/koje od navedenih aktivnosti ste do sada proveli u polju primjene digitalne transformacije u kompaniji? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Koje aktivnosti planirate provesti ili smatrate da bi bilo potrebno provesti u cilju primjene digitalne transformacije u kompaniji? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Kako primjenjujete vještačku inteligenciju u poslovanju? (Možete izabrati jedan odgovor)'
                            },
                            {
                                title: 'Kako se rukovodstvo odnosi prema inovacijama u kompaniji? (Možete izabrati jedan odgovor)'
                            },
                            {
                                title: 'Na koje područje u kompaniji će digitalna transformacija imati najveći uticaj? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Šta je prioritet kojem je potrebno posvetiti najviše resursa u naredne dvije godine? (Možete izabrati jedan ili više odgovora)'
                            },
                            {
                                title: 'Datum'
                            }
                        ],
                        data: columns
                    });
                } else {
                    console.log(response.data); // error message
                }
            }
        );


    })
</script>