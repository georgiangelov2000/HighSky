# HighSky Magento Modules

Custom Magento 2 modules under `src/app/code/HighSky` that expose a product synchronisation API and a set of tracking APIs consumed by the HighSky platform.

## Short Overview

The HighSky codebase is split into small Magento modules by feature so product sync, tracking, shared infrastructure, and storefront widget behavior can evolve independently without mixing responsibilities in one module.

## StartUpWidget

`HighSky/StartUpWidget` owns the storefront widget bootstrap flow.

- exposes `GET /rest/V1/highsky/tracking/widget/startup`
- contains the frontend widget embed files: layout XML, template, JS, and view model
- decides whether the widget should start based on Magento configuration
- works together with `HighSky/Shared` for auth, config, and shared tracking behavior

## Module Layout

```text
src/app/code/HighSky/
├── Shared/           shared config, auth, DTOs, validation, setup, rate limiting
├── Products/         product synchronisation API
├── TrackingUsers/    user tracking API
├── TrackingOrders/   order tracking API
├── TrackingCheckout/ checkout tracking API
└── StartUpWidget/    storefront widget bootstrap API + frontend embed
```

## Folder Structure Guide

The codebase is split by feature first, then by responsibility inside each feature module. This keeps unrelated logic out of `Products` and makes each API easier to extend and test independently.

### Top-Level Modules

**`Shared/`**
- Cross-module code used by more than one HighSky module
- Central place for runtime config, admin config UI, authentication, validation, common DTOs, error handling, setup patches, and request throttling
- Other HighSky modules depend on this module

**`Products/`**
- Owns the product synchronisation API only
- Contains product-specific request validation, repository/query logic, mapping, and response building
- Does not contain tracking-specific logic

**`TrackingUsers/`**
- Owns the `tracking/users/{userId}` endpoint
- Contains user lookup, previous-order lookup, and user response formatting

**`TrackingOrders/`**
- Owns the `tracking/orders/{orderId}` endpoint
- Contains order lookup and order response formatting

**`TrackingCheckout/`**
- Owns the `tracking/checkout/{sessionId}` endpoint
- Contains quote/session lookup and checkout response formatting

**`StartUpWidget/`**
- Owns the `tracking/widget/startup` endpoint
- Owns the storefront widget embed files: layout XML, template, JS, and view model

### Common Folder Patterns Inside A Module

These folders follow standard Magento conventions across the HighSky modules:

**`Api/`**
- Service contracts and public PHP interfaces exposed by the module
- Defines what the module provides without coupling callers to concrete implementations

**`Model/`**
- Concrete business logic and runtime implementation
- Usually contains endpoint classes, services, repositories, config readers, validators, and response builders

**`Model/Service/`**
- Business logic orchestration
- Coordinates validation, repository access, and response building

**`Model/Repository/`**
- Data access and query logic
- Responsible for fetching Magento entities efficiently from the database layer

**`Model/Response/`**
- Response-building logic for endpoint payloads
- Converts Magento entities into the API response shape

**`Model/Config/`**
- Reads Magento configuration values from `core_config_data`
- Keeps config access out of controllers/endpoints and templates

**`etc/`**
- Magento wiring for the module
- Common files here include:
  - `module.xml` for module registration metadata
  - `di.xml` for dependency injection preferences
  - `webapi.xml` for REST endpoint routing
  - `config.xml` for default configuration values

**`etc/adminhtml/`**
- Admin-only configuration definitions such as `system.xml`
- Used for Magento admin settings screens

**`view/frontend/`**
- Frontend assets such as layout XML, templates, and JavaScript
- Only present where a module has storefront behavior, like `StartUpWidget`

**`Setup/Patch/`**
- Idempotent install/upgrade data patches
- Used for one-time setup behavior such as generating initial config values

**`Block/` and `ViewModel/`**
- Presentation helpers for Magento admin or storefront rendering
- `Block` is typically used for admin configuration rendering
- `ViewModel` is preferred for storefront template data access

### Module Responsibilities

**`HighSky/Shared`**
- Admin configuration and ACL
- `GeneralConfig` — master enable flag, tenant ID
- `TrackingConfig` — tracking feature flags, auth token, widget flags, order history limit
- `SecurityConfig` — request throttling configuration
- `AuthHeaderValidator` — validates `X-SkyCommerce-Auth` on every protected endpoint
- `TrackingAvailabilityValidator` — gates all tracking endpoints behind the module/tracking flags
- `RequestRateLimiter` — per-endpoint, per-IP abuse protection for the public APIs
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

## API Parameters

Base URL for examples:

```text
https://magento.test/rest/V1
```

All current HighSky APIs are `GET` endpoints.

- No endpoint currently accepts JSON request bodies
- No endpoint currently accepts form fields
- No endpoint currently accepts file uploads

### Product Sync

**Endpoint**

```text
GET /rest/V1/highsky/sync/products
```

**Accepted query parameters**

| Parameter | Type | Required | Default | Validation |
|-----------|------|----------|---------|------------|
| `per_page` | integer or integer-like string | No | `100` | Must be an integer, minimum `1`, values above `200` are capped to `200` |
| `update_after` | string | No | none | Must match exact format `Y-m-d H:i:s` |

**Accepted body parameters**

- none

**Parameter notes**

- `per_page` is normalized server-side
- `update_after` is optional; when omitted, all products are eligible
- filtering uses `created_at > update_after OR updated_at > update_after`

**Example requests**

Minimal:

```bash
curl -k "https://magento.test/rest/V1/highsky/sync/products"
```

With `per_page`:

```bash
curl -k "https://magento.test/rest/V1/highsky/sync/products?per_page=50"
```

With all supported parameters:

```bash
curl -k "https://magento.test/rest/V1/highsky/sync/products?per_page=50&update_after=2026-04-25%2000:00:00"
```

**Example response**

```json
{
  "per_page": 1,
  "total_count": 1,
  "total_pages": 1,
  "products": [
    {
      "id": 1,
      "sku": "highsky-tracking-seeded-virtual",
      "name": "HighSky Seeded Tracking Virtual Product",
      "price": "1999.00",
      "tax_class_id": 0,
      "category_names": [],
      "created_at": "2026-04-25 12:56:42",
      "updated_at": "2026-04-25 12:56:42",
      "status": "Enabled",
      "visibility": "Catalog, Search",
      "is_in_stock": true,
      "manage_stock": false,
      "use_config_manage_stock": false,
      "backorders": 0,
      "min_qty": 0,
      "min_sale_qty": 1,
      "max_sale_qty": 10000,
      "notify_stock_qty": 1,
      "enable_qty_increments": false,
      "qty_increments": 0,
      "variants": []
    }
  ]
}
```

**Validation error examples**

Invalid `per_page`:

```json
{
  "message": "The \"per_page\" parameter must be an integer."
}
```

Invalid `update_after`:

```json
{
  "message": "The \"update_after\" parameter must use the format Y-m-d H:i:s."
}
```

### Tracking Orders

**Endpoint**

```text
GET /rest/V1/highsky/tracking/orders/:orderId
```

**Accepted path parameters**

| Parameter | Type | Required | Default | Validation |
|-----------|------|----------|---------|------------|
| `orderId` | string | Yes | none | Trimmed value must not be empty |

**Accepted query parameters**

- none

**Accepted body parameters**

- none

**Headers**

| Header | Type | Required | Default | Validation |
|--------|------|----------|---------|------------|
| `X-SkyCommerce-Auth` | string | Yes when tracking auth is enabled | none | Must exactly match configured token |

**Example request**

```bash
curl -k \
  -H "X-SkyCommerce-Auth: skycommerce-dev-token" \
  "https://magento.test/rest/V1/highsky/tracking/orders/000000001"
```

**Example response**

```json
{
  "order_id": "000000001",
  "status": "pending",
  "created_at": "2026-04-25 12:57:08",
  "user_id": "1",
  "user_name": "HighSky Seeded",
  "user_email": "highsky.tracking.seeded@example.com",
  "user_telephone": "+35970012345",
  "grand_total": 1999,
  "currency_code": "USD",
  "items": [
    {
      "product_id": "1",
      "sku": "highsky-tracking-seeded-virtual",
      "name": "HighSky Seeded Tracking Virtual Product",
      "qty": 1
    }
  ]
}
```

### Tracking Users

**Endpoint**

```text
GET /rest/V1/highsky/tracking/users/:userId
```

**Accepted path parameters**

| Parameter | Type | Required | Default | Validation |
|-----------|------|----------|---------|------------|
| `userId` | integer path segment | Yes | none | Must be a positive integer |

**Accepted query parameters**

- none

**Accepted body parameters**

- none

**Headers**

| Header | Type | Required | Default | Validation |
|--------|------|----------|---------|------------|
| `X-SkyCommerce-Auth` | string | Yes when tracking auth is enabled | none | Must exactly match configured token |

**Parameter notes**

- this endpoint uses only the provided `userId`
- no guest session fallback is used
- no customer session inference is used

**Example request**

```bash
curl -k \
  -H "X-SkyCommerce-Auth: skycommerce-dev-token" \
  "https://magento.test/rest/V1/highsky/tracking/users/1"
```

**Example response**

```json
{
  "user_name": "HighSky Seeded",
  "user_email": "highsky.tracking.seeded@example.com",
  "user_telephone": "+35970012345",
  "previous_orders": [
    {
      "order_id": "000000001",
      "status": "pending",
      "created_at": "2026-04-25 12:57:08",
      "items": [
        {
          "product_id": "1",
          "sku": "highsky-tracking-seeded-virtual",
          "name": "HighSky Seeded Tracking Virtual Product",
          "qty": 1
        }
      ]
    }
  ]
}
```

### Tracking Checkout

**Endpoint**

```text
GET /rest/V1/highsky/tracking/checkout/:sessionId
```

**Accepted path parameters**

| Parameter | Type | Required | Default | Validation |
|-----------|------|----------|---------|------------|
| `sessionId` | string | Yes | none | Trimmed value must not be empty |

**Accepted query parameters**

- none

**Accepted body parameters**

- none

**Headers**

| Header | Type | Required | Default | Validation |
|--------|------|----------|---------|------------|
| `X-SkyCommerce-Auth` | string | Yes when tracking auth is enabled | none | Must exactly match configured token |

**Example request**

```bash
curl -k \
  -H "X-SkyCommerce-Auth: skycommerce-dev-token" \
  "https://magento.test/rest/V1/highsky/tracking/checkout/I7eKc4F5cy2b5PBJWzLtxVbzEkfHOJRN"
```

**Example response**

```json
{
  "session_id": "I7eKc4F5cy2b5PBJWzLtxVbzEkfHOJRN",
  "quote_id": "1",
  "is_active": true,
  "customer_id": "1",
  "customer_email": "highsky.tracking.seeded@example.com",
  "items_count": 1,
  "items_qty": 1,
  "grand_total": 1999,
  "currency_code": "USD",
  "items": [
    {
      "product_id": "1",
      "sku": "highsky-tracking-seeded-virtual",
      "name": "HighSky Seeded Tracking Virtual Product",
      "qty": 1
    }
  ]
}
```

### Widget Startup

**Endpoint**

```text
GET /rest/V1/highsky/tracking/widget/startup
```

**Accepted path parameters**

- none

**Accepted query parameters**

- none

**Accepted body parameters**

- none

**Headers**

| Header | Type | Required | Default | Validation |
|--------|------|----------|---------|------------|
| `X-SkyCommerce-Auth` | string | Yes when tracking auth is enabled | none | Must exactly match configured token |

**Example request**

```bash
curl -k \
  -H "X-SkyCommerce-Auth: skycommerce-dev-token" \
  "https://magento.test/rest/V1/highsky/tracking/widget/startup"
```

**Example responses**

Enabled:

```json
{
  "enabled": true
}
```

Disabled:

```json
{
  "enabled": false
}
```

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
