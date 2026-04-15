const { defineConfig } = require('cypress')
const { moveDownloads } = require('./tasks/moveDownloads')
const { deleteDownloads } = require('./tasks/deleteDownloads')
const { getPdfProperties } = require('./tasks/getPdfProperties')

module.exports = defineConfig({
  video: false,
  pageLoadTimeout: 120000,
  defaultCommandTimeout: 8000,
  screenshotsFolder: 'data/screenshots',
  videosFolder: 'data/videos',
  downloadsFolder: 'data/downloads',
  trashAssetsBeforeRuns: false,
  e2e: {
    experimentalStudio: true,
    setupNodeEvents(on, config) {
      on('task', {
        deleteDownloads: () => deleteDownloads(config),
        getPdfProperties: async (path) => getPdfProperties(path),
        moveDownloads: (destSubDir) => moveDownloads(config, destSubDir),
      })
      const cypressTerminalReportOptions = {
        printLogsToConsole: 'always',
      }
      require('cypress-terminal-report/src/installLogsPrinter')(
        on,
        cypressTerminalReportOptions
      )
    },
    specPattern: 'specs/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: 'support/index.js',
    baseUrl: 'http://localhost:3000',
  },
  env: {
    PRINT_URL: 'http://localhost:3000/print',
    API_ROOT_URL: 'http://localhost:3000/api',
    API_ROOT_URL_CACHED: 'http://localhost:3004',
  },
})
