<?php
namespace Magechamp\CustomizeInvoice\Plugin;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Orders\Grid\Collection as SalesOrderInvoiceGridCollection;

class CustomizeInvoiceGrid
{
    private $messageManager;
    private $collection;

    public function __construct(MessageManager $messageManager,
        SalesOrderInvoiceGridCollection $collection
    ) {

        $this->messageManager = $messageManager;
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    ) {
        $result = $proceed($requestName);
        if ($requestName == 'sales_order_view_invoice_grid_data_source') {
            if ($result instanceof $this->collection) {
                $this->collection->getSelect()->join(
                    ["so" => $this->collection->getTable("sales_invoice")],
                    'main_table.entity_id = so.entity_id',
                    array('linked_shipping_id')
                );
                $this->collection->getSelect()->group('main_table.entity_id');
                $this->collection->addFilterToMap(
                    'order_id',
                    'main_table.order_id'
                );
                return $this->collection;
            }
        }
        return $result;
    }
}