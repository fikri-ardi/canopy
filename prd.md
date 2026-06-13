# Product Requirements Document (PRD): Alokasi

Last updated: 2026-06-14

## 1. Reference Basis

This PRD follows common product requirements document guidance:

- Atlassian's PRD template emphasizes product purpose, features, functionality, assumptions, user stories, UX design, scoping, success metrics, and open questions.
- ProductPlan describes a PRD as the release artifact that communicates required capabilities to engineering and testing, including use cases, functional requirements, system requirements, usability requirements, assumptions, constraints, and dependencies.
- Aha! recommends covering the basics, strategy, context, assumptions, requirements, design, metrics, impact, scope, and unresolved questions.

References:

- https://www.atlassian.com/software/confluence/templates/product-requirements
- https://www.productplan.com/glossary/product-requirements-document
- https://www.aha.io/roadmapping/guide/requirements-management/what-is-a-good-product-requirements-document-template

## 2. Product Summary

Alokasi is a personal finance application for planning income allocation, tracking expenses, managing savings or investment movement records, and reviewing financial health through dashboards and reports.

The product is built for individuals who want a disciplined way to answer three practical questions:

- How much money came in?
- Where did it go?
- How much remains available, allocated, withdrawn, saved, or invested?

Alokasi is implemented as a Laravel 11 and Livewire 3 web application with Tailwind CSS, Alpine.js, and MySQL.

## 3. Problem Statement

Many personal finance tools either focus on high-level dashboards or detailed transaction logs, but users often need a practical workflow that starts from a payday or funding event:

1. Create a plan with income.
2. Allocate money into expected uses.
3. Record actual expenses.
4. Track payment platforms, labels, and transaction status.
5. Understand remaining balance and investment progress without spreadsheet work.

Alokasi solves this by combining budget plans, expense entry, customizable categorization, allocation status, export/import, and analytics in one account-based workspace.

## 4. Goals

- Help users create structured money plans tied to income.
- Make expense capture fast, consistent, and traceable.
- Keep financial calculations deterministic and rupiah-safe.
- Provide dashboards that surface remaining balance, top categories, platforms, statuses, and recent expenses.
- Support investment tracking from expenses tagged as investment or investasi.
- Allow users to export, import, and preserve their own data.
- Keep multi-user data isolated by account.
- Provide admin visibility into application usage and feedback.

## 5. Non-Goals

- Alokasi is not a banking integration product.
- Alokasi does not automatically sync transactions from banks, cards, or e-wallet providers.
- Alokasi does not provide investment advice.
- Alokasi does not support multi-currency accounting in the current implementation.
- Alokasi does not currently support shared household budgets or team workspaces.
- Alokasi does not currently expose a public API.

## 6. Target Users

### Primary User: Personal Budgeter

A user who receives income periodically and wants to plan expenses, track spending, and see remaining balance.

Needs:

- Create one or more plans.
- Record expenses quickly.
- Categorize expenses by label, platform, and status.
- See remaining balance and spending progress.
- Export or import personal data.

### Secondary User: Investment Tracker

A user who records investment-related expenses and wants to track deposits, withdrawals, targets, and balances.

Needs:

- Group investment expenses by investment name.
- Record deposit and withdrawal movements.
- Set target amounts.
- See progress toward target.

### Admin User

A privileged user who monitors application usage and user feedback.

Needs:

- See active users.
- Review daily and monthly active users.
- Review recent users.
- Review recent feedback.

## 7. Product Scope

### In Scope

- Email/password registration and login.
- Google and GitHub social authentication when provider credentials are configured.
- Email verification.
- User onboarding from first plan to first expense to dashboard.
- Budget plan creation, selection, rename, duplication, income update, and deletion.
- Expense creation, editing, deletion, sorting, and pagination.
- Label, platform, and status management per user.
- Dashboard analytics by budget, label, platform, status, and activity period.
- Investment tracking based on investment-labeled spends.
- Investment deposits, withdrawals, targets, and progress.
- Settings for theme, profile, password, feedback, data export/import, legal links, account deletion, and logout.
- Export to CSV, XLSX, and ODS.
- Import from CSV, TXT, XLSX, and ODS.
- Admin analytics for users, activity, online state, and feedback.
- Legal privacy and terms pages.

### Out of Scope

- Bank account linking.
- Recurring transaction automation.
- OCR receipt scanning.
- Push notifications.
- Native mobile applications.
- Financial forecasting beyond current dashboards.
- Multi-user collaboration.

## 8. User Journeys

### 8.1 New User Onboarding

1. User registers with email/password or social login.
2. User verifies email when required.
3. User is routed to the budget plan flow.
4. User creates the first plan with income.
5. User creates the first expense.
6. User is guided back to the dashboard.
7. Onboarding is marked complete.

Acceptance criteria:

- New users receive default labels, platforms, and statuses when setup data is missing.
- Users cannot proceed with invalid plan or expense amounts.
- Onboarding state does not block existing verified users who already have budget and expense data.

### 8.2 Budget Planning

1. User opens the Plan page.
2. User creates a budget plan with name and income.
3. User selects a plan from the plan picker.
4. User reviews income, allocation, remaining balance, main bank balance, and investment summary.
5. User can rename, duplicate, update income, or delete the active plan.

Acceptance criteria:

- Income is stored as a deterministic non-float money value.
- Active plan belongs to the authenticated user.
- Deleting a plan removes its related spends inside a transaction.
- Duplicating a plan copies spends while preserving platform, status, label, name, and amount.

### 8.3 Expense Tracking

1. User opens a budget plan.
2. User adds an expense with name and amount.
3. User selects platform, status, and label.
4. User can later edit or delete the expense.
5. Expense totals update dashboard and plan analytics.

Acceptance criteria:

- Amount input accepts formatted rupiah digits and stores an integer-compatible value.
- Expense is attached only to a budget owned by the current user.
- Labels, platforms, and statuses are selectable only from the current user's data.
- Expenses can be searched and paginated in the Spends page.

### 8.4 Analytics Review

1. User opens Dashboard.
2. User sees total income, total expense, remaining balance, budget count, transaction count, average transaction, largest expense, recent expenses, budget health, label breakdown, platform breakdown, status breakdown, category chart, and label activity heatmap.
3. User can filter/search analytics where supported.

Acceptance criteria:

- Dashboard queries only include budgets and spends owned by the current user.
- Expensive relationship data is eager loaded where needed to avoid avoidable N+1 behavior.
- Empty data states are handled without errors.

### 8.5 Investment Tracking

1. User labels relevant expenses as investment or investasi.
2. User opens the Investment page.
3. User sees grouped investment principal by spend name.
4. User records deposit or withdrawal movements.
5. User optionally sets target amount.
6. User reviews balance and target progress.

Acceptance criteria:

- Investment balance equals principal plus deposits minus withdrawals.
- Movement type is limited to deposit or withdrawal.
- Movement amount is stored as an integer-compatible money value.
- Targets are unique per user and investment key.

### 8.6 Settings and Data Portability

1. User opens Settings.
2. User updates appearance, profile, password, feedback, export/import, legal pages, account deletion, or logout.
3. User exports data in CSV, XLSX, or ODS.
4. User imports supported export files to restore or merge data.

Acceptance criteria:

- Profile updates validate unique email and trigger re-verification when email changes.
- Password updates require the current password when a password already exists.
- Passwordless social accounts can set a password.
- Import accepts only CSV, TXT, XLSX, and ODS up to the configured size limit.
- Export validates that selected budgets belong to the current user.
- Account deletion requires current password and logs the user out.

### 8.7 Admin Analytics

1. Admin opens the Admin page.
2. Admin sees active users, daily active users, monthly active users, total users, recent users, and recent feedback.
3. Admin can sort recent users and show more or fewer rows.

Acceptance criteria:

- Admin page is protected by auth, verified, and admin middleware.
- Non-admin users cannot access admin analytics.
- Activity data uses tracked online and active-day records.

## 9. Functional Requirements

### Authentication and Account

- Users can register with name, email, and password.
- Users can log in with email/password.
- Users can log in with Google or GitHub when configured.
- Social login must reject unsupported providers.
- Social login must require complete provider ID and email data.
- Social login must create or link social account records.
- Users must verify email for protected app areas.
- Users can update profile name and email.
- Users can update or create a password.
- Users can delete their account with current password confirmation.

### Budget Plans

- Users can create budget plans with name and income.
- Users can switch active plan.
- Users can rename active plan.
- Users can update active plan income.
- Users can duplicate active plan.
- Users can delete active plan and its expenses.
- Users can view plan summary cards.
- Users can view top expenses, platform analytics, status analytics, spending progress, and insight cards.

### Expenses

- Users can create expenses for an active budget.
- Users can edit expense amount, label, platform, status, and name.
- Users can delete expenses.
- Users can view expenses in a table.
- Users can search across spend name, budget, platform, status, and label.
- Users can sort and paginate expenses.

### Labels

- Users can create labels.
- Users can edit labels.
- Users can delete labels.
- Label names must be unique per user.
- Label lists show usage count.
- Deleted labels must not delete historical spends.

### Platforms

- Users can create payment platforms.
- Users can edit platforms.
- Users can delete unused platforms.
- Platform names must be unique per user.
- Platforms used by spends cannot be deleted.

### Statuses

- Users can create statuses.
- Users can edit statuses.
- Users can delete unused statuses.
- Status names must be unique per user.
- Statuses used by spends cannot be deleted.

### Dashboard and Reports

- Dashboard must calculate total income, total expense, remaining balance, transaction count, average transaction, largest expense, and budget health.
- Dashboard must show recent expenses and top expenses.
- Dashboard must show label activity heatmap by selected year.
- Dashboard must show category budget chart by year.
- Dashboard must show savings rate
- Users can understand the components that make up the Savings Rate.

### Investment

- Investment page must identify investments from spends with label names investment or investasi.
- Investment groups must aggregate principal, deposits, withdrawals, balance, target, target progress, transaction count, budget count, and movement count.
- Users can select an investment group.
- Users can add deposit and withdrawal movements.
- Users can delete movements.
- Users can set target amounts.

### Data Export and Import

- Export endpoint must support CSV, XLSX, and ODS.
- CSV export contains budget and spend rows.
- XLSX and ODS export include multiple sheets for plans, labels, platforms, statuses, investment movements, and investment targets when available.
- Export can include all budgets or selected budgets.
- Import supports CSV, TXT, XLSX, and ODS files.
- Import resolves or creates budgets, labels, platforms, statuses, investment movements, and investment targets as needed.

### Feedback

- Users can send feedback with mood idea, issue, or love.
- Feedback message must be 8 to 1200 characters.
- Feedback stores user, mood, message, page, and timestamps.
- Admin can review recent feedback.

### Legal

- Public privacy policy page is available.
- Public terms and conditions page is available.
- Guest layout links to privacy and terms pages.

## 10. Data Model

### Core Tables

| Table | Purpose |
| --- | --- |
| users | Stores user identity, authentication, email verification, role, onboarding, and activity fields. |
| social_accounts | Links users to social providers such as Google and GitHub. |
| roles | Defines admin and user roles. |
| budgets | Stores user-owned plans and income. |
| labels | Stores user-owned spend labels. |
| platforms | Stores user-owned payment platforms. |
| statuses | Stores user-owned allocation statuses. |
| spends | Stores expense records tied to budget, platform, status, and optional label. |
| investment_movements | Stores user-owned investment deposits and withdrawals. |
| investment_targets | Stores user-owned investment target amounts. |
| feedback | Stores user feedback for admin review. |
| user_activity_days | Stores daily active user records. |

### Money Storage Rules

- Do not store money as float.
- Budget income uses decimal precision with zero decimal places.
- Spend amount uses integer.
- Investment movement and target amounts use unsigned integer-compatible values.
- User-facing values are formatted as rupiah.

## 11. Roles and Permissions

| Role | Capabilities |
| --- | --- |
| Guest | View legal pages, register, log in, start social auth. |
| Verified user | Use dashboard, plan, expenses, investment, settings, export/import, labels, platforms, statuses. |
| Admin | All user capabilities plus admin analytics and feedback review. |

Authorization requirements:

- Every user-owned query must filter by `user_id` directly or through owned budgets.
- Admin analytics must remain behind admin middleware.
- Export validation must ensure selected budgets belong to the requesting user.
- Social providers must be allow-listed.

## 12. UX Requirements

- UI must be mobile-first and avoid horizontal scrolling.
- Main authenticated experience uses sidebar navigation.
- Mobile sidebar behaves as a bottom navigation.
- Desktop sidebar supports collapse behavior.
- Use existing panel, button, metric, form, table, dropdown, and modal patterns.
- Use Alpine.js only for lightweight UI behavior such as dropdowns, modals, toggles, tabs, theme selection, and local interaction state.
- Avoid moving business logic into Alpine.js.
- Monetary inputs should support human-friendly formatted digit input.
- Empty states should be clear and non-breaking.
- Dark and light appearance modes are supported.

## 13. Technical Requirements

- Laravel 11 is the backend framework.
- Livewire 3 full-page components are the default page architecture.
- Tailwind CSS provides styling.
- Alpine.js supports lightweight interactions.
- MySQL is the target relational database.
- Maatwebsite Excel supports import and export.
- Laravel Socialite supports social authentication.
- Pest is used for automated tests.
- Business logic should live in services for new or touched complex features.
- Complex queries should move toward services or query objects as they are touched.
- Use policies for authorization where new authorization surfaces are introduced.
- Use Livewire form objects for new or substantially revised validation-heavy forms.

## 14. Non-Functional Requirements

### Security

- Validate all user input.
- Require authentication and verified email for app pages.
- Keep social redirect URLs safe: HTTPS for custom domains and HTTP only for localhost development.
- Store passwords hashed.
- Require current password for sensitive changes.
- Prevent cross-user data access.

### Performance

- Avoid N+1 queries by eager loading relationships in listing and dashboard views.
- Add indexes for frequently filtered or sorted columns.
- Keep dashboard queries scoped to the current user.
- Paginate long expense lists.

### Reliability

- Budget deletion and duplication should be transactional when related data is changed.
- Import/export should fail with validation errors rather than partial UI breakage.
- Empty schemas or missing optional columns should degrade gracefully where backward-compatibility checks exist.

### Accessibility

- Interactive controls should use semantic buttons, links, labels, and form inputs.
- Icon-only controls should have accessible labels or tooltips.
- Color should not be the only signal for critical actions.

## 15. Success Metrics

- New user activation: percentage of registered users who create at least one budget and one expense.
- Retention: weekly and monthly active users.
- Planning depth: average budgets per active user.
- Expense tracking depth: average spends per active user.
- Data quality: percentage of spends with label, platform, and status.
- Investment usage: number of users with investment-labeled expenses and movement records.
- Export/import usage: number of successful export and import operations.
- Feedback volume: number of feedback messages by mood.
- Reliability: failing import/export attempts and validation error rate.

## 16. Assumptions

- Users primarily manage finances in Indonesian rupiah.
- Users are comfortable manually recording expenses.
- Users want customizable labels, platforms, and statuses rather than fixed categories.
- A single user account owns its financial workspace.
- Users may use social login without a local password, then add a password later.
- Investment tracking can be derived from expenses labeled investment or investasi.

## 17. Constraints and Dependencies

- The application depends on Laravel, Livewire, Tailwind CSS, Alpine.js, MySQL, Socialite, and Maatwebsite Excel.
- Social login depends on provider credentials and valid redirect configuration.
- Email verification depends on mail configuration.
- File import depends on accepted spreadsheet formats and upload limits.
- Existing routes and components should remain stable unless a scoped refactor is planned.
- Current codebase contains some business and query logic inside Livewire components; future touched areas should move toward the AGENTS.md architecture.

## 18. Risks

- Complex dashboard queries may become harder to maintain if analytics grow without services or query objects.
- Import behavior may create duplicate or unexpected records if source files are malformed.
- Investment grouping by normalized spend name may be surprising if users use inconsistent names.
- Account deletion currently removes the user and cascaded data where database constraints support it; non-cascaded data paths need continued attention.
- Admin analytics privacy expectations should be documented clearly for production use.

## 19. Open Questions

- Should the Reports page become a dedicated reporting experience instead of redirecting to Dashboard?
- Should budget income and spend amounts use the same database type everywhere?
- Should investment tracking support manual investment creation rather than deriving groups from labels?
- Should labels, platforms, and statuses have archive behavior instead of delete behavior?
- Should import provide a preview and conflict-resolution step before writing data?
- Should users be able to restore deleted budgets or expenses?
- Should admin feedback review include status, assignment, or response workflows?
- Should PRD success metrics be captured in product analytics tables?

## 20. Future Opportunities

- Recurring expenses.
- Budget templates.
- Savings goals separate from investment tracking.
- Monthly close or reconciliation flow.
- Data import preview.
- Dedicated Reports page.
- CSV/XLSX import mapping UI.
- Browser notifications or email reminders.
- Shared household budgeting.
- API endpoints for integrations.

## 21. Release Readiness Checklist

- Relevant tests pass.
- No Livewire console errors.
- No JavaScript errors in primary flows.
- New endpoints include feature tests.
- New services include unit tests.
- Financial calculations include scenario coverage.
- User-owned data is scoped correctly.
- Import/export paths are validated.
- Mobile layouts avoid horizontal scrolling.
- Legal links are reachable from guest and settings pages.