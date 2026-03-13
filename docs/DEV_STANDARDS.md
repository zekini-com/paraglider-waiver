# Development Standards

## Architecture Overview

This application follows a layered architecture:

```
Request → FormRequest → Controller → Service → Repository → Model
                                       ↓
                                   DTO (data transfer)
```

**Controllers** are thin — they accept requests, delegate to services, and return responses.
**Services** contain business logic and orchestrate operations across repositories and other services.
**Repositories** encapsulate data access and query logic behind interfaces.
**DTOs** carry validated data between layers as immutable value objects.
**Enums** define fixed sets of values used across the application.
**Form Requests** handle validation and authorization.

## SOLID Principles

### Single Responsibility (SRP)
Each class has one reason to change:
- Controllers handle HTTP concerns only (request in, response out)
- Services handle business logic and orchestration
- Repositories handle data access and queries
- DTOs carry data between layers
- Form Requests handle validation

### Open/Closed (OCP)
- New waiver versions → add a new enum case and view, no existing code changes
- New relationship types → add a new enum case
- New search filters → extend the DTO, repository handles the rest

### Liskov Substitution (LSP)
- `WaiverRepository` implements `WaiverRepositoryInterface` — any implementation can be swapped (e.g., for testing)
- All enum cases are interchangeable where the enum type is expected

### Interface Segregation (ISP)
- `WaiverRepositoryInterface` defines only the methods needed by consumers
- No bloated base repository with unused generic methods

### Dependency Inversion (DIP)
- Controllers and services depend on `WaiverRepositoryInterface`, not the concrete `WaiverRepository`
- Binding is configured in `AppServiceProvider`
- Services are injected via constructor, resolved by the container

## Static Analysis — PHPStan (Larastan)

We use [Larastan](https://github.com/larastan/larastan) at **level 6** for static analysis.

```bash
# Run via Sail
./vendor/bin/sail shell
./vendor/bin/phpstan analyse

# Or directly if PHP extensions are available
./vendor/bin/phpstan analyse
```

Configuration is in `phpstan.neon`. All code in `app/` is analysed.

### Rules
- Fix all PHPStan errors before merging
- Do not add baseline ignores without team discussion
- Prefer fixing the code over suppressing errors

## Linting — Laravel Pint

We use [Laravel Pint](https://laravel.com/docs/pint) with the `laravel` preset.

```bash
# Check formatting
./vendor/bin/pint --test

# Fix formatting
./vendor/bin/pint
```

Configuration is in `pint.json`. Run Pint before committing.

### Rules
- `laravel` preset (PSR-12 + Laravel conventions)
- Unused imports are removed automatically
- Imports are sorted alphabetically

## Conventions

### DTOs

- Located in `app/DTOs/`
- Always `readonly` classes
- Include a `fromRequest()` static factory method
- Include a `toArray()` method when the DTO feeds into model creation
- Named as `{Noun}Data` or `{Noun}{Purpose}` (e.g., `WaiverData`, `WaiverSearchFilters`)

### Enums

- Located in `app/Enums/`
- Use PHP 8.1+ backed enums (string-backed preferred)
- Include helper methods like `label()` or `viewName()` for display logic
- Use in validation rules via `Rule::in(EnumClass::cases())`
- Cast on Eloquent models for automatic serialization/deserialization

### Repositories

- Located in `app/Repositories/`
- Contracts (interfaces) in `app/Repositories/Contracts/`
- Bind interface to implementation in `AppServiceProvider`
- Return Eloquent models (not DTOs) — keeps things simple for a single-model app
- No generic/abstract base repository — extract only when a second model needs one

### Services

- Located in `app/Services/`
- Inject dependencies via constructor
- Auto-resolved by the container (no explicit binding needed unless using interfaces)
- Group related operations (e.g., `WaiverService` handles submit + lookup + download)
- Keep email sending as a private method within the relevant service

### Form Requests

- **Always use Form Request classes for validation** — never validate inline in controllers
- Located in `app/Http/Requests/`
- Named as `{Action}{Model}Request` (e.g., `StoreWaiverRequest`, `AdminLoginRequest`)
- Define `rules()` and `messages()` methods
- Use enums in validation rules where applicable
- Type-hint the Form Request in the controller method signature — Laravel auto-validates before the method runs
- Keep authorization logic in `authorize()` (return `true` for public endpoints)

### Naming

| Concept | Convention | Example |
|---------|-----------|---------|
| DTO | `{Noun}Data` | `WaiverData` |
| Filter DTO | `{Noun}SearchFilters` | `WaiverSearchFilters` |
| Enum | PascalCase noun | `EmergencyContactRelationship` |
| Repository Interface | `{Model}RepositoryInterface` | `WaiverRepositoryInterface` |
| Repository | `{Model}Repository` | `WaiverRepository` |
| Service | `{Domain}Service` | `WaiverService`, `PdfService` |
| Form Request | `{Action}{Model}Request` | `StoreWaiverRequest` |

### No Globals

- **Never use global functions or state** — use dependency injection, facades, or helper classes
- Avoid `global` keyword, `$GLOBALS`, and static mutable state
- Use constructor injection for dependencies in services, repositories, and controllers
- Config values accessed via `config()` helper are acceptable (they're read-only)
- Use facades sparingly; prefer injected contracts where possible

### Date Formatting

- Display dates in **day month year** format: `d M Y` (e.g., `12 Mar 2026`)
- Use `d M Y, H:i` when time is needed (e.g., `12 Mar 2026, 14:30`)
- Store dates in the database as `YYYY-MM-DD` (standard MySQL format)
- Use Carbon for all date formatting in PHP: `$date->format('d M Y')`

### HTTP Status Codes

- **Never use magic numbers for HTTP status codes** — use `Symfony\Component\HttpFoundation\Response` constants
- Use `Response::HTTP_OK` (200), `Response::HTTP_NOT_FOUND` (404), `Response::HTTP_FOUND` (302), etc.
- Applies to `abort()`, `abort_unless()`, `assertStatus()`, and any other HTTP status code usage
- Import via `use Symfony\Component\HttpFoundation\Response;`

### Testing

- Feature tests for controller endpoints (HTTP tests)
- Unit tests for services and DTOs
- Repository tests against a real database (use `RefreshDatabase` trait)
- Do not mock the database — use SQLite in-memory or the test database
- Run PHPStan and Pint before submitting PRs

## Directory Structure

```
app/
├── DTOs/                              # Data transfer objects
├── Enums/                             # PHP backed enums
├── Http/
│   ├── Controllers/                   # Thin controllers
│   ├── Middleware/                     # HTTP middleware
│   └── Requests/                      # Form request validation
├── Mail/                              # Mailable classes
├── Models/                            # Eloquent models
├── Providers/                         # Service providers
├── Repositories/
│   ├── Contracts/                     # Repository interfaces
│   └── {Model}Repository.php          # Implementations
└── Services/                          # Business logic
```

## Workflow

1. Write code following the layered architecture
2. Run `./vendor/bin/pint` to fix formatting
3. Run `./vendor/bin/phpstan analyse` to check for type errors
4. Run `php artisan test` to verify tests pass
5. Commit and push
