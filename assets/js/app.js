// CSS
import 'bootstrap/scss/bootstrap.scss';
import '../css/app.css';

// JS
import 'bootstrap';
import feather from 'feather-icons';

feather.replace();

// Images
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);
