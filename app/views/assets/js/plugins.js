(function () {
    var main = document.querySelector('main'),
        versionCheckButtons = document.querySelectorAll('.check-plugin-version'),
        startVersionCheck = function () {
        var plugin = this.dataset.pluginName,
            version = this.dataset.pluginVersion,
            xhr = new XMLHttpRequest();

        xhr.open('GET', '/admin/plugins/' + plugin + '/versionCheck');

        xhr.addEventListener('load', function() {
            var response = JSON.parse(this.responseText),
                messageContainer = document.createElement('div'),
                messageList = document.createElement('ul'),
                message = document.createElement('li');
            messageContainer.classList.add('messages');

            switch (response.status) {
                case 'update required':
                    messageList.classList.add('error');
                    message.textContent = plugin + ' needs to be updated: Installed version is ' + version + ' while ' + response.currentVersion + ' is available!';
                    messageList.appendChild(message);
                    break;
                case 'up to date':
                    messageList.classList.add('success');
                    message.textContent = plugin + ' is up to date.';
                    messageList.appendChild(message);
                    break;

                // everything else should be an error
                case 'error':
                    messageList.classList.add('error');
                    message.textContent = response.message;
                    messageList.appendChild(message)
            }

            messageContainer.appendChild(messageList);
            main.parentNode.insertBefore(messageContainer, main);
        });

        xhr.send();
    };

    for (var i = versionCheckButtons.length; i--;) {
        versionCheckButtons[ i ].addEventListener('click', startVersionCheck.bind(versionCheckButtons[ i ]));
    }
})();
