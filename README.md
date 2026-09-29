# Task Manager

A small but complete full-stack task manager built with **Laravel 12**, **SQLite**, and **Tailwind CSS 4**. It ships a polished Blade web UI and a JSON REST API over the same domain model — a compact reference for how a Laravel app is wired end to end: migrations, Eloquent, form requests, resource controllers, API resources, factories, seeders, and feature tests.

![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Tests](https://img.shields.io/badge/tests-26%20passed-brightgreen)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## Features

- **Task CRUD** — create, view, edit, and delete tasks with title, description, status, priority, and due date.
- **One-click complete** — toggle any task between done and pending straight from the list or detail page; completion timestamps are stamped and cleared automatically.
- **Smart list** — search by title/description, filter by status and priority, and a default ordering that floats open, high-priority, and soon-due work to the top while sinking completed items.
- **Dashboard stats** — live counts for pending, in progress, done, and overdue tasks.
- **Overdue awareness** — due-date chips turn red when a task is past due and amber when it is due within three days; completed tasks are never flagged overdue.
- **REST API** — the same resources exposed as JSON at `/api/tasks`, with pagination, filtering, validation errors, and a stats endpoint.
- **Validation everywhere** — shared form requests guard both the web and API surfaces, with inline error messages in the UI and `422` payloads over JSON.
- **Tested** — 26 feature tests (113 assertions) covering web flows, API contracts, validation, and the completion-timestamp behaviour.

## Tech stack

| Layer      | Choice                          |
| ---------- | ------------------------------- |
| Framework  | Laravel 12 (PHP ≥ 8.2)          |
| Database   | SQLite (zero-config)            |
| Frontend   | Blade components + Tailwind CSS 4 |
| Build      | Vite 7                          |
| Testing    | PHPUnit via `php artisan test`  |

## Requirements

- PHP **8.2+** with the `sqlite3` extension
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) 18+ and npm

## Quick start

```bash
# 1. Clone and enter the project
git clone <your-repo-url> task-manager
cd task-manager

# 2. Install PHP dependencies
composer install

# 3. Prepare the environment
cp .env.example .env
php artisan key:generate

# 4. Create the SQLite database, run migrations, and load demo data
touch database/database.sqlite
php artisan migrate --seed

# 5. Install and build frontend assets
npm install
npm run build        # or: npm run dev  (for live reload while developing)

# 6. Serve it
php artisan serve
```

Open **http://127.0.0.1:8000** — you will land on the task board, pre-populated with 20 demo tasks (6 curated + 14 generated) so every state — pending, in progress, done, overdue — is visible immediately.

To reset the demo data at any time:

```bash
php artisan migrate:fresh --seed
```

## Using the web app

| Page            | Route              | What it does                                        |
| --------------- | ------------------ | --------------------------------------------------- |
| Task board      | `/tasks`           | Stats, filters, search, paginated list, quick toggle |
| New task        | `/tasks/create`    | Validated creation form                              |
| Task detail     | `/tasks/{id}`      | Full detail, mark done / reopen, edit link           |
| Edit task       | `/tasks/{id}/edit` | Update form plus a danger-zone delete                |

`/` redirects to `/tasks`.

## API reference

All endpoints live under `/api`. Send `Accept: application/json` to get JSON error payloads.

| Method     | Endpoint             | Description                              |
| ---------- | -------------------- | ---------------------------------------- |
| `GET`      | `/api/tasks`         | Paginated list (`q`, `status`, `priority`, `per_page`) |
| `POST`     | `/api/tasks`         | Create a task (`201` on success)         |
| `GET`      | `/api/tasks/{id}`    | Fetch one task                           |
| `PUT`/`PATCH` | `/api/tasks/{id}` | Update a task (partial updates allowed)  |
| `DELETE`   | `/api/tasks/{id}`    | Delete a task                            |
| `GET`      | `/api/tasks/stats`   | Aggregate counts by status + overdue     |

### Examples

```bash
# List open high-priority tasks
curl "http://127.0.0.1:8000/api/tasks?status=pending&priority=high" \
  -H "Accept: application/json"

# Create a task
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"title":"Ship the readme","priority":"high","due_date":"2026-10-15"}'

# Complete a task
curl -X PATCH http://127.0.0.1:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"done"}'

# Summary statistics
curl http://127.0.0.1:8000/api/tasks/stats -H "Accept: application/json"
```

A single-task response looks like this:

```json
{
  "data": {
    "id": 21,
    "title": "Ship the readme",
    "description": null,
    "status": "pending",
    "priority": "high",
    "due_date": "2026-10-15",
    "is_overdue": false,
    "completed_at": null,
    "created_at": "2026-09-29T00:00:00+00:00",
    "updated_at": "2026-09-29T00:00:00+00:00",
    "links": {
      "self": "http://127.0.0.1:8000/api/tasks/21"
    }
  }
}
```

Invalid input returns `422` with a per-field error map:

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

### Field reference

| Field         | Type            | Rules                                    |
| ------------- | --------------- | ---------------------------------------- |
| `title`       | string          | required on create, max 255, trimmed     |
| `description` | string \| null  | max 5000                                 |
| `status`      | string          | `pending`, `in_progress`, `done` (default `pending`) |
| `priority`    | string          | `low`, `medium`, `high` (default `medium`) |
| `due_date`    | date \| null    | any valid date                           |

`completed_at` is read-only: setting `status` to `done` stamps it, and moving a task back to `pending` or `in_progress` clears it.

## Running the tests

```bash
php artisan test
```

```
Tests:    26 passed (113 assertions)
```

The suite uses an in-memory SQLite database, so it never touches your dev data. Coverage includes:

- web CRUD flows, redirects, and flash messages
- list filtering and search
- the done/pending toggle and completion-timestamp lifecycle
- API contracts: pagination shape, filtering, `201`/`422`/`404` responses
- the stats aggregate, including overdue detection

## Project structure

The interesting parts, beyond the standard Laravel skeleton:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── TaskController.php        # Web CRUD + toggle
│   │   └── Api/TaskController.php    # JSON REST endpoints
│   ├── Requests/
│   │   ├── StoreTaskRequest.php      # Create validation
│   │   └── UpdateTaskRequest.php     # Partial-update validation
│   └── Resources/
│       └── TaskResource.php          # API serialization
├── Models/
│   └── Task.php                      # Scopes, stats(), completion lifecycle
database/
├── factories/TaskFactory.php         # Realistic fake tasks + overdue() state
├── migrations/*_create_tasks_table.php
└── seeders/TaskSeeder.php            # Curated + generated demo data
resources/views/
├── components/                       # Layout, badges, chips, fields, stats
└── tasks/                            # index, create, edit, show, form partial
routes/
├── web.php                           # UI routes + toggle
└── api.php                           # REST routes + stats
tests/Feature/
├── TaskWebTest.php
└── TaskApiTest.php
```

## Configuration

Everything runs on SQLite with no external services. The defaults in `.env.example` are sufficient; the only values you may want to change are:

| Key            | Default  | Notes                              |
| -------------- | -------- | ---------------------------------- |
| `APP_NAME`     | `Laravel`| Shown in the browser title         |
| `DB_CONNECTION`| `sqlite` | Uses `database/database.sqlite`    |

## Ideas for next steps

- Authentication and per-user task lists (Laravel Breeze or Fortify)
- Comments, tags, and task attachments
- Drag-and-drop reordering and a kanban view
- Email/Slack digests for overdue tasks via scheduled commands
- Token-authenticated API (Sanctum) and rate limiting

## License

MIT — free to use, modify, and redistribute.
