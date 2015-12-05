<?php
namespace jones\novaposhta;

/**
 * Class Address
 * This model contain methods to work with addresses
 *
 * @method array getAreas()
 */
final class Address extends Api
{
    /**
     * Filter for request
     * @var string
     */
    public $FindByString;

    /**
     * Unique identifier
     * @var string
     */
    public $Ref;

    /**
     * List of available address api methods
     * @var array
     */
    protected $methods = [
        'getAreas'
    ];

    /**
     * Get list of cities
     * @param string $filter
     * @return array|bool
     */
    public function getCities($filter = '')
    {
        $this->FindByString = $filter;
        return $this->call('getCities');
    }

    /**
     * Delete contractor address
     */
    public function delete($id)
    {
        $this->Ref = $id;
        return (boolean) $this->call('delete');
    }
}
