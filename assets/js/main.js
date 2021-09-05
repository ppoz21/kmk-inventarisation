const $ = require('jquery')
const routes = require('../../public/js/fos_js_routes.json')
const Routing = require('../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js')
const Swal = require('sweetalert2/dist/sweetalert2.all')

window.$ = window.jQuery = $

Routing.setRoutingData(routes)

window.Routing = Routing;

window.Swal = Swal

require('jquery-ui')
require('bootstrap/dist/js/bootstrap')

require('./template/adminlte')

// custom js

require('./custom/custom')

// styles
import '../scss/all.scss'
