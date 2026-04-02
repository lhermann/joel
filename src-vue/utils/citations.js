/**
 * Citation parsing utilities for Zeteo chat.
 *
 * The LLM produces citation markers in various formats:
 *   [1]              — single ref
 *   [1, 2]           — multiple refs
 *   [1, 2, 4]        — non-sequential refs
 *   [1: 30:02]       — ref with timestamp
 *   [1: 30:02, 31:59] — ref with multiple timestamps
 *   (1 13:41, 2 11:31) — parenthesized refs with timestamps
 *
 * All regexes extract just the leading ref number(s) and ignore
 * trailing timestamp/extra content after a colon.
 */

/**
 * Regex matching citation markers in text.
 * Captures the ref number(s) in group 1, ignores optional `: ...` suffix.
 */
export const CITATION_PATTERN = /\[(\d+(?:\s*,\s*\d+)*)(?::[^\]]*)?\]/g

/**
 * Extract all cited ref numbers from text.
 * @param {string} text
 * @returns {Set<number>}
 */
export function parseCitedRefs (text) {
  const refs = new Set()
  const pattern = new RegExp(CITATION_PATTERN.source, 'g')
  let match
  while ((match = pattern.exec(text)) !== null) {
    for (const num of match[1].split(',')) {
      refs.add(parseInt(num.trim(), 10))
    }
  }
  return refs
}

/**
 * Renumber citation markers in text using a remap object.
 * @param {string} text
 * @param {Object<number, number>} refRemap - maps old ref → new ref
 * @returns {string}
 */
export function renumberCitations (text, refRemap) {
  const pattern = new RegExp(CITATION_PATTERN.source, 'g')
  return text.replace(pattern, (match, nums) => {
    const remapped = nums.split(',').map(n => {
      const orig = n.trim()
      return refRemap[orig] != null ? String(refRemap[orig]) : orig
    })
    return `[${remapped.join(', ')}]`
  })
}

/**
 * Convert citation markers in HTML to styled superscripts.
 * @param {string} html
 * @param {string} [className] - CSS classes for the <sup> element
 * @returns {string}
 */
export function renderCitationSups (html, className = 'text-[10px] text-blue-600/70 font-medium ml-px cursor-pointer hover:text-blue-800') {
  const pattern = new RegExp(CITATION_PATTERN.source, 'g')
  return html.replace(
    pattern,
    (_, nums) => nums.split(',').map(n => {
      const ref = n.trim()
      return `<sup class="${className}" data-cite-ref="${ref}">${ref}</sup>`
    }).join(''),
  )
}
