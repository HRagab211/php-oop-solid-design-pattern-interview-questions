# Top 50 PHP/Laravel Interview Questions With Answers

Audience: PHP/Laravel developers with around 3 years of production experience.

Use this as a GitHub reference: each answer is written for live interviews, with practical Laravel trade-offs and common production concerns.

## 1. Explain the PHP request lifecycle in a typical Laravel application running behind Nginx and PHP-FPM.

Nginx receives the HTTP request and serves static files directly when possible. For PHP routes, Nginx forwards the request to PHP-FPM through FastCGI. PHP-FPM uses an available worker process to execute `public/index.php`, Composer loads classes, Laravel bootstraps the app, resolves the HTTP kernel, runs middleware, matches the route, executes the controller/action, returns a response, and terminates middleware.

Important production points:

- PHP-FPM workers handle one request at a time.
- Normal PHP-FPM requests should not rely on in-memory state between requests.
- Queue workers and Laravel Octane are long-running, so static state and singletons become more risky.
- Config, route, and view caches affect what code/config is actually loaded.

## 2. What changed in PHP 8+ that improves code safety and maintainability? Give production examples.

PHP 8+ added stronger language tools: union types, `mixed`, `static` return type, constructor property promotion, attributes, named arguments, enums, readonly properties/classes, `match`, nullsafe operator, and better error behavior.

Production examples:

```php
enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
}

final readonly class Money
{
    public function __construct(
        public int $amountCents,
        public string $currency,
    ) {}
}
```

These reduce invalid string states, make data contracts clearer, and improve static analysis. The trap is using new syntax to hide bad design, for example `int|string|array|null` everywhere.

## 3. When would you use union types, enums, readonly properties, and attributes in a Laravel project?

Use **union types** when a boundary genuinely accepts more than one type, such as `int|float` for numeric calculation. Avoid them when they hide unclear input.

Use **enums** for fixed states: invoice status, payment provider, user role type, queue priority. They work well with Eloquent casts and validation.

Use **readonly** for immutable DTOs and value objects such as `Money`, `EmailAddress`, `CreateInvoiceData`. Do not use readonly on Eloquent models because models are mutable active-record objects.

Use **attributes** for metadata that benefits from reflection, such as route-like metadata, auditing markers, or package integration. Do not replace clear configuration with attributes just to look modern.

## 4. What is the difference between an interface, abstract class, and trait in PHP?

An **interface** defines a contract. It says what methods an implementation must provide.

An **abstract class** can define shared behavior and force subclasses to implement missing pieces.

A **trait** copies reusable methods into a class. It is not a contract.

Example:

```php
interface PaymentGateway
{
    public function charge(int $amountCents): string;
}

abstract class Report
{
    final public function download(): string
    {
        return $this->render();
    }

    abstract protected function render(): string;
}

trait HasUuid
{
    public function newUuid(): string
    {
        return bin2hex(random_bytes(16));
    }
}
```

Interview answer: use interfaces for replaceable dependencies, abstract classes for stable shared workflows, and traits for small horizontal reuse. Avoid deep inheritance and traits with hidden dependencies.

## 5. Explain late static binding and when it can be useful or dangerous.

Late static binding means `static::` resolves to the class that was called at runtime, while `self::` resolves to the class where the method is defined.

```php
class BaseModel
{
    public static function label(): string
    {
        return static::class;
    }
}

final class Invoice extends BaseModel {}

echo Invoice::label(); // Invoice
```

It is useful in base classes, fluent factories, and ORM-style APIs. It is dangerous when you build too much business logic around static methods, because static-heavy code is harder to replace, test, and reason about.

## 6. How do closures and anonymous functions work in PHP, and where does Laravel use them?

Closures are functions stored as values. They can capture variables with `use`, and arrow functions capture by value automatically.

Laravel uses closures in routes, middleware, query constraints, collections, transactions, cache callbacks, events, and tests.

```php
$orders = Order::query()
    ->where(fn ($query) => $query
        ->where('status', 'paid')
        ->orWhere('status', 'shipped')
    )
    ->get();
```

Good rule: closures are great for local composition. Extract named classes when logic becomes reusable, complex, or important to test independently.

## 7. What are generators, and when would you use them instead of loading everything into memory?

Generators produce values lazily with `yield`, so PHP does not need to keep the entire dataset in memory.

```php
function readRows(string $path): Generator
{
    $handle = fopen($path, 'r');

    try {
        while (($row = fgetcsv($handle)) !== false) {
            yield $row;
        }
    } finally {
        fclose($handle);
    }
}
```

Use generators for large CSV files, exports, imports, logs, and batch processing. In Laravel, use `cursor()`, `lazy()`, `lazyById()`, or chunking instead of `Model::all()`.

## 8. How does Composer PSR-4 autoloading work? What can go wrong in production?

PSR-4 maps namespace prefixes to directories.

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```

Composer generates `vendor/autoload.php`, which maps class names to file paths. In production, optimized autoloading can improve performance.

Problems:

- Namespace does not match directory or filename case.
- `composer dump-autoload` was not run after adding classes.
- Dev dependencies are used in production code.
- Cached config points to old classes.
- Linux production is case-sensitive while some local machines are not.

## 9. How does Laravel handle a request from `public/index.php` to a response?

`public/index.php` loads Composer autoloading and bootstraps Laravel. Laravel creates the application, resolves the HTTP kernel, captures the request, passes it through global and route middleware, resolves the route/controller, executes the action, converts the result into a response, sends it, and runs termination callbacks.

Key interview point: service providers, middleware, route model binding, validation, authorization, exception handling, and response formatting happen at different stages. Knowing the lifecycle helps debug unexpected behavior.

## 10. What is the Laravel service container, and how is it different from a service provider?

The **service container** resolves classes and injects dependencies. It knows how to build objects and which implementations are bound to interfaces.

A **service provider** registers and boots services. It is where you configure container bindings, event listeners, macros, package services, and framework integrations.

```php
// AppServiceProvider
public function register(): void
{
    $this->app->bind(PaymentGateway::class, StripeGateway::class);
}
```

The container is the engine. Providers are configuration/bootstrap classes for that engine.

## 11. How do Laravel facades work internally? Are they real static calls?

Laravel facades look static, but they proxy calls to objects resolved from the service container.

```php
Cache::remember('dashboard', 300, fn () => $this->buildDashboard());
```

`Cache` extends Laravel's facade base class. When you call a static method, `__callStatic()` resolves the underlying service from the container and calls the method on that object.

They are not normal static utility classes. They are convenient and testable, but overusing them inside domain services can hide dependencies.

## 12. When should you bind an interface to an implementation in Laravel?

Bind an interface when you have real substitutability or want a clear boundary:

- Payment provider implementations.
- SMS/email gateway implementations.
- External API clients.
- Storage/report exporters.
- Domain repositories with complex persistence.

Do not bind interfaces for every class by default. If there is only one implementation and no boundary benefit, constructor injection of the concrete class is fine.

## 13. What is contextual binding, and when would it help?

Contextual binding tells Laravel to inject different implementations depending on the consuming class.

```php
$this->app->when(AdminReportService::class)
    ->needs(Exporter::class)
    ->give(CsvExporter::class);

$this->app->when(FinanceReportService::class)
    ->needs(Exporter::class)
    ->give(XlsxExporter::class);
```

Use it when two services need the same interface but different implementations. Avoid it if it makes dependency resolution surprising.

## 14. How do middleware differ from policies and form requests?

**Middleware** handles cross-cutting HTTP concerns: authentication, tenant resolution, rate limits, CORS, locale.

**Policies** answer authorization questions around models or abilities.

**Form requests** validate request input and can also authorize that specific request.

Example: login throttling belongs in middleware/rate limiter, `update invoice` permission belongs in a policy, and invoice payload rules belong in a form request.

## 15. How do FormRequest classes improve validation and authorization?

FormRequest classes move validation and request-specific authorization out of controllers.

```php
final class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Invoice::class);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
```

They improve consistency, testability, and controller readability. Keep business workflows in services/actions, not inside FormRequest.

## 16. Explain Eloquent relationships and how to avoid N+1 queries.

Eloquent relationships define how models connect: `hasOne`, `hasMany`, `belongsTo`, `belongsToMany`, `morphMany`, etc.

N+1 happens when you load a list, then trigger one extra query per row inside a loop.

Bad:

```php
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->customer->name;
}
```

Better:

```php
$orders = Order::query()
    ->with('customer:id,name')
    ->get();
```

Use eager loading, constrained eager loading, `withCount`, `withSum`, selected columns, and query monitoring tools.

## 17. What are local scopes, global scopes, and when can global scopes be dangerous?

Local scopes are explicit reusable query methods:

```php
public function scopePaid(Builder $query): Builder
{
    return $query->where('status', 'paid');
}
```

Global scopes apply automatically to every query for a model, such as soft deletes or tenant filtering.

Global scopes are dangerous when developers forget they exist. They can hide records from admin jobs, reports, exports, or maintenance scripts. Always document them and use `withoutGlobalScope()` intentionally when needed.

## 18. How would you optimize a slow Eloquent query?

Start by measuring:

- Inspect SQL with query logs, Telescope, Debugbar, or database logs.
- Run `EXPLAIN`.
- Check indexes and cardinality.
- Look for N+1 queries.
- Select only needed columns.
- Replace heavy model hydration with Query Builder for read-only reports.
- Use pagination, chunking, cursors, or background jobs.
- Cache expensive results with clear invalidation.

Do not start by adding cache blindly. Fix query shape and indexing first.

## 19. When should you use Query Builder or raw SQL instead of Eloquent?

Use Query Builder when you need efficient read-only data, aggregates, joins, reporting, bulk updates, or less hydration overhead.

Use raw SQL when the database feature is hard to express through Eloquent/Query Builder, such as window functions, advanced CTEs, optimizer hints, or complex reports.

Keep raw SQL parameterized to prevent injection:

```php
DB::select('select * from orders where status = ?', ['paid']);
```

## 20. Explain database transactions in Laravel and how you handle exceptions inside them.

Transactions make a group of database operations commit together or roll back together.

```php
DB::transaction(function () use ($invoice) {
    $invoice->markAsPaid();
    PaymentAuditLog::record($invoice);
});
```

If an exception escapes the callback, Laravel rolls back and rethrows it. Keep external side effects, like emails or API calls, outside the transaction or dispatch them after commit. For deadlock-prone operations, Laravel's `DB::transaction($callback, $attempts)` can retry.

## 21. How do queues work in Laravel, and what should be queued?

Laravel serializes a job payload to a queue backend such as database, Redis, SQS, or Beanstalkd. A worker process reserves the job, executes `handle()`, deletes it on success, or releases/fails it on error depending on retries.

Queue slow or unreliable work:

- Emails and notifications.
- PDF generation.
- Webhook delivery.
- Imports/exports.
- External API calls.
- Image/video processing.

Do not queue work that must finish before the HTTP response unless you have a clear async UX.

## 22. How do you make queued jobs safe, idempotent, and retryable?

A safe job can run more than once without corrupting data.

Techniques:

- Store idempotency keys or processed timestamps.
- Re-load models inside `handle()` instead of serializing full state.
- Use unique jobs or locks for duplicate prevention.
- Set timeouts, tries, backoff, and failure handling.
- Make external API calls idempotent when the provider supports it.
- Wrap DB changes in transactions where needed.

```php
if ($invoice->receipt_sent_at !== null) {
    return;
}
```

## 23. What is the difference between events/listeners, observers, jobs, and notifications?

**Events** announce something happened, such as `OrderWasPaid`.

**Listeners** react to events.

**Observers** react to Eloquent model lifecycle events like `created`, `updated`, `deleted`.

**Jobs** represent queueable work.

**Notifications** format and send user-facing messages through mail, SMS, database, Slack, etc.

Good design often combines them: `OrderWasPaid` event -> listener dispatches `SendReceiptJob` -> job sends a notification.

## 24. How do gates and policies differ? Where would you put role-based access logic?

Gates are closure/class-based authorization checks for abilities not necessarily tied to one model. Policies group authorization around a model.

Put model-specific checks in policies:

```php
public function update(User $user, Invoice $invoice): bool
{
    return $user->company_id === $invoice->company_id
        && $user->can('invoice.update');
}
```

Role-based access can use permissions/roles as data, but final business authorization should often live in policies so it can include ownership, status, tenant, and domain rules.

## 25. How would you secure a Laravel API using Sanctum or Passport?

Use **Sanctum** for first-party SPAs, mobile apps, and simple token APIs. Use **Passport** when you need full OAuth2 flows.

Security checklist:

- Enforce HTTPS.
- Hash tokens.
- Scope abilities.
- Use short-lived tokens where possible.
- Revoke compromised tokens.
- Validate and authorize every endpoint.
- Add rate limiting.
- Avoid leaking model fields in API resources.
- Use CORS carefully for browser clients.

## 26. How would you design rate limiting for login, OTP, and public APIs?

Use different limiters by risk and identity.

- Login: limit by email+IP, add progressive backoff, log suspicious attempts.
- OTP: limit generation and verification separately by phone/email+IP/device.
- Public APIs: limit by API key/user/IP and endpoint cost.

Laravel supports named rate limiters:

```php
RateLimiter::for('otp', fn (Request $request) => [
    Limit::perMinute(3)->by($request->ip().'|'.$request->input('phone')),
]);
```

Avoid one global limit for everything. Expensive endpoints need stricter limits.

## 27. What are the most common Laravel security mistakes?

Common mistakes:

- Missing authorization after authentication.
- Mass assignment with unsafe `$fillable` or unguarded models.
- Trusting request data after validation but before authorization.
- Raw SQL string concatenation.
- Exposing sensitive fields in API resources.
- Weak rate limiting on auth/OTP endpoints.
- Insecure file uploads.
- Storing secrets in Git.
- Misconfigured CORS.
- Forgetting HTTPS/session cookie security in production.

Strong answer: validate input, authorize actions, escape output, parameterize queries, protect secrets, and log security-relevant events.

## 28. Explain SRP with a Laravel controller example.

Single Responsibility Principle means a class should have one reason to change.

A controller should handle HTTP concerns, not validation, pricing, payment, database transactions, notifications, and response formatting all at once.

Better shape:

```php
final class PayInvoiceController
{
    public function __invoke(PayInvoiceRequest $request, PayInvoiceAction $action): InvoiceResource
    {
        $invoice = $action->execute($request->toData());

        return InvoiceResource::make($invoice);
    }
}
```

The controller changes when HTTP behavior changes. Payment logic changes in the action/service.

## 29. Explain OCP using a payment gateway or notification channel example.

Open/Closed Principle means code should be open for extension but closed for modification.

Instead of editing checkout every time a gateway is added, depend on an interface:

```php
interface PaymentGateway
{
    public function charge(int $amountCents): PaymentResult;
}
```

Add `StripeGateway`, `PayPalGateway`, or `TapGateway` without changing the checkout service. The selection can happen through config, factory, or container binding.

## 30. Explain LSP using interchangeable implementations in a Laravel service.

Liskov Substitution Principle means any implementation of an interface should be usable without breaking client expectations.

If `PaymentGateway::charge()` promises a `PaymentResult`, all gateways should return a consistent result or throw documented exceptions. A `FakePaymentGateway` used in tests should behave like a real gateway from the service's perspective.

Violation: one gateway returns `null` on failure while another throws, forcing the service to know concrete types.

## 31. Explain ISP using separate interfaces for reports, exports, and notifications.

Interface Segregation Principle means clients should not depend on methods they do not use.

Bad:

```php
interface BusinessTool
{
    public function export(): string;
    public function notify(): void;
    public function generateReport(): array;
}
```

Better:

```php
interface Exporter { public function export(array $rows): string; }
interface Notifier { public function send(string $message): void; }
interface ReportGenerator { public function generate(): array; }
```

Smaller interfaces make implementations cleaner and tests easier.

## 32. Explain DIP using Laravel container bindings.

Dependency Inversion Principle means high-level code depends on abstractions, not concrete low-level details.

```php
final class CheckoutService
{
    public function __construct(private PaymentGateway $gateway) {}
}
```

Laravel binds the abstraction:

```php
$this->app->bind(PaymentGateway::class, StripeGateway::class);
```

The checkout service does not know whether Stripe, PayPal, or a fake test gateway is used.

## 33. What is composition over inheritance? Give a Laravel business-rule example.

Composition means building behavior by combining objects instead of extending base classes.

For discounts, prefer composing rules:

```php
final class DiscountCalculator
{
    public function __construct(private DiscountRule $rule) {}
}
```

This is usually cleaner than a hierarchy like `BaseDiscount`, `VipDiscount`, `HolidayVipDiscount`, `HolidayVipCouponDiscount`. Composition avoids inheritance explosion and makes rules easier to swap.

## 34. What is a value object, and how is it different from an Eloquent model?

A value object represents a domain value and is usually immutable. It validates its own invariants.

```php
final readonly class EmailAddress
{
    public function __construct(public string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email.');
        }
    }
}
```

An Eloquent model represents a database row with identity, persistence, relationships, and mutable state. A value object has no database identity; two equal values are conceptually the same.

## 35. What is a DTO, and when should you avoid it?

A DTO is a typed object for moving data across boundaries.

```php
final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
```

Use DTOs for request data, API responses, job payloads, and service input. Avoid DTOs when they only duplicate a model with no boundary benefit, or when they become mutable domain objects with business behavior.

## 36. When is Repository pattern useful in Laravel, and when is it over-engineering?

Useful when:

- Persistence logic is complex.
- The domain should not depend on Eloquent.
- Data comes from multiple sources.
- You need a clear testing boundary.
- Queries are reused and not naturally model scopes.

Over-engineering when:

- Every model gets a pass-through CRUD repository.
- The repository only calls `Model::find()` and `Model::create()`.
- It hides useful Eloquent features without improving architecture.

Good answer: repositories should earn their place.

## 37. Explain Strategy pattern and implement it for payment methods.

Strategy puts interchangeable algorithms behind a shared interface.

```php
interface PaymentStrategy
{
    public function pay(int $amountCents): string;
}

final class CardPayment implements PaymentStrategy
{
    public function pay(int $amountCents): string
    {
        return "Paid {$amountCents} by card";
    }
}

final class WalletPayment implements PaymentStrategy
{
    public function pay(int $amountCents): string
    {
        return "Paid {$amountCents} by wallet";
    }
}

final class Checkout
{
    public function __construct(private PaymentStrategy $payment) {}

    public function complete(int $amountCents): string
    {
        return $this->payment->pay($amountCents);
    }
}
```

Use it for payments, shipping, tax, discounts, and notification routing.

## 38. Explain Factory pattern and where Laravel uses factories differently from GoF Factory.

Factory pattern centralizes object creation, especially when creation depends on runtime conditions.

```php
final class PaymentGatewayFactory
{
    public function make(string $provider): PaymentGateway
    {
        return match ($provider) {
            'stripe' => new StripeGateway(),
            'paypal' => new PayPalGateway(),
            default => throw new InvalidArgumentException('Unsupported provider.'),
        };
    }
}
```

Laravel model factories are different: they create test/seeding data for Eloquent models. They are factory-like, but not the same as GoF Factory Method used for runtime polymorphic object creation.

## 39. Explain Observer pattern in Laravel and when model observers are risky.

Laravel model observers listen to Eloquent lifecycle events such as `created`, `updated`, and `deleted`.

Good uses:

- Lightweight side effects.
- Audit logs.
- Search index syncing.
- Cache invalidation.

Risks:

- Hidden business behavior.
- Unexpected side effects in tests/imports.
- Observers firing during seeders or bulk updates.
- Critical rules running outside explicit use cases.

Strong answer: use observers carefully for side effects, not for core business workflows.

## 40. Explain Adapter pattern for integrating third-party APIs.

Adapter wraps a vendor API in your own interface.

```php
interface SmsSender
{
    public function send(string $phone, string $message): void;
}

final class TwilioSmsAdapter implements SmsSender
{
    public function __construct(private TwilioClient $client) {}

    public function send(string $phone, string $message): void
    {
        $this->client->messages->create($phone, ['body' => $message]);
    }
}
```

This prevents vendor SDK details from spreading through controllers and services. It also makes testing and provider replacement easier.

## 41. Explain Decorator pattern for adding behavior without changing a service.

Decorator wraps a service with the same interface and adds behavior before/after delegation.

```php
final class CachedCatalog implements ProductCatalog
{
    public function __construct(
        private ProductCatalog $inner,
        private Repository $cache,
    ) {}

    public function priceFor(string $sku): int
    {
        return $this->cache->remember("price:{$sku}", 300, fn () => $this->inner->priceFor($sku));
    }
}
```

Use decorators for caching, logging, retrying, metrics, tracing, and authorization checks.

## 42. Explain Command pattern using Laravel console commands or jobs.

Command encapsulates an operation as an object. Laravel jobs and console commands are practical examples.

```php
final class SendInvoiceEmail implements ShouldQueue
{
    public function __construct(public int $invoiceId) {}

    public function handle(Mailer $mailer): void
    {
        // send invoice email
    }
}
```

The job packages the request, can be queued/retried, and executes later. Keep commands small and idempotent.

## 43. Explain Builder pattern with complex search filters or reports.

Builder creates complex objects or queries step by step.

```php
$query = Order::query()
    ->when($filters->status, fn ($q, $status) => $q->where('status', $status))
    ->when($filters->from, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
    ->when($filters->customerId, fn ($q, $id) => $q->where('customer_id', $id));
```

Laravel's query builder is a classic practical builder. For very complex reports, move filter application into a dedicated query object to keep controllers clean.

## 44. What is the difference between WHERE and HAVING?

`WHERE` filters rows before grouping. `HAVING` filters grouped/aggregated results after `GROUP BY`.

```sql
SELECT customer_id, SUM(total) AS total_spent
FROM orders
WHERE status = 'paid'
GROUP BY customer_id
HAVING SUM(total) > 10000;
```

Use `WHERE` for normal columns and `HAVING` for aggregate conditions.

## 45. Explain INNER JOIN vs LEFT JOIN with an orders/customers example.

`INNER JOIN` returns only matching rows from both tables.

```sql
SELECT orders.id, customers.name
FROM orders
INNER JOIN customers ON customers.id = orders.customer_id;
```

`LEFT JOIN` returns all rows from the left table and matching rows from the right table, with `NULL` when missing.

```sql
SELECT customers.name, orders.id
FROM customers
LEFT JOIN orders ON orders.customer_id = customers.id;
```

Use `LEFT JOIN` when you need customers even if they have no orders.

## 46. How do composite indexes work, and why does column order matter?

A composite index includes multiple columns, for example `(company_id, status, created_at)`. Databases can use the leftmost prefix: `company_id`, `company_id + status`, or `company_id + status + created_at`.

Column order matters because the index is sorted in that order. Put high-selectivity and common equality filters first, then range/sort columns when appropriate.

Example useful query:

```sql
WHERE company_id = 10
  AND status = 'paid'
ORDER BY created_at DESC
```

Good candidate index: `(company_id, status, created_at)`.

## 47. How would you investigate a slow SQL query using EXPLAIN?

Run `EXPLAIN` on the query and inspect:

- Table access type.
- Which indexes are possible and which are used.
- Estimated rows scanned.
- Join order.
- Extra operations like temporary tables or filesort.

Then compare with the actual query patterns. Add or adjust indexes, rewrite joins/subqueries, reduce selected columns, filter earlier, and avoid functions on indexed columns where possible.

Do not add indexes blindly. Indexes speed reads but slow writes and consume storage.

## 48. What are ACID properties and isolation levels?

ACID:

- **Atomicity:** all operations commit or none do.
- **Consistency:** data moves between valid states.
- **Isolation:** concurrent transactions do not incorrectly interfere.
- **Durability:** committed data survives crashes.

Isolation levels define how much transactions can see from each other. Common levels include Read Uncommitted, Read Committed, Repeatable Read, and Serializable. Higher isolation reduces anomalies but may increase locking and contention.

Laravel uses the database's transaction behavior; know your database defaults, especially MySQL InnoDB.

## 49. What causes deadlocks, and how do you reduce them?

Deadlocks happen when transactions hold locks and wait on each other in a cycle.

Reduce them by:

- Updating rows in a consistent order.
- Keeping transactions short.
- Avoiding user/API calls inside transactions.
- Indexing predicates so fewer rows are locked.
- Retrying deadlock-safe transactions.
- Locking only what you need.

Laravel can retry transactions:

```php
DB::transaction(function () {
    // database changes
}, attempts: 3);
```

## 50. How would you design an invoice/payment flow with consistency, retries, and audit logs?

Use an explicit application action/service:

1. Validate request input with a FormRequest/DTO.
2. Authorize with a policy.
3. Load invoice and check it is payable.
4. Create a payment attempt with a unique idempotency key.
5. Call the payment provider using that idempotency key.
6. In a DB transaction, mark invoice paid, store payment result, and write audit log.
7. Dispatch events/jobs after commit for receipt email, webhooks, and notifications.
8. Make jobs idempotent and retryable.
9. Store provider request/response metadata safely.
10. Monitor failures and expose admin reconciliation tools.

Key design points:

- Do not send emails inside the payment transaction.
- Do not mark invoice paid before provider success unless using a pending/async state.
- Use database constraints to prevent duplicate successful payments.
- Record audit logs for financial traceability.
- Handle webhooks idempotently because providers may send duplicates.

Strong interview answer: model the flow as states, protect consistency with transactions and idempotency, and move slow side effects to queues after commit.

