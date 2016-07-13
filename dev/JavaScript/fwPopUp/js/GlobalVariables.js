    /*** Global (app) Private Variables ***/

    // Create a public Object as a property of the jQuery Object. This will have values indicating if the app has been initialized, the version number, and publicly accessible methods.
    var $fwPopup = $.fwPopup = {
        // Used for the deep linking to make sure not to call the same function several times.
        initialized: false,
        version: '2.0.2'
    };

    var $WIN = $(window);
    var isOpen;
    var $overlay;

    // Window size
    var windowHeight = $WIN.height();
    var windowWidth = $WIN.width();


    /*** Public Variables ***/

    // Make the "controller" method public by adding it to the jQuery Object.
    $.fn.fwPopup = fwPopup;