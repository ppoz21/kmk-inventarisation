const todoPage = () => {
    $(document).on('change', '.todo-element', function (){
        let element = $(this);
        let $id = element.data('id')
        let $state = element.prop('checked');

        $.ajax({
            type: 'POST',
            url: '/do-zrobienia-ajax',
            data: {
                'id': $id,
                'state': $state
            }
        }).done(function (){
            window.location.reload();
        })

    })
}

window.initTodoPage = () => {
    todoPage()
}
