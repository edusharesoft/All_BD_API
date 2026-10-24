# shurjoPay

Bangladesh Bank licensed gateway (online payments, QR, payment links).

- Developer docs: https://shurjopay.com.bd/developers/shurjopay-restapi
- Plugins/examples: https://github.com/shurjopay-plugins/sp-plugin-usage-examples
- **Doc status:** ✅ verified against official REST API v2 page

## Base URLs

| Env | URL |
|---|---|
| Sandbox | `https://sandbox.shurjopayment.com/api` |
| Live | `https://engine.shurjopayment.com/api` |

## Three APIs

1. **Get token** – `POST /get_token` with `username`, `password` (store credentials). Response includes `token`, `store_id`, `execute_url`.
2. **Initiate payment** – `POST /secret-pay` with `Authorization: Bearer {token}`. Body: `prefix`, `token`, `store_id`, `return_url`, `cancel_url`, `amount`, `order_id`, `currency`, customer fields (`customer_name`, `customer_phone`, `customer_address`, `customer_city`, `customer_post_code`, …). Response gives `checkout_url` (redirect the customer) and `sp_order_id`.
3. **Verify payment** – `POST /verification` with the token and `order_id` (the `sp_order_id`). Check `sp_code`/`bank_status` before fulfilling. A `/payment-status` endpoint also exists.

`prefix` and live credentials are given by shurjoPay after subscription. Sandbox credentials are listed in the official docs.
