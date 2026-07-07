# SOLID Principles in PHP

This folder explains the SOLID principles with practical PHP 8.2+ examples. The examples use backend domains that are common in Laravel projects: invoices, payments, notifications, reports, files, and repositories.

## Run All Examples

From the project root:

```bash
php solid/examples/run-all.php
```

Or with Composer:

```bash
composer solid
```

Run one example:

```bash
php solid/examples/01-single-responsibility.php
```

## Quick Summary

| Principle | Meaning | Practical Goal |
|---|---|---|
| SRP | Single Responsibility Principle | A class should have one reason to change |
| OCP | Open/Closed Principle | Add behavior without editing stable code |
| LSP | Liskov Substitution Principle | Implementations must be safely interchangeable |
| ISP | Interface Segregation Principle | Keep interfaces small and client-specific |
| DIP | Dependency Inversion Principle | High-level code depends on abstractions, not concrete details |

## 1. Single Responsibility Principle

**Definition:** A class should have one reason to change.

In Laravel terms, a controller should not validate input, calculate invoice totals, save models, call payment APIs, send emails, and format responses all in one method.

### Bad Example

```php
final class InvoiceController
{
    public function pay(array $request): string
    {
        // validates request
        // calculates totals
        // charges payment provider
        // saves invoice
        // sends receipt
        // returns response
    }
}
```

This class changes when validation changes, payment logic changes, email content changes, persistence changes, or response formatting changes.

### Better Approach

Split responsibilities:

- `InvoiceTotalCalculator` calculates money.
- `PaymentGateway` charges payment.
- `ReceiptSender` sends receipt.
- `PayInvoiceAction` coordinates the use case.

**Real Laravel use:** Thin controllers, Form Requests for validation, Actions/Services for use cases, Notifications/Mailables for communication.

**Interview answer:** SRP is not about making every class tiny. It is about grouping code by reason to change. If a class changes for unrelated business, infrastructure, and presentation reasons, it is doing too much.

Runnable example:

```bash
php solid/examples/01-single-responsibility.php
```

## 2. Open/Closed Principle

**Definition:** Code should be open for extension but closed for modification.

You should be able to add a new payment method without editing the checkout service every time.

### Bad Example

```php
final class CheckoutService
{
    public function pay(string $method, int $amountCents): string
    {
        return match ($method) {
            'card' => 'Paid by card',
            'wallet' => 'Paid by wallet',
        };
    }
}
```

Every new method requires editing `CheckoutService`.

### Better Approach

Use a `PaymentMethod` interface. Add new implementations without changing checkout logic.

**Real Laravel use:** Payment gateways, shipping calculators, tax calculators, notification channels, export formats.

**Interview answer:** OCP usually appears through interfaces, strategy objects, events, decorators, or configuration-driven class selection. The goal is reducing changes to stable, tested code.

Runnable example:

```bash
php solid/examples/02-open-closed.php
```

## 3. Liskov Substitution Principle

**Definition:** Subtypes must be usable wherever their parent type or interface is expected without breaking behavior.

If `PaymentGateway` promises `charge()` returns a successful `PaymentResult` or throws a `PaymentFailed` exception, every implementation must follow that contract.

### Bad Example

```php
final class BrokenGateway implements PaymentGateway
{
    public function charge(int $amountCents): PaymentResult
    {
        return null; // breaks the contract
    }
}
```

### Better Approach

All implementations return the expected type and use expected exceptions for failures.

**Real Laravel use:** Replacing a real gateway with a fake gateway in tests, switching SMS providers, changing storage drivers.

**Interview answer:** LSP is about behavioral compatibility, not just matching method signatures. If the caller must check the concrete class to avoid bugs, LSP is probably broken.

Runnable example:

```bash
php solid/examples/03-liskov-substitution.php
```

## 4. Interface Segregation Principle

**Definition:** Clients should not be forced to depend on methods they do not use.

Large interfaces create fake methods, runtime exceptions, and tightly coupled services.

### Bad Example

```php
interface ReportTool
{
    public function generate(): array;
    public function exportPdf(): string;
    public function sendEmail(): void;
}
```

A CSV exporter should not be forced to implement email sending.

### Better Approach

Split contracts:

- `ReportGenerator`
- `ReportExporter`
- `ReportNotifier`

**Real Laravel use:** Separate interfaces for exporting, notifying, uploading files, generating reports, and reading repositories.

**Interview answer:** ISP keeps dependencies focused. Smaller contracts are easier to implement, test, mock, and replace.

Runnable example:

```bash
php solid/examples/04-interface-segregation.php
```

## 5. Dependency Inversion Principle

**Definition:** High-level modules should not depend on low-level modules. Both should depend on abstractions.

An invoice service should not directly create a Stripe client. It should depend on a `PaymentGateway` interface.

### Bad Example

```php
final class InvoicePaymentService
{
    public function pay(int $amountCents): string
    {
        $gateway = new StripeGateway();
        return $gateway->charge($amountCents);
    }
}
```

This hardcodes infrastructure into business logic.

### Better Approach

Inject the abstraction:

```php
final class InvoicePaymentService
{
    public function __construct(private PaymentGateway $gateway) {}
}
```

**Real Laravel use:** Bind interfaces in the service container:

```php
$this->app->bind(PaymentGateway::class, StripeGateway::class);
```

**Interview answer:** DIP improves testability and flexibility, but do not create interfaces for every class. Add abstractions at boundaries that are likely to vary or that represent infrastructure.

Runnable example:

```bash
php solid/examples/05-dependency-inversion.php
```

## Common Interview Questions

### Which SOLID principle is most important in Laravel?

SRP and DIP usually show up the most. SRP keeps controllers, jobs, and services focused. DIP lets Laravel's container inject replaceable dependencies.

### Does SOLID mean every class needs an interface?

No. That is over-engineering. Interfaces are useful when implementations vary, when a boundary matters, or when testing requires substitution.

### How does SOLID relate to design patterns?

Design patterns are common solutions. SOLID principles are design guidelines. For example, Strategy often supports OCP and DIP; Adapter supports DIP; Decorator supports OCP.

### Can SOLID be overused?

Yes. Too many tiny classes, unnecessary interfaces, and abstract factories can make a simple feature harder to understand. Good SOLID design reduces change risk without hiding the business flow.

## Practical Laravel Mapping

| SOLID Principle | Laravel Example |
|---|---|
| SRP | Controller delegates to Form Request, Action, Service, Resource |
| OCP | Add a new payment strategy without editing checkout service |
| LSP | Fake payment gateway works anywhere real gateway is expected |
| ISP | Separate `Exporter`, `Notifier`, `Uploader` contracts |
| DIP | Service depends on `PaymentGateway`; container binds Stripe implementation |

