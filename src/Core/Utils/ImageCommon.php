<?php

declare(strict_types=1);

namespace Src\Core\Utils;

use Imagick;
use ImagickException;

class ImageCommon
{
    /**
     * @param  string  $image_path
     * @param  int|float  $width
     * @param  int|float  $height
     * @param  int  $filter_type
     * @param  int|float  $blur
     * @param  bool  $best_fit
     * @param  bool  $crop_zoom
     * @return string
     * @throws ImagickException
     */
    public function resizeImage(
        string $image_path,
        int|float $width,
        int|float $height,
        int $filter_type = Imagick::FILTER_LANCZOS,
        int|float $blur = 1,
        bool $best_fit = false,
        bool $crop_zoom = true
    ): string {
        $imagick = new Imagick($image_path);

        $imagick->resizeImage($width, $height, $filter_type, $blur, $best_fit);

        $crop_width = $imagick->getImageWidth();
        $crop_height = $imagick->getImageHeight();

        if ($crop_zoom) {
            $new_width = $crop_width / 2;
            $new_height = $crop_height / 2;

            $imagick->cropImage($new_width, $new_height, ($crop_width - $new_width) / 2, ($crop_height - $new_height) / 2);
            $imagick->scaleImage($imagick->getImageWidth() * 4, $imagick->getImageHeight() * 4);
        }

        return $imagick->getImageFilename();
    }
}
