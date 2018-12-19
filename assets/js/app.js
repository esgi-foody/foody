const $ = require('jquery');

// CSS
import 'bootstrap/scss/bootstrap.scss';
require('../css/app.css');

// JS
require('bootstrap');

// Images
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);
