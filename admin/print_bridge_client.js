/**
 * Client cetak ke print-bridge lokal (PC kasir).
 * Mencoba HTTP dan HTTPS — browser HTTPS boleh fetch http://localhost.
 */
(function () {
  function buildUrls(path) {
    return [
      'http://localhost:3000' + path,
      'https://localhost:3000' + path,
      'https://localhost:3443' + path
    ];
  }

  function postToBridge(path, payload, idx, urls) {
    urls = urls || buildUrls(path);
    idx = idx || 0;

    if (idx >= urls.length) {
      var msg = 'Print bridge tidak aktif di PC kasir. Jalankan setup-kasir.bat lalu buka http://localhost:3000/health';
      console.error('❌ ' + msg);
      if (typeof alertyaz === 'function') {
        alertyaz(msg, 'warning');
      } else {
        alert(msg);
      }
      return Promise.reject(new Error(msg));
    }

    var url = urls[idx];
    console.log('🖨️ Mencoba print bridge: ' + url);

    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
      .then(function (res) {
        var ct = res.headers.get('content-type') || '';
        if (!res.ok) {
          return (ct.indexOf('application/json') !== -1 ? res.json() : res.text()).then(function (body) {
            return Promise.reject({ status: res.status, body: body, url: url });
          });
        }
        return ct.indexOf('application/json') !== -1 ? res.json() : res.text();
      })
      .then(function (result) {
        console.log('✅ Cetak berhasil via ' + url + ':', result);
        return result;
      })
      .catch(function (err) {
        console.warn('⚠️ Gagal ' + url + ':', err.message || err);
        return postToBridge(path, payload, idx + 1, urls);
      });
  }

  window.printBridgePost = function (path, payload) {
    return postToBridge(path, payload, 0, buildUrls(path));
  };

  window.printBridgeNota = function (notaData) {
    return window.printBridgePost('/print/nota', notaData);
  };
})();
