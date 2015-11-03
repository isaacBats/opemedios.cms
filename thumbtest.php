<?php

/*function _ckdir($fn) {
    if (strpos($fn,"/") !== false) {
        $p=substr($fn,0,strrpos($fn,"/"));
        if (!is_dir($p)) {
            _o("Mkdir: ".$p);
            mkdir($p,777,true);
        }
    }
}*/

function img_resizer($src,$quality,$w,$h,$saveas) {
    /* v2.5 with auto crop */
    $r=1;
    $e=strtolower(substr($src,strrpos($src,".")+1,3));
    if (($e == "jpg") || ($e == "jpe")) {
        $OldImage=ImageCreateFromJpeg($src) or $r=0;
    } elseif ($e == "gif") {
        $OldImage=ImageCreateFromGif($src) or $r=0;
    } elseif ($e == "bmp") {
        $OldImage=ImageCreateFromwbmp($src) or $r=0;
    } elseif ($e == "png") {
        $OldImage=ImageCreateFromPng($src) or $r=0;
    } else {
        die("Not a Valid Image! (".$e.") -- ".$src);$r=0;
    }
    if ($r) {
        list($width,$height)=getimagesize($src);
        // check if ratios match
        $_ratio=array($width/$height,$w/$h);
        if ($_ratio[0] != $_ratio[1]) { // crop image

            // find the right scale to use
            $_scale=min((float)($width/$w),(float)($height/$h));

            // coords to crop
            $cropX=(float)($width-($_scale*$w));
            $cropY=(float)($height-($_scale*$h));

            // cropped image size
            $cropW=(float)($width-$cropX);
            $cropH=(float)($height-$cropY);

            $crop=ImageCreateTrueColor($cropW,$cropH);
            // crop the middle part of the image to fit proportions
            ImageCopy(
                $crop,
                $OldImage,
                0,
                0,
                (int)($cropX/2),
                (int)($cropY/2),
                $cropW,
                $cropH
            );
        }

        // do the thumbnail
        $NewThumb=ImageCreateTrueColor($w,$h);
        if (isset($crop)) { // been cropped
            ImageCopyResampled(
                $NewThumb,
                $crop,
                0,
                0,
                0,
                0,
                $w,
                $h,
                $cropW,
                $cropH
            );
            ImageDestroy($crop);
        } else { // ratio match, regular resize
            ImageCopyResampled(
                $NewThumb,
                $OldImage,
                0,
                0,
                0,
                0,
                $w,
                $h,
                $width,
                $height
            );
        }
       // _ckdir($saveas);
        ImageJpeg($NewThumb,$saveas,$quality);
        ImageDestroy($NewThumb);
        ImageDestroy($OldImage);
    }
    return $r;
}

$hola = img_resizer("data/thumbs/GTAVbox_final.jpg",80,370,285,"data/thumbs/hola.jpg");

echo '<img src="data/thumbs/hola.jpg" />';

?>