/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery'); global.$ = global.jQuery = $;
global.bootstrap = require('./js/bootstrap.bundle.min.js')
import '../node_modules/bootstrap-icons/font/bootstrap-icons.css';
import './styles/app.css';
import './css/bootstrap.min.css'
import './js/bootstrap.bundle.min.js'
import './css/bootstrap.rtl.min.css'
import './css/bootstrap-grid.min.css'
import './css/bootstrap-reboot.rtl.min.css'
import './css/bootstrap-utilities.min.css'

// start the Stimulus application
import './bootstrap';
import './controllers/jquery';
