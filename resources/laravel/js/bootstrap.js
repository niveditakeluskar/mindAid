//import * as pdfMake from 'pdfmake/build/pdfmake.js';
//import * as pdfFonts from 'pdfmake/build/vfs_fonts';

// window._ = require('lodash');
import InputMask from 'inputmask'; 
import _ from 'lodash';
// window.Popper = require('popper.js').default; // Already included in Dashmix Core JS

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// Already included in Dashmix Core JS
/*
try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}
*/
try {
	
	// window.$ = window.jQuery = require('jquery');	
	window.moment = require('moment');
    window.businessAdd = require('moment-business-days');
    window.Timer = require('moment-timer');
	window.momentDurationFormatSetup = require("moment-duration-format");

    // require('bootstrap');
    require('bootstrap-notify');
	
    require('bootstrap-select-v4');
    // window.InputMask = require('inputmask');
    window.InputMask = InputMask;
$.fn.inputmask = window.InputMask;

    require('inputmask/dist/jquery.inputmask.bundle.js');

    //require( 'jszip' );
    //require( 'pdfmake' );
   // require( 'datatables.net-dt' );
   // require( 'datatables.net-buttons-dt' );
} catch (e) {}
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
// window.axios = require('axios');
import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

/* <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script> */
