<?php
namespace jones\novaposhta\helpers;

/**
 * Class Formatter
 * @package jones\novaposhta\helpers
 */
trait Formatter
{
    /**
     * Convert amount to 0,00 format
     * @param $value
     * @return string
     */
    public function formatPrice($value)
    {
        return $this->format($value, 2);
    }

    /**
     * Convert weight to 0,000 format
     * @param $value
     * @return string
     */
    public function formatWeight($value)
    {
        return $this->format($value, 3);
    }

    /**
     * Convert float value within precision to comma separated string
     * @param $value
     * @param $precision
     * @return string
     */
    protected function format($value, $precision)
    {
        return str_replace('.', ',', sprintf('%.' . $precision . 'F', $value));
    }
}