$(document).ready(function() {

    $('body').vegas({
        overlay: true,
        transition: 'zoomOut',
        transitionDuration: 4000,
        delay: 10000,
        animation: 'kenburnsLeft',
        animationDuration: 20000,
        slides: [
            {src: 'custompages/assets/img/slider01.jpg'},
            {src: 'custompages/assets/img/slider02.jpg'},
            {src: 'custompages/assets/img/slider03.jpg'}

        ]
    });
});