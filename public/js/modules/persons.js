import Request from './Request.js'
import selector from "./selector.js";

const container = document.querySelector(selector.person.container)
const input = document.querySelector(selector.person.input)
const form = document.querySelector(selector.person.form)
const nameSelections = document.querySelectorAll(selector.name)

let _loading = false

export default function enablePersons () {
  // container.querySelectorAll('li').forEach(li => {
  //   addSelectOption(li.innerText, li.dataset.id)
  // })
  form.addEventListener('submit', addPerson)
}

async function addPerson(event) {
  event.preventDefault()

  if (_loading) {
    return
  }

  _loading = true
  const personName = input.value

  const personData = await Request.post('/add-person', {name: personName})
  _loading = false

  input.value = ''
  const person = document.createElement('li')
  person.innerText = personName
  person.dataset.id = personData.id
  container.appendChild(person)
  addSelectOption(personName, personData.id)
}

function addSelectOption(name, value = '0') {
  nameSelections.forEach(select => {
    const personOption = document.createElement('option')
    personOption.value = value
    personOption.innerText = name

    if (select.dataset.value.length > 0 && select.data.value === value) {
      personOption.selected = true
    }

    select.appendChild(personOption)
  })
}