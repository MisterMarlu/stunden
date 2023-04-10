import enablePersons from './modules/persons.js'

const currentMonthNumber = parseInt(document.querySelector('#month').value)
const print = document.querySelector('#print')

print.addEventListener('click', () => {
  window.print()
})
enablePersons()