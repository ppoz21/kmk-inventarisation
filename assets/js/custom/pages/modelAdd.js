import '../../plugins/select2/js/select2.full'

const modelAdd = () => {

    $(() => {
        //Initialize Select2 Elements
        $('.select2').select2()
    })

}

window.initModelAdd = () => {
    modelAdd()
}