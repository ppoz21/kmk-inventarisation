import '../../plugins/select2/js/select2.full'

const stationList = () => {

    function createModal(id = null)
    {
        const getFormURL = Routing.generate('get_form_ajax', {id: id})

        $.ajax({
            type: 'POST',
            url: getFormURL
        }).done(function (response){
            Swal.fire({
                title: 'Dodaj nową stację',
                html: response,
                showCancelButton: true,
                cancelButtonText: 'Anuluj',
                confirmButtonText: 'Dodaj',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    let valid = true;
                    let name = $('#add_station_form_name').val()
                    let description = $('#add_station_form_description').val()
                    let users = $('#add_station_form_users').val()
                    let apiKey = $('#api_key').val()

                    let url = Routing.generate('add_station_ajax')


                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'name': name,
                            'description': description,
                            'users': users,
                            'api_key': apiKey,
                            'id': id
                        }
                    }).done(function (d) {
                        if (d.status === 'success')
                        {
                            Swal.fire(
                                'Sukces',
                                'Poprawnie dodano stację',
                                'success'
                            ).then(function () {
                                window.location.reload();
                            })
                        }
                        else
                        {
                            let errors = d.errors;
                            let text = 'Wystąpiły następujące błędy: '
                            $.each(errors, function (key, val) {
                                text += '<br>' + val
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Błąd',
                                html: text,
                                confirmButtonColor: '#28a745',
                                confirmButtonText: 'OK'
                            })
                        }
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            $('.select2').select2();
        })

    }

    $( () => {
        $('.select2').select2()

        $('#listaStacji').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "paginate": {
                    "first": "Pierwsza",
                    "last": "Ostatnia",
                    "next": "Następna",
                    "previous": "Poprzednia"
                },
                "info": "Wyniki od _START_ do _END_ z _TOTAL_"
            }
        });
    })

    $('#addStation').click(function () {
        createModal(null);
    })

    $('.edit-station').click(function () {
        const id = $(this).data('id')
        createModal(id)
    })
}

window.initStationList = () => {
    stationList()
}
