import {getLocal, getResult, dateFormat, getDate, getTime} from './functions.js'

const $filterContainer = document.querySelector('.container .filter')

const $localFilter = $filterContainer.querySelector('#local')
const $dateFromFilter = $filterContainer.querySelector('#from')
const $dateToFilter = $filterContainer.querySelector('#to')
const $applyButton = $filterContainer.querySelector('#apply')

const $table = document.querySelector('.table')

$applyButton.addEventListener('click', event => {
  try {
    const local = $localFilter.value
    const dateFrom = dateFormat($dateFromFilter.valueAsDate)
    const dateTo = dateFormat($dateToFilter.valueAsDate)

    const $bodyTable =  $table.querySelector('tbody')

    $bodyTable.innerHTML = ''

    getResult(dateFrom, dateTo, local).then(res => {
      for(const item of res) {
        console.log(item)
        const tr = document.createElement('tr')

        const nameField = document.createElement('td')
        nameField.innerText = item.name
        tr.appendChild(nameField)

        const date = new Date(item.dateCreate)
        const dateField = document.createElement('td')
        dateField.innerText = getDate(date)
        tr.appendChild(dateField)

        const timeField = document.createElement('td')
        timeField.innerText = getTime(date)
        tr.appendChild(timeField)

        const userField = document.createElement('td')
        userField.innerText = item.userName
        tr.appendChild(userField)

        const localField = document.createElement('td')
        localField.innerText = item.local
        tr.appendChild(localField)

        $bodyTable.appendChild(tr)
      }
    })
  } catch (e) {
    console.log(e)
  }

})


getLocal().then(locals => {
  if(locals) {
    for(const local of Array.from(locals)) {
      const option = document.createElement('option')
      option.value = local
      option.innerText = local

      $localFilter.appendChild(option)
    }
  }
})

