(function() {
    const PAGE_LIMIT = 5000;
    const REMAINING_WITHOUT_BREAK = 500;
    tinymce.PluginManager.add('apb_mce_button', function( editor, url ) {
        editor.addButton('apb_mce_button', {
            text: 'Расставить разрывы страниц',
            icon: false,
            onclick: function() {
                let content = editor.getContent().replace(/(<p>)?<!--nextpage-->(<\/p>)?/gm, '');
                let contentLength = content.length;
                let newContent = '';
                let currentPosition = 0;
                while (currentPosition <= contentLength) {
                    let matchIndex = content.indexOf('</p>', currentPosition + PAGE_LIMIT);
                    if (matchIndex > 0 && matchIndex < contentLength - REMAINING_WITHOUT_BREAK) {
                        newContent += content.slice(currentPosition, matchIndex + 4) + '<!--nextpage-->';
                        currentPosition = matchIndex + 4;
                    } else {
                        newContent += content.slice(currentPosition);
                        break;
                    }
                }
                editor.setContent(newContent);
            }
        });
    });
})();