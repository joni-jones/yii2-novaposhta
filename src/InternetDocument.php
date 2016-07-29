<?php
namespace jones\novaposhta;

/**
 * Class InternetDocument
 * @package jones\novaposhta
 * @author sergii gamaiunov <hello@webkadabra.com?
 *
 * This model contain methods to create and configure orders
 */
final class InternetDocument extends Api
{
    const SCENARIO_DOCUMENT_PRICE = 'getDocumentPrice';

    /** @var string */
    public $CitySender;
    /** @var string */
    public $CityRecipient;
    /** @var string (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors) */
    public $ServiceType;
    /** @var integer|float  */
    public $Weight;
    /** @var float */
    public $Cost;
    /** @var string */
    public $DateTime;
    /** @var float volume of parcel in qubic centimeters (width * height * length / 1000000) */
    public $VolumeGeneral;

    public $SenderAddress;
    public $SeatsAmount;
    public $CargoType;

    public $PayerType = 'Sender';
    public $PaymentMethod = 'Cash';
    public $Sender;
    public $ContactSender;
    public $SendersPhone;
    public $RecipientAddress;
    public $Recipient;
    public $RecipientsPhone;
    public $ContactRecipient;
    public $Description;

    /**
     * Get price of delivery between two cities
     * @return mixed
     */
    function getDocumentPrice() {
        $query = [
            'CitySender' => $this->CitySender,
            'CityRecipient' => $this->CityRecipient,
            'ServiceType' => $this->ServiceType,
            'Weight' => $this->Weight,
            'Cost' => $this->Cost,
            'VolumeGeneral' => $this->VolumeGeneral
        ];
        return $this->call('getDocumentPrice', $query);
    }

    /**
     * Get price of delivery between two cities
     * @return mixed
     */
    function saveDocument() {
        $query = [
            'Sender' => $this->Sender,
            'PayerType' => $this->PayerType,
            'PaymentMethod' => $this->PaymentMethod,
            'CargoType' => $this->CargoType,
            'SenderAddress' => $this->SenderAddress,
            'ContactSender' => $this->ContactSender,
            'SendersPhone' => $this->SendersPhone,
            'RecipientAddress' => $this->RecipientAddress,
            'Recipient' => $this->Recipient,
            'ContactRecipient' => $this->ContactRecipient,
            'RecipientsPhone' => $this->RecipientsPhone,
            'Description' => $this->Description,

            'CitySender' => $this->CitySender,
            'CityRecipient' => $this->CityRecipient,
            'ServiceType' => $this->ServiceType,
            'Weight' => $this->Weight,
            'Cost' => $this->Cost,
            'VolumeGeneral' => $this->VolumeGeneral,
        ];
        return $this->call('save', $query);
    }
}
