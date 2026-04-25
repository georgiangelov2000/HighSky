<?php
declare(strict_types=1);

namespace HighSky\Shared\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ApiDocsBlock extends Field
{
    private const ENDPOINTS = [
        [
            'method'      => 'GET',
            'path'        => '/V1/highsky/sync/products',
            'description' => 'Returns product catalog data for synchronisation.',
            'auth'        => true,
        ],
        [
            'method'      => 'GET',
            'path'        => '/V1/highsky/tracking/widget/startup',
            'description' => 'Widget bootstrap check — returns whether the widget is active.',
            'auth'        => false,
        ],
        [
            'method'      => 'GET',
            'path'        => '/V1/highsky/tracking/checkout/:sessionId',
            'description' => 'Active checkout data for the given guest/customer session.',
            'auth'        => true,
        ],
        [
            'method'      => 'GET',
            'path'        => '/V1/highsky/tracking/orders/:orderId',
            'description' => 'Order details by order ID.',
            'auth'        => true,
        ],
        [
            'method'      => 'GET',
            'path'        => '/V1/highsky/tracking/users/:userId',
            'description' => 'Customer profile and order history.',
            'auth'        => true,
        ],
    ];

    public function render(AbstractElement $element): string
    {
        return '<tr id="row_' . $element->getHtmlId() . '">'
            . '<td colspan="4" style="padding:12px 0 4px">'
            . $this->getDocsHtml()
            . '</td></tr>';
    }

    private function getDocsHtml(): string
    {
        $rows = '';
        foreach (self::ENDPOINTS as $ep) {
            $authBadge = $ep['auth']
                ? '<code style="background:#fff3cd;color:#856404;padding:1px 6px;border-radius:3px;font-size:11px;">X-SkyCommerce-Auth</code>'
                : '<span style="color:#6c757d;font-size:12px;">—</span>';

            $rows .= '<tr style="border-bottom:1px solid #e9ecef">'
                . '<td style="padding:8px 12px;white-space:nowrap">'
                .   '<code style="background:#e8f4e8;color:#2d6a2d;padding:2px 7px;border-radius:3px;font-size:12px;font-weight:600">GET</code>'
                . '</td>'
                . '<td style="padding:8px 12px;font-family:monospace;font-size:13px;white-space:nowrap">'
                .   htmlspecialchars($ep['path'])
                . '</td>'
                . '<td style="padding:8px 12px;color:#495057;font-size:13px">'
                .   htmlspecialchars($ep['description'])
                . '</td>'
                . '<td style="padding:8px 12px">' . $authBadge . '</td>'
                . '</tr>';
        }

        return '<table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #dee2e6;border-radius:4px">'
            . '<thead>'
            . '<tr style="background:#f8f9fa;border-bottom:2px solid #dee2e6">'
            . '<th style="padding:8px 12px;text-align:left;font-size:12px;color:#6c757d;font-weight:600;white-space:nowrap">Method</th>'
            . '<th style="padding:8px 12px;text-align:left;font-size:12px;color:#6c757d;font-weight:600">Path</th>'
            . '<th style="padding:8px 12px;text-align:left;font-size:12px;color:#6c757d;font-weight:600">Description</th>'
            . '<th style="padding:8px 12px;text-align:left;font-size:12px;color:#6c757d;font-weight:600;white-space:nowrap">Required Header</th>'
            . '</tr>'
            . '</thead>'
            . '<tbody>' . $rows . '</tbody>'
            . '</table>';
    }
}
