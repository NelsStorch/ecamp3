// https://docs.cypress.io/api/introduction/api.html

const path = require('path')

describe('Client print test', () => {
  it('downloads PDF', () => {
    cy.task('deleteDownloads')
    cy.login('test@example.com')

    cy.visit('/camps')
    cy.get('a:contains("GRGR")').click()
    cy.get('a:contains("Admin")').click()
    cy.get('a:contains("Drucken")').click()
    cy.get('button:contains("PDF herunterladen (Layout #2)")').click()

    const downloadsFolder = Cypress.config('downloadsFolder')
    const filePath = path.join(downloadsFolder, 'Pfila-2023.pdf')
    cy.readFile(filePath, {
      timeout: 30000,
    })
    cy.getPdfProperties(filePath).its('numPages').should('eq', 18)
    cy.moveDownloads()
  })
})
