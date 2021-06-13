# GitHub App Proof of Concept

## Setup
- Run `$ php -S localhost:8080`
- Start `ngork`
- Create App that targets relevant `.php` files using hostname provided by `ngrok`
- Generate private key for App and store it in `secret/` directory (create directory if not exists)
- Configure `.env` by copying `.env.dist`
    - Add `GITHUB_APP_ID` and `GITHUB_APP_SECRET`
    - Configure `GITHUB_PRIVATE_PEM` with path to generated private key
    
---

Example `.php` files will write to standard output so that request payloads can be inspected through terminal instance
that is running PHP built-in server.