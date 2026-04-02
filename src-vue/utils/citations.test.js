import { describe, it, expect } from 'vitest'
import { parseCitedRefs, renumberCitations, renderCitationSups } from './citations.js'

describe('parseCitedRefs', () => {
  it('extracts single ref [1]', () => {
    expect(parseCitedRefs('text [1] more')).toEqual(new Set([1]))
  })

  it('extracts multiple refs [1, 2]', () => {
    expect(parseCitedRefs('text [1, 2] more')).toEqual(new Set([1, 2]))
  })

  it('extracts non-sequential refs [1, 2, 4]', () => {
    expect(parseCitedRefs('text [1, 2, 4] more')).toEqual(new Set([1, 2, 4]))
  })

  it('extracts ref with timestamp [1: 30:02]', () => {
    expect(parseCitedRefs('text [1: 30:02] more')).toEqual(new Set([1]))
  })

  it('extracts ref with multiple timestamps [1: 30:02, 31:59]', () => {
    expect(parseCitedRefs('text [1: 30:02, 31:59] more')).toEqual(new Set([1]))
  })

  it('extracts multiple separate citations', () => {
    expect(parseCitedRefs('see [1] and [3] also [5]')).toEqual(new Set([1, 3, 5]))
  })

  it('deduplicates refs across multiple citations', () => {
    expect(parseCitedRefs('[1, 2] and [2, 3]')).toEqual(new Set([1, 2, 3]))
  })

  it('returns empty set for no citations', () => {
    expect(parseCitedRefs('no citations here')).toEqual(new Set())
  })

  it('ignores markdown links like [text](url)', () => {
    expect(parseCitedRefs('[click here](https://example.com)')).toEqual(new Set())
  })

  it('handles refs without spaces [1,2,4]', () => {
    expect(parseCitedRefs('[1,2,4]')).toEqual(new Set([1, 2, 4]))
  })
})

describe('renumberCitations', () => {
  it('renumbers single ref', () => {
    const remap = { 3: 1 }
    expect(renumberCitations('text [3] more', remap)).toBe('text [1] more')
  })

  it('renumbers multiple refs', () => {
    const remap = { 1: 1, 2: 2, 4: 3 }
    expect(renumberCitations('text [1, 2, 4] more', remap)).toBe('text [1, 2, 3] more')
  })

  it('renumbers ref with timestamp (strips timestamp)', () => {
    const remap = { 1: 1 }
    expect(renumberCitations('text [1: 30:02, 31:59] more', remap)).toBe('text [1] more')
  })

  it('keeps unmapped refs as-is', () => {
    const remap = { 1: 1 }
    expect(renumberCitations('text [1] and [5] more', remap)).toBe('text [1] and [5] more')
  })

  it('renumbers multiple citations in same text', () => {
    const remap = { 2: 1, 4: 2 }
    expect(renumberCitations('[2] foo [4] bar', remap)).toBe('[1] foo [2] bar')
  })
})

describe('renderCitationSups', () => {
  const cls = 'test-class'

  it('renders single ref as superscript with data-cite-ref', () => {
    expect(renderCitationSups('text [1] more', cls)).toBe('text <sup class="test-class" data-cite-ref="1">1</sup> more')
  })

  it('renders multiple refs as separate superscripts', () => {
    expect(renderCitationSups('text [1, 2] more', cls)).toBe(
      'text <sup class="test-class" data-cite-ref="1">1</sup><sup class="test-class" data-cite-ref="2">2</sup> more',
    )
  })

  it('renders ref with timestamp (strips timestamp)', () => {
    expect(renderCitationSups('text [1: 30:02] more', cls)).toBe(
      'text <sup class="test-class" data-cite-ref="1">1</sup> more',
    )
  })

  it('leaves non-citation brackets alone', () => {
    expect(renderCitationSups('[click here](url)', cls)).toBe('[click here](url)')
  })
})
