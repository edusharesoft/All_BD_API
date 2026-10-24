# bKash – Tokenized Checkout

- Developer portal: https://developer.bka.sh/
- Overview: https://developer.bka.sh/docs/tokenized-checkout-overview
- Official GitHub: https://github.com/bKash-developer
- **Doc status:** ⚠️ endpoint layout cross-checked from several community SDKs (bKash portal itself was not fetched). Confirm against the portal and your onboarding email.

## Environments

| Env | Base URL (v1.2.0-beta) |
|---|---|
| Sandbox | `https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized` |
| Live | `https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized` |

## Credentials (issued at merchant onboarding)
`app_key`, `app_secret`, `username`, `password`. Your site must have valid SSL. Set a 30 s timeout on all calls.

## Flow

1. **Grant token** – `POST {base}/checkout/token/grant` (username/password in headers, `app_key`/`app_secret` in body). Returns `id_token` and `refresh_token`.
2. **Refresh token** – when the token expires.
3. **Create payment** – `POST {base}/checkout/create` with amount, currency, `merchantInvoiceNumber`, `callbackURL`. Response contains `bkashURL` and `paymentID`; `statusCode == "0000"` only means the session was created. Redirect the customer to `bkashURL`.
4. **Callback** – bKash redirects the browser to your `callbackURL` with `paymentID` and a status.
5. **Execute payment** – `POST {base}/checkout/execute` with `paymentID`. Do this in the callback handler to confirm.
6. **Query payment** – `GET/POST` payment-status endpoint for reconciliation.
7. Also available: search transaction, refund, and IPN (SNS-signed) verification.

Notes: the `Authorization` header carries the raw `id_token` (no `Bearer ` prefix) alongside `X-APP-Key`. Treat a payment as completed only when Execute/Status returns a success `statusCode` **and** a completed transaction status; never trust the redirect alone.

## Sandbox test wallet (from a community project, not official – verify)
Wallet `01770618575`, OTP `123456`, PIN `12121`. Demo: https://merchantdemo.sandbox.bka.sh/frontend/checkout/version/1.2.0-beta

## Community packages
`karim-007/laravel-bkash-tokenize`, `irfan-chowdhury/bkash-tokenized-checkout`, `flutter_bkash`, PyPI `bkash-pgw-tokenized`.
