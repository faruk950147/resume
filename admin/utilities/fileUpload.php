<?php
    // File Upload Function
    function fileUpload($image){
        $path = '../assets/img/hero-section/';

        // Create folder if not exists
        if(!file_exists($path)){
            mkdir($path, 0755, true);
        }

        // Check for upload error
        if($image['error'] !== UPLOAD_ERR_OK){
            return ["error" => "File upload error!"];
        }

        // Allowed image extensions
        $allowed_ext = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed_ext)){
            return ["error" => "Invalid file type! Only JPG, PNG, WEBP allowed."];
        }

        // Check if uploaded file is valid image
        if(!getimagesize($image['tmp_name'])){
            return ["error" => "File is not a valid image!"];
        }

        // Size check (2MB limit)
        if($image['size'] > 2 * 1024 * 1024){
            return ["error" => "Image must be under 2MB!"];
        }

        // Generate unique filename
        $newName = bin2hex(random_bytes(16)) . "." . $ext;
        $file_path = $path . $newName;

        // Move uploaded file
        if(move_uploaded_file($image['tmp_name'], $file_path)){
            return ["name" => $newName];
        } else {
            return ["error" => "Failed to upload image!"];
        }
    }

?>