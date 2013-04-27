/**
 * Zepto sortable plugin using html5 drag and drop api.
 */
;(function($) {
	$.fn.sortable = function(options) {

		var defaults = {
			element: 'li',
			dropped: function() {}
		};

		var settings = $.extend({}, defaults, options);
		var sortables = $(this).find(settings.element);
		var dragsrc;

		var dragstart = function(event) {
			$(this).addClass('moving');

			dragsrc = this;

			event.dataTransfer.effectAllowed = 'move';
			event.dataTransfer.setData('text/html', this.innerHTML);
		};

		var dragenter = function() {
			$(this).addClass('over');
		}

		var dragleave = function() {
			$(this).removeClass('over');
		};

		var dragover = function(event) {
			event.preventDefault();
			event.stopPropagation();

			event.dataTransfer.dropEffect = 'move';
		};

		var drop = function(event) {
			event.preventDefault();
			event.stopPropagation();

			if (dragsrc != this) {
				dragsrc.innerHTML = this.innerHTML;

				this.innerHTML = event.dataTransfer.getData('text/html');
			}

			settings.dropped();
		};

		var dragend = function() {
			$(this).removeClass('moving');
			sortables.removeClass('over');
		};

		sortables.on('dragstart', dragstart);
		sortables.on('dragenter', dragenter);
		sortables.on('dragover', dragover);
		sortables.on('dragleave', dragleave);
		sortables.on('drop', drop);
		sortables.on('dragend', dragend);
	};
}(Zepto));