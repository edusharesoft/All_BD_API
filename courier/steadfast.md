# Steadfast Courier

- Site: https://steadfast.com.bd/ · WordPress plugin: https://wordpress.org/plugins/steadfast-api/
- **Doc status:** ✅ base URL, auth headers and endpoints confirmed by the official plugin page and SDKs

## Basics
- Base URL: `https://portal.packzy.com/api/v1`
- Auth: static headers on every request – `Api-Key: …` and `Secret-Key: …` (no tokens). Credentials come from your merchant account / Steadfast support.
- Content type: `application/json`

## Endpoints

| Purpose | Request |
|---|---|
| Create order | `POST /create_order` – `invoice`, `recipient_name`, `recipient_phone`, `recipient_address`, `cod_amount`, optional `note` |
| Status by consignment ID | `GET /status_by_cid/{id}` |
| Status by invoice | `GET /status_by_invoice/{invoice}` |
| Status by tracking code | `GET /status_by_trackingcode/{code}` |
| Balance | `GET /get_balance` |

Bulk orders and return requests also exist; see the merchant API document from Steadfast. Parameter details beyond those above come from SDK usage – confirm against the official PDF/panel.
