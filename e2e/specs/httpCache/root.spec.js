const user1 = 'test@example.com'
const user2 = 'castor@example.com'
it('caches the root endpoint', () => {
  const uri = '/api/index'

  Cypress.session.clearAllSavedSessions()
  cy.login(user1)

  // first request is a cache miss
  cy.expectCacheMiss(uri)

  // second request is a cache hit
  cy.expectCacheHit(uri)

  cy.login(user2)
  cy.expectCacheMiss(uri)
})
