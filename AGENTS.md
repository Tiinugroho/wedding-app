# AGENTS.md

## Scope
- This is a Laravel 12 wedding invitation app with Blade-first frontend templates and a small Vite/Tailwind asset layer.
- Prefer small, local edits. Avoid broad refactors unless they are required for the task.
- Link to existing docs instead of repeating them.

## Useful Commands
- Backend tests: `composer test`
- Local dev stack: `composer run dev`
- Frontend dev server: `npm run dev`
- Frontend production build: `npm run build`
- Full setup flow: `composer run setup`

## Main Edit Areas
- Routes: [routes/web.php](routes/web.php)
- Guest invitation controller: [app/Http/Controllers/FrontController.php](app/Http/Controllers/FrontController.php)
- Invitation templates: [resources/views/template](resources/views/template)
- Shared helpers: [app/Helpers/helpers.php](app/Helpers/helpers.php)
- WhatsApp bridge: [wa-server.js](wa-server.js)

## Conventions
- Keep guest invitation routes at the bottom of [routes/web.php](routes/web.php). The `/{slug}` route must stay below more specific routes.
- Treat preview mode as a restriction on sharing actions, not as a URL rewrite. Do not reintroduce address-bar manipulation in templates.
- When adding RSVP or gift flows, keep the Blade template, controller response, and route names aligned.
- Use the existing template style when adding modals, toast messages, and form behavior. Match the current template rather than applying a generic UI.
- If you touch queue, storage, or webhook behavior, check the corresponding config files before changing code.

## Pitfalls
- The dev command starts a queue listener and Vite together; queued features may look different if the worker is not running.
- Tests use the repository’s configured environment, so database and queue behavior may not match production defaults.
- The WhatsApp server is a separate Node process; Laravel changes alone do not update that service.
- Midtrans webhook and slug routes have ordering and middleware constraints; be careful before moving them.

## References
- [README.md](README.md)
- [composer.json](composer.json)
- [package.json](package.json)
- [routes/web.php](routes/web.php)
- [routes/console.php](routes/console.php)
- [bootstrap/app.php](bootstrap/app.php)
- [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
- [config/filesystems.php](config/filesystems.php)
- [config/queue.php](config/queue.php)

## When to Add More Customization
- Create a separate instruction file if a pattern is specific to one area, such as templates, admin flows, or WhatsApp integration.
- Create a custom agent if you need a repeatable multi-step workflow with tighter tool limits.
- Create a prompt if the task is a single, repeatable action with clear inputs.
