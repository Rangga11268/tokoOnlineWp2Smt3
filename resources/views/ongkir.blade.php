<!DOCTYPE html>
<html>
<head>
    <title>Cek Ongkir</title>
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <style>
        body{
            font-family: Arial;
            padding:20px;
        }
        select,input,button{
            padding:10px;
            margin:5px;
        }
        #result{
            margin-top:20px;
        }
    </style>
</head>
<body>

<h2>Cek Ongkir</h2>
<form id="ongkirForm">

    <select id="province">
        <option value="">Pilih Provinsi</option>
    </select>
    <select id="city">
        <option value="">Pilih Kota</option>
    </select>
    <input type="number"
           id="weight"
           placeholder="Berat (gram)">
    <select id="courier">
        <option value="">Pilih Kurir</option>
        <option value="jne">JNE</option>
        <option value="tiki">TIKI</option>
        <option value="pos">POS Indonesia</option>
    </select>
    <button type="submit">
        Cek Ongkir
    </button>
</form>
<div id="result"></div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch('/provinces')
    .then(response => response.json())
    .then(data => {
        let provinces = data.data;
        let provinceSelect =
            document.getElementById('province');

        provinces.forEach(province => {
            let option =
                document.createElement('option');
            option.value = province.id;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
    });
    document.getElementById('province')
    .addEventListener('change', function(){
        let provinceId = this.value;
        fetch('/cities/' + provinceId)
        .then(response => response.json())
        .then(data => {
            let citySelect =
                document.getElementById('city');
            citySelect.innerHTML =
                '<option>Pilih Kota</option>';
            data.data.forEach(city => {
                let option =
                    document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        });
    });
    document.getElementById('ongkirForm')
    .addEventListener('submit', function(e){

        e.preventDefault();
        fetch('/cost', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                    'meta[name="csrf-token"]'
                    ).getAttribute('content')
            },
            body:JSON.stringify({
                origin: 649,
                destination:
                    document.getElementById('city').value,
                weight:
                    document.getElementById('weight').value,
                courier:
                    document.getElementById('courier').value
            })
        })
        .then(response => response.json())
        .then(data => {
            let result =
                document.getElementById('result');
            result.innerHTML = '';
            data.data.forEach(item => {
                let div =
                    document.createElement('div');

                div.innerHTML = `
                    <p>
                    <b>${item.name}</b><br>
                    ${item.service}<br>
                    Rp ${item.cost}<br>
                    Estimasi: ${item.etd}
                    </p>
                `;
                result.appendChild(div);
            });

        });

    });

});
</script>
</body>
</html>
