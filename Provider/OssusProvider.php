<?php
namespace AppVentus\OssusBundle\Provider;


use Gedmo\Sluggable\Util\Urlizer;

class OssusProvider extends \Faker\Provider\Base
{
    const IMAGE_PROVIDER = 'lorempixel.com';

    private static $zipcodes = array(
        '44000', '44200', '44300', '44400', '44470',
        '85000', '49000', '35000', '68000'
    );

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function slug($text, $glue = '-')
    {
        return Urlizer::urlize($text, $glue);
    }

    public static function imageLink($width = 200, $height = 150, $type = '')
    {
        return sprintf("http://%s/%d/%d/%s", self::IMAGE_PROVIDER, $width, $height, $type);
    }

    public function image($dir, $width = null, $height = null, $type= '')
    {
        $width = $width ?: rand(100,300);
        $height = $height ?: rand(100,300);
        $fileName = uniqid("image_{$width}x{$height}_");
        $imageName = sprintf('%s/%s/%s.png', $this->container->getParameter('media_path'), $dir, $fileName);
        $image = sprintf("http://%s/%d/%d/%s", self::IMAGE_PROVIDER, $width, $height, $type);

        if (! is_dir(dirname($imageName))) {
            mkdir(dirname($imageName), 0777, true);
        }
        file_put_contents($imageName, file_get_contents($image));
        $imagePath = $dir . '/' . $fileName;
        
        return $imagePath;
    }

    public static function zipcode()
    {
        return static::randomElement(static::$zipcodes);
    }
}
