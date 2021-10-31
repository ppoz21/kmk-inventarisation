import '../../plugins/select2/js/select2.full'
require('inputmask/dist/jquery.inputmask.min');

const adminAdd = () => {

    $(() => {
        //Initialize Select2 Elements
        $('.select2').select2()

        $('.phoneNo').inputmask('(+48) 999-999-999')
    })

}

window.initAdminAdd = () => {
    adminAdd()
}