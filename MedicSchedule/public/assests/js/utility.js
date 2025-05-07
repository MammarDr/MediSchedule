function getCookie(name) {

    const cookies = document.cookie.split(';');

    for (let cookie of cookies) {

        const [key, value] = cookie.trim().split('=');
        
        if (key === name) {

        return decodeURIComponent(value);
        }
    }
    
    return null;
}


function removeAppointmentByID(id) {
    const cookie = getCookie('user_session'); 
    fetch(`../../src/core/handle.php?session=${cookie}&id=${parseInt(id)}&delete=true`)
    .then(response => response.json())
    .then(res => {
        if(res['state'] === 'success') {
            console.log('success');
        }
    }).catch(error => {
        console.error('Fetch error:', error); 
    });
    }


function confirmAppointmentByID(id) {
    const cookie = getCookie('user_session'); 
    fetch(`../..//src/core/handle.php?session=${cookie}&id=${parseInt(id)}&confirm=true`)
    .then(response => response.json())
    .then(res => {
        if(res['state'] === 'success') {
            console.log('success');
        }
    }).catch(error => {
        console.error('Fetch error:', error); 
    });
}