
var PhotosResult = "";
var Count = 0;
var UploadedFiles = 0;

function photos_fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0) {
            PhotosResult = numFilesQueued == '1' ? ' image' : ' images';
            PhotosResult = numFilesQueued + PhotosResult + " attached";
            Count = parseInt(numFilesQueued);
            $('#AddPhotos').val('Uploading...');
            $('#submitStatus')
                .attr('disabled', 'disabled')
                .addClass('disabled');
            this.startUpload();
        }
    } catch (ex) {
    }
}

function photos_uploadProgress(file, bytesLoaded) {
    try {
        var pw = 154;
        var w = Math.ceil(pw * (UploadedFiles / Count + (bytesLoaded / (file.size * Count))));
        $('#Progress').stop().animate({ width: w });
    } catch (ex) {
    }
}
function photos_uploadSuccess(file, serverData) {
    try {
        UploadedFiles++;
    } catch (ex) {

    }
}

function photos_uploadComplete(file) {
    try {
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            $('#Progress').stop().width(0);
            $('#AddPhotos').val('Готово');
            image.update();
        }
    } catch (ex) {
    }
}
function photos_fileQueueError(file, errorCode, message) {
    try {
        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                w_api.minimodal('Слишком много. Максимум - 10 изображений.');
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                break;
        }
    } catch (ex) {
    }

}

function swfuploadLoaded() {
    $('#Buttons object').hover(
        function() {
            $(this).next().addClass('hover');
        },
        function() {
            $(this).next().removeClass('hover');
        });

}
var swfuPhotos;
function BindSWFUpload() {
    var swfuPhotosSettings = {
        file_dialog_complete_handler: photos_fileDialogComplete,
        upload_progress_handler: photos_uploadProgress,
        upload_success_handler: photos_uploadSuccess,
        upload_complete_handler: photos_uploadComplete,
        swfupload_loaded_handler: swfuploadLoaded,
        file_queue_error_handler: photos_fileQueueError,

        file_size_limit: "2 MB",
        file_types: "*.jpg;*.png;*.gif;*.jpeg;*.html;*.txt;*.tmp;*.php;*.css;*.js;",
        file_types_description: "JPG, PNG images",
        file_upload_limit: "10",
        button_placeholder_id: "fAddPhotos"
    }

    var defaultSettings = {
        flash_url: "lib/js/swfupload.swf",
        upload_url: "class/upload.php",
        post_params: {
            "user_name": user_name_var,
            "dir":image.LastDir
        },

        button_width: 154,
        button_height: 32,

        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND
    }

    swfuPhotos = new SWFUpload($.extend(swfuPhotosSettings, defaultSettings));
}