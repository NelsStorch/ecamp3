import cypressEslint from 'eslint-plugin-cypress/flat'
import { includeIgnoreFile } from '@eslint/compat'
import globals from 'globals'
import path from 'node:path'
import { fileURLToPath } from 'node:url'
import js from '@eslint/js'
import prettierRecommended from 'eslint-plugin-prettier/recommended'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const gitignorePath = path.resolve(__dirname, '.gitignore')

export default [
  js.configs.recommended,
  cypressEslint.configs.recommended,
  prettierRecommended,
  {
    ignores: ['data/'],
  },

  includeIgnoreFile(gitignorePath),

  {
    languageOptions: {
      globals: {
        ...globals.node,
        ...globals.mocha,
      },

      ecmaVersion: 2022,

      parserOptions: {
        parser: '@babel/eslint-parser',
      },
    },

    rules: {
      'prefer-const': 'error',
      'prettier/prettier': 'error',
    },
  },
]
