# HighSky_Products

`HighSky_Products` exposes a Magento 2 REST API endpoint for product synchronization:

```text
GET /rest/V1/highsky/sync/products
```

The module uses Magento service contracts and DTOs, so the response is returned as a structured JSON object with named keys.

## What The Module Does

- exposes an anonymous Magento Web API endpoint for product sync
- validates and normalizes incoming request parameters
- filters products by a single `update_after` timestamp
- returns paginated product data with pricing, category, image, and stock details
- uses DTO-based responses for predictable JSON output
- keeps pagination deterministic with `updated_at ASC, entity_id ASC` ordering

## Endpoint

```text
GET /rest/V1/highsky/sync/products
```

Configured in `etc/webapi.xml` and handled by `HighSky\Products\Api\ProductSyncInterface::execute()`.

## Query Parameters

| Parameter | Required | Type | Notes |
| --- | --- | --- | --- |
| `per_page` | No | `int` | Default: `100`, max: `200` |
| `update_after` | No | `string` | Format: `Y-m-d H:i:s` |

## Admin Configuration

In Magento Admin, go to:

```text
Stores > Configuration > Catalog > HighSky Products
```

There you can:

- choose which standard API columns are included in the response
- add extra Magento product attribute codes as comma-separated custom columns

This allows the API payload to evolve without changing the endpoint contract each time a client needs one more attribute.

## Parameter Behavior

### `update_after`

When `update_after` is provided, the endpoint returns products where either:

- `created_at > update_after`
- `updated_at > update_after`

This includes both:

- products newly created after the timestamp
- products that already existed but were updated after the timestamp

When `update_after` is omitted, the endpoint returns all products.

### `per_page`

`per_page` defines how many products are returned in the response page.

The response uses the full filtered result set to calculate:

- `total_count`
- `total_pages`
- `current_page`

## Validation Rules

The API returns a Magento validation error when:

- `per_page` is not an integer
- `per_page` is less than `1`
- `update_after` does not match `Y-m-d H:i:s`

## Pagination

- default `per_page` is `100`
- maximum `per_page` is `200`
- results are ordered by `updated_at ASC, entity_id ASC`
- `total_count` is calculated from all products matching `update_after`
- `current_page` is currently fixed to `1` because the endpoint contract only accepts `per_page` and `update_after`

## Response Shape

Top-level response keys:

- `update_after`
- `per_page`
- `current_page`
- `total_count`
- `total_pages`
- `products`

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
- `variants`

## Example Requests

```bash
curl -H "Accept: application/json" "https://magento.test/rest/V1/highsky/sync/products"
```

```bash
curl -H "Accept: application/json" "https://magento.test/rest/V1/highsky/sync/products?per_page=50"
```

```bash
curl -H "Accept: application/json" "https://magento.test/rest/V1/highsky/sync/products?per_page=50&update_after=2026-03-24%2000:00:00"
```

## Example Response

```json
{
  "update_after": "2026-03-24 00:00:00",
  "per_page": 50,
  "current_page": 1,
  "total_count": 1,
  "total_pages": 1,
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
      "qty_increments": null,
      "variants": []
    }
  ]
}
```

## Installation

Install the module in your Magento project under:

```text
app/code/HighSky/Products
```

From the Magento root, enable the module and apply its setup changes:

```bash
php bin/magento module:enable HighSky_Products
php bin/magento setup:upgrade
php bin/magento cache:flush
```

If your project is in production mode or needs regenerated code, also run:

```bash
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

### Setup In A Magento Project Using This Docker Repo

If you are using the `markshust/docker-magento` setup in this repository, the Magento root is inside `compose/src`.

From the repository root:

```bash
cd compose
bin/start
bin/magento module:enable HighSky_Products
bin/magento setup:upgrade
bin/magento cache:flush
```

If you copied or updated the module files from the host and need to sync them into the running container, run:

```bash
cd compose
bin/copytocontainer app/code/HighSky/Products
```

You can confirm the module is active with:

```bash
cd compose
bin/magento module:status HighSky_Products
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
