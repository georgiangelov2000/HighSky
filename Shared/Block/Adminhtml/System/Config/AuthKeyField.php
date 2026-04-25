<?php
declare(strict_types=1);

namespace HighSky\Shared\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Escaper;

class AuthKeyField extends Field
{
    public function __construct(
        Context $context,
        private readonly Escaper $esc,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        $fieldId      = $this->esc->escapeHtmlAttr($element->getHtmlId());
        $getTokenUrl  = $this->esc->escapeUrl($this->getUrl('highsky_shared/config/getAuthToken'));
        $saveTokenUrl = $this->esc->escapeUrl($this->getUrl('highsky_shared/config/saveAuthToken'));
        $websiteCode  = $this->esc->escapeHtmlAttr((string) $this->getRequest()->getParam('website', ''));
        $storeCode    = $this->esc->escapeHtmlAttr((string) $this->getRequest()->getParam('store', ''));

        return <<<HTML
{$element->getElementHtml()}
<div style="margin-top:8px;display:flex;gap:8px;align-items:center;">
    <button type="button" id="{$fieldId}_generate_btn" class="action-default scalable"
            onclick="highskyGenerateKey('{$fieldId}', '{$saveTokenUrl}', '{$websiteCode}', '{$storeCode}')">
        <span>Generate New Key</span>
    </button>
    <button type="button" class="action-default scalable"
            onclick="highskyCopyKey('{$fieldId}', '{$getTokenUrl}', '{$websiteCode}')">
        <span>Copy Current Key</span>
    </button>
    <span id="{$fieldId}_status" style="font-size:12px;"></span>
</div>
<script>
(function () {
    window.highskyGenerateKey = function (fieldId, saveUrl, websiteCode, storeCode) {
        var array = new Uint8Array(32);
        crypto.getRandomValues(array);
        var token = Array.from(array).map(function (b) {
            return b.toString(16).padStart(2, '0');
        }).join('');

        var btn = document.getElementById(fieldId + '_generate_btn');
        var status = document.getElementById(fieldId + '_status');
        btn.disabled = true;
        status.style.color = '#666';
        status.textContent = 'Saving…';

        var params = new URLSearchParams({token: token, form_key: window.FORM_KEY || ''});
        if (websiteCode) { params.append('website', websiteCode); }
        if (storeCode)   { params.append('store', storeCode); }

        fetch(saveUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        })
        .then(function (r) {
            if (!r.ok) { throw new Error('HTTP ' + r.status); }
            return r.json();
        })
        .then(function (data) {
            btn.disabled = false;
            if (data.success) {
                var field = document.getElementById(fieldId);
                if (field) {
                    field.value = token;
                    field.type  = 'text';
                }
                status.style.color = '#4caf50';
                status.textContent = '✔ New key saved and active.';
            } else {
                status.style.color = '#e22626';
                status.textContent = '✖ ' + (data.message || 'Save failed.');
            }
        })
        .catch(function (err) {
            btn.disabled = false;
            status.style.color = '#e22626';
            status.textContent = '✖ ' + (err.message || 'Request failed.');
        });
    };

    window.highskyCopyKey = function (fieldId, getUrl, websiteCode) {
        var field = document.getElementById(fieldId);
        var currentValue = field ? field.value : '';

        var doCopy = function (text) {
            navigator.clipboard.writeText(text).then(function () {
                alert('Key copied to clipboard.');
            }).catch(function () {
                prompt('Copy this key:', text);
            });
        };

        // If field is showing a real hex value (just generated), copy directly.
        if (currentValue && /^[0-9a-f]{64}$/.test(currentValue)) {
            doCopy(currentValue);
            return;
        }

        var params = websiteCode ? '?website=' + encodeURIComponent(websiteCode) : '';
        fetch(getUrl + params, {
            credentials: 'same-origin',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.token) {
                doCopy(data.token);
            } else {
                alert('No auth key is configured yet.');
            }
        })
        .catch(function () {
            alert('Could not retrieve the current key.');
        });
    };
}());
</script>
HTML;
    }
}
