/**
 * Card row horizontal scroll behavior.
 *
 * Uses native overflow-x scroll with CSS snap.
 * JS handles: prev/next button clicks, animated show/hide of buttons.
 *
 * Button visibility uses a Vue-style transition state machine:
 *   show: display→'', add .enter-from, next frame: remove .enter-from + add .enter-to
 *   hide: add .leave-from, next frame: remove .leave-from + add .leave-to, on transitionend: display→none
 *
 * Expected HTML structure (via data-ref attributes):
 *   [data-vue="cardRowScroll"]
 *     button[data-ref="prev"]
 *     div[data-ref="scroller"]
 *     button[data-ref="next"]
 */
export default function cardRowScroll (el) {
  const scroller = el.querySelector('[data-ref="scroller"]')
  const prevBtn = el.querySelector('[data-ref="prev"]')
  const nextBtn = el.querySelector('[data-ref="next"]')

  if (!scroller) return

  // Track visibility state per button
  const state = new Map()

  function transitionShow (btn) {
    if (!btn || state.get(btn) === 'visible') return
    state.set(btn, 'visible')

    // Start: make visible at enter-from state
    btn.style.display = ''
    btn.classList.add('enter-from')

    // Next frame: transition to enter-to
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        btn.classList.remove('enter-from')
        btn.classList.add('enter-to')

        function onEnd () {
          btn.classList.remove('enter-to')
          btn.removeEventListener('transitionend', onEnd)
        }
        btn.addEventListener('transitionend', onEnd, { once: true })
      })
    })
  }

  function transitionHide (btn) {
    if (!btn || state.get(btn) === 'hidden') return
    state.set(btn, 'hidden')

    // Start: leave-from state
    btn.classList.add('leave-from')

    // Next frame: transition to leave-to
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        btn.classList.remove('leave-from')
        btn.classList.add('leave-to')

        function onEnd () {
          btn.classList.remove('leave-to')
          btn.style.display = 'none'
          btn.removeEventListener('transitionend', onEnd)
        }
        btn.addEventListener('transitionend', onEnd, { once: true })
      })
    })
  }

  function updateState () {
    const { scrollLeft, scrollWidth, clientWidth } = scroller
    const atStart = scrollLeft <= 2
    const atEnd = scrollLeft + clientWidth >= scrollWidth - 2

    if (atStart) transitionHide(prevBtn)
    else transitionShow(prevBtn)

    if (atEnd) transitionHide(nextBtn)
    else transitionShow(nextBtn)
  }

  function scrollByPage (direction) {
    const pageWidth = scroller.clientWidth * 0.85
    scroller.scrollBy({ left: direction * pageWidth, behavior: 'smooth' })
  }

  if (prevBtn) prevBtn.addEventListener('click', () => scrollByPage(-1))
  if (nextBtn) nextBtn.addEventListener('click', () => scrollByPage(1))

  scroller.addEventListener('scroll', updateState, { passive: true })
  window.addEventListener('resize', updateState)

  // Initial state — hide immediately without transition
  if (prevBtn) { prevBtn.style.display = 'none'; state.set(prevBtn, 'hidden') }
  if (nextBtn) { nextBtn.style.display = 'none'; state.set(nextBtn, 'hidden') }
  updateState()
}
