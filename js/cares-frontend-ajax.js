
function seeMoreProjects() {
	/* 
	 * Front page-related function (extendable if we want) to allow for infinite scroll
	*/
	
	jQuery("a.more-projects").click(function(e){
		jQuery('.spinny').css('visibility', 'visible');
		//console.log ('more');
		//what page are we on?
		currentPageNum = parseInt( jQuery( "input[name='project_page']" ).val() );
		
		//set up box render size, based on origin page
		if ( jQuery('.portfolio-archive').length > 0 ) {
			boxRenderSize = 2;
			isFrontPage = 'no';
		} else if ( jQuery('.home').length > 0 ) {
			boxRenderSize = 3;
			isFrontPage = 'yes';
		}

		//console.log(currentPageNum);
		
		//set up ajax to insert new posts into front page
		jQuery.ajax({
			type: 'GET',
			data: (
				{ 
					action: 'load_more_projects',
					current_page_num : currentPageNum,
					posts_per_page : 6,
					box_render_size : boxRenderSize,
					is_front_page : isFrontPage
				}
			),
			dataType: "html", //since we're using get_template_part in ajax call for now
			url: cares_ajax.adminAjax,
			success: function(data) {
				if ( data != undefined ){
				//if no more data, change message on front end
					if ( data.length == 0 ) {
						//jQuery('.more-projects').html('No More Projects');
						jQuery('.more-projects').hide();
						jQuery('.spinny').hide();
						
					} else {
						//since get_template_part isn't letting us prepend anything, let's get article class and surround with divs!
						newData = jQuery( data ).filter('.type-portfolio_item');
						morePostData = jQuery( data ).filter('#more_posts');
						morePosts = morePostData.find('#num_more_posts').attr('value');
						
						
						newData.addClass('portfolio_item new_item');
						newWrap = newData.wrap( "<div class='third-block'></div>" ).parent();
						//jQuery('#recent-projects').append('<div id="next-projects"></div>');
						
						//jQuery('.new_item').hide();
						//jQuery('#recent-projects').append(newWrap).fadeIn(2000);
						
						jQuery(newWrap).hide();
						jQuery(newWrap).appendTo("#recent-projects").fadeIn(1600);
						
						//remove new_item designation
						jQuery('.portfolio_item').removeClass('new_item');


						//jQuery('#next-projects').html(newWrap).hide().fadeIn(2000);
						//jQuery('.new_item').hide().fadeIn(2000);
						jQuery( "input[name='project_page']" ).val( currentPageNum + 1 );
						
						if ( morePosts == "0" ) {
							jQuery('.more-projects').hide();
							jQuery('.spinny').hide();
							jQuery('#more_posts #num_more_posts').val('0');
						} else {
							jQuery('#more_posts #num_more_posts').val('1');
							jQuery('.spinny').css('visibility', 'hidden');
						}
					}
				}
				picturefill();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
				jQuery('.spinny').hide();
			}
		});
	
	});


}


//some jQ $ love to make everything work
jQuery(function($){
	seeMoreProjects();
});