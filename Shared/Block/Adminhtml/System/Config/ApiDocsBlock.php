<?php
declare(strict_types=1);

namespace HighSky\Shared\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ApiDocsBlock extends Field
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private const ENDPOINTS = [
        [
            'name' => 'Product Sync',
            'method' => 'GET',
            'path' => '/V1/highsky/sync/products',
            'description' => 'Returns product catalog data for synchronisation.',
            'headers' => [
                [
                    'name' => 'X-SkyCommerce-Auth',
                    'type' => 'string',
                    'required' => 'No',
                    'default' => '—',
                    'validation' => 'Not used by this endpoint.',
                ],
            ],
            'path_params' => [],
            'query_params' => [
                [
                    'name' => 'per_page',
                    'type' => 'integer',
                    'required' => 'No',
                    'default' => '200',
                    'validation' => 'Must be an integer, minimum 1, values above 200 are capped to 200.',
                ],
                [
                    'name' => 'update_after',
                    'type' => 'string',
                    'required' => 'No',
                    'default' => '—',
                    'validation' => 'Must match Y-m-d H:i:s exactly.',
                ],
            ],
            'body_params' => [],
            'example' => 'GET /rest/V1/highsky/sync/products?per_page=50&update_after=2026-04-25%2000:00:00',
        ],
        [
            'name' => 'Tracking Orders',
            'method' => 'GET',
            'path' => '/V1/highsky/tracking/orders/:orderId',
            'description' => 'Returns a single order payload by Magento order increment ID.',
            'headers' => [
                [
                    'name' => 'X-SkyCommerce-Auth',
                    'type' => 'string',
                    'required' => 'Yes when Tracking Auth Required is enabled',
                    'default' => '—',
                    'validation' => 'Must exactly match the configured auth token.',
                ],
            ],
            'path_params' => [
                [
                    'name' => 'orderId',
                    'type' => 'string',
                    'required' => 'Yes',
                    'default' => '—',
                    'validation' => 'Trimmed value must not be empty.',
                ],
            ],
            'query_params' => [],
            'body_params' => [],
            'example' => 'GET /rest/V1/highsky/tracking/orders/000000001',
        ],
        [
            'name' => 'Tracking Users',
            'method' => 'GET',
            'path' => '/V1/highsky/tracking/users/:userId',
            'description' => 'Returns customer profile data and previous orders.',
            'headers' => [
                [
                    'name' => 'X-SkyCommerce-Auth',
                    'type' => 'string',
                    'required' => 'Yes when Tracking Auth Required is enabled',
                    'default' => '—',
                    'validation' => 'Must exactly match the configured auth token.',
                ],
            ],
            'path_params' => [
                [
                    'name' => 'userId',
                    'type' => 'integer',
                    'required' => 'Yes',
                    'default' => '—',
                    'validation' => 'Must be a positive integer. No session fallback is used.',
                ],
            ],
            'query_params' => [],
            'body_params' => [],
            'example' => 'GET /rest/V1/highsky/tracking/users/1',
        ],
        [
            'name' => 'Tracking Checkout',
            'method' => 'GET',
            'path' => '/V1/highsky/tracking/checkout/:sessionId',
            'description' => 'Returns quote/session tracking data.',
            'headers' => [
                [
                    'name' => 'X-SkyCommerce-Auth',
                    'type' => 'string',
                    'required' => 'Yes when Tracking Auth Required is enabled',
                    'default' => '—',
                    'validation' => 'Must exactly match the configured auth token.',
                ],
            ],
            'path_params' => [
                [
                    'name' => 'sessionId',
                    'type' => 'string',
                    'required' => 'Yes',
                    'default' => '—',
                    'validation' => 'Trimmed value must not be empty.',
                ],
            ],
            'query_params' => [],
            'body_params' => [],
            'example' => 'GET /rest/V1/highsky/tracking/checkout/I7eKc4F5cy2b5PBJWzLtxVbzEkfHOJRN',
        ],
        [
            'name' => 'Widget Startup',
            'method' => 'GET',
            'path' => '/V1/highsky/tracking/widget/startup',
            'description' => 'Returns whether the storefront widget should be considered enabled.',
            'headers' => [
                [
                    'name' => 'X-SkyCommerce-Auth',
                    'type' => 'string',
                    'required' => 'Yes when Tracking Auth Required is enabled',
                    'default' => '—',
                    'validation' => 'Must exactly match the configured auth token.',
                ],
            ],
            'path_params' => [],
            'query_params' => [],
            'body_params' => [],
            'example' => 'GET /rest/V1/highsky/tracking/widget/startup',
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
        $html = '<div style="display:flex;flex-direction:column;gap:16px">';

        foreach (self::ENDPOINTS as $endpoint) {
            $html .= '<div style="background:#fff;border:1px solid #dee2e6;border-radius:4px;overflow:hidden">';
            $html .= '<div style="padding:12px 16px;background:#f8f9fa;border-bottom:1px solid #dee2e6">';
            $html .= '<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">';
            $html .= '<code style="background:#e8f4e8;color:#2d6a2d;padding:2px 7px;border-radius:3px;font-size:12px;font-weight:600">'
                . htmlspecialchars((string) $endpoint['method']) . '</code>';
            $html .= '<strong style="font-size:14px">' . htmlspecialchars((string) $endpoint['name']) . '</strong>';
            $html .= '<code style="font-size:12px">' . htmlspecialchars((string) $endpoint['path']) . '</code>';
            $html .= '</div>';
            $html .= '<div style="margin-top:6px;color:#495057;font-size:13px">'
                . htmlspecialchars((string) $endpoint['description']) . '</div>';
            $html .= '<div style="margin-top:6px;color:#6c757d;font-size:12px">'
                . 'Example: <code>' . htmlspecialchars((string) $endpoint['example']) . '</code></div>';
            $html .= '</div>';

            $html .= '<div style="padding:12px 16px">';
            $html .= $this->renderParamTable('Headers', $endpoint['headers']);
            $html .= $this->renderParamTable('Path Parameters', $endpoint['path_params']);
            $html .= $this->renderParamTable('Query Parameters', $endpoint['query_params']);
            $html .= $this->renderParamTable('Body Parameters', $endpoint['body_params']);
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * @param array<int, array<string, string>> $rows
     */
    private function renderParamTable(string $title, array $rows): string
    {
        $html = '<div style="margin-bottom:14px">';
        $html .= '<div style="font-weight:600;font-size:13px;margin-bottom:6px;color:#343a40">'
            . htmlspecialchars($title) . '</div>';

        if ($rows === []) {
            $html .= '<div style="font-size:12px;color:#6c757d">None.</div>';
            $html .= '</div>';

            return $html;
        }

        $bodyRows = '';
        foreach ($rows as $row) {
            $bodyRows .= '<tr style="border-bottom:1px solid #eef1f4">'
                . '<td style="padding:8px 10px;font-family:monospace;font-size:12px;vertical-align:top">'
                . htmlspecialchars($row['name']) . '</td>'
                . '<td style="padding:8px 10px;font-size:12px;vertical-align:top">'
                . htmlspecialchars($row['type']) . '</td>'
                . '<td style="padding:8px 10px;font-size:12px;vertical-align:top">'
                . htmlspecialchars($row['required']) . '</td>'
                . '<td style="padding:8px 10px;font-size:12px;vertical-align:top">'
                . htmlspecialchars($row['default']) . '</td>'
                . '<td style="padding:8px 10px;font-size:12px;vertical-align:top;color:#495057">'
                . htmlspecialchars($row['validation']) . '</td>'
                . '</tr>';
        }

        $html .= '<table style="width:100%;border-collapse:collapse;border:1px solid #eef1f4">';
        $html .= '<thead><tr style="background:#fafbfc">'
            . '<th style="padding:8px 10px;text-align:left;font-size:11px;color:#6c757d">Name</th>'
            . '<th style="padding:8px 10px;text-align:left;font-size:11px;color:#6c757d">Type</th>'
            . '<th style="padding:8px 10px;text-align:left;font-size:11px;color:#6c757d">Required</th>'
            . '<th style="padding:8px 10px;text-align:left;font-size:11px;color:#6c757d">Default</th>'
            . '<th style="padding:8px 10px;text-align:left;font-size:11px;color:#6c757d">Validation</th>'
            . '</tr></thead>';
        $html .= '<tbody>' . $bodyRows . '</tbody></table>';
        $html .= '</div>';

        return $html;
    }
}
