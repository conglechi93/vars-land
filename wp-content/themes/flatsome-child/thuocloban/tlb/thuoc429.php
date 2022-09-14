<?php
putenv('GDFONTPATH=' . realpath('.'));
//Send a generated image to the browser 
create_image();
exit();

function create_image() {
    $trimStart = isset($_GET['trimStart']) && $_GET['trimStart'] > 0 ? $_GET['trimStart'] * 10 : 0;
    $rulerLength = isset($_GET['rulerLength']) && $_GET['rulerLength'] > 0 ? $_GET['rulerLength']*10 : 10000;
    //Set the image width and height 
    $width = $rulerLength;
    $height = 100;

    if ($trimStart >= $width) {
        $page = $trimStart / $width;
    } else {
        $page = 0;
    }

    if ($page > 0) {
        $am = ($page * $width) % 4290;
    }

    $image = ImageCreate($width, $height);
    $image1 = imagecreatefrompng("thuoc429_old.png");
    $image2 = imagecreatefrompng("thuoc.png");
    $black = ImageColorAllocate($image, 63, 63, 63);

    $out_img = imagecreatetruecolor($width, $height);
    $curr_x = 0;
    while ($curr_x < $width) {
        imagecopy($out_img, $image1, $page > 0 && $curr_x == 0 ? -$am : $curr_x, 0, 0, 0, imagesx($image1), imagesy($image1));
        $curr_x += $page > 0 && $curr_x == 0 ? imagesx($image1) - $am : imagesx($image1);
    }

    imagecopymerge($image, $out_img, 0, 0, 0, 0, $width, imagesy($image1), 100);
    $out_img2 = imagecreatetruecolor($width, $height);
    $curr_x = 0;
    while ($curr_x < $width) {
        imagecopy($out_img2, $image2, $curr_x, 0, 0, 0, imagesx($image2), imagesy($image2));
        $curr_x += imagesx($image2);
    }

    imagecopymerge($image, $out_img2, 0, 0, 0, 0, $width, imagesy($image2), 100);

    $font_file = 'arial.ttf';
    if ($page > 0) {
        imagefttext($image, 8, 0, -9, 25, $black, $font_file, (($page * $width) / imagesx($image2)) . ' cm');
    } else {
        imagefttext($image, 8, 0, 1, 25, $black, $font_file, 'cm');
    }

    $curr_x = 0;
    while ($curr_x <= $width) {
        imagefttext($image, 8, 0, $curr_x - 9, 25, $black, $font_file, ($curr_x > 0 ? (($page * $width + $curr_x) / imagesx($image2)) . ' cm' : ''));
        $curr_x += imagesx($image2);
    }

    //xuất ra file ảnh
    header("Content-Type: image/png");
    imagepng($image);
    ImageDestroy($image);
}