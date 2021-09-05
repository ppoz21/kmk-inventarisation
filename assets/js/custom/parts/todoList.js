const todoList = () => {
    $('.show-task').on('click', function (e){
        let element = $(this);
        let description = element.data('description');
        let title = element.data('title')
        if (description.trim() === '')
        {
            description = "Brak opisu dla tego zadania"
        }
        Swal.fire({
            title: title,
            text: description,
            icon: 'warning',
            confirmButtonText: 'Zamknij',
            confirmButtonColor: '#28a745',
        })
    })
}

window.initTodoList = () => {
    todoList();
}
