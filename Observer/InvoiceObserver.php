<?php
namespace Magechamp\CustomizeInvoice\Observer;
  
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order\ShipmentRepository as Shipment;

class InvoiceObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var Shipment
     */
    protected $shipmentFactory;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param Shipment $shipmentFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        Shipment $shipmentFactory
    ) {
        $this->_request = $request;
        $this->shipmentFactory = $shipmentFactory;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $invoice = $observer->getEvent()->getInvoice();
        $currentInvoiceItems = $this->_request->getPost('invoice');
        $currentShipmentId = $this->_request->getPost('linked_shipping_id');

        if (!$currentShipmentId) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please select atleast one shipment.'));
            return;
        }
        
        if (isset($currentInvoiceItems['comment_text'])) {
            unset($currentInvoiceItems['comment_text']);
        }

        $loadShipment = $this->shipmentFactory->get($currentShipmentId);
        $shipmentItems = [];
        
        foreach ($loadShipment->getItems() as $item) {
            $shipmentItems['items'][$item->getOrderItemId()] = (int) $item->getQty();
        }
        
        $allowInvoice = true;
        foreach ($currentInvoiceItems['items'] as $key => $value) {
            if ($value) {
                if (isset($shipmentItems['items'][$key]) && $shipmentItems['items'][$key] != $value) {
                    $allowInvoice = false;
                }
            }
        }

        if (!$allowInvoice) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Quantity of the calculated items are mismatched with the quantity of the shipments.'));
            return;
        }

        $invoice->setLinkedShippingId($currentShipmentId);
    }
}
