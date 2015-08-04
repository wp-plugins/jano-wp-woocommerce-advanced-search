(function($){
     $.fn.btsearch_autocomplete = function(options){
		
        var base = this;
		
 		var baseClass = base.attr('class');
		
		var cachedResponse = new Array();
		
		var isResults = false;
		
		defaultOptions = {
			formID : '',
			minChar : 1,
			resultContainerWidth : false,
			loaderImg : false,
			loader : false,
			perPage : 10,
			onSearchStart : function() { },
			onSearchComplete : function() { },
			view_all : false,
			siteurl : false,
		
   		 };
        
        base.init = function(){
			
            base.requests = Array();
			
			base.runningAjax = true;
			
            base.btoptions = $.extend({},defaultOptions, options);
          
		    base.container = $('#'+base.btoptions.formID+' .bt-search-result-container');
			
            base.on('onKeydown', function (e) { base.onKeydown(e); });

            base.on('keyup', function (e) { base.onKeyup(e); });

            base.on('blur', function (e) { base.onBlur(e); });

            base.on('focus', function (e) { base.onFocus(e); });
			
			$(document).on('click', function (e) { base.hideResults(e); });
			
			$('#'+base.btoptions.formID+' .btsearch-cross').on('click', function (e){ 
													   
				base.val(''); 
				
				base.hideCross();
				
			});
			
        };
		
        base.onKeydown = function(e) {
			
		}
		
		base.onKeyup = function(e) {
			
			var basevalue = base.val();
			
			if( basevalue.length == 0 ) {
				
				base.hideCross();
					
			}
			
			if( base.btoptions.minChar <= basevalue.length) {
				
				if(isResults==false) {
					
					if(base.btoptions.loaderImg == false || base.btoptions.loaderImg == '') { 
						
						$('#'+base.btoptions.formID+' .btsearch-loader').addClass('btsearch-show').addClass(base.btoptions.loader);
					
					} else {
						
						$('#'+base.btoptions.formID+' .btsearch-loader').addClass('btsearch-show').css('backgroundImage','url('+base.btoptions.loaderImg+')');
						
					}
					
				}else {
					
					if(base.btoptions.loaderImg == false || base.btoptions.loaderImg == '') {
						
						$('#'+base.btoptions.formID+' .btsearch-loader').addClass('btsearch-show').addClass(base.btoptions.loader);
					
					} else {
						
						$('#'+base.btoptions.formID+' .btsearch-loader').addClass('btsearch-show').css('backgroundImage','url('+base.btoptions.loaderImg+')');
						
					}
					
					base.container.show();
					
				}
				
				for(var i = 0; i < base.requests.length; i++)
    				base.requests[i].abort();
				var data = {
					'action': 'btsearch_action',
					'keyword' : base.val(),
					'postType' : base.btoptions.postType,
					'perPage': base.btoptions.perPage,
					
				};
				if(isResults) { 
				
					if(typeof cachedResponse[basevalue] != 'undefined') {
						
						base.Response = cachedResponse[basevalue];
						
						base.processResponse();
						
						return;
					}
					
				}
				
				base.hideCross();
				
				base.requests.push(
								   
					$.post( base.btoptions.siteurl+"/wp-admin/admin-ajax.php", data, function(response) {
																   
							isResults = true;							
							
							base.Response = response;
							
							base.runningAjax = true;
							
							cachedResponse[basevalue] = response;
							
							base.processResponse();
							
					})
				 );
			
			}
			
		}
		
		base.onBlur = function(e) {
				
			  if(base.runningAjax == false) { 
				
				if(base.val() != '') {
				
					base.showCross();
				
				}
				
			 } 
			  
			 if (base.btoptions.onSearchComplete.call(base.btoptions) === false) {

                    return;

               }
		}
		
		base.onFocus = function(e) {
			
			var basevalue = base.val();
			
			if(typeof cachedResponse[basevalue] != 'undefined') { 
						
				base.Response = cachedResponse[basevalue];
						
				base.processResponse();
						
				return;
			}
			
			if (base.btoptions.onSearchStart.call(base.btoptions) === false) {

                    return;

            }
		}
		base.processResponse = function() {
			var response = $.parseJSON(base.Response);
			var html = '<ul>';
			 $.each(response, function (i, suggestion) { 
										
				if(suggestion.ID == 0) {
					 html += '<li class="btsearch_result" data-index="' + i + '"> '+suggestion.value+'</li>';
				}else {
					html += '<li class="btsearch_result" data-index="' + suggestion.ID + '">';
					html += '<a href="'+suggestion.permalink+'" >';
					html += suggestion.post_title;
					html += '</a></li>';
				}

            });
			 html += '<li class="btsearch_result view_all">';
			 html += '<a href="'+ base.btoptions.siteurl+'/?s='+base.val()+'&post_type='+base.btoptions.postType+'" >';
			 html += base.btoptions.view_all+'</a></li>';
			 html += '</ul>';
			 $('#'+base.btoptions.formID+' .btsearch-loader').removeClass('btsearch-show');
			 base.container.html(html);
			 if(base.btoptions.resultContainerWidth !=false) {
			 	base.container.css({'width' : base.btoptions.resultContainerWidth});
			 }
			 base.container.show();
			 
			 base.runningAjax = false;
			 
			 base.showCross();
		}
		
		base.hideResults = function(event) { 
			 if ( !$(event.target).closest( "#"+base.btoptions.formID+" .bt-search-result-container" ).length &&
					!$(event.target).closest( "#"+base.btoptions.formID+" .bt-search-field" ).length) {
       				 base.container.hide();
   			 }
		}
		
		base.hideCross = function() {
			
			 $('#'+base.btoptions.formID+' .btsearch-cross').removeClass('btsearch-cross-show');
			
		}
		
		base.showCross = function() { 
		
			if( base.val() != '' ){
			 	
				$('#'+base.btoptions.formID+' .btsearch-cross').addClass('btsearch-cross-show');
		    
			}
			
		}
		
        base.init();
		
		
    };
    
    
    
   
    
})(jQuery);