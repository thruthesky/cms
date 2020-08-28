<?php


class ApiFile extends ApiLibrary
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Upload
     *
     * upload image and generates resized image.
     */
    public function uploadFile($in)
    {

        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;

        if (empty($_FILES)) {
            return ERROR_NO_FILE_PROVIDED;
        }


        print_r($_FILES);
        $file = $_FILES['userfile'];
        if ($file['error']) {
            $msg = $this->fileUploadErrorCodeToMessage($file['error']);
            return ERROR_FILE_UPLOAD_ERROR;
//            $this->error(ERROR_FILE_UPLOAD_ERROR, ['reason' => "FILE_UPLOAD_ERROR CODE: " . $file['error'] . " MESSAGE: $msg"]);
        }

        // Prepare to save
        $file_type = wp_check_filetype(basename($file["name"]), null); // get file type
        $file_name = $this->get_safe_filename($file["name"]); // get save filename to save -----
        $dir = wp_upload_dir(); // Get WordPress upload folder.
        $file_path = $dir['path'] . "/$file_name"; // Get Path of uploaded file. ----
        $file_url = $dir['url'] . "/$file_name"; // Get Path of uploaded file.

        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            return ERROR_FILE_MOVE;
        }

        // Create a post of attachment type.
        $attachment = array(
            'guid'              => $file_url,
            'post_author'       => wp_get_current_user()->ID,
            'post_mime_type'    => $file_type['type'],
            'post_name'         => $file['name'],
            'post_title'        => $file_name,
            'post_content'      => '',
            'post_status'       => 'inherit',
            // 'post_parent'       => 8
        );

        // xlog($attachment);
        // This does not upload a file but creates a 'attachment' post type in wp_posts.
        $attach_id = @wp_insert_attachment($attachment, $file_name);
        if ($attach_id == 0 || is_wp_error($attach_id)) {
            return ERROR_FAILED_TO_ATTACH_UPLOADED_FILE_TO_A_POST;
        }

//        xlog("attach_id: $attach_id");
        update_attached_file($attach_id, $file_path); // update post_meta for the use of get_attached_file(), get_attachment_url();
        require_once ABSPATH . 'wp-admin/includes/image.php';

        /**
         * Generating attachment metadata and Resized images derived from the original (thumbnails).
         *
         * @note - `wp_generate_attachment_metadata` will also generates different sizes of the image.
         *          meaning, it will generate several more files.
         *
         * @note - it will only generates sizes according to default WP sizes and custom sizes added on functions.php.
         *
         */
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id,  $attach_data);

        return $this->get_uploaded_file($attach_id);
    }


    /**
     * Deletes a file
     *
     *
     *  - file record information on success.
     *  - ErrorObject otherwise.
     */
    public function deleteFile($in)
    {
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;

        if (!$in['ID']) return ERROR_FILE_ID_NOT_PROVIDED;

        if (admin() || $this->isMyFile(in('ID'))) {
            // pass
        } else {
            return ERROR_NOT_YOUR_FILE;
        }

        /// get file
        $file = get_post($in['ID']);

        /**
         * For comment files, the post type is COMMENT_ATTACHMENT and it cannot be deleted.
         * @see attachFiles() for details.
         */
        global $wpdb;
        $result = $wpdb->update($wpdb->posts, ['post_type' => 'attachment'], ['ID' => $in['ID']]);

        $re = wp_delete_attachment($in['ID'], true);

        $this->updateFirstImage($file->post_parent);

        if ($re) return ['ID' => $re->ID];
        else return ERROR_FAILED_TO_DELETE_FILE;
    }

    public function customFileUpload($in)
    {
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;
        if (empty($_FILES)) {
            return ERROR_NO_FILE_PROVIDED;
        }
//        xlog($_FILES);
//        xlog($_REQUEST);
        $file = $_FILES['userfile'];
        if ($file['error']) {
            $msg = $this->fileUploadErrorCodeToMessage($file['error']);
                return ERROR_FILE_UPLOAD_ERROR;
//            $this->error(ERROR_FILE_UPLOAD_ERROR, ['reason' => "FILE_UPLOAD_ERROR CODE: " . $file['error'] . " MESSAGE: $msg"]);
        }

        $filename = md5(time() . $file['name'] . $_SERVER['REMOTE_ADDR']) . '.' . $this->get_extension($file['name']);

        $dist_path = CUSTOM_UPLOAD_DIR . "/$filename";
        if (!move_uploaded_file($file['tmp_name'], $dist_path)) {
            return ERROR_FILE_MOVE;
        }
        xlog($dist_path);
        // $this->success(['url' => home_url() . "/$dist_path"]);

        $delimiter = '/wp-content/';
        $paths = explode($delimiter, $dist_path);
        return ['url' => home_url() . $delimiter . end($paths)];
    }

    public function customFileDelete($in)
    {
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;
        $filename = $in['file'];

        // xlog($filename);

        $dist_path = CUSTOM_UPLOAD_DIR . "/$filename";
        // if ( !file_exists($dist_path) ) {
        //     $this->error(ERROR_FILE_NOT_EXIST);
        // }

        // xlog($dist_path);

        @unlink($dist_path);
        if (file_exists($dist_path)) {
            return ERROR_FAILED_TO_DELETE_FILE;
        } else {
            return ['file' => $filename];
        }
    }


    private function get_extension($name)
    {
        $arr = explode('.', $name);
        if (count($arr) < 2) return '';
        else return array_pop($arr);
    }


}
