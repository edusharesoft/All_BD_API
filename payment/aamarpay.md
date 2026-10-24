# aamarPay

Hosted checkout; cards and wallets.

- Docs: https://aamarpay.readme.io/reference/initiate-payment-json
- Official GitHub: https://github.com/aamarpay-dev
- **Doc status:** ✅ endpoints consistent across docs and multiple SDKs

## Endpoints

| Purpose | Sandbox | Live |
|---|---|---|
| Initiate payment (JSON) | `https://sandbox.aamarpay.com/jsonpost.php` | `https://secure.aamarpay.com/jsonpost.php` |
| Verify transaction | `https://sandbox.aamarpay.com/api/v1/trxcheck/request.php` | `https://secure.aamarpay.com/api/v1/trxcheck/request.php` |

## Flow
1. `POST` JSON to `jsonpost.php`: `store_id`, `signature_key`, `tran_id` (unique), `amount`, `currency`, `desc`, `cus_name`, `cus_email`, `cus_phone`, `success_url`, `fail_url`, `cancel_url`, `type: "json"`.
2. Response contains `payment_url` (`.../paynow.php?track=...`). Redirect the customer there.
3. aamarPay POSTs to your success/fail/cancel URL. Then **verify** server-side: `GET trxcheck/request.php?request_id={tran_id}&store_id=…&signature_key=…&type=json`.

## Sandbox
`store_id = aamarpaytest` with the public sandbox signature key shown in aamarPay's GitHub examples. In sandbox only the bKash method is available; live shows all methods.

## Laravel tip
The callback is a cross-site POST: exclude success/fail/cancel routes from CSRF verification and check `same_site` / `secure` in `config/session.php`.
