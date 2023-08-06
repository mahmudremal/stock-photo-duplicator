export const prompts = {
    storedUploads: [],updated: false,postId: false,
    before_init_popup: (thisClass, args) => {
        thisClass.Swal.fire({
            title: 'How many items would you like to duplicate?',
            input: 'number',
            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Generate',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                if(login <= 0) {
                    thisClass.Swal.showValidationMessage(`you must set 1 or up to field repeat. You set : ${error}`)
                    return false;
                }
                return login;
            },
            allowOutsideClick: () => !thisClass.Swal.isLoading()
        }).then((result) => {
            if(result.isConfirmed && (result?.value??false)) {
                args.repeater = result.value;
                prompts.init_popup(thisClass, args);
            }
        })
    },
    init_popup: (thisClass, args) => {
        thisClass.Swal.fire({
            title: thisClass.i18n?.are_u_sure??'Are you sure?',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            customClass: {popup: 'fwp-swal-popup'},
            html: `
            <div class="dynamic_popup">
                <div class="duplicat__container"> 
                    <div class="duplicat__row">

                        ${prompts.do_repeater(thisClass, args)}

                    </div>
                </div>
            </div>
            `,
            didOpen: () => {
                prompts.init_events(thisClass, args);
            },
            preConfirm: (data) => {
                if(thisClass.prompts.storedUploads.length <= 0) {return true;}
                if(!thisClass.prompts.postId) {return true;}
                return new Promise((resolve, reject) => {
                    var formdata = new FormData();
                    formdata.append('action', 'stockphotoduplicator/generate/stock/finish');
                    formdata.append('dataset', JSON.stringify(thisClass.prompts.storedUploads));
                    formdata.append('refered', thisClass.prompts.postId);
                    formdata.append('_nonce', thisClass.ajaxNonce);
                    thisClass.sendToServer(formdata);
                    var theInterval = setInterval(() => {
                        if(thisClass.prompts.updated == true) {
                            thisClass.prompts.updated == false;resolve();
                        } else if(thisClass.prompts.updated == 'failed') {
                            thisClass.prompts.updated = false;
                            clearInterval(theInterval);reject();
                        } else {}
                    }, 2000)
                })
            },
        }).then((result) => {
            thisClass.prompts.storedUploads = [];
            if (result.isConfirmed) {
                // var formdata = new FormData();
                // formdata.append('action', 'futurewordpress/project/action/deletearchives');
                // formdata.append('archive', archive.dataset.archive);
                // formdata.append('userid', archive.dataset.userid);
                // formdata.append('_nonce', thisClass.ajaxNonce);
                // thisClass.sendToServer(formdata);
            }
        });
    },
    do_repeater: (thisClass, args) => {
        var html = '';
        for(let index = 0; index < args.repeater; index++) {
            args.index = index;
            html += `
            <div class="duplicat__field">
                <div class="duplicat__field__grid">

                    <div class="duplicat__field__dropzone" data-index="${index}" data-type="thumbnails">
                        <h3>Thumbnails</h3>
                        ${prompts.do_dropzone(thisClass, args)}
                    </div>

                    <div class="duplicat__field__dropzone" data-index="${index}" data-type="downloadable">
                        <h3>Downloadable Files</h3>
                        ${prompts.do_dropzone(thisClass, args)}
                    </div>
                    
                </div>
            </div>
            `;
        }
        return html;
    },
    do_dropzone: (thisClass, args) => {
        return `
        <!-- Dropzone -->
        <div class="svg-preloader dropzone u-dropzone gradient-overlay-half-primary-v4">
            <div class="dz-message py-6">
                <figure class="max-width-10 mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.1 58" style="enable-background:new 0 0 66.1 58;" xml:space="preserve" class="injected-svg js-svg-injector" data-parent="#SVGIcon"><style type="text/css">.drag-n-drop-1{fill:#377DFF;}.drag-n-drop-2{fill:#FFFFFF;}</style><g opacity=".5"><circle class="drag-n-drop-1 fill-primary" cx="26.4" cy="43.1" r="0.6"></circle></g><g opacity=".5"><circle class="drag-n-drop-1 fill-primary" cx="20.3" cy="51.2" r="1.3"></circle></g><g><circle class="drag-n-drop-1 fill-primary" cx="22" cy="37.5" r="1.3"></circle></g><g><circle class="drag-n-drop-1 fill-primary" cx="31.1" cy="49.5" r="1.3"></circle></g><g opacity=".3"><circle class="drag-n-drop-1 fill-primary" cx="39.1" cy="43.2" r="0.6"></circle></g><g opacity=".3"><circle class="drag-n-drop-1 fill-primary" cx="35.1" cy="57.4" r="0.6"></circle></g><g opacity=".2"><circle class="drag-n-drop-1 fill-primary" cx="33.1" cy="38" r="0.6"></circle></g><g opacity=".5"><circle class="drag-n-drop-1 fill-primary" cx="42.2" cy="49.4" r="1.3"></circle></g><g><circle class="drag-n-drop-1 fill-primary" cx="44.2" cy="37.5" r="1.3"></circle></g><path class="drag-n-drop-1 fill-primary" opacity=".2" d="M27.8,2.1c-0.4,0.4-2.5,0-5,2.2c-0.7,0.7-1.1,1.5-1.6,2.2c-0.4,0.7-0.7,1.5-0.9,2.1  c0,0,0,0-0.1,0c-0.5-0.1-1.2-0.3-1.7-0.3l0,0h-0.1c-4.7,0-8.8,3.3-9.8,7.9H7.6C3.4,16.3,0,19.8,0,24v0.5c0,3.2,1.8,5.8,4.6,7H24  L27.8,2.1z"></path><path class="drag-n-drop-1 fill-primary" d="M52.4,31.6h7.4c3.4,0,6.3-3.2,6.3-7v-0.5c0-3.8-2.8-7-6.3-7h-0.2  c-1.1-4.1-4.5-7.1-8.5-7.1c-1.8,0-3.4,0.5-4.8,1.6C45.3,5,40.4,0,34.2,0c-3.8,0-7.1,1.8-9.3,4.9C24.4,5.5,24,6.2,23.6,7  c-0.4,0.8-0.7,1.7-0.9,2.6c-0.7-0.3-1.4-0.4-2.1-0.4c-4.1,0-7.7,3.4-8.3,7.9h-1.4c-3.4,0-6.3,3.2-6.3,7v0.5c0,3.8,2.8,7,6.3,7h34.3"></path><g><path class="drag-n-drop-2 fill-white" d="M27.8,19.6c-0.1-0.1-0.2-0.3-0.2-0.5s0.1-0.4,0.2-0.5l5.8-5.8c0.1-0.1,0.3-0.2,0.5-0.2s0.4,0.1,0.5,0.2   l5.8,5.8c0.1,0.1,0.2,0.3,0.2,0.5s-0.1,0.4-0.2,0.5l-0.7,0.7c-0.1,0.2-0.3,0.2-0.5,0.2c-0.2,0-0.4-0.1-0.5-0.2l-3.5-3.6v8.6   c0,0.2-0.1,0.4-0.2,0.5C35,25.9,34.8,26,34.6,26h-1c-0.2,0-0.4-0.1-0.5-0.2c-0.1-0.1-0.2-0.3-0.2-0.5v-8.6l-3.5,3.6   c-0.1,0.1-0.3,0.2-0.5,0.2c-0.2,0-0.4-0.1-0.5-0.2L27.8,19.6z"></path></g></svg>
                </figure>
                <span class="d-block">Drag files here to upload</span>
                <button class="button btn fwp-button btn-open-media-library" type="button">Media library</button>
            </div>
        </div>
        <!-- End Dropzone -->
        `;
    },
    init_events: (thisClass, args) => {
        document.querySelectorAll('.duplicat__field__grid .u-dropzone:not([data-handled])').forEach((el) => {
            el.dataset.handled = true;
            const myDropzone = new thisClass.Dropzone(el, {
                url: thisClass.ajaxUrl+'?action=stockphotoduplicator/generate/stock/upload&type='+el.parentElement.dataset.type+'&index='+el.parentElement.dataset.index+'&auto_watermark=true',
                // acceptedFiles: "image/*",
                // maxFilesize: 400,
                init: function () {
                    this.on("success", function (file, json) {
                        thisClass.lastJson = json.data;
                        var message = ( typeof json.data.message === 'string') ? json.data.message : (
                            ( typeof json.data === 'string') ? json.data : false
                        );
                        if(message) {
                            thisClass.toastify({text: message,className: "info", duration: 3000, stopOnFocus: true, style: {background: (json.success)?"linear-gradient(to right, #00b09b, #96c93d)":"linear-gradient(to right, rgb(255, 95, 109), rgb(255, 195, 113))"}}).showToast();
                        }
                        if(json.data.hooks) {
                            json.data.hooks.forEach((hook) => {
                                document.body.dispatchEvent(new Event(hook));
                            });
                        }
                    });
                    this.on("error", function (err) {
                        if(err.responseText) {
                            thisClass.toastify({text: err.responseText,className: "warning", duration: 3000, stopOnFocus: true, style: {background: "linear-gradient(to right, #00b09b, #96c93d)"}}).showToast();
                        }
                        console.log(err.responseText);
                    });
                },
            });
            myDropzone.on("addedfile", file => {
                console.log(`File added: ${file.name}`);
            });
            // Handle Media Library Button Click Event
            el.querySelectorAll('.btn-open-media-library:not([data-handled])').forEach((btn)=>{
                el.dataset.handled = true;
                btn.addEventListener('click', (event) => {
                    event.preventDefault();event.stopPropagation();
                    const frame = wp.media({
                        title: 'Select File from Media Library',
                        button: {
                            text: 'Insert',
                        },
                        multiple: true // Set to false if you want to allow only a single file selection
                    });
                    // When a file is selected from the media library, handle the selection
                    frame.on('select', function() {
                        const selection = frame.state().get('selection');
                        selection.each(async (attachment) => {
                            // Get the attachment URL and set it as the file's thumbnail in Dropzone
    
                            console.log(attachment);
                            
                            // Create a file object and add it to Dropzone
                            const file = {
                                name: attachment.attributes.filename,
                                size: attachment.attributes?.filesize??attachment.attributes.filesizeInBytes,
                                type: attachment.attributes.mime,
                                url: attachment.attributes.url
                            };
                            // myDropzone.emit('thumbnail', attachment, attachment.attributes.url);
                            // myDropzone.files.push(file);
                            // myDropzone.emit('addedfile', file);
                            // myDropzone.emit('complete', file);

                            // const response = await fetch(file.url);
                            // file.url = await response.blob();
                            // myDropzone.addFile({name: file.name, size: file.size, type: file.type, dataURL: file.url, accepted: true});

                            const stored = thisClass.prompts.storedUploads;const index = parseInt(el.parentElement.dataset.index);
                            stored[index] = (stored[index])?stored[index]:[];
                            stored[index].push({
                                type: el.parentElement.dataset.type,
                                attach_id: attachment.attributes.id
                            });
                            thisClass.prompts.storedUploads = stored;
                            
                            myDropzone.displayExistingFile({name: file.name, size: file.size}, file.url);
                        });
                    });
                    // Open the media library popup
                    frame.open();
                });
            });
        });
        
    },
    imageToUri: (url, callback) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        var base_image = new Image();
        base_image.src = url;
        base_image.onload = function() {
            canvas.width = base_image.width;
            canvas.height = base_image.height;
            ctx.drawImage(base_image, 0, 0);
            callback(canvas.toDataURL('image/png'));
            canvas.remove();
        }
    }
}


