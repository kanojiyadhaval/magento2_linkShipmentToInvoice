<?php
namespace Magechamp\CustomizeInvoice\Block\Adminhtml\Order\View;


/**
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Info extends \Magento\Sales\Block\Adminhtml\Order\View\Info
{

    /**
     * Return an array of linked shipping ids with any of invoices
     * 
     * @return array
     */
    public function getLinkedShipmentIds()
    {
        $linkedShipmentIds = array();
        foreach ($this->getOrder()->getInvoiceCollection() as $invoice) {
            if ($invoice->getLinkedShippingId()) {
                $linkedShipmentIds[] = $invoice->getLinkedShippingId();
            }
        }
        return $linkedShipmentIds;
    }
}
