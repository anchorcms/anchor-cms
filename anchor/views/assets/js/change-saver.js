/* Prompts the user if they attempt to leave and there
 * are still unsaved changes on important fields
 */
(function($) {
    /* first and only argument should be selector for which fields to check within form */
	$.fn.changeSaver = function() {
        var form = $(this);
        var submitted = false;
        var value_store = [];
        var field_selector = arguments[0] || "input[type=text], textarea";//by default save all text inputs

        form.find(field_selector).forEach(function(item, index){
            value_store.push({
                element: item,
                original_value: $(item).val()
            });
        });

        function hasDiffs() {
            for(var i = 0; i < value_store.length; i++){
                var input = value_store[i];
                if (input.original_value != $(input.element).val()) {
                    return true;
                }
            }
            return false;
        }
        
        $(form).on("submit", function() {
            submitted = true;
        });

        $(window).on("beforeunload", function() {
            if (!submitted && hasDiffs()) {
                return "There are unsaved changes";
            } 
        });
    };
}(Zepto));
