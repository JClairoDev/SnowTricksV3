/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/style.scss';

// start the Stimulus application
import './bootstrap';


import 'jquery';
import 'slick-carousel';
const $= require('jquery');

$(document).ready(function(){
    $('.your-class').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 5
});
});

