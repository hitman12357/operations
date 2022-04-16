export const getLocal =  async () => {
  try {
    const response = await fetch("/local")
    return await response.json()
  } catch (e) {
    console.log(e)
  }
}

export const getResult = async (from = '', to = '', local = '-') => {
  try {
    const params = {}
    if(from) {
      params.from = from
    }
    if(to) {
      params.to = to
    }
    if(local !== '-') {
      params.local = local
    }

    let url = '/operation'
    if(params) {
      url += '?' + ( new URLSearchParams( params ) ).toString();
    }

    const response =  await fetch(url)
    return await response.json()
  } catch (e) {
    console.log(e)
  }
}

export const dateFormat = date => {
  return `${("0" + date.getDate()).slice(-2)}.${("0" + (date.getMonth()+1)).slice(-2)}.${date.getFullYear()} 00:00:00`
}

export const getDate  = date => {
  return `${("0" + date.getDate()).slice(-2)}.${("0" + (date.getMonth()+1)).slice(-2)}.${date.getFullYear()}`
}

export const getTime  = date => {
  return `${("0" + date.getHours()).slice(-2)}:00`
}
