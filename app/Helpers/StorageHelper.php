<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 05/03/2019
 * Time: 1:42 CH
 */

namespace App\Helpers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class StorageHelper
{
    const TYPES = [
        'logo' => 0,
        'banner' => 1,
        'product' => 2,
        'avatar' => 3,
    ];

    const TYPE_NAMES = [
        0 => 'logo',
        1 => 'banner',
        2 => 'product',
        3 => 'avatar',
    ];

    //<editor-fold desc="Base methods">

    /**
     * @param $sourcePath
     * @param $targetPath
     *
     * @return bool
     */
    public static function copy($sourcePath, $targetPath)
    {
        return self::disk()->copy($sourcePath, $targetPath);
    }

    /**
     * @return Filesystem
     */
    private static function disk($diskName = 'local')
    {
        return Storage::disk($diskName);
    }

    /**
     * @param $sourcePath
     * @param $targetPath
     *
     * @return bool
     */
    public static function move($sourcePath, $targetPath)
    {
        return self::disk()->move($sourcePath, $targetPath);
    }

    /**
     * @param $path
     *
     * @return string
     * @throws FileNotFoundException
     */
    public static function get($path)
    {
        return self::disk()->get($path);
    }

    public static function locatePath($path)
    {
        return self::disk()->path($path);
    }

    public static function imagePath($path)
    {
        return self::image_path($path);
    }

    public static function urlPath($path)
    {
        return self::disk()->url($path);
    }

    public static function download($path)
    {
        return self::disk()->download($path);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public static function mimeType($path)
    {
        return self::disk()->mimeType($path);
    }

    public static function saveProductPath($id, $file, $withTimestamp = false)
    {
        $fileName = $file->getClientOriginalName();
        if ($withTimestamp) $fileName = date('Ym/d/') . $fileName;
        $filePath = self::getProductPath($id);
        self::save($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    public static function getThumbPath($filePath = '', $fileName = '', $fullPath = true)
    {
        $imagePath = self::imagePath($filePath);
        if (file_exists($imagePath)){
            $name = pathinfo($fileName, PATHINFO_FILENAME);
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $thumbPath = $filePath . '/' . $name . THUMBNAIL_SUFFIX . '.' . $extension;
            $imageThumbPath = self::imagePath($thumbPath);
            if (!file_exists($imageThumbPath)){
                self::generateThumbnail($filePath);
            }

            return self::getFullPath($thumbPath, $fullPath);

        }
        return self::getFullPath($filePath, $fullPath);
    }

    public static function generateThumbnail($imagePath, $suffix = THUMBNAIL_SUFFIX, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT){
        $img = Image::make($imagePath);

        self::resizeAndSave($img, $suffix, $width, $height);
    }

    private static function resizeAndSave($img, $name, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT)
    {
        $img->fit($width, $height);
        // finally we save the image as a new file
        $savePath = $img->dirname . '/' . $img->filename . $name . '.' . $img->extension;

        $img->save($savePath);
    }
    //</editor-fold>

    //<editor-fold desc="Product">

    /**
     * @param string $bookId
     *
     * @param string $fileName
     *
     * @param bool $fullPath
     *
     * @return string
     * @throws Exception
     */
    public static function getProductPath($storeId, $id = '', $fileName = '', $fullPath = true)
    {
        $folderPath = self::getTypeFolder(self::TYPES['product']);
        return self::getFullPath($folderPath . $storeId . '/' . $id . '/' . $fileName, $fullPath);
    }

    /**
     * @param $type
     *
     * @return string
     * @throws Exception
     */
    private static function getTypeFolder($type)
    {
        if (!isset(self::TYPE_NAMES[$type]))
            throw new Exception('type is undefined');
        return self::TYPE_NAMES[$type] . '/';
    }

    public static function getFullPath($path, $fullPath)
    {
        return ($fullPath ? (url('images') . '/') : '') . $path;
    }

    /**
     * @param        $file
     * @param        $path
     * @param string $fileName
     * @param null $diskName
     */
    public static function saveLogo($file, $path, $fileName = '', $diskName = 'logo')
    {
        if (!$fileName) {
            $fileName = $file->getClientOriginalName();
        }
        self::disk($diskName)->putFileAs($path, $file, $fileName);

        return self::disk($diskName)->path($path . '/'.$fileName);
    }

    /**
     * @param        $file
     * @param        $path
     * @param string $fileName
     * @param null $diskName
     */
    public static function save($file, $path, $fileName = '', $diskName = null)
    {
        if (!$fileName) {
            $fileName = $file->getClientOriginalName();
        }
        self::disk($diskName)->putFileAs($path, $file, $fileName);

        return self::disk($diskName)->path($path . $fileName);
    }

    /**
     * @param      $id
     * @param      $file
     * @param bool $withTimestamp
     *
     * @return string
     * @throws Exception
     */
    public static function saveProductImage($storeId, $id, $file, $withTimestamp = false)
    {
        $fileName = $file->getClientOriginalName();
        if ($withTimestamp) $fileName = date('Ym/d/hi-') . $fileName;

        $filePath = self::getProductPath($storeId, $id, '', false);

        self::saveImage($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    /**
     * @param        $file
     * @param        $path
     * @param string $fileName
     */
    public static function saveImage($file, $path, $fileName = '')
    {
        if (!$fileName) {
            $fileName = $file->getClientOriginalName();
        }
        self::image_disk()->putFileAs($path, $file, $fileName);
    }

    /**
     * @return Filesystem
     */
    private static function image_disk()
    {
        return Storage::disk('image');
    }

    /**
     * @return Filesystem
     */
    private static function image_path($path)
    {
        return Storage::disk('image')->path($path);
    }

    public static function saveAvatarImage($id, $file)
    {
        $fileName = $file->getClientOriginalName();
        $filePath = self::getAvatarPath($id, false);
        self::saveImage($file, $filePath, $fileName);
        $path = $filePath . '/'. $fileName;

        StorageHelper::generateThumbnail(self::imagePath($path), '', THUMBNAIL_WIDTH, THUMBNAIL_WIDTH);

        return $path;
    }

    public static function saveLogoImage($file)
    {
        $fileName = $file->getClientOriginalName();
        $filePath = self::getLogoPath('', false);
        self::saveImage($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    /**
     * @param string $fileName
     * @param bool $fullPath
     *
     * @return string
     * @throws Exception
     */
    public static function getLogoPath($fileName = '', $fullPath = true)
    {
        $folderPath = self::getTypeFolder(self::TYPES['logo']);
        return self::getFullPath($folderPath . $fileName, $fullPath);
    }

    public static function getAvatarPath($fileName = '', $fullPath = true)
    {
        $folderPath = self::getTypeFolder(self::TYPES['avatar']);
        return self::getFullPath($folderPath . $fileName, $fullPath);
    }

    /**
     * @param      $file
     * @param bool $withTimestamp
     *
     * @return string
     * @throws Exception
     */
    public static function saveBannerImage($file, $withTimestamp = false)
    {
        $fileName = $file->getClientOriginalName();
        if ($withTimestamp) $fileName = date('Ym/d/hi-') . $fileName;
        $filePath = self::getBannerPath('', false);
        self::saveImage($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    /**
     * @param string $fileName
     *
     * @param bool $fullPath
     *
     * @return string
     * @throws Exception
     */
    public static function getBannerPath($fileName = '', $fullPath = true)
    {
        $folderPath = self::getTypeFolder(self::TYPES['banner']);
        return self::getFullPath($folderPath . $fileName, $fullPath);
    }
    //</editor-fold>

    //<editor-fold desc="Private methods">

    /**
     * @param $bookId
     * @param $fileName
     *
     * @return string
     * @throws FileNotFoundException
     * @internal param $bookPath
     */
    public static function getProduct($bookId, $fileName)
    {
        return self::disk()->get(self::getProductPath($bookId) . $fileName);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function getBannerImage($fileName = '')
    {
        return self::getBannerPath($fileName);
    }

    //</editor-fold>

}
