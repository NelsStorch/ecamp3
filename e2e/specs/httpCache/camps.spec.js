it("doesn't cache /camps", () => {
  const uri = '/api/camps'
  Cypress.session.clearAllSavedSessions()
  cy.login('test@example.com')
  cy.expectCachePass(uri)
})
