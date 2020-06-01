<?php
namespace Magechamp\CustomizeInvoice\Ui\Component\Listing\Column;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use Magento\Sales\Model\Order\ShipmentRepository as Shipment;
 
class LinkedShippingId extends Column
{
    /**
     * @var Shipment
     */
    protected $shipmentFactory;
 
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Shipment $shipmentFactory,
        array $components = [],
        array $data = [])
    {
        $this->shipmentFactory = $shipmentFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $currentShipmentId = $item[$this->getData('name')];
                if ($currentShipmentId) {
                    $loadShipment = $this->shipmentFactory->get($currentShipmentId);
                    $item[$this->getData('name')] = $loadShipment->getIncrementId();
                } else {
                    $item[$this->getData('name')] = '';
                }
            }
        }
        return $dataSource;
    }
}
