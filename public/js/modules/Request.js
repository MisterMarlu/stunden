export default class Request {

  /**
   * Send a post request.
   *
   * @param {string} url
   * @param {object|FormData} postData
   * @param {boolean} showLoad
   */
  static post (url, postData, showLoad = false) {
    let info = {
      url,
      method: 'POST',
      body: `json=${encodeURIComponent(JSON.stringify(postData))}`,
    }

    if (postData instanceof FormData) {
      info.body = postData
    }

    return Request._request(info, showLoad)
  }

  /**
   * Send a get request.
   *
   * @param {string} url
   * @param {boolean} showLoad
   */
  static get (url, showLoad = false) {
    let info = {
      url,
      method: 'GET',
    }

    return Request._request(info, showLoad)
  }

  /**
   * Send the request.
   *
   * @param params
   * @param {boolean} showLoad
   * @private
   */
  static _request (params, showLoad) {
    return new Promise((resolve, reject) => {
      let headers = params.headers || {}
      let body = params.body
      let method = params.method || (body ? 'POST' : 'GET')
      let request = new XMLHttpRequest()
      let called = false

      request.open(method, params.url, true)
      request.timeout = 0

      if (body) {
        Request._setDefault(headers, 'X-Requested-With', 'XMLHttpRequest')

        if (!FormData || !(body instanceof FormData)) {
          Request._setDefault(headers, 'Content-Type', 'application/x-www-form-urlencoded')
        }
      }

      for (let field in headers) {
        request.setRequestHeader(field, headers[field])
      }

      /**
       * @internal
       */
      function finished (status, response) {
        return () => {
          if (called) {
            return
          }

          called = true
          let status = request.status === undefined ? 0 : request.status
          let myResponse = request.status === 0 ? 'Error' : (request.response || request.responseText || response)

          try {
            myResponse = JSON.parse(myResponse)
          } catch (e) {
          }

          if (status >= 300) {
            reject(myResponse)
          }

          resolve(myResponse)
        }
      }

      let success = request.onload = finished(200)

      request.onreadystatechange = () => {
        if (request.readyState === 4) {
          success()
        }
      }
      request.onerror = finished(null, 'Error')
      request.ontimeout = finished(null, 'Timeout')
      request.onabort = finished(null, 'Abort')

      request.send(body)
    })
  }

  /**
   * Set a default value.
   *
   * @param headers
   * @param key
   * @param {string} value
   * @private
   */
  static _setDefault (headers, key, value) {
    headers[key] = headers[key] || value
  }
}