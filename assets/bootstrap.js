// Import Moment and Jalali-Moment first
import moment from 'jalali-moment';
import 'jalali-moment';

// Import Alpine.js and other dependencies
import Alpine from 'alpinejs';
import axios from 'axios';

// Assign global variables
window.Momentj = moment;
window.Alpine = Alpine;
window.Axios = axios;

// Start Alpine.js
Alpine.start();
