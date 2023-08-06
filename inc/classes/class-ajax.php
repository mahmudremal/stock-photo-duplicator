<?php
/**
 * Archive Settings
 *
 * @package FutureWordPressScratchProject
 */
namespace STOCK_PHOTO_DUPLICATOR\Inc;
use STOCK_PHOTO_DUPLICATOR\Inc\Traits\Singleton;
class Ajax {
	use Singleton;
	protected function __construct() {
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action('wp_ajax_stockphotoduplicator/generate/stock/duplicate', [ $this, 'do_stockDuplicate' ], 10, 0);
		add_action('wp_ajax_nopriv_stockphotoduplicator/generate/stock/duplicate', [ $this, 'do_stockDuplicate' ], 10, 0);

		// Add AJAX action to handle the uploaded image
		add_action('wp_ajax_stockphotoduplicator/generate/stock/upload', [$this, 'stock_upload']);
		// add_action('wp_ajax_nopriv_stockphotoduplicator/generate/stock/upload', [$this, 'stock_upload']);

		add_action('wp_ajax_stockphotoduplicator/generate/stock/finish', [$this, 'stock_finish']);
		// add_action('wp_ajax_nopriv_stockphotoduplicator/generate/stock/upload', [$this, 'stock_upload']);

		
		// $this->test_get_meta();
	}
	
	public function test_get_meta() {
		global $wpdb;
		// if(!isset($_GET['action']) || $_GET['action'] != 'edit') {return;}
		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id=%d", 4748
			)
		);
		// print_r(wp_get_attachment_image_url(4463));
		print_r($result);wp_die('Site is under development refer: Remal Mahmud');
	}


	public function do_stockDuplicate() {
		$args = ['message' => __('Something went wrong', 'stock-photo-duplicator'), 'hooks' => ['stock_duplication_failed']];
		// to do stuff.
		wp_send_json_error($args);
	}
	public function stock_upload() {
		$uploaded_file = $_FILES['file'];$args = ['message' => '', 'hooks' => ['uploadedsinglestockerror']];
		$args['type'] = isset($_GET['type'])?$_GET['type']:false;
		$args['index'] = isset($_GET['index'])?$_GET['index']:false;

		// Example: Move the uploaded file to the uploads directory
		$upload_dir = wp_upload_dir();
		$uploaded_filename = basename($uploaded_file['name']);
		$uploaded_filename = wp_unique_filename($upload_dir['path'], $uploaded_filename);
		$upload_path = $upload_dir['path'] . '/' . $uploaded_filename;
		if($args['type'] == 'downloadable') {
			try {
				$upload_path = pathinfo($upload_path, PATHINFO_DIRNAME).'/'.pathinfo($upload_path, PATHINFO_FILENAME).'.zip';
				$uploaded_file['path'] = $this->compress_files_to_zip($_FILES, $upload_path);
				$uploaded_file['name'] = $uploaded_filename = pathinfo($uploaded_filename, PATHINFO_FILENAME).'.zip';
				$uploaded_file['size'] = filesize($uploaded_file['tmp_name']);
				$uploaded_file['type'] = 'application/x-zip-compressed';
			} catch (\Exception $e) {
				$args['message'] = sprintf(__('Compression Error: %s', 'stock-photo-duplicator'),  $e->getMessage());
		  		wp_send_json_error($args);
			}
		}

		// wp_send_json_error($uploaded_file);
		
		try {
			$is_moved = ($args['type'] == 'downloadable')?true:move_uploaded_file($uploaded_file['tmp_name'], $upload_path);
			if($is_moved) {
				$attachment = array(
					'post_mime_type' => $uploaded_file['type'],
					'post_title' => sanitize_file_name($uploaded_filename),
					'post_content' => '',
					'post_status' => 'inherit',
				);
				$attach_id = wp_insert_attachment($attachment, $upload_path);

				// Apply watermark using the Easy Watermark plugin
				if(($args['type'] != 'downloadable') && function_exists('ew_apply_watermark')) {
					$apply_watermark_result = ew_apply_watermark($attach_id);
					if (is_wp_error($apply_watermark_result)) {
						// Handle any errors from the watermarking process
						$args['message'] = __('Error applying watermark', 'stock-photo-duplicator');
					}
				}


				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attach_data = wp_generate_attachment_metadata($attach_id, $upload_path);
				wp_update_attachment_metadata($attach_id, $attach_data);
				
				// $files = array_diff(scandir($upload_dir['path']), array('..', '.'));
				// usort($files, function($a, $b) use ($directory) {
				// 	$fileA = $directory . '/' . $a;
				// 	$fileB = $directory . '/' . $b;
				// 	return filemtime($fileB) - filemtime($fileA);
				// });
				// $args['dir'] = $upload_dir['path'];
				// $args['scan'] = $files;



				$args['message'] = __('Image uploaded successfully', 'stock-photo-duplicator');
				$args['attach_id'] = $attach_id;$args['hooks'] = ['uploadedsinglestocksuccess'];
				wp_send_json_success($args);
			} else {
				$args['message'] = __('Failed to upload image', 'stock-photo-duplicator');
				wp_send_json_error($args);
			}
		} catch (\Exception $e) {
			// Catch any exception thrown during move_uploaded_file()
			$args['message'] = sprintf(__('File Move Error: %s', 'stock-photo-duplicator'), $e->getMessage());
			wp_send_json_error($args);
		}
	}
	public function stock_finish() {
		$args = ['message' => __('Something went wrong!', 'stock-photo-duplicator'), 'hooks' => ['finishingstockfailed']];
		$dataset = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', stripslashes(html_entity_decode(isset($_POST['dataset'])?$_POST['dataset']:'{}'))), true);
		$post_id = $_POST['refered'];
		
		foreach($dataset as $i => $row) {
			$stockImages = $meta_keys = [];
			foreach($row as $item) {
				$stockImages[$item['type']] = isset($stockImages[$item['type']])?$stockImages[$item['type']]:[];
				$stockImages[$item['type']][] = ['id' => $item['attach_id']];
			}
			if(isset($stockImages['thumbnails'])) {$meta_keys['ulz_gallery'] = json_encode($stockImages['thumbnails']);}
			if(isset($stockImages['downloadable'])) {$meta_keys['ulz_download'] = json_encode($stockImages['downloadable']);}
			if(isset($stockImages['thumbnails']) && count($stockImages['thumbnails']) >= 1) {$meta_keys['_thumbnail_id'] = $stockImages['thumbnails'][0]['id'];}
			
			$new_post_id = $this->duplicate_post($post_id);
			
			if($new_post_id) {
				foreach($meta_keys as $meta_key => $meta_value) {
					$meta_id = update_post_meta($new_post_id, $meta_key, $meta_value);
				}
			}
		}
		$args['hooks'] = ['finishingstocksuccess'];
		$args['message'] = sprintf(__('Successfully generated (%s) duplicate of the post "%s".', 'stock-photo-duplicator'), count($dataset), get_the_title($post_id));
		wp_send_json_success($args);
	}
	public function compress_files_to_zip($files, $filepath) {
		if (!class_exists('ZipArchive')) {
		  throw new \Exception(__('ZipArchive class not available. Please make sure your PHP installation supports ZipArchive.', 'stock-photo-duplicator'));
		}
		// $zipFilename = tempnam(sys_get_temp_dir(), 'compressed'); //  . '.zip';
		$zipFilename = $filepath;
		$zip = new \ZipArchive();
		if ($zip->open($zipFilename, \ZipArchive::CREATE) !== true) {
		  throw new \Exception('Failed to create the zip archive.');
		}
		foreach($files as $i => $file) {
			if($file['error'] !== UPLOAD_ERR_OK) {continue;}
			$tmpFilePath = $file['tmp_name'];
			$zip->addFile($tmpFilePath, $file['name']);
		}
		$zip->close();
		return $zipFilename;
	}
	public function duplicate_post($post_id) {
		// Get the post to be duplicated
		$post = get_post($post_id);
	
		if (!$post) {
			return false;
		}
	
		// Prepare post data for duplication
		$post_data = array(
			'post_title' => $post->post_title, //  . ' (Copy)'
			'post_content' => $post->post_content,
			'post_excerpt' => $post->post_excerpt,
			'post_status' => 'publish', // Set the new post status (e.g., 'draft', 'publish', etc.)
			'post_type' => $post->post_type,
		);
	
		// Insert the duplicated post
		$new_post_id = wp_insert_post($post_data);
	
		if (!$new_post_id) {
			return false;
		}
	
		// Duplicate post meta data
		$post_meta = get_post_meta($post_id);
		foreach ($post_meta as $meta_key => $meta_value) {
			foreach ($meta_value as $value) {
				add_post_meta($new_post_id, $meta_key, $value);
			}
		}
	
		// Duplicate taxonomies (categories and tags)
		$taxonomies = get_object_taxonomies($post->post_type);
		foreach ($taxonomies as $taxonomy) {
			$terms = get_the_terms($post_id, $taxonomy);
			if (is_array($terms)) {
				foreach ($terms as $term) {
					wp_set_post_terms($new_post_id, $term->term_id, $taxonomy, true);
				}
			}
		}
	
		return $new_post_id;
	}
	/**
	 * Thumbnail meta: _thumbnail_id			value: 4463
	 * Gallery Image : ulz_gallery				value: [{"id":"4463"}]
	 * Downloadable  : ulz_download				value: [{"id":"4464"}]
	 */
}
