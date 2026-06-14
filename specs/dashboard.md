# Dashboard

## Purpose
Provide a quick overview of the user's financial condition.

## User Story
As a user,
I want to see my financial summary and spending patterns,
so that I can understand my money situation quickly.

## Features
### Summary Metrics
Display:
- Total Income
- Savings Rate
- Remaining Balance

Acceptance Criteria
- Income is calculated from all user plans.
- Remaining balance = income - expenses.
- Negative balance uses danger styling.

### Savings Rate Card
Display:
- Savings Rate %
- Total Saved

Interaction:
- Click card → open detail drawer (desktop)
- Click card → open bottom sheet (mobile)

Acceptance Criteria:
- User can understand how savings rate is calculated.
- User can compare current period vs previous period.

Empty States:
- No income.
- No savings.

### Category Trend

Purpose:
Show spending trend by category.

Acceptance Criteria:
- Show top 5 categories.
- Allow year selection.
- Show empty state when no data exists.

### Budget Health
Purpose:
Show latest budget performance.

Acceptance Criteria:
- Show up to 5 latest budgets.
- Highlight overspent budgets.
- Show empty state when no budget exists.