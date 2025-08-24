// https://docs.cypress.io/api/introduction/api.html

const path = require('path')

describe('Nuxt print test', () => {
  it('shows print preview', () => {
    cy.login('test@example.com')

    cy.request('/api/camps.jsonhal').then((response) => {
      const body = response.body
      const camp = body._embedded.items.filter((c) => c.motto)[0]
      const campUri = camp._links.self.href
      const campPeriodsLink = camp._links.periods.href
      cy.request(campPeriodsLink).then((periodsResponse) => {
        const period = periodsResponse.body._embedded.items[0]
        const periodUri = period._links.self.href

        const printConfig = {
          language: 'en',
          documentName: 'camp',
          camp: campUri,
          contents: [
            {
              type: 'Cover',
              options: {},
            },
            {
              type: 'Picasso',
              options: {
                periods: [periodUri],
                orientation: 'L',
              },
            },
            {
              type: 'Story',
              options: {
                periods: [periodUri],
                contentType: 'Storycontext',
              },
            },
            {
              type: 'Program',
              options: {
                periods: [periodUri],
                dayOverview: true,
              },
            },
            {
              type: 'Toc',
              options: {},
            },
          ],
        }

        cy.visit(
          Cypress.env('PRINT_URL') +
            '/?config=' +
            encodeURIComponent(JSON.stringify(printConfig))
        )
        cy.contains(camp.title)
        cy.contains(camp.motto)

        cy.get('#content_0_cover').should('have.css', 'font-size', '50px') // this ensures Tailwind is properly built and integrated
      })
    })
  })

  describe('downloads PDF', () => {
    beforeEach(() => {
      cy.task('deleteDownloads')
      cy.login('test@example.com')

      cy.visit('/camps')
      cy.get('a:contains("GRGR")').click()
    })

    afterEach(() => {
      cy.moveDownloads()
    })

    it('for whole camp', () => {
      cy.get('a:contains("Admin")').click()
      cy.get('a:contains("Drucken")').click()
      cy.get('button:contains("PDF herunterladen (Layout #1)")').click()

      const downloadsFolder = Cypress.config('downloadsFolder')
      const pdfPath = path.join(downloadsFolder, 'Pfila-2023.pdf')
      cy.readFile(pdfPath, {
        timeout: 30000,
      })
      cy.getPdfProperties(pdfPath).its('numPages').should('eq', 25)
    })

    it('for picasso', () => {
      if (Cypress.browser.name === 'firefox') {
        console.log(
          "This test doesn't test browser specific behaviour. Firefox makes problems, thus we dont test this with firefox."
        )
        return
      }

      cy.get('a:contains("Programm")').click()
      cy.get('[data-testid="campprogram-menu"]').click()
      cy.get('[role="menuitem"] :contains("PDF herunterladen (Layout #1)")')
        .should('be.visible')
        .click()

      const downloadsFolder = Cypress.config('downloadsFolder')
      const pdfPath = path.join(downloadsFolder, 'Pfila-2023-Hauptlager.pdf')
      cy.readFile(pdfPath, {
        timeout: 30000,
      })
      cy.getPdfProperties(pdfPath).its('numPages').should('eq', 1)
    })
  })
})
