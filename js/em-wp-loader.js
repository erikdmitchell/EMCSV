(function($) {

	$.fn.EMWPLoader=function(options) {
		var settings=$.extend({
			ajax_action : 'em_wp_loader_run',
			ids : {},
			count : 0,
			percent : 0,
			success : 0,
			failure : 0,
			runContinue : true,
			extra_fields : {},
			$loaderWrap : $('#jq-loader-wrap'),
			$fill : $('#jq-loader-wrap .fill'),
			$percent : $('#jq-loader-percent'),
			$data : $('#jq-loader-data'),
			$final : $('.jq-update-final'),
			$button : $('.jq-loader-btn'),
			$counter : $('.jq-counter-details'),
			$counterCurrent : $('.jq-counter-details .current'),
			$counterTotal : $('.jq-counter-details .total'),
		}, options);

		settings.$wrap=$(this);
		settings.total=settings.ids.length;

		// converts ids to array if object //
		if (typeof settings.ids=='object') {
			settings.ids = Object.keys(settings.ids).map(function (key) {return settings.ids[key]});
			settings.total=settings.ids.length;
		}

		var init=function() {
			setupButton();

			settings.$counterTotal.text(settings.total);
			settings.$counterCurrent.text(settings.count);
		}

		var setupButton=function() {
			settings.$button.live('click', function(e) {
				e.preventDefault();

				settings.$loaderWrap.show();
				settings.$counter.show();
				settings.$button.hide();

				runScript();
			});
		};

		var runScript=function() {
			// make sure we have stuff to process //
			if (settings.total==0) {
				settings.$data.append('There is no data to migrate.<br />');
				finishScript();
				return false;
			} else {
				runAJAX(settings.ids.shift());
			}
		};

		var finishScript=function() {
			var msg='There were '+settings.failure+' failures.';

			// force 100% just in case //
			settings.$fill.css({
				width: '100%'
			})

			settings.$percent.html('100%');

			settings.$final.append(msg);
		};

		var runAJAX=function(id) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: settings.ajax_action,
					id: id,
					extra_fields: settings.extra_fields
				},
				success: function( response ) {
					if ( response !== Object( response )) {
						response=JSON.parse(response);
					}

					if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
						response = new Object;
						response.success = false;
						response.error = 'The request was abnormally terminated. Contact admin or script creator.';
					}

					if ( response.success ) {
						updateProgress(id,true,response);
					} else {
						updateProgress(id,false,response);
					}

					if (settings.ids.length && settings.runContinue) {
						runAJAX(settings.ids.shift());
					} else {
						finishScript();
					}
				},
				error: function( response ) {
					response=JSON.parse(response);
					updateProgress(id,false,response);

					if (ids.length && settings.runContinue) {
						runAJAX(settings.ids.shift());
					}
					else {
						finishScript();
					}
				}
			});

			settings.count++;
		};

		var updateProgress=function(id,success,response) {
			var percent=Math.floor((settings.count/settings.total)*100);

			settings.$fill.css({
				width: percent+'%'
			})

			settings.$percent.html(percent+'%');

			if (success) {
				settings.$data.append('ID #'+id+' - '+response.success+'<br />');
				settings.success++;
			} else {
				settings.$data.append('ID #'+id+' - '+response.error+'<br />');
				settings.failure++;
			}

			settings.$counterCurrent.text(settings.count);
		};

		init();

		return this;
	};

})(jQuery);