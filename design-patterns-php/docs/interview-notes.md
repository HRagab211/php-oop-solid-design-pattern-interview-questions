# PHP Design Patterns Interview Notes

Related reference: [Top 50 PHP/Laravel Interview Questions With Answers](top-50-php-laravel-interview-questions.md).

## Short Explanations

| Pattern | Short explanation |
| --- | --- |
| Factory Method | Lets subclasses or dedicated creators decide which concrete object to instantiate. |
| Abstract Factory | Creates families of related objects that must work together. |
| Builder | Builds complex objects step by step with readable configuration. |
| Singleton | Ensures one shared instance, but often creates global-state problems. |
| Prototype | Creates objects by cloning a prepared template. |
| Adapter | Converts an incompatible interface into the interface your app expects. |
| Decorator | Adds behavior by wrapping an object with the same interface. |
| Facade | Provides a simple API over a complex subsystem. |
| Proxy | Controls access to another object while keeping the same interface. |
| Composite | Treats single objects and groups through the same interface. |
| Bridge | Separates an abstraction from an implementation so both can vary independently. |
| Flyweight | Shares immutable repeated state to reduce memory or construction cost. |
| Strategy | Swaps algorithms behind a common interface. |
| Observer | Notifies dependent objects when something changes. |
| Command | Encapsulates a request or operation as an object. |
| Chain of Responsibility | Passes a request through handlers until one handles it. |
| Template Method | Defines fixed algorithm steps while subclasses customize parts. |
| State | Moves state-specific behavior into state objects. |
| Iterator | Traverses a collection without exposing internal storage. |
| Mediator | Coordinates components to reduce direct dependencies. |
| Specification | Encapsulates and composes business rules. |
| Repository | Abstracts persistence behind a collection-like interface. |
| Service Layer | Holds application business workflows outside controllers. |
| Action Class | Handles one application use case in one focused class. |
| DTO | Transfers typed data across boundaries. |
| Value Object | Represents an immutable domain value with validation. |
| Domain Event | Records a meaningful domain fact that already happened. |
| Query Object | Encapsulates reusable query/filter logic. |

## Most Common in Laravel

- **Strategy:** payment gateways, shipping calculators, discount rules.
- **Observer / Domain Event:** Eloquent observers, Laravel events and listeners.
- **Command:** jobs, artisan commands, queued work.
- **Chain of Responsibility:** middleware pipeline.
- **Facade:** Laravel's `Cache`, `Log`, `DB`, `Queue`, and similar facades.
- **Builder:** query builder, mail/message builders.
- **Repository:** useful in complex domains, integrations, or multiple data stores.
- **Service Layer and Action Class:** common for keeping controllers thin.
- **DTO and Value Object:** increasingly common in modern PHP for safer service boundaries.
- **Query Object:** reporting, admin filters, and reusable Eloquent filtering.

## Often Overused

- **Singleton:** Prefer dependency injection and container singletons.
- **Repository:** Do not create empty CRUD wrappers around every Eloquent model.
- **Service Layer:** Avoid vague services with unrelated methods.
- **Factory:** Do not hide every `new` behind a factory.
- **Specification:** Do not build a rule engine for two simple conditions.
- **Template Method:** Inheritance becomes rigid when the workflow is not truly stable.

## Similar Pattern Differences

### Factory vs Abstract Factory

Factory Method creates one product type. Abstract Factory creates multiple related product types that should be selected as a family.

### Strategy vs State

Strategy changes an algorithm, usually selected by the caller or configuration. State changes behavior because the object has moved through its lifecycle.

### Decorator vs Adapter

Decorator keeps the same interface and adds behavior. Adapter changes one interface into another.

### Facade vs Proxy

Facade simplifies a subsystem and often exposes a new interface. Proxy stands in for one object and usually keeps the same interface.

### Repository vs Service

Repository is about data access and persistence boundaries. Service is about application workflow and business orchestration.

### DTO vs Value Object

DTO transfers data and usually has little behavior. Value Object represents a meaningful domain value, validates invariants, and should be immutable.

### Observer vs Domain Event

Observer is a mechanism for notification. Domain Event is the message/fact being published, such as `OrderWasPaid`.

### Command vs Action Class

Command packages an operation for dispatch, queueing, retrying, or undo. Action Class directly executes one application use case and is often called synchronously.

## Common Interview Questions and Strong Answers

**How would you design multiple payment methods?**

Use a `PaymentGateway` or `PaymentStrategy` interface, one implementation per provider, and select the implementation through configuration, request context, or a factory. Keep checkout logic dependent on the interface.

**Should every Laravel model have a repository?**

No. Eloquent already implements many repository-like capabilities. Add repositories when they hide complex persistence, external data sources, or protect a domain layer from infrastructure details.

**Where should business logic live in Laravel?**

Not in controllers. Controllers should translate HTTP into application calls. Business workflows usually belong in services, actions, domain models, policies, specifications, or jobs depending on the responsibility.

**How do you avoid large `switch` statements for business rules?**

If each branch is a real algorithm, use Strategy. If each branch is lifecycle behavior, use State. If each branch is eligibility logic, Specification may fit.

**What is the risk of Laravel observers?**

They can hide important business behavior. Use them for side effects and integration hooks, but keep critical use-case rules explicit.

**How do DTOs improve maintainability?**

They make data shape explicit, typed, and discoverable. They reduce fragile associative array access and make service contracts easier to refactor.

**What makes a value object production-ready?**

It is immutable, validates itself, models a real domain concept, and compares by value rather than identity.

**How would you reduce unnecessary API calls?**

Use caching decorators or proxies around API clients, batch requests when possible, and keep API clients behind interfaces so optimizations do not leak through the app.

## Practical Advice for a 3-Year PHP Developer

- Learn the intent of each pattern before memorizing class diagrams.
- In Laravel, use the container and interfaces before reaching for manual singletons or service locators.
- Keep controllers thin, but do not create abstractions without a real reason.
- Prefer explicit names: `CapturePaymentAction` is better than `PaymentManager`.
- Optimize rendering and state use in frontend-heavy Nuxt apps by reducing shared reactive state and avoiding duplicate API calls.
- In backend work, measure slow paths before adding caches. Add caching behind decorators/proxies so the core service remains clean.
- Treat Eloquent as a powerful tool, not something that must always be hidden. Add repositories only when they buy clarity.
- For interviews, explain tradeoffs. Senior answers include when not to use a pattern.
- Production-ready code is boring in the best way: clear boundaries, small classes, typed contracts, tests around risky behavior, and no pattern theater.

## Laravel Mapping Cheat Sheet

| Laravel concept | Related pattern |
| --- | --- |
| Model factories | Factory-style test/data creation |
| Service container bindings | Dependency Injection, Factory, Singleton lifecycle |
| Query builder | Builder |
| Facades | Facade over container services |
| Middleware | Chain of Responsibility |
| Jobs | Command |
| Events and listeners | Observer, Domain Event |
| Notifications and channels | Bridge/Strategy ideas |
| Eloquent collections | Iterator |
| Policies and gates | Specification-style rules |
| Form requests to data classes | DTO |
| Custom casts | Value Object |
| Local/global scopes | Query Object |
