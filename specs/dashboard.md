# Dashboard Feature Specification

Last updated: 2026-06-14

Note: This file uses the requested path `specs/dasboard.md`. The feature name remains Dashboard.

## 1. Reference Basis

This spec follows a spec-driven development shape:

- Requirements first: describe the user-visible behavior and acceptance criteria before implementation work.
- Design second: connect behavior to routes, Livewire state, queries, view sections, and data contracts.
- Tasks and tests last: list verifiable work items and coverage expectations.
- Acceptance criteria use an EARS-inspired style where useful: `WHEN <event or condition>, THE SYSTEM SHALL <expected behavior>`.
- Acceptance tests should verify externally visible outcomes from the user's point of view.

References:

- https://en.wikipedia.org/wiki/Easy_Approach_to_Requirements_Syntax
- https://en.wikipedia.org/wiki/Acceptance_test-driven_development
- https://www.productplan.com/glossary/product-requirements-document

## 2. Feature Summary

The Dashboard is the authenticated user's financial overview page. It aggregates all user-owned budget plans and expenses into summary metrics, category trends, label activity, budget health, recent expenses, top expenses, platform breakdown, and status breakdown.

Current implementation:

- Route: `/`
- Route name: `dashboard`
- Component: `App\Livewire\Dashboard`
- View: `resources/views/livewire/dashboard.blade.php`
- Layout context: authenticated and verified user area with sidebar navigation
- Lightweight Alpine scope: `alokasiDashboardPage(@js($showOnboardingWelcome))`

## 3. Goals

- Give users a fast answer to total income, total spending, and remaining balance.
- Surface spending trends by label/category for the selected year.
- Show spending density by label over time.
- Highlight recent, largest, platform, and status spending patterns.
- Show current plan health for the latest plans.
- Complete onboarding after the user reaches the dashboard welcome step.

## 4. Non-Goals

- The Dashboard does not create, edit, or delete budgets.
- The Dashboard does not create, edit, or delete expenses.
- The Dashboard does not replace the dedicated Plan, Spends, Investment, or Settings pages.
- The Dashboard does not currently support custom date ranges beyond available years.
- The Reports route currently redirects to Dashboard and is not a separate reporting feature.

## 5. Users and Permissions

### Primary User

A verified user who wants to understand the current state of their money plans.

### Access Rules

- The Dashboard is available only inside the `auth` and `verified` route group.
- All displayed financial data must be scoped to the authenticated user.
- Spend ownership is determined through the related budget's `user_id`.
- Label, platform, and status analytics must not include another user's data.

## 6. Current UI Sections

### Header

Displays the page identity:

- Eyebrow: `Dashboard`
- Title: `Dashboard`
- Dashboard icon
- Optional onboarding spotlight target

### Onboarding Welcome Tour

Displayed when:

- The authenticated user still needs onboarding.
- The user has at least one budget with spends.

Behavior:

- Shows a spotlight overlay and tooltip.
- Button calls `$wire.completeOnboarding()`.
- Completion stores `onboarding_completed_at`.

### Summary Metrics

Cards shown at the top:

#### Total Pemasukan: sum of all user budget income.

#### Saving Rates: show how much income is saved across all budget plans.
Formula
Savings Rate =
Total spends with "investasi" label across budget plan / Total Income across budget plan × 100

Display
The card displays:
Savings Rate (%)

Example:
26%

Interaction
When the user clicks the card:
Open the Savings Rate Detail Panel
The user remains on the Dashboard
No page navigation occurs

Detail Panel

Desktop
Presentation:
Right Drawer

Mobile
Presentation:
Bottom Sheet

Detail Content
Display:
Saving rates
Total Savings (total spends with "investasi" label accross platform)
Savings Rate Formula
Trend Comparison

Example:
Savings Rate
26%

Total Income
Rp5.000.000

Total Saved
Rp1.300.000

Trend
Mei: 22%
Jun: 26%
+4%

States
No Income
Display:
"No income has been recorded for this period."

No Savings
Display:
"No savings or investments have been recorded for this period."

Success Criteria
Users understand how the Savings Rate is calculated.
Users can view the Savings Rate breakdown.
Users can compare the Savings Rate with the previous period.

#### Sisa: total income minus total expenses.

#### Transaksi: count of all user expenses.

#### Label Aktif: count of label breakdown groups currently shown.

### Category Trend Chart

Purpose:

- Show monthly spending trends by category/label for a selected year.

Behavior:

- Uses labels when label schema is ready.
- Groups unlabeled expenses as `Tanpa label`.
- Takes the top 5 categories by annual total.
- Produces one line series per category.
- Allows selecting a visible series in the legend.
- Shows crosshair details on hover or click.
- Allows changing year through Livewire period buttons.

Empty states:

- If label schema is not ready, show a message asking the user to add labels.
- If there are no category expenses in the year, show an empty state.

### Label Activity Heatmap

Purpose:

- Show activity density per label across the selected year.

Behavior:

- Uses two-week buckets from the start to end of the selected year.
- Groups unlabeled expenses as `Tanpa label`.
- Calculates a cell intensity level from 0 to 4.
- Provides tooltip and aria-label information for each heatmap cell.
- Allows filtering by available year.

Empty states:

- If label schema is not ready, show a message asking the user to add labels.
- If there are no rows for the selected year, show an empty state.

### Budget Health

Purpose:

- Show latest plan health for up to 5 budgets.

Fields:

- Budget name.
- Spent amount.
- Income amount.
- Percentage spent.
- Progress bar.
- Tone: currently danger when remaining balance is negative, otherwise healthy.

Empty state:

- Shows `Belum ada plan.` when the user has no budgets.

### Recent Expenses

Purpose:

- Show the 5 newest expenses.

Fields:

- Expense name.
- Budget name.
- Label name or `Tanpa label`.
- Created date.
- Raw amount formatted as rupiah.

Also shows the largest expense card when one exists.

### Top Expenses

Purpose:

- Show the 5 largest expenses across the user's budgets.

Fields:

- Expense name.
- Budget name.
- Label name or `Tanpa label`.
- Amount formatted as rupiah.

### Platform Breakdown

Purpose:

- Show up to 6 platforms by spending total.

Fields:

- Platform name.
- Percentage of total user expense.
- Progress bar.

### Status Breakdown

Purpose:

- Show spending totals by allocation status.

Fields:

- Status name.
- Transaction count.
- Total amount formatted as rupiah.

## 7. Data Inputs

### Models

- `Budget`
- `Spend`
- Optional relations: `Label`, `Platform`, `Status`

### Component State

| Property | Purpose |
| --- | --- |
| `search` | Filters label breakdown and label detail queries. There is no visible input in the current dashboard view. |
| `categoryChartPeriod` | Selected year for the category trend chart. |
| `labelActivityYear` | Selected year for the heatmap. |

### Render Payload

The component passes these values to the view:

- `labelBreakdown`
- `labelActivityHeatmap`
- `labelActivityYears`
- `platformBreakdown`
- `statusBreakdown`
- `topExpenses`
- `categoryBudgetChart`
- `totalIncome`
- `totalExpense`
- `remainingBalance`
- `budgetCount`
- `transactionCount`
- `averageTransaction`
- `largestExpense`
- `recentExpenses`
- `budgetHealth`
- `labelCount`
- `labelsReady`
- `topLabel`
- `showOnboardingWelcome`

## 8. Calculation Rules

### Money Formatting

- All displayed money values use `rupiah($amount)`.
- Format is `Rp` plus Indonesian thousands separators.
- Amounts are cast to integer before formatting.

### Total Income

Formula:

```text
sum(budgets.income) for authenticated user
```

### Total Expense

Formula:

```text
sum(spends.amount) where spend.budget.user_id = authenticated user id
```

### Remaining Balance

Formula:

```text
totalIncome - totalExpense
```

### Average Transaction

Formula:

```text
0 if transactionCount = 0
round(totalExpense / transactionCount) otherwise
```

### Budget Health Percentage

Formula:

```text
0 if income <= 0
min(100, round(spent / income * 100)) otherwise
```

### Platform Percentage

Formula:

```text
round(platformTotal / max(totalExpense, 1) * 100)
```

### Category Trend Percentage Change

Formula:

```text
100.0 if start = 0 and end > 0
0.0 if start = 0 and end = 0
(end - start) / start * 100 otherwise
```

### Heatmap Cell Level

Formula:

```text
0 if amount = 0
max(1, min(4, ceil(amount / maxCell * 4))) otherwise
```

## 9. Requirements and Acceptance Criteria

### Requirement DSH-001: Dashboard Access

User story:

As a verified user, I want to open the dashboard so that I can understand my financial state.

Acceptance criteria:

- WHEN an authenticated verified user visits `/`, THE SYSTEM SHALL render the Dashboard Livewire page.
- WHEN a guest visits `/`, THE SYSTEM SHALL require authentication according to the route middleware.
- WHEN an authenticated but unverified user visits `/`, THE SYSTEM SHALL require email verification according to the route middleware.

### Requirement DSH-002: Summary Metrics

User story:

As a user, I want high-level totals so that I can understand income, expenses, remaining balance, transactions, and active labels at a glance.

Acceptance criteria:

- WHEN the dashboard renders, THE SYSTEM SHALL show total income from the current user's budgets.
- WHEN the dashboard renders, THE SYSTEM SHALL show total expense from spends attached to the current user's budgets.
- WHEN total expense exceeds total income, THE SYSTEM SHALL show remaining balance with danger styling.
- WHEN there are no transactions, THE SYSTEM SHALL show transaction count as 0 and average transaction as 0.

### Requirement DSH-003: Category Trend Chart

User story:

As a user, I want to see expense trends by category so that I can detect patterns over the selected year.

Acceptance criteria:

- WHEN labels schema is unavailable, THE SYSTEM SHALL show the chart unavailable empty state.
- WHEN labels schema is available and the selected year has no spends, THE SYSTEM SHALL show the no category expenses empty state.
- WHEN labeled or unlabeled spends exist in the selected year, THE SYSTEM SHALL show up to 5 category series ordered by total spend.
- WHEN the user selects a year, THE SYSTEM SHALL update the chart period only if the year is in available options.
- WHEN a category series is selected, THE SYSTEM SHALL update the highlighted line and summary without a full page reload.

### Requirement DSH-004: Label Activity Heatmap

User story:

As a user, I want a time-based activity view by label so that I can see which categories are used regularly.

Acceptance criteria:

- WHEN labels schema is unavailable, THE SYSTEM SHALL show the label activity unavailable empty state.
- WHEN the selected year has no label activity, THE SYSTEM SHALL show the no labeled expenses empty state.
- WHEN label activity exists, THE SYSTEM SHALL render one row per label group and one cell per two-week bucket.
- WHEN a heatmap cell has expenses, THE SYSTEM SHALL expose tooltip and aria-label text containing spend summary, date range, and amount.
- WHEN the selected year is invalid, THE SYSTEM SHALL fall back to the current year.

### Requirement DSH-005: Budget Health

User story:

As a user, I want to see plan health so that I can quickly identify plans that are close to or over budget.

Acceptance criteria:

- WHEN budgets exist, THE SYSTEM SHALL show up to 5 latest budgets by created date and id.
- WHEN a budget has spent more than income, THE SYSTEM SHALL mark the row with danger tone.
- WHEN a budget has no income, THE SYSTEM SHALL show 0 percent progress.
- WHEN no budgets exist, THE SYSTEM SHALL show `Belum ada plan.`

### Requirement DSH-006: Recent and Top Expenses

User story:

As a user, I want recent and largest expenses so that I can review what just happened and what had the highest impact.

Acceptance criteria:

- WHEN expenses exist, THE SYSTEM SHALL show up to 5 newest expenses in Recent.
- WHEN expenses exist, THE SYSTEM SHALL show up to 5 largest expenses in Top.
- WHEN an expense has no label, THE SYSTEM SHALL display `Tanpa label`.
- WHEN no recent expenses exist, THE SYSTEM SHALL show `Belum ada pengeluaran terbaru.`
- WHEN no top expenses exist, THE SYSTEM SHALL show `Belum ada pengeluaran di tampilan ini.`

### Requirement DSH-007: Platform and Status Breakdowns

User story:

As a user, I want platform and status breakdowns so that I can understand where money was spent and how it is allocated.

Acceptance criteria:

- WHEN platform data exists, THE SYSTEM SHALL show platform rows ordered by total spend descending.
- WHEN no platform data exists, THE SYSTEM SHALL show `Belum ada data platform.`
- WHEN status data exists, THE SYSTEM SHALL show status rows ordered by total spend descending.
- WHEN no status data exists, THE SYSTEM SHALL show `Belum ada data status.`

### Requirement DSH-008: Onboarding Completion

User story:

As a new user, I want a dashboard welcome step so that I understand the page after creating my first budget and expense.

Acceptance criteria:

- WHEN a user needs onboarding and has at least one budget with spends, THE SYSTEM SHALL show the dashboard welcome tour.
- WHEN the user clicks `Mengerti`, THE SYSTEM SHALL set `onboarding_completed_at` and close the welcome tour.
- WHEN a user does not need onboarding, THE SYSTEM SHALL not show the welcome tour.

## 10. Design Notes

### Component Responsibilities

`App\Livewire\Dashboard` currently handles:

- UI state for selected years.
- User-scoped query construction.
- Aggregation queries.
- Chart and heatmap data shaping.
- Onboarding completion.
- Rupiah formatting.

AGENTS.md direction:

- Future dashboard changes that add meaningful business logic should move aggregation and calculation logic into services or query objects.
- Livewire should remain focused on UI state, interaction, and render payload composition.

### View Responsibilities

`resources/views/livewire/dashboard.blade.php` currently handles:

- Dashboard layout and sections.
- Empty states.
- Chart SVG rendering.
- Heatmap rendering.
- Alpine-only interactions for chart selection, crosshair, and onboarding overlay behavior.

### Alpine Responsibilities

Allowed current Alpine usage:

- Welcome tour display and positioning.
- Category chart series selection.
- Category chart crosshair and tooltip summary.

Alpine must not:

- Fetch dashboard data.
- Calculate authoritative financial totals.
- Persist dashboard state to the database.

## 11. Data and Query Contracts

### Ownership Scope

Every dashboard query must be scoped to authenticated user data:

```php
whereHas('budget', fn ($query) => $query->where('user_id', auth()->id()))
```

or:

```php
Budget::query()->where('user_id', auth()->id())
```

### Relationship Loading

Queries returning expense models for display should eager load:

- `budget`
- `platform`
- `status`
- `label` when labels schema is ready

### Optional Label Schema

Dashboard must tolerate label schema not being ready by returning empty or unavailable states for label-dependent features.

## 12. Empty and Edge States

- No budgets: summary income is 0, budget health shows empty state.
- No spends: expense totals are 0, recent/top/platform/status sections show empty states.
- Labels unavailable: chart and heatmap show setup-oriented empty states.
- Labels available but no selected-year data: chart and heatmap show no-data empty states.
- Negative remaining balance: remaining metric uses danger styling.
- Invalid chart year: category chart falls back to default available year.
- Invalid heatmap year: heatmap falls back to current year.
- Very large chart percentages: spending progress and budget health clamp visual width to 100 where implemented.

## 13. Test Strategy

### Feature Tests

Recommended coverage:

- Authenticated verified user can render the dashboard.
- Guest cannot access dashboard.
- Unverified user is redirected to email verification.
- Dashboard displays totals scoped to the current user only.
- Dashboard does not include another user's budgets or spends.
- Empty dashboard renders without errors.
- User needing onboarding with a spend sees welcome state.
- Completing onboarding stores `onboarding_completed_at`.

### Livewire Tests

Recommended coverage:

- `setCategoryChartPeriod` accepts an available year.
- `setCategoryChartPeriod` ignores an unavailable year.
- Render payload includes expected keys.
- Invalid `labelActivityYear` falls back safely.

### Scenario Tests for Financial Calculations

Recommended coverage:

- Remaining balance equals income minus expenses.
- Average transaction is 0 when there are no spends.
- Average transaction rounds deterministic integer output.
- Budget health percentage clamps at 100.
- Platform percentage uses `max(totalExpense, 1)` to avoid division by zero.

### Browser or UI Checks

Recommended coverage:

- Dashboard page has no horizontal overflow on mobile.
- Chart empty states render correctly.
- Heatmap cells expose accessible labels.
- Alpine chart interactions do not throw JavaScript errors.

## 14. Traceability Matrix

| Requirement | Current Code Area | Suggested Test |
| --- | --- | --- |
| DSH-001 | `routes/web.php`, `Dashboard.php` | Feature access tests |
| DSH-002 | `totalIncome`, `totalExpense`, `averageTransaction`, render payload | Financial scenario tests |
| DSH-003 | `categoryBudgetChart`, `setCategoryChartPeriod`, chart view section | Livewire and view tests |
| DSH-004 | `labelActivityHeatmap`, `labelActivityYears`, heatmap view section | Livewire and accessibility checks |
| DSH-005 | `budgetHealth` | Feature or unit scenario tests |
| DSH-006 | `recentExpenses`, `topExpenses`, `largestExpense` | Feature render tests |
| DSH-007 | `platformBreakdown`, `statusBreakdown` | Feature render tests |
| DSH-008 | `shouldShowOnboardingWelcome`, `completeOnboarding` | Livewire onboarding tests |

## 15. Implementation Task Backlog

These are documentation-derived tasks for future hardening, not changes made by this spec.

- Move dashboard aggregation logic into a `DashboardService` or query object when dashboard behavior is next changed.
- Add focused Dashboard feature tests for access, empty state, and cross-user scoping.
- Add financial scenario tests for summary calculations.
- Add Livewire tests for chart period validation and onboarding completion.
- Consider exposing a visible dashboard search input or removing unused `search` state.
- Consider making Reports a dedicated page or removing the redirected route from navigation expectations.
- Consider normalizing budget income and spend amount database types in a future schema plan.

## 16. Open Questions

- Should Dashboard support custom date ranges beyond year selection?
- Should label activity use weekly buckets instead of two-week buckets?
- Should Budget Health include a warning tone before a plan reaches 100 percent?
- Should Dashboard include links from each section to filtered Spends, Plan, or Investment pages?
- Should `search` become a visible control for label/category analytics?
- Should the Dashboard chart and heatmap move to reusable view components?
