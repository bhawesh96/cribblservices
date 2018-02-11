(function() {
    CKEDITOR.dialog.add('spectrum', function(editor) {
        var $spectrumBlockElement;
        var $dialogElement;
        var apiSender;

        editor.on('beforeDestroy', function() {
            apiSender && apiSender.hide();
        });

        function onClickDialog(event) {
            event.stopPropagation();
        }

        return {
            title: editor.config.$injector.get('$translate').instant('COMMON.TEXT.SELECT_COLOR_LABEL'),
            minWidth: 300,
            minHeight: 280,
            resizable: CKEDITOR.DIALOG_RESIZE_NONE,
            contents: [{
                id: 'content_spectrum',
                label: '',
                title: '',
                elements: [{
                    type: 'html',
                    id: 'wrapSpectrum',
                    html: '<div class="spectrum-wrap"><span class="spectrum-block"></span></div>'
                }]
            }],
            onShow: function(api) {
                if ($spectrumBlockElement) {
                    return;
                }
                apiSender = api.sender;
                $spectrumBlockElement = $('.cke_editor_' + editor.name + '_dialog .spectrum-block');
                if ($spectrumBlockElement.length > 0) {
                    $dialogElement = $spectrumBlockElement.closest('.cke_dialog');
                    $dialogElement.addClass('moto-spectrum-dialog');
                    $dialogElement.on('click', onClickDialog);
                    $spectrumBlockElement.spectrum({
                        flat: true,
                        showButtons: false,
                        showAlpha: false,
                        showInitial: true,
                        showInput: true,
                        inputUpdateOriginal: false,
                        preferredFormat: 'hex',
                        color: CKEDITOR.plugins.colorizer.getCurrentColor() || '#000000',
                        hide: function() {
                            if ($spectrumBlockElement) {
                                api.sender.hide();
                            }
                        }
                    });
                }
            },
            buttons: [
                CKEDITOR.dialog.cancelButton,
                CKEDITOR.dialog.okButton
            ],
            onOk: function() {
                var color = $spectrumBlockElement.spectrum('get').toString();

                if (color) {
                    CKEDITOR.plugins.colorizer.setColor(editor, color);
                }
            },
            onHide: function() {
                $spectrumBlockElement.spectrum('destroy');
                $spectrumBlockElement = null;
                $dialogElement.off('click', onClickDialog);
                $dialogElement = null;
                apiSender = null;
            }
        };
    });
})();
