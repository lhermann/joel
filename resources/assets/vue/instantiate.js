/* Instantiate Vue Components
 *****************************/

export default {
  instances: [],
  add(selector, instantiator) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`);
    elements.forEach(el =>
      this.instances.push(instantiator("#" + el.getAttribute("id")))
    );
  }
};
