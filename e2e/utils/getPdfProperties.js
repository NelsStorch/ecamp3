import { getDocument } from 'pdfjs-dist'

async function getPdfProperties(buffer) {
  const data = new Uint8Array(buffer)
  const loadDocument = getDocument({ data: data })
  const pdfDocument = await loadDocument.promise
  return {
    numPages: pdfDocument.numPages,
  }
}

export { getPdfProperties }
