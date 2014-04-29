(function($){

	var options,
		progressContainer,
		errorContainer,
		progressbar,
		progressNum;

	$.fn.ajaxupload = function(_options) {
		var self = this,
			defaultOptions = {
				chunkSize : 10000000,
				debugIframe : false,
				previewResize: false,
				previewWidth: 80,
				previewHeight: 80,
				maximum: false,
				uploadedSelector: false,
				removeFailed: false,
				disableImageResize: true,
				imageMaxWidth: 1920,
				imageMaxHeight: 1080,
				imageCrop: false
			};
		options = $.extend({}, defaultOptions, _options);

		if (options.progressbar) {
			progressbar = $(options.progressbar);
		}
		if (options.progress) {
			progressNum = $(options.progress);
		}
		if (options.progressContainer) {
			progressContainer = $(options.progressContainer);
		}
		if (options.errorContainer) {
			errorContainer = $(options.errorContainer);
		}

		this.fileupload({
			dataType: 'json',
			formData: {},
			maxChunkSize: options.chunkSize,
			forceIframeTransport: options.debugIframe,
			previewMaxWidth: options.previewWidth,
			previewMaxHeight: options.previewHeight,
			previewCrop: true,
			disableImageResize: options.disableImageResize,
			imageMaxWidth: options.imageMaxWidth,
			imageMaxHeight: options.imageMaxHeight,
			imageCrop: options.imageCrop,
			start: function() {
				if (progressContainer) {
					progressContainer.stop().fadeIn(400);
				}
			},
			stop: function() {
				if (progressContainer) {
					progressContainer.stop().fadeOut(400,function() {
						progressbar && progressbar.css(options.progressbarAllProperty ? options.progressbarAllProperty : 'width',0);
					});
				}
			},
			add: function (e, data) {

				if (errorContainer) {
					errorContainer.empty();
				}

				if (options.maximum !== false && options.uploadedSelector !== false && $(options.uploadedSelector).length >= options.maximum ) {
					return false;
				}
				data.context = $();
				$.each(data.files,function(index, file) {

					var picture = $($.parseHTML($.trim(options.pictureTemplate))).appendTo('#'+options.divId);
					if (options.pictureSelectors['filename']) {
						picture.find(options.pictureSelectors['filename']).text(file.name);
					}
					if (options.pictureSelectors['progressbar']) {
						picture.find(options.pictureSelectors['progressbar']).css(options.progressbarProperty ? options.progressbarProperty : 'width',0);
					}
					if (options.pictureSelectors['retry']) {
						picture.find(options.pictureSelectors['retry']).hide();
					}
					if (options.pictureSelectors['delete']) {
						picture.find(options.pictureSelectors['delete']).hide();
					}
					if (options.pictureSelectors['cancel']) {
						picture.find(options.pictureSelectors['cancel']).show().on('click.ajaxupload',function(e) {
							e.preventDefault();
							data.abort();

							if (cleanupUrl = $(this).data('cleanupUrl')) {
								$.ajax(cleanupUrl);
							}

							data.context.remove();
						});
					}

					data.context = data.context.add(
						picture
					);
				});

				/*if (data.autoUpload || (data.autoUpload !== false &&
						$(this).fileupload('option', 'autoUpload'))) {
					data.process().done(function () {
						data.submit();
					});
				}*/

				if (data.autoUpload || (data.autoUpload !== false && $(this).fileupload('option', 'autoUpload'))) {
					var that = this;
					data.process(function () {
						return $(that).fileupload('process', data);
					}).done(function () {
						data.submit();
						if (options.pictureSelectors['preview']) {
							data.context.find(options.pictureSelectors['preview']).each(function (index, elem) {
								$(elem).prepend($(data.files[index].preview).addClass('origpreview'));
							});
						}
					});
				}
			},
			chunkdone: function(e, data) {
				//chunks and multiFileUploads cannot be used together, so don't bother
				if (!data.result[options.inputName][0]) {
					return;
				}
				if (options.pictureSelectors['cancel']) {
					var cancelButtons = data.context.find(options.pictureSelectors['cancel']);
					if (data.result[options.inputName][0].deleteUrl) {
						cancelButtons.each(function(index, elem) {
							$(elem).data('cleanupUrl',data.result[options.inputName][0].deleteUrl);
						});
					}
				}
				// TODO: real error message instead of aborted
				/*if (data.result[options.inputName][0].error) {
					return false;
				}*/
			},
			fail: function(e, data) {
				data.context.each(function(index) {
					var $this = $(this);

					if (errorContainer) {
						errorContainer.append(document.createTextNode(options.strings['upload-failed']));
						errorContainer.append('<br/>');
					}

					if (options.removeFailed) {
						$this.remove();
						return;
					}
					if (options.pictureSelectors['error']) {
						$this.find(options.pictureSelectors['error']).text(options.strings['upload-failed']);
					}
					if (options.pictureSelectors['retry']) {
						$(this).find(options.pictureSelectors['retry'])
							.show()
							.off('click.ajaxupload')
							.on('click.ajaxupload',function(e) {
								e.preventDefault();

								if (options.pictureSelectors['error']) {
									$this.find(options.pictureSelectors['error']).text('');
								}

								$(this).hide();
								data.submit();
							});
					}
				});
				/*if (data.errorThrown !== 'abort') {
					data.submit();
				}*/
			},
			progress: function(e, data) {
				if (data.context) {
					var progress = Math.floor(data.loaded / data.total * 100);
					if (options.pictureSelectors['progress']) {
						data.context.find(options.pictureSelectors['progress']).text(progress);
					}
					if (options.pictureSelectors['progressbar']) {
						data.context.find(options.pictureSelectors['progressbar']).css(options.progressbarProperty ? options.progressbarProperty : 'width',progress+'%');
					}
				}
			},
			progressall: function(e, data) {
				if (progressbar || progressNum) {
					var progress = Math.floor(data.loaded / data.total * 100);
					if (progress <=100 && progress >=0) {
						progressbar && progressbar.css(options.progressbarAllProperty ? options.progressbarAllProperty : 'width',progress+'%');
						progressNum && progressNum.text(progress);

					}
				}
			},
			done: function (e, data) {
				data.context.each(function(index) {
					var $this = $(this);
					if (!data.result[options.inputName][index]) {
						$this.remove();
						return;
					}

					var file = data.result[options.inputName][index],
						field = $($.parseHTML($.trim(options.fieldTemplate))).val(file.name);

					if (options.pictureSelectors['delete']) {
						$this.find(options.pictureSelectors['delete'])
							.show()
							.off('click.ajaxupload')
							.on('click.ajaxupload',function(e) {
								e.preventDefault();
								if (file.deleteUrl) {
									$.ajax(file.deleteUrl).done(function() {
										$this.remove();
									}).fail(function() {
										console.log("Delete failed!");
									});
								} else {
									$this.remove();
								}
							});
					}
					if (options.pictureSelectors['cancel']) {
						$this.find(options.pictureSelectors['cancel']).hide();
					}

					if (file.error) {

						if (errorContainer) {
							errorContainer.append(document.createTextNode(file.error));
							errorContainer.append('<br/>');
						}

						if (options.removeFailed) {
							$this.remove();
							return;
						}
						if (options.pictureSelectors['error']) {
							$this.find(options.pictureSelectors['error']).text(file.error);
						}
					} else {
						$this.append(field);
					}
					if (file.thumbnailUrl) {
						if (options.pictureSelectors['preview']) {
							$this.find(options.pictureSelectors['preview'])
								.find('.origpreview').remove().end()
								.prepend($('<img src="'+file.thumbnailUrl+'"/>'));
						}
					}

					if (options.pictureSelectors['filename']) {
						$this.find(options.pictureSelectors['filename']).text(file.name);
					}
				});
			}
		});
		return this;
	};
}(jQuery));
