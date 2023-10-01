/**
 * Frontend Script.
 * 
 * @package FutureWordPressScratchProject
 */

import Swal from "sweetalert2"; // "success", "error", "warning", "info" or "question"
import { toast } from 'toast-notification-alert';
import { prompts } from "../modules/prompts";
import Dropzone from "dropzone";
Dropzone.autoDiscover = false;
import Toastify from 'toastify-js';


(function ($) {
	class FutureWordPress_Frontend {
		/**
		 * Constructor
		 */
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl ?? '';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce ?? '';
			this.lastAjax = false;window.thisClass = this;
			this.profile = fwpSiteConfig?.profile ?? false;
			this.i18n = fwpSiteConfig?.i18n ?? {};this.noToast	 = true;
			this.prompts = prompts;this.Swal = Swal;this.Dropzone = Dropzone;
			this.init_toast();this.setup_events();this.setup_hooks();
			this.init_neccessery_scripts();
		}
		init_toast() {
			const thisClass = this;
			this.toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3500,
				timerProgressBar: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer )
					toast.addEventListener('mouseleave', Swal.resumeTimer )
				}
			});
			this.notify = Swal.mixin({
				toast: true,
				position: 'bottom-start',
				showConfirmButton: false,
				timer: 6000,
				willOpen: (toast) => {
				  // Offset the toast message based on the admin menu size
				  var dir = 'rtl' === document.dir ? 'right' : 'left'
				  toast.parentElement.style[dir] = document.getElementById('adminmenu')?.offsetWidth + 'px'??'30px'
				}
			})
			this.toastify = Toastify; // https://github.com/apvarun/toastify-js/blob/master/README.md
		}
		setup_hooks() {
			this.enqueueScripts();
			this.handleAdminDuplicateButton();
			this.handleFrontEndDuplicateButton();
		}
		setup_events() {
			const thisClass = this;var index, stored;// console.log('setup_events...');
			document.body.addEventListener('uploadedsinglestocksuccess', (event) => {
				stored = thisClass.prompts.storedUploads;index = parseInt(thisClass.lastJson.index);
				stored[index] = (stored[index])?stored[index]:[];
				stored[index].push({
					type: thisClass.lastJson.type,
					attach_id: thisClass.lastJson.attach_id
				});
				thisClass.prompts.storedUploads = stored;
			});
			document.body.addEventListener('uploadedsinglestockerror', (event) => {
				// console.log('');
			});
			document.body.addEventListener('finishingstocksuccess', (event) => {
				thisClass.prompts.updated = true;
				thisClass.Swal.close();
				location.reload();
			});
			document.body.addEventListener('finishingstockfailed', (event) => {
				thisClass.prompts.updated = 'failed';
			});
		}
		sendToServer(data) {
			const thisClass = this;var message;
			$.ajax({
				url: thisClass.ajaxUrl,
				type: "POST",
				data: data,    
				cache: false,
				contentType: false,
				processData: false,
				success: function(json) {
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
				},
				error: function(err) {
					if(err.responseText) {
						thisClass.toastify({text: err.responseText,className: "warning", duration: 3000, stopOnFocus: true, style: {background: "linear-gradient(to right, #00b09b, #96c93d)"}}).showToast();
					}
					console.log(err.responseText);
				}
			});
		}
		handleAdminDuplicateButton() {
			const thisClass = this;var el, message;
			document.querySelectorAll('.button.button-icon.icon-duplicate:not([data-handled])').forEach((el) => {
				el.dataset.handled = true;
				el.addEventListener('click', (event) => {
					event.preventDefault();
					const args = {};
					const matches = el.getAttribute('href').match(/post=(\d+)/);
					args.postId = (matches)?parseInt(matches[1]):null;
					thisClass.prompts.postId = args.postId;
					thisClass.prompts.before_init_popup(thisClass, args);
				});
			});
		}
		handleFrontEndDuplicateButton() {
			const thisClass = this;var el, message;
			document.querySelectorAll('.ulz--primary-actions ul li:last-child:not([data-handled])').forEach((el)=>{
				var args, href, li, a, i;el.dataset.handled = true;
				href = el.children[0].href;href = href.replace('action=delete_listing', '')
				li = document.createElement('li');a = document.createElement('a');a.href = href;a.dataset.action = 'account-listing-duplicate';
				a.dataset.confirmation = 'Are you sure you want to delete this item?';
				i = document.createElement('i');i.classList.add('fa', 'fa-clone');
				// i.innerHTML = 'duplicate';
				a.addEventListener('click', (event) => {
					event.preventDefault();args = {};
					const matches = a.getAttribute('href').match(/id=(\d+)/);
					args.postId = (matches)?parseInt(matches[1]):null;
					thisClass.prompts.postId = args.postId;
					thisClass.prompts.before_init_popup(thisClass, args);
				});
				a.appendChild(i);li.appendChild(a);el.parentElement.insertBefore(li, el);
			});
		}
		enqueueScripts() {
			const thisClass = this;var script, link;
			link = document.createElement('link');link.rel = 'stylesheet';
			link.href = 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css';
			document.head.appendChild(link);
		}
		init_neccessery_scripts() {
			// codes goes here.
		}
	}
	new FutureWordPress_Frontend();
})(jQuery);
