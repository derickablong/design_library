/**
 * Design Library Script
 *
 * @author Market Better
 * @package design-library 
 * @version 1.1
 */


/**
 * Avoid conflict with other
 * libraries
 */
var dl = jQuery.noConflict();


/**
 * Design Library Class
 * @type object
 */
DesignLibrary = {
	init: function() {		
		DesignLibrary.actions();
	},

	prepare: function(e, callback) {
		e.stopPropagation();
		e.preventDefault();
		callback();
	},

	request: function( options, callback ) {
		dl.ajax(options).done(function(response){
			callback( JSON.parse(response) );
		});
	},

	deleteLibrary: function( action ) {
		DesignLibrary.getItems(function( dl_id ) {
			DesignLibrary.request({
				url: ajax_object.ajax_url,
				type: 'POST',
				data: {
					action: action,
					dl_id: dl_id
				}
			}, function(response) {				
				DesignLibrary.clean(function() {
					DesignLibrary.message(response);
				});				
			});
		});		
	},

	getItems: function( callback ) {
		var dl_id = [];
		dl('.dl_checkbox:checked').each(function() {
			dl_id.push( dl(this).val() );
		});
		callback( dl_id );
	},

	actions: function() {

		

		/**
		 * Delete Action For Library
		 */
		dl(document).on('click', '.lib-delete-action', function(e) {
			DesignLibrary.prepare(e, function() {	
				if( dl('#bulk-action-selector-top').val() === 'delete'
					|| dl('#bulk-action-selector-bottom').val() === 'delete' ) {

					if( dl('#the-list tr').length <= 0
						|| dl('.dl_checkbox:checked').length <= 0 ) {
						DesignLibrary.message({
							status: 'error',
							message: "There's nothing to delete!"
						});
					} else {
						var del = confirm('Are you sure you want to delete selected items?');
						if( del )
							DesignLibrary.deleteLibrary( 'dl_library_delete' );	
					}	

				} else {
					DesignLibrary.message({
						status: 'error',
						message: "Select what type of action you want first."
					});
				}	
			});
		});


		/**
		 * Delete Action For Category
		 */
		dl(document).on('click', '.cat-delete-action', function(e) {			
			DesignLibrary.prepare(e, function() {	
				if( dl('#bulk-action-selector-top').val() === 'delete'
					|| dl('#bulk-action-selector-bottom').val() === 'delete' ) {

					if( dl('#the-list tr').length <= 0
						|| dl('.dl_checkbox:checked').length <= 0 ) {
						DesignLibrary.message({
							status: 'error',
							message: "There's nothing to delete!"
						});
					} else {
						var del = confirm('Are you sure you want to delete selected items?');
						if( del )
							DesignLibrary.deleteLibrary( 'dl_category_delete' );	
					}	

				} else {
					DesignLibrary.message({
						status: 'error',
						message: "Select what type of action you want first."
					});
				}	
			});
		});


		/**
		 * Delete Action For Filter
		 */
		dl(document).on('click', '.filter-delete-action', function(e) {			
			DesignLibrary.prepare(e, function() {	
				if( dl('#bulk-action-selector-top').val() === 'delete'
					|| dl('#bulk-action-selector-bottom').val() === 'delete' ) {

					if( dl('#the-list tr').length <= 0
						|| dl('.dl_checkbox:checked').length <= 0 ) {
						DesignLibrary.message({
							status: 'error',
							message: "There's nothing to delete!"
						});
					} else {
						var del = confirm('Are you sure you want to delete selected items?');
						if( del )
							DesignLibrary.deleteLibrary( 'dl_filter_delete' );	
					}	

				} else {
					DesignLibrary.message({
						status: 'error',
						message: "Select what type of action you want first."
					});
				}	
			});
		});


		/**
		 * Blur Name
		 * set slug
		 */
		dl(document).on('blur', '.dl-name-tag', function() {
			var slug = (dl(this).val()).toLowerCase();
			dl('.dl-slug-tag').val( slug.replace(/ /g,"_") );
		});


		/**
		 * Filter Parent
		 */
		dl(document).on('click', '.filter-parent input', function() {
			if( dl(this).is(':checked') )
				dl('.filter-child-' + dl(this).val() + ' input').prop('checked', true);
			else
				dl('.filter-child-' + dl(this).val() + ' input').prop('checked', false);
		});


		/**
		 * Filter Child
		 */
		dl(document).on('click', '.filter-child input', function() {			
			var par = dl(this).attr('data-parent');
			dl('.filter-parent-' + par + ' input').prop('checked', false);			
		});


		/**
		 * Filter Image Preview
		 */
		dl(document).on('change', '#filter-image', function() {
			DesignLibrary.preview(
				this,
				'.dl-filter-image-preview'
			);			
		});


		/**
		 * Settings
		 */
		dl(document).on('click', '.dl-grid img', function() {
			
			var _layer = dl(this).attr('data-layer');
			var _type = dl(this).attr('data-file');
			var _img = dl(this).attr('src');

			var _parent = dl(this).parent();
			_parent.children('img').removeClass('selected');
			_parent.children('input').val( _layer + '::' + _type + '::' + _img );	
			dl(this).addClass('selected');

		});



	},



	preview: function( el, target ) {
		var input = el;
	    var url = dl(el).val();
	    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	    if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
	        var reader = new FileReader();

	        reader.onload = function (e) {		        	
	           dl(target).html('<img src="'+ e.target.result +'" alt="">');
	        }
	       reader.readAsDataURL(input.files[0]);
	    } else {
	    	alert('Invalid image.');
	    }  
	},


	clean: function( callback ) {
		dl('.dl_checkbox:checked').each(function() {
			var con = dl(this);
			if( typeof(con) !== 'undefine' ) {
				con.closest('tr').fadeOut(1000, function() {
					con.remove();
				});
			}
		});
		dl('input[type=checkbox]').prop('checked', false);
		callback();
	},

	message: function( resonse ) {
		dl('.dl-response').html(`
			<div class='notice notice-`+ resonse.status +` is-dismissible'>
				<p>`+ resonse.message +`</p>
			</div>
		`);		
	}
}
DesignLibrary.init();