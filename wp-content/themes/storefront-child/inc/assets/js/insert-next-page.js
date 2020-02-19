(function () {
    const PAGE_LIMIT = 5000;
    const REMAINING_WITHOUT_BREAK = 500;
    tinymce.PluginManager.add('apb_mce_button', function (editor, url) {
        editor.addButton('apb_mce_button', {
            text: 'Расставить разрывы страниц',
            icon: false,
            onclick: function () {
                let content = editor.getContent().replace(/(<p>)?<!--nextpage-->(<\/p>)?/gm, '');
                let contentLength = content.length;
                let newContent = '';
                let nextPart = '';
                let currentPosition = 0;
                let charCount = 0;
                let insideTag = false;
                for (let i = 0; i < contentLength; i++) {
                    if (content.charAt(i) === '<') {
                        insideTag = true;
                    }
                    if (insideTag && (i + 3) < contentLength && content.charAt(i + 1) === '/' && content.charAt(i + 2) === 'p' && content.charAt(i + 3) === '>' && charCount >= PAGE_LIMIT) {
                        if (newContent !== '') {
                            newContent += '<!--nextpage-->';
                        }
                        newContent += nextPart;
                        nextPart = content.slice(currentPosition, i + 4);
                        currentPosition = i + 4;
                        i += 3;
                        charCount = 0;
                    }
                    if (!insideTag) {
                        charCount++;
                    }
                    if (content.charAt(i) === '>') {
                        insideTag = false
                    }
                }
                if (newContent !== '') {
                    newContent += '<!--nextpage-->';
                }
                newContent += nextPart;
                if (charCount >= REMAINING_WITHOUT_BREAK) {
                    newContent += '<!--nextpage-->';
                }
                newContent += content.slice(currentPosition);
                editor.setContent(newContent);
            }
        });
    });
})();