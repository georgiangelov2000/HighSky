# HighSky_Products

`HighSky_Products` is a Magento 2 module that exposes a REST API endpoint for product synchronization:

```text
GET /rest/highsky/v1/sync/products
```

The module uses Magento service contracts and DTOs, so the response is returned as a structured JSON object with named keys.

## What The Module Does

- exposes an anonymous Magento Web API endpoint for product sync
- validates and normalizes incoming request parameters
- returns paginated product data with pricing, category, image, and stock details
- uses DTO-based responses for predictable JSON output
- keeps pagination deterministic with `entity_id ASC` ordering

## Endpoint

```text
GET /rest/highsky/v1/sync/products
```

Configured in `etc/webapi.xml` and handled by `HighSky\Products\Api\ProductSyncInterface::execute()`.

## Query Parameters

| Parameter | Required | Type | Notes |
| --- | --- | --- | --- |
| `status` | Yes | `string` | Allowed values: `new`, `old` |
| `from` | No | `string` | Format: `Y-m-d H:i:s` |
| `to` | No | `string` | Format: `Y-m-d H:i:s` |
| `limit` | No | `int` | Default: `100`, max: `200` |
| `offset` | No | `int` | Default: `0` |

## Validation Rules

The API returns a Magento validation error when:

- `status` is missing or not `new` / `old`
- only one of `from` or `to` is provided
- `from` or `to` does not match `Y-m-d H:i:s`
- `from` is later than or equal to `to`
- `limit` is not an integer
- `limit` is less than `1`
- `offset` is not an integer
- `offset` is less than `0`

## Date Normalization

When both `from` and `to` are present:

- `from` is normalized to `00:00:00`
- `to` is normalized to `23:59:59`

Example:

- input `from=2026-03-24 15:30:00`
- normalized `from=2026-03-24 00:00:00`
- input `to=2026-03-26 09:10:00`
- normalized `to=2026-03-26 23:59:59`

When `from` and `to` are omitted, the service uses a fallback window covering the last `2` days.

## Pagination

- default `limit` is `100`
- maximum `limit` is `200`
- default `offset` is `0`
- results are ordered by `entity_id ASC`
- the repository loads `limit + 1` items internally to calculate `has_more`

The response includes:

- `count`
- `limit`
- `offset`
- `has_more`
- `next_offset`

## Response Shape

Top-level response keys:

- `status`
- `updated_after`
- `count`
- `limit`
- `offset`
- `has_more`
- `next_offset`
- `products`

`updated_after` is populated for `status=old` responses and otherwise remains `null`.

Each product item contains:

- `id`
- `sku`
- `name`
- `price`
- `special_price`
- `special_from_date`
- `special_to_date`
- `cost`
- `tax_class_id`
- `category_ids`
- `category_names`
- `created_at`
- `updated_at`
- `status`
- `visibility`
- `image_url`
- `qty`
- `is_in_stock`
- `manage_stock`
- `use_config_manage_stock`
- `backorders`
- `min_qty`
- `min_sale_qty`
- `max_sale_qty`
- `notify_stock_qty`
- `enable_qty_increments`
- `qty_increments`

## Example Requests

```bash
curl -H "Accept: application/json" "https://magento.test/rest/highsky/v1/sync/products?status=new"
```

```bash
curl -H "Accept: application/json" "https://magento.test/rest/highsky/v1/sync/products?status=old"
```

```bash
curl -H "Accept: application/json" "https://magento.test/rest/highsky/v1/sync/products?status=new&from=2026-03-24%2000:00:00&to=2026-03-26%2000:00:00&limit=50&offset=0"
```

## Example Response

```json
{
  "status": "new",
  "count": 1,
  "limit": 100,
  "offset": 0,
  "has_more": false,
  "next_offset": null,
  "products": [
    {
      "id": 1,
      "sku": "iphone-13-pro",
      "name": "iPhone 13 Pro",
      "price": 999,
      "special_price": null,
      "special_from_date": null,
      "special_to_date": null,
      "cost": null,
      "tax_class_id": 2,
      "category_ids": [3, 8],
      "category_names": ["Phones", "Apple"],
      "created_at": "2026-03-25 22:04:59",
      "updated_at": "2026-03-25 22:04:59",
      "status": 1,
      "visibility": 4,
      "image_url": "https://magento.test/media/catalog/product/i/p/iphone-13-pro.jpg",
      "qty": 10,
      "is_in_stock": true,
      "manage_stock": true,
      "use_config_manage_stock": true,
      "backorders": 0,
      "min_qty": 0,
      "min_sale_qty": 1,
      "max_sale_qty": 10000,
      "notify_stock_qty": 1,
      "enable_qty_increments": false,
      "qty_increments": null
    }
  ]
}
```

## Installation

Place the module in:

```text
app/code/HighSky/Products
```

Then run:

```bash
php bin/magento setup:upgrade
php bin/magento cache:flush
```

If needed, also run:

```bash
php bin/magento setup:di:compile
```

## Module Structure

```text
app/code/HighSky/Products/
├── Api/
├── etc/
├── Exception/
├── Model/
├── composer.json
├── README.md
└── registration.php
```

## Notes

- the REST route is defined in `etc/webapi.xml`
- the API entry point is `Model/ProductSync.php`
- response objects are built via `Model/Data/ProductSyncResponse.php`
- product item mapping is handled by `Model/Mapper/ProductMapper.php`
- category names are resolved through Magento category collections
- stock data is resolved through `StockRegistryInterface`
