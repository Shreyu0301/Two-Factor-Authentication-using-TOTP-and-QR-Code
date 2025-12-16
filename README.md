# Minimal TOTP 2FA Demo

## Quick start

1. Install composer dependencies:
   ```
   composer install
   ```

2. Initialize database:
   ```
   php scripts/init_db.php
   ```

3. Serve the `public` folder:
   ```
   php -S localhost:8000 -t public
   ```

4. Open http://localhost:8000
   - email: `user@example.com`
   - password: `password123`

When you first log in you'll be prompted to set up 2FA (scan the QR and verify the code).

Security notes are in the conversation. This is a demo — do not use as-is in production.

## Demo Screenshots

Place the screenshots under `docs/screenshots/` and they will render below. I added placeholders you can replace by saving your images with these names.

1. Login screen

![Login Screen](docs/screenshots/login.png)

2. 2FA verification (QR + code input)

![2FA Verify](docs/screenshots/verify-qr.png)

If you have multiple angles, you can also add:

- ![Login (alt)](docs/screenshots/login-2.png)
- ![Verify (alt)](docs/screenshots/verify-qr-2.png)

### How to add your screenshots
- Create folder: `docs/screenshots/`
- Save your images there using the names above (or adjust the links here)
- Commit and push:
   ```
   git add docs/screenshots/*.png
   git commit -m "Add demo screenshots"
   git push
   ```

---

## Technical Documentation: Privacy & Security

- **Local Processing:** All authentication (QR generation, code verification) runs locally in your PHP app. No OTP secrets are sent to external services.
- **Secrets Storage:** The TOTP secret is stored per-user in the `users.twofa_secret` column (SQLite). Use file permissions to protect `data/app.db`.
- **Transport Security:** In production, serve over HTTPS to protect credentials and OTP codes.
- **Session Security:** Uses `httponly` and `samesite=Lax` cookies; sessions are regenerated on sensitive flows.
- **Timeouts:** `includes/session_bootstrap.php` demonstrates inactivity and absolute session timeouts for improved security.

## Future Enhancements

- Add backup codes and recovery flows
- Enforce stronger password policy and rate limiting
- Integrate secret rotation and device management
- Use real-time SMS/Email fallback for recovery

## Use Cases & Applications

- Securing admin dashboards and internal tools
- Adding 2FA to existing login flows with minimal code
- Educational demo for TOTP and QR-based onboarding

## Step-by-Step Installation

```bash
composer install
php scripts/init_db.php
php -S localhost:8000 -t public
# Open http://localhost:8000 and login with user@example.com / password123
```

## Complete Setup Guide

- Login with the sample user
- Scan the QR shown on the setup page with Google Authenticator
- Enter the 6-digit code to enable 2FA
- Next logins will require the current code

## Technology Stack

- **PHP 8** for application logic
- **SQLite (PDO)** for storage
- **Sonata Google Authenticator** for TOTP + QR
- **Plain CSS** for UI

## License

This repository includes third-party code under their respective licenses (see `vendor/`). Add your preferred license for the application layer as needed.

## Developed by

- **Name:** SHREYAS M P.
- **Contact:** shreyas.cta61@gmail.com
- **GitHub:** https://github.com/Shreyu0301

## Why This Project Stands Out

- Clear, minimal implementation of TOTP with QR onboarding
- Small footprint; easy to integrate into existing PHP apps
- Uses trusted library (Sonata) with straightforward APIs

## Project Snapshots & Demonstrations

See the screenshots above in the Demo Screenshots section. Add more under `docs/screenshots/` to expand.

## Core Features & Capabilities

- Email/password login
- TOTP 2FA setup via QR code
- Code verification on login
- Basic banners for success/error feedback
