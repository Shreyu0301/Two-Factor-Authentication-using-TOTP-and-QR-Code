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
