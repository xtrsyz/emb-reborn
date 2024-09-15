#### \# App Construct:
- load internal default config
- build properties
- load config default
- load config single domain
- load database
- load config from database [`rule_`, `site_`]
- load add-ons
- set router (rule => route)

#### \# run()
- initializing CLI
- action hook: `init`
- dispatch route by matching rule
- www redirector
- https redirector
- action hook: `ready`
- action hook: `<route>_ready`
- invoke: core init
- invoke: core router
- action hook: `finish`
- action hook: `<route>_finish`

#### \# Invoke core router
- prepare content from db
- action hook: `content_ready`
- action hook: `<route>_content_ready`
- do some stuff
- action hook: `done`
- action hook: `<route>_done`
- render view

#### \# Render View
- action hook: `render`
- action hook: `<route>_render`
- invoke: theme init
- invoke: theme router file

### \# Rule => Route
- `rule_home` => `home`
- `rule_home_paged` => `home`
- `rule_category` => `category`
- `rule_category_paged` => `category`
- `rule_tag` => `tag`
- `rule_tag_paged` => `tag`
- `rule_search` => `search`
- `rule_search_paged` => `search`
- `rule_post` => `post`
- `rule_page` => `page`
- `rule_sitemap` => `sitemap`
- `rule_sitemap_paged` => `sitemap`
- `rule_dashboard` => `dashboard`
- `rule_dashboard.'/%route%'` => `dashboard`
- `rule_dashboard.'/%route%/%method%'` => `dashboard`
- `rule_dashboard.'/%route%/%method%/%target%'` => `dashboard`
