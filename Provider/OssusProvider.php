<?php
namespace AppVentus\OssusBundle\Provider;

use Gedmo\Sluggable\Util\Urlizer;

/**
 * OssusProvider
 */
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

    /**
     * Build a slug from a given text
     * @param string $text
     * @param string $glue
     *
     * @return string The sluggified chain
     */
    public static function slug($text, $glue = '-')
    {
        return Urlizer::urlize($text, $glue);
    }

    /**
     * build a sample image URL for given dimension and type
     *
     * @param string $width  The image width
     * @param string $height The image height
     * @param string $type   Could be one the following : abstract | animals | business | cats | city | food | nightlife | fashion | people | nature | sports | technics | transport
     *
     * @return string The image url
     */
    public static function imageLink($width = 200, $height = 150, $type = '')
    {
        return sprintf('http://%s/%d/%d/%s', self::IMAGE_PROVIDER, $width, $height, $type);
    }

    /**
     * Find a sample image for given dimension and type and place it in the good directory
     *
     * @param string $dir    The image upload final directory
     * @param string $width  The image width
     * @param string $height The image height
     * @param string $type   Could be one the following : abstract | animals | business | cats | city | food | nightlife | fashion | people | nature | sports | technics | transport
     *
     * @return string The image url
     */
    public function image($dir, $width = null, $height = null, $type= '')
    {
        $width = $width ? $width : rand(100, 1000);
        $height = $height ? $height : rand(100, 1000);
        $fileName = uniqid('image_'.$width.'x'.$height.'_').'.png';
        $baseDir = $this->container->getParameter('av_ossus.media_path');
        $imageName = sprintf('%s/%s/%s', $baseDir, $dir, $fileName);
        $image = sprintf('http://%s/%d/%d/%s', self::IMAGE_PROVIDER, $width, $height, $type);

        if (! is_dir(dirname($imageName))) {
            mkdir(dirname($imageName), 0777, true);
        }
        file_put_contents($imageName, file_get_contents($image));
        $baseDir = str_replace($this->container->getParameter('kernel.root_dir').'/../web/', '', $baseDir);
        $imagePath = $baseDir . '/' . $dir . '/' . $fileName;

        return $imagePath;
    }

    /**
     * Return a sample zipcode
     * @return integer
     */
    public static function zipcode()
    {
        return static::randomElement(static::$zipcodes);
    }
}
