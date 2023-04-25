import selector from "./selector.js";
import Formatter from "./Formatter.js";

export default class {
    #total
    #personSelect
    #from
    #to
    #dayMinutes

    constructor(container) {
        this.#personSelect = container.querySelector(selector.name)
        this.#from = container.querySelector(selector.from)
        this.#to = container.querySelector(selector.to)
        this.#total = container.querySelector(selector.result)
        this.#dayMinutes = 24 * 60
        this.#listen()
    }

    initiate() {
        this.#updateTotal()
        this.#updateColor()
    }

    #listen() {
        this.#personSelect.addEventListener('change', this.#updateColor.bind(this))
        this.#from.addEventListener('change', this.#fire.bind(this))
        this.#to.addEventListener('change', this.#fire.bind(this))
    }

    #updateColor() {
        const option = this.#personSelect.options[this.#personSelect.selectedIndex]

        if (option.dataset.color) {
            this.#personSelect.style.backgroundColor = option.dataset.color
            return
        }

        this.#personSelect.style.backgroundColor = null
    }

    #fire() {
        document.dispatchEvent(new CustomEvent('updateTotal', {detail: this.getPerson()}))
        this.#updateTotal()
    }

    #updateTotal() {
        this.#total.innerText = Formatter.getFormatted(this.getMinutes())
    }

    getMinutes() {
        if (this.#from.value.length === 0 || this.#to.value.length === 0) {
            return 0
        }

        return this.#calcDiff(this.#toMinutes(this.#from.value), this.#toMinutes(this.#to.value))
    }

    getPerson() {
        return this.#personSelect.value
    }

    #calcDiff(from, to) {
        if (from < to) {
            return to - from
        }

        return from - to + this.#dayMinutes
    }

    #toMinutes(timeString) {
        let minuteArray = timeString.split(':')
            .map((value, i) => {
                return i === 0 ? parseInt(value) * 60 : parseInt(value)
            })

        let minutes = 0

        minuteArray.forEach(part => {
            minutes += part
        })

        return minutes
    }

}