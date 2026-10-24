# BulkSMSBD (bulksmsbd.net)

- Docs: https://bulksmsbd.net/bulksms-api-bangladesh.php
- **Doc status:** ✅ endpoints confirmed by the vendor's WooCommerce plugin; param names from SDKs
- Not to be confused with BulkSMSDhaka (bulksmsdhaka.net) or BulkSMS Bangladesh (bulksmsbd.com).

## Endpoints
| Purpose | URL |
|---|---|
| Send SMS (one-to-many; many-to-many via JSON) | `https://bulksmsbd.net/api/smsapi` |
| Balance | `https://bulksmsbd.net/api/getBalanceApi` (`api_key`) |

Main params for `smsapi`: `api_key`, `senderid` (approved), `number` (format `8801XXXXXXXXX`, comma-separated for many), `message`, `type=text`. OTP-specific helpers exist in the SDKs.

## Gotchas
- **IP whitelisting** must be disabled in the dashboard (OTP-confirmed) if your server IP isn't registered.
- Common response codes: `1001` invalid number, `1013`/`1014`/`1015` sender ID / gateway not found for the API key. Full list is in the vendor docs.
