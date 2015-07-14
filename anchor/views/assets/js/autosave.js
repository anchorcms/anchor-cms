/**
 * From @TheBrenny:
 *      This JS File holds the functions that are used to determine whether or
 *      not we should turn on/off autosave, and determines whether or not
 *      autosave is active.
 */

$(document).ready(function() {
	var autosaveInterval;
	var maxSeconds = 30;
	var secondsPassed = 0;
	
	var onInterval = function() {
		secondsPassed++;
		if(secondsPassed > maxSeconds) {
			secondsPassed = 0;
			submitDocument();
		}
		$(".autosave-label").text("Autosave in " + (maxSeconds - secondsPassed));
	};
	
	var submitDocument = function() {
		$("form").first().trigger("submit");
	};
	
	var alterAutosaveActionButton = function() {
		var pressOn = (autosaveInterval !== null);
		$(".autosave-action").toggleClass("green", pressOn);
		$(".autosave-action").toggleClass("autosave-on", pressOn);
		$(".autosave-action").toggleClass("secondary", !pressOn);
		$(".autosave-label").text(pressOn ? "Autosave in 30" : "Autosave: Off");
		/*
		if(pressOn) { // Just turned on autosave
			$(".autosave-action").addClass("green");
			$(".autosave-action").removeClass("secondary");
			$(".autosave-label").text("Autosave in 30");
		} else { // Just turned off autosave
			$(".autosave-action").addClass("secondary");
			$(".autosave-action").removeClass("green");
			$(".autosave-label").text("Autosave: Off");
		}
		*/
	};
	
	$(".autosave-action").click(function() {
		if(autosaveInterval === null) {
			autosaveInterval = setInterval(function() {onInterval();}, 1000);
		} else {
			clearInterval(autosaveInterval);
			autosaveInterval = null;
			secondsPassed = 0;
		}
		alterAutosaveActionButton();
	});
});