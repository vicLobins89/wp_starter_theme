jQuery(function($){
    /**
     * Load more button
     */
    $('.load-more').click(function(){
        var $button = $(this);

        $.ajax({
            url : wp_starter_theme_params.ajaxurl,
            data : {
                'action' : 'loadmore',
                'query' : wp_starter_theme_params.posts, //getting data from wp_localize_script() function
                'page' : wp_starter_theme_params.current_page
            },
            type : 'POST',
            beforeSend : function(){
                $button.text('Loading...');
            },
            success : function( data ){
                if( data ) {
                    $button.text( 'More posts' );
                    $('.posts-container').append(data); // insert posts
                    wp_starter_theme_params.current_page++;

                    if( wp_starter_theme_params.current_page == wp_starter_theme_params.current_page.max_page ) {
                        $button.hide();
                    }
                } else {
                    $button.hide();
                }
            }
        });

        return false;
    });

    /**
     * Categories filter
     */
    $('input.post-filter').on('change', function() {
        $(this).closest("#filter").submit();
    });

    $('#filter').submit(function(){
        var $filter = $('#filter');

        $.ajax({
            url : wp_starter_theme_params.ajaxurl,
            data : $filter.serialize(), //form data
            dataType : 'json',
            type : 'POST',
            success : function(data){
                // Add URL parameter
                var plain_url = window.location.href.split('?')[0];
                console.log(data.post_type);
                if( data.category !== null ) {
                    window.history.replaceState( {} , '', plain_url+'?query='+data.post_type+'&filter='+data.category );
                } else {
                    window.history.replaceState( {} , '', plain_url+'?query='+data.post_type );
                }

                // when filter applied set the current page to 1
                wp_starter_theme_params.current_page = 1;
                
                // new query params
                wp_starter_theme_params.posts = data.posts;

                // new max page param
                wp_starter_theme_params.max_page = data.max_page;

                // Insert posts
                $('.posts-container').html(data.content);

                // Hide button if no more posts
                if ( data.max_page < 2 ) {
					$('.load-more').hide();
				} else {
					$('.load-more').show();
				}
            }
        });

        return false;
    });
});