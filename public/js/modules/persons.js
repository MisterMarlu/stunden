import Request from './Request.js'
import selector from "./selector.js";

const container = document.querySelector(selector.person.container)
const input = document.querySelector(selector.person.input)
const colorInput = document.querySelector(selector.person.color)
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
  const postData = {
    name: input.value,
    color: colorInput.value
  }

  const personData = await Request.post('/add-person', postData)
  _loading = false

  input.value = ''
  colorInput.value = '#ffffff'
  const person = document.createElement('li')
  person.innerText = personData.name
  person.dataset.id = personData.id
  person.dataset.color = personData.color
  container.appendChild(person)
  addSelectOption(personData)
}

function addSelectOption(person) {
  nameSelections.forEach(select => {
    const personOption = document.createElement('option')
    personOption.value = person.id
    personOption.innerText = person.name
    personOption.dataset.color = person.color

    if (select.dataset.value.length > 0 && select.dataset.value === person.id) {
      personOption.selected = true
    }

    select.appendChild(personOption)
  })
}