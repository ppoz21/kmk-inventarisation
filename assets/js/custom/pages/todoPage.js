const todoPage = () => {
    $(document).on('change', '.todo-element', function (){
        let element = $(this);
        let $id = element.data('id')
        let $state = element.prop('checked');

        $.ajax({
            type: 'POST',
            url: Routing.generate('todo_list_ajax'),
            data: {
                id: $id,
                state: $state
            }
        }).done(function (){
            window.location.reload();
        })

    })

    $(document).on('click', '.add-task', async () => {
        const steps = ['1', '2', '3']
        const swalQueue = Swal.mixin({
            progressSteps: steps,
            confirmButtonText: 'Dalej <i class="fas fa-arrow-right"></i>',
            allowOutsideClick: false,
            title: 'Dodaj nowe zadanie',
            confirmButtonColor: '#dc3545'
        })

        const a1 = await swalQueue.fire({
            inputLabel: 'Tytuł zadania',
            currentProgressStep: 0,
            input: 'text',
            inputValidator: (value) =>
            {
                if (!value) {
                    return 'Tytuł zadania nie może być pusty!'
                }
            }
        })
        const a2 = await swalQueue.fire({
            inputLabel: 'Treść zadania (opcjonalne)',
            currentProgressStep: 1,
            input: 'textarea',
            inputPlaceholder: 'Pole opcjonalne'
        })
        const a3 = await swalQueue.fire({
            currentProgressStep: 2,
            confirmButtonText: 'Dodaj zadanie',
            html: `<label for="swal2-input" class="swal2-input-label">Termin wykonania zadania (opcjonalne)</label><input class="swal2-input" id="swal2-input" placeholder="" type="date" style="display: flex;">`,
            preConfirm: () => {
                return document.getElementById('swal2-input').value
            }
        })

        const url = Routing.generate('todo_list_add');


        $.ajax({
            type: 'post',
            url: url,
            data: {
                name: a1.value,
                description: a2.value,
                deadline: a3.value
            }
        }).done( (resp) => {
            if (resp.ok)
            {
                Swal.fire({
                    icon: 'success',
                    title: 'Poprawnie dodano zadanie',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Zamknij',
                }).then( (d) => {
                    location.reload()
                })
            }
            else if (resp.error)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Błąd',
                    text: resp.error,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Zamknij'
                }).then( (d) => {
                    location.reload()
                })
            }
        })

    })

    $(document).on('click', '.add-task-admin', async () => {
        const url = Routing.generate('todo_list_add_admin')
        Swal.fire({
            icon: 'warning',
            title: 'Dodaj zlecenie',
            html: '<div></div>',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Dodaj zadanie',
            didOpen() {
                Swal.showLoading()
                fetch(url)
                    .then(resp => resp.json())
                    .then(data => {
                        const holder = Swal.getHtmlContainer().querySelector('div');
                        Swal.hideLoading()
                        holder.innerHTML = data.html
                        $('.select2').select2()
                    })
            },
            preConfirm: async () => {
                const form = $('#admin-task-form')
                const holder = Swal.getHtmlContainer().querySelector('div');
                Swal.showLoading()
                holder.innerHTML = ''

                const test = await $.ajax({
                    type: 'post',
                    url: url,
                    data: form.serialize()
                })

                if (test.ok)
                {
                    return test.ok
                }
                else if (test.error)
                {
                    return test.error
                }
                else
                {
                    holder.innerHTML = test.html
                    Swal.hideLoading()
                    $('.select2').select2()
                    return false
                }

            }
        }).then( (d) => {
            location.reload()
        })
    })

    $(document).on('click', '.hide-task', async function () {
        const id = $(this).data('id')

        Swal.fire({
            icon: 'warning',
            title: 'Uwaga!',
            text: 'Czy na pewno chcesz usunąć zadanie?',
            confirmButtonText: 'Tak, usuń',
            cancelButtonText: 'Anuluj',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#aaaaaa',
            allowOutsideClick: false
        }).then( async r => {
            if (r.isConfirmed)
            {
                const resp = await $.ajax({
                    type: 'post',
                    url: Routing.generate('todo_list_hide'),
                    data: {
                        id: id
                    }
                })

                if (resp.ok)
                {
                    Swal.fire({
                        icon: 'success',
                        title: 'Usunięto wpis'
                    }).then( r => {
                        location.reload()
                    })
                }
                else if (resp.error)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Wystąpił błąd serwera!'
                    })
                }

            }
        })
    })
}

window.initTodoPage = () => {
    todoPage()
}
