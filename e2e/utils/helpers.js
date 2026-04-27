import { expect, request, test } from '@playwright/test'
export const API_ROOT_URL = process.env.API_ROOT_URL || 'http://localhost:3000/api'
export const API_ROOT_URL_CACHED =
  process.env.API_ROOT_URL_CACHED || 'http://localhost:3004'

export async function login(request, identifier, password = 'test') {
  const response = await request.post(`${API_ROOT_URL}/authentication_token`, {
    data: { identifier, password },
  })
  expect([204]).toContain(response.status())
}

/**
 * @param page import('@playwright/test').Page
 * @param user string
 * @param password string
 * @return {Promise<void>}
 */
export async function loginAndSetCookie(page, _, user, password = 'test') {
  await page.goto('/')
  await page.locator('[type="email"]').fill(user)
  await page.locator('[type="password"]').fill(password)
  await Promise.all([
    page.locator('[type="submit"]').click(),
    page.waitForURL('/camps', { timeout: 60000 }),
  ])
}

/**
 * @param user string
 * @return {Promise<import('@playwright/test').APIRequestContext>}
 */
export async function getAuthContext(user) {
  const defaultRequest = await request.newContext()
  await login(defaultRequest, user)
  const state = await defaultRequest.storageState()
  await defaultRequest.dispose()
  return await request.newContext({
    storageState: state,
  })
}

export { getPdfProperties } from './getPdfProperties'

export async function expectCacheHeader(request, uri, expectedHeader) {
  await test.step(
    `Check Header: ${expectedHeader}`,
    async () => {
      const response = await request.get(`${API_ROOT_URL_CACHED}${uri}.jsonhal`)
      expect(response.headers()['x-cache']).toBe(expectedHeader)
    },
    { box: true }
  )
}

export async function expectCacheHit(request, uri) {
  await test.step(
    'Expect Cache HIT',
    async () => {
      await expectCacheHeader(request, uri, 'HIT')
    },
    { box: true }
  )
}

export async function expectCacheMiss(request, uri) {
  await test.step(
    'Expect Cache MISS',
    async () => {
      await expectCacheHeader(request, uri, 'MISS')
    },
    { box: true }
  )
}

export async function expectCachePass(request, uri) {
  await test.step(
    'Expect Cache PASS',
    async () => {
      await expectCacheHeader(request, uri, 'PASS')
    },
    { box: true }
  )
}

export async function waitForCacheMiss(request, uri) {
  await test.step(
    `Wait for Cache MISS on ${uri}`,
    async () => {
      await expect
        .poll(
          async () => {
            const response = await request.get(`${API_ROOT_URL_CACHED}${uri}.jsonhal`)
            return response.headers()['x-cache']
          },
          { timeout: 10000 }
        )
        .toBe('MISS')
    },
    { box: true }
  )
}

export async function apiGet(request, uri) {
  return await request.get(`${API_ROOT_URL_CACHED}${uri}.jsonhal`)
}

export async function apiPatch(request, uri, body) {
  return await request.patch(`${API_ROOT_URL_CACHED}${uri}.jsonhal`, {
    data: body,
    headers: {
      'Content-Type': 'application/merge-patch+json',
    },
  })
}

export async function apiPost(request, uri, body) {
  return await request.post(`${API_ROOT_URL_CACHED}${uri}.jsonhal`, {
    data: body,
    headers: {
      'Content-Type': 'application/hal+json',
    },
  })
}

export async function apiDelete(request, uri) {
  return await request.delete(`${API_ROOT_URL_CACHED}${uri}.jsonhal`)
}
