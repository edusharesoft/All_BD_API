# Pathao Courier (Merchant API)

- Merchant panel: https://merchant.pathao.com/ → Developers API
- Official WooCommerce plugin: https://github.com/pathao-eng/courier-woocommerce-plugin
- **Doc status:** ⚠️ flow confirmed via several SDKs; **live base URL differs between sources** – see below.

## Base URLs
- Sandbox: `https://courier-api-sandbox.pathao.com`
- Live: your root README lists `https://api-hermes.pathao.com`; community SDKs use `https://courier-api.pathao.com`. Use the URL shown in your Pathao merchant panel.

## Auth (OAuth2 password grant)
`POST /aladdin/api/v1/issue-token` with `client_id`, `client_secret`, `username` (email), `password`, `grant_type=password`. Use the returned `access_token` as `Authorization: Bearer …`; refresh before expiry.

## Main operations
- Cities → zones → areas (reference data; cache it)
- Stores: list, create (new stores need ~1 hour approval before use)
- Orders: create, bulk create, order info
- Price calculation (price-plan)
- Webhooks: the dashboard verifies your URL by sending a UUID that you echo in header `X-Pathao-Merchant-Webhook-Integration-Secret`.

Exact paths and payloads: use the official plugin source or the PDF from the merchant panel.

## Community packages
`enuenan/pathao-courier` (Laravel), `mojnomiya/pathao-python`, `Sifat07/pathao-merchant-sdk` (Node/TS).
