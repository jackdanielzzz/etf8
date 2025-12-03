<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/../engine/app/functions.php');

    function loadJpgToServer($name) {
        $result = false;
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        $filename = $_FILES[$name]["name"];
        $filetype = $_FILES[$name]["type"];
        $filesize = $_FILES[$name]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 2MB maximum
        $maxsize = 10 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            resize_image($_FILES[$name]["tmp_name"], 330, 200);
            $imgFullPath = "/userfiles/news/" . $filename;
            $path = $_SERVER['DOCUMENT_ROOT'];
            if (move_uploaded_file($_FILES[$name]["tmp_name"], $path . $imgFullPath)) {
                $result = true;
            }
        }

    return $result;
    }

//    print_arr($_POST);
//    print_arr($_FILES);

if (!empty($_POST['id'])) {
    $newsImg = '';
    if (isset($_POST['news_img']))
        $newsImg = $_POST['news_img'];

    if ($_POST['id'] == 'new') {
        $id = null;
    }
    else {
        $id = $_POST['id'];
    }

    foreach ($_FILES as $key => $data) {
        if ( $key == 'news_img_new' ) {
            if ($data['error'] == 0)
                if (loadJpgToServer($key)) {
                    $newsImg = '/userfiles/news/' . $data['name'];
                }
        }
    }

    if ($_POST['id'] == 'new') {
        if (createNewNews($connect,
             str_replace("'", "\\'", $_POST['news_title_ru']),
             str_replace("'", "\\'", $_POST['news_title_en']),
             str_replace("'", "\\'", $_POST['text_short_ru']),
             str_replace("'", "\\'", $_POST['text_short_en']),
             str_replace("'", "\\'", $_POST['text_more_ru']),
             str_replace("'", "\\'", $_POST['text_more_en']),
            $newsImg, $_POST['news_date'])) {
            header('Location: /admin/news');
        }

    } else {
       if (editNews($connect,
           $_POST['id'],
           str_replace("'", "\\'", $_POST['news_title_ru']),
           str_replace("'", "\\'", $_POST['news_title_en']),
           str_replace("'", "\\'", $_POST['text_short_ru']),
           str_replace("'", "\\'", $_POST['text_short_en']),
           str_replace("'", "\\'", $_POST['text_more_ru']),
           str_replace("'", "\\'", $_POST['text_more_en']),
           $newsImg, $_POST['news_date'])) {

           header('Location: /admin/news');
       }

    }


}
