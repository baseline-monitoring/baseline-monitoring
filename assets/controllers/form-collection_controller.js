import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

  static targets = [ 'fields', 'field', 'addButton' ]
  static values = {
    prototype: String,
    maxItems: Number,
    itemsCount: Number,
  }

  connect() {
    this.index = this.itemsCountValue = this.fieldTargets.length
  }

  addItem() {
    let prototype = JSON.parse(this.prototypeValue)
    const newField = prototype.replace(/__name__/g, this.index)
    this.fieldsTarget.insertAdjacentHTML('beforeend', newField)
    this.index++
    this.itemsCountValue++
  }

  removeItem(event) {
    this.fieldTargets.forEach(element => {
      if (element.contains(event.target)) {
        element.remove()
        this.itemsCountValue--
      }
    })
  }

  itemsCountValueChanged() {
    if (false === this.hasAddButtonTarget || 0 === this.maxItemsValue) {
      return
    }
    const maxItemsReached = this.itemsCountValue >= this.maxItemsValue
    this.addButtonTarget.classList.toggle('hidden', maxItemsReached)
  }

}
