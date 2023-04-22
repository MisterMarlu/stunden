import enablePersons from './modules/persons.js'
import calculation from "./modules/calculation.js";

const currentMonthNumber = parseInt(document.querySelector('#month').value)
const print = document.querySelector('#print')

print.addEventListener('click', () => {
  window.print()
})
enablePersons()
new calculation()