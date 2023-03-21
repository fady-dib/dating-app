const baseUrl = "http://127.0.0.1:8000/api/";

const submit = () => {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
//     regex_email=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     regex_password =/^(?=.*[a-z])(?=.*[A-Z])/
// if(!regex_email.test(email)){
//     alert('Please enter a valid email')
// }
    // if (password.length < 6 || !regex_email.test(password) ) {
    //     alert('Password should contain 6 characters minimum and at least one uppercase');

    // }

    let data = new FormData();
    data.append('email', email);
    data.append('password', password);
    axios.post(`${baseUrl}login`, data).then(function (res) {
        if (res.data.status == 'success') {
            location.replace('home.html')
            // console.log(res.data.authorisation.token)
            window.localStorage.setItem('token', res.data.authorisation.token);
        }

    }).catch(function (error) {
        console.log(error)
    })
}

const register =() =>{
    const first_name = document.getElementById('firstname').value;
    const last_name = document.getElementById('lastname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const dob = document.getElementById('dob').value;
    const gender_id = document.querySelector('input[name="gender"]:checked').value;

   const formatDate = () => {
    const date = dob.toLocaleString('default', {year: 'numeric', month: '2-digit', day: '2-digit'})
    return date
   }

   console.log(formatDate());
    

    let data = new FormData();
    data.append('first_name', first_name);
    data.append('last_name', last_name)
    data.append('email', email);
    data.append('password', password)
    data.append('dob', formatDate())
    data.append('gender_id', gender_id)

    axios.post(`${baseUrl}register`, data).then(function(res){
        if (res.data.message == "User with this email already exists"){
            alert('Already registred')
        }
        if(res.data.message == "User created successfully"){
            alert('Registered successfully')
            window.localStorage.setItem('token',res.data.authorisation.token)
            // location.replace('./index.html')
        }
    }).catch(function(error){
        console.log(error)
})
}

// const getUsers =(){

// }
