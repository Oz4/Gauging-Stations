function getHash(str, algo = 'SHA-256') {
  let strBuf = new TextEncoder().encode(str)
  return crypto.subtle.digest(algo, strBuf).then((hash) => {
    window.hash = hash
    let result = ''
    const view = new DataView(hash)
    for (let i = 0; i < hash.byteLength; i += 4) {
      result += ('00000000' + view.getUint32(i).toString(16)).slice(-8)
    }
    return result
  })
}

function getExtension(filename) {
  let parts = filename.split('.');
  return parts[parts.length - 1];
}

function isExcel(filename) {
  let ext = getExtension(filename);
  switch (ext.toLowerCase()) {
    case 'xlsx':
    case 'xlsm':
    case 'xlsb':
    case 'xls':
    case 'xml':
    case 'tsv':
    case 'csv':
    case 'dif':
    case 'sylk':
    case 'xlw':
    case 'htm':
    case 'xml':
    case 'xltx':
    case 'xltm':
    case 'xlt':
    case 'xlam':
    case 'xla':
    case 'ods':
    case 'dbf':
      return true;
  }
  return false;
}

const readExcel = (file) => {
  const promise = new Promise((resolve, reject) => {
    const fileReader = new FileReader()
    fileReader.readAsArrayBuffer(file)
    fileReader.onload = (e) => {
      const bufferArray = e.target.result
      const wb = XLSX.read(bufferArray, { type: 'buffer' })
      const wsname = wb.SheetNames[0]
      const ws = wb.Sheets[wsname]
      const data = XLSX.utils.sheet_to_json(ws, { raw: false })
      resolve(data)
    }
    fileReader.onerror = (error) => {
      reject(error)
    }
  })
  promise.then( async (d) => {
    let mapPromise = d.map((r, index) => {
      r.no = parseInt(r.no)
      r.level = parseFloat(r.level)
      r.ph = parseFloat(r.ph)
      r.tds = parseFloat(r.tds)
      r.tur = parseFloat(r.tur)
      let hash
      getHash(JSON.stringify(r)).then((result) => {
        hash = result
        $.ajax({
          url: './php/insertion.php',
          type: 'post',
          data: {
            id: hash,
            no: r.no,
            tds: r.tds,
            tur: r.tur,
            ph: r.ph,
            level: r.level,
            date: r.date,
            time: r.time,
          },
          success: (results) => {
            console.log('imported row' , index + 1);
          },
          error: (err) => {
            console.log(err);
          }
        })
      })
    })
  });
  Promise.allSettled([promise]).then(() => {
    document.getElementById('import-log').style.display='block';
  })
}

const input = document.getElementById('fileInput')
input.addEventListener('change', () => {
  let file = input.files[0];
  if (isExcel(file['name'])) {
    readExcel(file);  
  }
  else{
    document.getElementById('fail-log').style.display = 'block';
    console.log('Error: unsupported format');
  }

})


let ImportLog = document.getElementById('import-log');
let editLog = document.getElementById('edit-log');
let deletetLog = document.getElementById('delete-log');
let failLog = document.getElementById('fail-log');
let modalDelete = document.getElementById('modal-delete-id');
let modalEdit = document.getElementById('modal-edit-id');
window.onclick = function(event) {
  if (event.target == ImportLog || event.target == editLog || event.target ==  deletetLog || event.target ==  modalDelete || event.target ==  modalEdit || event.target == failLog) {
    ImportLog.style.display = "none";
    editLog.style.display = "none";
    deletetLog.style.display = "none";
    modalEdit.style.display = "none";
    modalDelete.style.display = "none";
    failLog.style.display = "none";
  }
}


