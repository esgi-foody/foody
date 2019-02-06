// CSS
import 'bootstrap/scss/bootstrap.scss';
import '../css/app.css';

// JS
import 'jquery';
import 'bootstrap';
import feather from 'feather-icons';

feather.replace();

// Images
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|svg)$/);
imagesContext.keys().forEach(imagesContext);

