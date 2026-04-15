const pdfjsLib = require('pdfjs-dist/legacy/build/pdf.mjs')
const { readFileSync } = require('node:fs')

async function getPdfProperties(path) {
  const data = new Uint8Array(readFileSync(path))

  const loadDocument = pdfjsLib.getDocument({ data: data })
  const pdfDocument = await loadDocument.promise
  return {
    numPages: pdfDocument.numPages,
  }
}

module.exports = { getPdfProperties }
