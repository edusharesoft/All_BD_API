# SSLCOMMERZ

Aggregated gateway: cards, internet banking and MFS (bKash, Nagad, Rocket, Upay…) behind one API.

- Official docs: https://developer.sslcommerz.com/doc/v4/
- Sandbox signup: https://developer.sslcommerz.com/registration/
- Official GitHub: https://github.com/sslcommerz
- **Doc status:** ✅ endpoints verified against official docs (Oct 2026)

## Environments

| Env | Base URL |
|---|---|
| Sandbox | `https://sandbox.sslcommerz.com` (session API also at `https://sandbox-gw.sslcommerz.com`) |
| Live | `https://securepay.sslcommerz.com` |

TLS 1.2 or higher is required. Merchant panels: live `https://merchant.sslcommerz.com/`, test `https://sandbox.sslcommerz.com/manage/`.

## Flow (3 steps)

1. **Create session** – server-side `POST {base}/gwprocess/v4/api.php` (form-encoded). Response is JSON; redirect the customer to `GatewayPageURL`.
2. **IPN listener** – SSLCOMMERZ POSTs the result server-to-server to your `ipn_url` (set also in Merchant Panel → My Store → IPN Settings). Status is `VALID`, `FAILED` or `CANCELLED`.
3. **Callback redirect** – customer returns to your `success_url` / `fail_url` / `cancel_url`.

**Always call the Order Validation API** before fulfilling an order (check `tran_id`, amount and currency). If `risk_level = 1` and status is `VALID`, hold the order and verify the customer.

## Session request – main fields

`store_id`, `store_passwd`, `total_amount` (decimal 10,2), `currency` (BDT), `tran_id` (unique), `success_url`, `fail_url`, `cancel_url`, `ipn_url` (recommended), plus customer (`cus_name`, `cus_email`, `cus_add1`, `cus_city`, `cus_country`, `cus_phone`), `shipping_method`, `product_name`, `product_category`, `product_profile`.
Check the official parameter list for the full set; field requirements can change.

## Validation endpoints (from the community PHP library's config, matching official paths)

| Purpose | Path |
|---|---|
| Order validation | `/validator/api/validationserverAPI.php` (GET, `val_id`, `store_id`, `store_passwd`, `format=json`) |
| Transaction query / refund / refund status | `/validator/api/merchantTransIDvalidationAPI.php` |

## Credentials
`store_id` and `store_passwd` – sandbox ones arrive by email after sandbox signup; live ones are issued by SSLCOMMERZ.
