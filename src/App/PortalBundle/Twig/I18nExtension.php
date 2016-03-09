<?php

namespace App\PortalBundle\Twig;

class I18nExtension extends \Twig_Extension
{
    private static $fallback = 'fr';
    private static $format   = array(
        'en'    => ':',
        'fr'    => 'h',
    );

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('formatTime', array($this ,'formatTime')),
            new \Twig_SimpleFunction('formatSingleDayPart', array($this ,'formatSingleDayPart')),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public static function get($locale)
    {
        return (self::$format[$locale])? : self::$format[self::$fallback];
    }

    public static function getDefault()
    {
        return self::get(\Locale::getDefault());
    }

    public static function formatTime($time, $format = ':')
    {
        $timeParts       = explode($format, $time);
        $transformedTime = $timeParts[0] . self::getDefault() . $timeParts[1];

        return $transformedTime;
    }

    public static function formatSingleDayPart($daypart)
    {
        $daypartParts       = explode('-', $daypart);
        $tempDayPart        = self::constructDayPart($daypartParts);
        $daypartTransformed = implode('-', $tempDayPart);

        return $daypartTransformed;
    }

    private static function constructDayPart($daypartParts)
    {
        $tempDayPart = array();

        foreach ($daypartParts as $hour) {
            $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) . self::getDefault();

            if ('en' == \Locale::getDefault()) {
                $hour .= '00';
            }
            $tempDayPart[] = $hour;
        }

        return $tempDayPart;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'i18n_extension';
    }

}