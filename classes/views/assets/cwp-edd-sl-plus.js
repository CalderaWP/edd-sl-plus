(function ($) {
    if( 'object' == typeof  CWPEDDSLPLUS ){
        new CWP_EDD_SL_Plus_List( CWPEDDSLPLUS, $, Vue );
    }
})(jQuery );

function CWP_EDD_SL_Plus_List( config, $, Vue ){

    var target = 'cwp-edd-sl-plus';
    console.log( config );
    var vm;


    $.ajax({
        url: config.route,
        success: function( r ){
            vm = new Vue({
                el: '#' + target,
                template: '#' + target + '-tmpl',
                data: function () {
                    return {
                        downloads: r
                    }
                },
                methods: {
                    downloadButton: function( code ){
                        console.log( config.download_link + '=' + code );
                        document.location.href = config.download_link + '=' + code;
                    }
                }

            });
        },
        error: function(){
            $( '#' + target ).html( config.strings.error );
        }
    });

}