# HighSky Magento Modules

Custom Magento 2 modules under `src/app/code/HighSky` that expose a product synchronisation API and a set of tracking APIs consumed by the HighSky platform.

## Module Layout

```text
src/app/code/HighSky/
├── Shared/           shared config, auth, DTOs, validation, setup
├── Products/         product synchronisation API
├── TrackingUsers/    user tracking API
├── TrackingOrders/   order tracking API
├── TrackingCheckout/ checkout tracking API
└── StartUpWidget/    storefront widget bootstrap
```

### Module Responsibilities

**`HighSky/Shared`**
- Admin configuration and ACL
- `GeneralConfig` — master enable flag, tenant ID
- `TrackingConfig` — tracking feature flags, auth token, widget flags, order history limit
- `AuthHeaderValidator` — validates `X-SkyCommerce-Auth` on every protected endpoint
- `TrackingAvailabilityValidator` — gates all tracking endpoints behind the module/tracking flags
- Shared tracking DTOs and interfaces
- `TrackingRequestValidator` — path-param validation (userId, orderId, sessionId)
- `TrackingApiExceptionFactory` — standardised 400/401/403/404/500 responses
- `InitializeDefaultConfig` setup patch — generates the auth token on first install

**`HighSky/Products`**
- `GET /rest/V1/highsky/sync/products` — full product catalog export
- Product sync contracts, DTOs, repository, mapper, service, validator
- `ProductSyncAvailabilityValidator` — gates endpoint behind module + product synchronisation flags

**`HighSky/TrackingUsers`**
- `GET /rest/V1/highsky/tracking/users/:userId` — customer profile + order history

**`HighSky/TrackingOrders`**
- `GET /rest/V1/highsky/tracking/orders/:orderId` — single order detail

**`HighSky/TrackingCheckout`**
- `GET /rest/V1/highsky/tracking/checkout/:sessionId` — active checkout session data

**`HighSky/StartUpWidget`**
- `GET /rest/V1/highsky/tracking/widget/startup` — widget bootstrap check
- Storefront widget layout, template, and view model

## Public Endpoints

| Method | Path | Auth Header | Description |
|--------|------|-------------|-------------|
| GET | `/rest/V1/highsky/sync/products` | Required | Product catalog export |
| GET | `/rest/V1/highsky/tracking/widget/startup` | — | Widget bootstrap check |
| GET | `/rest/V1/highsky/tracking/checkout/:sessionId` | Required | Active checkout data |
| GET | `/rest/V1/highsky/tracking/orders/:orderId` | Required | Order detail |
| GET | `/rest/V1/highsky/tracking/users/:userId` | Required | Customer profile + history |

"Required" means the `X-SkyCommerce-Auth` header must be present when **Tracking Auth Required** is enabled in config. When the flag is off, the header is ignored.

## Configuration

**Admin path:**

```
Stores > Configuration > Catalog > HighSky Products
```

### Groups

| Group | Purpose |
|-------|---------|
| General | Master enable flag |
| Product | Product sync enable flag |
| Product API Columns | Column selection for the product synchronisation response |
| Settings | Tracking feature flags and order history limit |
| API Reference | Read-only endpoint reference table |
| Authentication | Auth token management |

### Where Settings Are Stored

Every value saved through `Stores > Configuration` is written to the `core_config_data` table in the Magento database.

**Table structure:**

| Column | Description |
|--------|-------------|
| `scope` | `default`, `websites`, or `stores` — the config level the value applies to |
| `scope_id` | `0` for default scope, website ID or store ID otherwise |
| `path` | Dot-separated config path, e.g. `highsky_products/tracking/enabled` |
| `value` | The stored value. Encrypted fields (like the auth token) are stored as `<version>:<key_id>:<ciphertext>` |

**Scoping rules:** Magento resolves config from the most specific scope outward — store → website → default. If a value is set at website scope it overrides the default for that website only, leaving other websites unaffected.

**Sensitive values:** Fields with `backend_model="Magento\Config\Model\Config\Backend\Encrypted"` (the auth token) are encrypted using Magento's `EncryptorInterface` before being written. The raw token is never stored in plaintext.

**Querying values directly:**

```sql
SELECT scope, scope_id, path, value
FROM core_config_data
WHERE path LIKE 'highsky_products/%'
   OR path LIKE 'skycommerce/%'
ORDER BY path, scope;
```

**Clearing cached config** after a direct database change:

```bash
php bin/magento cache:flush config
```

### Config Paths

| Path | Default | Purpose |
|------|---------|---------|
| `highsky_products/general/module_enabled` | `1` | Master flag — disables all endpoints and widget when off |
| `skycommerce/general/tenant_id` | — | Tenant ID injected into the storefront widget |
| `highsky_products/product_sync/enabled` | `1` | Enables `/sync/products` |
| `highsky_products/tracking/enabled` | `1` | Enables all tracking endpoints and widget |
| `highsky_products/tracking/auth_required` | `1` | Enforces `X-SkyCommerce-Auth` on all endpoints |
| `highsky_products/tracking/widget_enabled` | `0` | Enables storefront widget script injection |
| `highsky_products/tracking/user_order_history_limit` | `20` | Max orders returned by the users endpoint |
| `highsky_products/tracking_authentication/auth_token` | generated | Encrypted auth token — compared against `X-SkyCommerce-Auth` |

## X-SkyCommerce-Auth

All five endpoints require this header when `auth_required = 1`:

```
X-SkyCommerce-Auth: <token>
```

- Missing or wrong value → `401 Unauthorized`
- Module or tracking disabled → `403 Forbidden`
- Auth disabled in config → header is ignored

The token is stored encrypted in `core_config_data`. Manage it from:

```
Stores > Configuration > Catalog > HighSky Products > Authentication
```

The **Authentication** config group provides:
- **Generate New Key** — generates a cryptographically random 64-char hex token and saves it immediately (no page reload needed)
- **Copy Current Key** — copies the current decrypted token to the clipboard via AJAX

## Storefront Widget

The widget is injected on every storefront page via `before.body.end` when all three flags are on:
`module_enabled`, `tracking/enabled`, `tracking/widget_enabled`.

**Template:** `StartUpWidget/view/frontend/templates/tracking/widget.phtml`

The template outputs an inline `<script>` block that loads the widget from the HighSky CDN and calls `ChatWidget.init()` with the following config:

| Field | Value |
|-------|-------|
| `tenantId` | `skycommerce/general/tenant_id` from config |
| `apiBaseUrl` | `https://cfe.highsky.ai` (hardcoded) |
| `chat_init_url` | `window.location.href` at page load (client-side) |
| `cart_session_id` | `null` (reserved for v2) |
| `user_id` | customer ID from session; `null` for guests |
| `shop_platform` | `"magento2"` (hardcoded) |

The script URL is `https://cfe.highsky.ai/highsky-chatwidget.js?v=<YYYY-MM-DDTHH>` — the `?v=` parameter rotates every hour to cap browser cache TTL without touching cache-control headers.

The widget startup endpoint (`/tracking/widget/startup`) is called by the widget itself after load to confirm the widget is still active.

## Installation

```bash
php bin/magento module:enable \
  HighSky_Shared \
  HighSky_Products \
  HighSky_TrackingUsers \
  HighSky_TrackingOrders \
  HighSky_TrackingCheckout \
  HighSky_StartUpWidget

php bin/magento setup:upgrade
php bin/magento cache:flush
```

For production mode:

```bash
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

## Setup Behavior

`HighSky/Shared/Setup/Patch/Data/InitializeDefaultConfig` runs during `setup:upgrade` and generates an auth token at `highsky_products/tracking_authentication/auth_token` if none exists. Existing tokens are preserved. The patch is idempotent.

## Local Development

Seed script at `src/var/seed_highsky_tracking.php` creates or reuses:

- product SKU `highsky-tracking-seeded-virtual`
- customer `highsky.tracking.seeded@example.com`
- a masked checkout session
- an order for that customer
- tracking config values for local verification

Use the **Authentication** admin panel to generate and copy a local token for testing, or check the seeded token in `core_config_data` directly.
