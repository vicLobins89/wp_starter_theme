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
    $('#filter').submit(function(){
        var $filter = $('#filter');

        $.ajax({
            url : wp_starter_theme_params.ajaxurl,
            data : $filter.serialize(), //form data
            dataType : 'json',
            type : 'POST',
            beforeSend : function(){
                // Change button label
                $filter.find('button').text('Processing...');
            },
            success : function(data){
                // when filter applied set the current page to 1
                wp_starter_theme_params.current_page = 1;
                
                // new query params
                wp_starter_theme_params.posts = data.posts;

                // new max page param
                wp_starter_theme_params.max_page = data.max_page;

                // Change the button label back               
                $filter.find('button').text('Apply filter');

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