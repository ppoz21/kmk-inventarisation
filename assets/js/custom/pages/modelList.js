const modelList = () => {


    $( () => {
        $('.datatable').DataTable({
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
                "info": "Wyniki od _START_ do _END_ z _TOTAL_",
                "emptyTable": "Nie znaleziono danych"
            }
        });
    })

}

window.initModelList = () => {
    modelList()
}
