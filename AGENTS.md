# AGENTS.md

## Stack
* Laravel 11
* Livewire 3
* Alpine.js
* Tailwind CSS
* MySQL

## Architecture
### Preferred Flow
Route → Full Page Livewire Component → Service → Model
Full Page Livewire Components are the default page architecture.
Use Controllers only for:
* External APIs
* Webhooks
* Third-party integrations
* Non-UI HTTP endpoints

### Rules
* Business logic belongs in Services.
* Complex queries belong in Services or Query Objects.
* Livewire Components handle UI state and user interaction.
* Keep Livewire components focused and small.
* Use Policies for authorization.
* Use Livewire form objects for valition.

### Alpine
Use Alpine only for lightweight UI interactions

## Database
* Use foreign keys whenever possible.
* Avoid N+1 queries.
* Use eager loading when appropriate.
* Add indexes for frequently filtered or sorted columns.
* Avoid raw SQL unless necessary.

## Financial Rules
* Never store money as float.
* Use integer (rupiah) or a consistent decimal format.
* Calculations must be deterministic.
* Do not round values without a clear reason.

## UI Rules
* Mobile-first.
* Avoid horizontal scrolling.
* Follow existing UI patterns.
* Avoid excessive nesting and visual noise.
* Apply a clean, minimalist UI, with a touch of layered blur effects.
* The UI should be consistent across all pages.

## Testing
Required:
* New endpoint → Feature Test
* New service → Unit Test
All changes must:
* Pass relevant tests.
* Not break existing features.
* Not introduce Livewire or JavaScript errors.

## Ask First
* Database schema changes
* New packages or dependencies
* File deletions
* Major refactors
* Business rule changes