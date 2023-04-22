import selector from "./selector.js";
import Shift from "./Shift.js";
import Formatter from "./Formatter.js";

export default class Calculation {
    #shifts = []
    #totalContainer

    constructor() {
        this.#totalContainer = document.querySelector(selector.monthResult)
        document.querySelectorAll(selector.row).forEach(row => {
            row.querySelectorAll(selector.shift).forEach(shift => {
                this.#shifts.push(new Shift(shift))
            })
        })

        this.#updateTotal()
        this.#listen()
    }

    #listen() {
        document.addEventListener('updateTotal', this.#updateTotal.bind(this))
    }

    #updateTotal() {
        let persons = {}

        for (let i = 0; i < this.#shifts.length; i += 1) {
            const shift = this.#shifts[i]

            if (!persons.hasOwnProperty(shift.getPerson())) {
                persons[shift.getPerson()] = 0
            }

            persons[shift.getPerson()] += shift.getMinutes()
        }

        for (const id in persons) {
            const personTotal = this.#totalContainer.querySelector(`[data-id="${id}"]`)

            if (!personTotal) {
                continue
            }

            personTotal.innerText = Formatter.getFormatted(persons[id])
        }
    }
}