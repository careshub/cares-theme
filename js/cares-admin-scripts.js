
function adminSelectProjects () {

	//utility function for getting query vars from current page (we will need to get the post # in the admin edit screens)
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	//autocomplete ajax for returning title/ids of projects
	jQuery("#auto-projects").autocomplete({
	
		source: function(request, response) {

			jQuery("#empty-message").empty(); //if we had vals here, no more!
			
			jQuery.ajax({
				url: cares_ajax.adminAjax,
				type: 'GET',
				data: (
					{
						action: 'find_projects',
						get_projects : true,
						term: request.term
					}
				),
				dataType: "json",
				success: function(data) {
					if (data == null) {
						jQuery("#empty-message").text("No results found");
						//might need to clean up UI if beginning text returned project before 'no results'
					} else {
						jQuery("#empty-message").empty();
					}
					response( jQuery.map( data, function( item ) {
						return {
							label: item.title,
							value: item.id
						}
					}));
				}
			});
		},
		minLength: 4,
        select: function( event, ui ) {
			event.preventDefault();

			if (ui.item.value == -1) {
				return false;
			}
			jQuery('#pending-projects').append('<input type="checkbox" name="projects-checkboxes[]" class="projects-checkboxes" value="' + ui.item.value + '" checked> ' + ui.item.label + '<br>');

			jQuery('.project-result').empty();
			jQuery('button#associate-projects').show();
			
		}
    });
	
	
	jQuery("button#associate-projects").click(function(e){
	
		//what wp post are we editing?
		var postId = 0;
		postID = getParameterByName('post');
		
		//aggregate project IDs from projects that are checked
		var projectIDs = new Array();
		var nextID;
		
		//Are there checked projects?
		if ( jQuery('input[name="projects-checkboxes[]"]:checked').length > 0 ) {
		
			jQuery('input[name="projects-checkboxes[]"]:checked').each( function() {
				nextID = parseInt( jQuery(this).val() );
				projectIDs.push( nextID );
			});

			// 2. Now post metadata to this post (portfolio_item or profile item) - array of project ids?  
			jQuery.ajax({
				type: 'POST',
				data: (
					{ 
						action: 'select_projects',
						project_ids : projectIDs,
						post_id : postID
					}
				),
				dataType: "json",
				url: cares_ajax.adminAjax,
				success: function(data) {
					console.log(data);
					jQuery('.project-result').empty();
				
					//uncheck the project boxes for now
					jQuery('input[name="projects-checkboxes[]"]:checkbox').removeAttr('checked');
					
					for (var key in data) {
					
						var id = data[key]['id'];
						var title = data[key]['title'];
						var error = data[key]['error'];
						if (error) {
							switch (error) {
								case 1:
									jQuery('.project-result').append('<p style="color:red;">&#149; The Project, <b>' + title + '</b>, is already associated with this post.</p>');
									break;
							}
						} else {
							jQuery('.project-result').append('<p>&#149; The Project, ' + title + ', has been associated with this post.</p>');
						}
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
				
		} else {
			jQuery('.project-result').append('<p style="color:red;">No Projects Selected</p>');
		}
		
		jQuery('#pending-reviews').empty();
		jQuery('button#user-create-reviews').hide();


	});

	//Listen for change in remove checkboxes for REMOVE PROJECTS button triggering goodness
	jQuery('input[name="remove-projects-checkboxes[]"]:checkbox').change(function(e){
		jQuery("button#remove-projects").show();

	});
		
	jQuery("button#remove-projects").click(function(e){
	
		//what wp post are we editing?
		var postId = 0;
		postID = getParameterByName('post');
		
		//aggregate project IDs from projects that are checked to be disassociated
		var projectIDs = new Array();
		var nextID;

		//any review boxes selected for deletion?
		if ( jQuery('input[name="remove-projects-checkboxes[]"]:checked').length > 0 ) {
		//which boxes? Array them!
			jQuery('input[name="remove-projects-checkboxes[]"]:checked').each( function() {
				nextID = parseInt( jQuery(this).val() );
				projectIDs.push( nextID );
			});
			
			jQuery.ajax({
				type: 'POST',
				data: (
					{ 
						action: 'remove_projects',
						project_ids : projectIDs,
						post_id : postID
					}
				),
				dataType: "json",
				url: cares_ajax.adminAjax,
				success: function(data) {
					window.location.reload(true);  //so we can know in our hearts that the project is removed from the post


				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		
		} else {
			jQuery('.review-delete').append('<p style="color:red;">Hmm, ya might wanna select a review first...</p>');
		}


	});
		
}
	
//some jQ $ love to make everything work
jQuery(function($){
	adminSelectProjects();
});