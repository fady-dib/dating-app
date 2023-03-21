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
                const bio = data[i].bio;
                const id = data[i].id;
                html = ` <div class="user">
                <img class="image" src="images/1.jpeg">
                <h3 class="name">${name}</h3>
                <div class="bio">${bio}<p>
                    </p>
                </div>
                <div class="btn">
                    <button class="favorite" onClick="favorite(${id})">Favorite</button>
                    <button onClick="message(${id})"class="message">Message</button>
                </div>
            </div>`
            container.insertAdjacentHTML('beforeend', html)
            }
        }
    })
}

const favorite=(id)=>{
    const data = new FormData();
    data.append('favorite_id', id)
    axios.post(`${base_url}favorite`, data, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    }).then(function (res){
        if(res.data.status == "failed"){
            alert('this user is already in your favorites')
        }
        else if(res.data.status == "success"){
            alert('Added to your favorites')
        }
    }).catch(function (error) {
        console.error(error);
    });
}

const message = (id)=>{

}

