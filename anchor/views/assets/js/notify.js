var Notify = {
    html: '<div class="mask"><div class="panel"><p></p><div class="buttons"></div></div></div>',
    
    create: function(msg, buttons) {
        var html = $(html).find('p').html(msg);
            
        $('body').append(html);
        
        return false;
    },
    
    alert: function(msg, callback) {
    
    },
    
    confirm: function(msg, good, bad) {
        return this.create(msg);
    }
};

window.alert = Notify.alert;
window.confirm = Notify.confirm;