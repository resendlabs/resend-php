
```php
<?php
require_once "vendor/autoload.php";
use ResendLabs\ResendSDK\Resend;
use ResendLabs\ResendSDK\Models\Shared\Security;
use ResendLabs\ResendSDK\Models\Shared\SchemeBearerAuth;
use ResendLabs\ResendSDK\Models\Operations\SendEmailRequest;
$security = new Security();
$security->bearerAuth = "Bearer YOUR_BEARER_TOKEN_HERE";
$sdk = Resend::builder()
    ->setSecurity($security)
    ->build();
$request = new SendEmailRequest();
$request->from = "hello@resend.com"
$request->to = "thefuture@yourcompany.com"
$request->subject = "Welcome to Resend!"
$request->text = "Hello, World!"
$response = $sdk->email->sendEmail($request);
if ($response->statusCode === 200) {
    echo "Email sent successfully!";
} else {
    echo "Something went wrong";
}
```