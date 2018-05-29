if (document.URL.indexOf("main.html") != -1) {
    if (localStorage.email == null) {
        window.location.href = "http://localhost:3000/index.html"
    }
} else {
    if (localStorage.email != null) {
        window.location.href = "http://localhost:3000/main.html"
    }
}
