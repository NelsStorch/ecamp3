# End-to-end tests

As a pre-requisite for running end-to-end tests, we assume you have the application fully up and running on your system.
If not, please follow the documentation links in the README.md in the root of this repository.

## Option A: Run end-to-end tests in Docker container (headless)

### Install dependencies

```shell
docker compose --profile e2e run --rm e2e npm ci
```

### Update dependencies

```shell
docker compose --profile e2e run --rm e2e npm update
```

or

```shell
docker compose --profile e2e run --rm e2e "npm update <dependency>"
```

### Run all e2e tests

```shell
docker compose --profile e2e run --rm e2e npx playwright test
```

### Run a specific e2e test

```shell
docker compose --profile e2e run --rm e2e npx playwright test tests/login.spec.js
```

### Run tests using a specific browser

Supported browsers: `chromium`, `firefox`, `webkit`

```shell
docker compose --profile e2e run --rm e2e npx playwright test --project firefox
```

## Option B: Run end-to-end tests locally

### Install dependencies

```shell
npm install
npx playwright install
```

### Run end-to-end tests (CLI)

```shell
docker compose up -d
npm test
```

### Open Playwright UI

```shell
docker compose up -d
npm run test:ui
```

### Run lint

```shell
docker compose --profile e2e run --rm e2e npm run lint
```

## For both options: run against prod api image

### Run the dev api image to generate jwt pair

```shell
docker compose up -d --wait
```

### Build the prod api image

```shell
docker compose -f ../docker-compose.yml build api
```

### Run the prod api image

```shell
docker compose -f ../docker-compose.yml up --wait -d api
```
