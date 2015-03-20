<?php

class wysiwyg extends Admin_Controller {
	
	function upload_image() {
		$config ['upload_path'] = $this->config->item ( 'upload_server_path' ) . 'wysiwyg/images';
		$config ['allowed_types'] = 'gif|jpg|png';
		
		$this->load->library ( 'upload', $config );
		
		if (! $this->upload->do_upload ( 'file' )) {
			$error = array ('error' => $this->upload->display_errors () );
			echo stripslashes ( json_encode ( $error ) );
		} else {
			$data = $this->upload->data ();
			
			//upload successful generate a thumbnail
			$config ['image_library'] = 'gd2';
			$config ['source_image'] = base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/images/' . $data ['file_name'] );
			$config ['new_image'] = base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/thumbnails/' . $data ['file_name'] );
			$config ['create_thumb'] = FALSE;
			$config ['maintain_ratio'] = TRUE;
			$config ['width'] = 75;
			$config ['height'] = 50;
			
			$this->load->library ( 'image_lib', $config );
			
			$this->image_lib->resize ();
			
			$data = array ('filelink' => base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/images/' . $data ['file_name'] ), 'filename' => $data ['file_name'] );
			echo stripslashes ( json_encode ( $data ) );
		}
	}
	
	function upload_file() {
		$config ['upload_path'] = $this->config->item ( 'upload_server_path' ) . 'wysiwyg';
		$config ['allowed_types'] = '*';
		
		$this->load->library ( 'upload', $config );
		
		if (! $this->upload->do_upload ( 'file' )) {
			$error = array ('error' => $this->upload->display_errors () );
			echo stripslashes ( json_encode ( $error ) );
		} else {
			$data = $this->upload->data ();
			$data = array ('filelink' => base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/' . $data ['file_name'] ), 'filename' => $data ['file_name'] );
			echo stripslashes ( json_encode ( $data ) );
		}
	}
	
	function get_images() {
		$files = get_filenames ( base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/thumbnails' ) );
		
		$return = array ();
		foreach ( $files as $file ) {
			$return [] = array ('thumb' => base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/thumbnails/' . $file ), 'image' => base_url ( (! preg_match ( '/localhost/', current_url () ) ? '' : 'clairetnathalie/') . IMG_UPLOAD_FOLDER . 'wysiwyg/images/' . $file ) );
		}
		echo stripslashes ( json_encode ( $return ) );
	}

}