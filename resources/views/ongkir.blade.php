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
                '<option value="">Pilih Kota</option>';
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

        const destination = document.getElementById('city').value;
        const weight = document.getElementById('weight').value;
        const courier = document.getElementById('courier').value;

        if (!destination || !weight || !courier) {
            alert('Harap isi semua field!');
            return;
        }

        const result = document.getElementById('result');
        result.innerHTML = '<p>Sedang memuat...</p>';

        fetch('{{ route("ongkir.cost") }}', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                    'meta[name="csrf-token"]'
                    ).getAttribute('content')
            },
            body:JSON.stringify({
                origin: 649,
                destination: destination,
                weight: weight,
                courier: courier
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Server Error (' + response.status + '): ' + text.substring(0, 100));
                });
            }
            return response.json();
        })
        .then(data => {

            console.log(data);

            let result =
                document.getElementById('result');

            result.innerHTML = '';

            let costs = data.data || [];

            costs.forEach(item => {

                let div =
                    document.createElement('div');

                div.innerHTML = `
                    <p>
                        <b>${item.name}</b><br>
                        Layanan : ${item.service}<br>
                        Harga : Rp ${item.cost}<br>
                        Estimasi : ${item.etd}
                    </p>
                    <hr>
                `;

                result.appendChild(div);

            });

        });

    });

});
</script>
</body>
</html>
