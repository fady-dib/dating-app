const base_url = "http://127.0.0.1:8000/api/";
container = document.getElementById('container');

const token = window.localStorage.getItem('token');
let html = '';
window.onload = () => {
    axios.get(`${base_url}users`, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    }).then(function (res) {
        if (res.data.message == 'No users') {
            html = `<div><p>No users to show </p></div>`;
            container.insertAdjacentHTML('beforeend', html);
        }
        else if (res.data.status == "success") {
            const data = res.data.data;
            console.log(data)
            for(i=0;i<data.length;i++){
                const name=data[i].first_name+" "+data[i].last_name;
            }
        }
    })
}

