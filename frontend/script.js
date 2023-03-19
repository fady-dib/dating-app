const baseUrl = "http://127.0.0.1:8000/api/";

const submit = () => {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    regex_email=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    regex_password =/^(?=.*[a-z])(?=.*[A-Z])/
if(!regex_email.test(email)){
    alert('Please enter a valid email')
}
    if (password.length < 6 || !regex_email.test(password) ) {
        alert('Password should contain 6 characters minimum and at least one uppercase');

    }

    let data = new FormData();
    data.append('email', email);
    data.append('password', password);
    axios.post(`${baseUrl}login`, data).then(function (res) {
        if (res.data.status == 'success') {
            // location.replace('')
            // console.log(res.data.authorisation.token)
            window.localStorage.setItem('token', res.data.authorisation.token);
        }

    }).catch(function (error) {
        console.log(error)
    })
}