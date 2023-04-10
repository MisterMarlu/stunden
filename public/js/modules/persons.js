import Request from './Request.js'

const container = document.querySelector('#persons')
const input = document.querySelector('#person-name')
const form = document.querySelector('#person-form')
const nameSelections = document.querySelectorAll('select[name="name"]')

let _loading = false

export default function enablePersons () {
  container.querySelectorAll('li').forEach(li => {
    addSelectOption(li.innerText, li.dataset.id)
  })
  form.addEventListener('submit', addPerson)
}

async function addPerson(event) {
  event.preventDefault()

  if (_loading) {
    return
  }

  _loading = true
  const personName = input.value

  await Request.post('/add-person', {name: personName})
  _loading = false

  input.value = ''
  const person = document.createElement('li')
  person.innerText = personName
  container.appendChild(person)
  addSelectOption(personName, personName)
}

function addSelectOption(name, value = '0') {
  const personOption = document.createElement('option')
  personOption.value = value
  personOption.innerText = name

  nameSelections.forEach(select => {
    select.appendChild(personOption)
  })
}