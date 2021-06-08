
let originalColor;

function onClick(id) {
    let
        idnum = id.split('-')[1];
    icon = document.getElementById("icon-" + idnum),
        card = document.getElementById("card-" + idnum);
    if (!icon.classList.contains("checkbox")) {
        icon.classList.remove("empty-checkbox");
        icon.classList.add("checkbox");
    } else {
        icon.classList.remove("checkbox");
        icon.classList.add("empty-checkbox");
    }

}

function hover(id) {
    let idnum = id.split('-')[1];
    card = document.getElementById("card-" + idnum),
        icon = document.getElementById("icon-" + idnum),
        color = getComputedStyle(card).getPropertyValue("background-color");
    originalColor = color;
    card.style.backgroundColor = changeColor(-0.2, color);
    if (!icon.classList.contains("checkbox")) {
        icon.classList.remove("icon");
        icon.classList.add("empty-checkbox");
    }
}

function leave(id) {
    let
        idnum = id.split('-')[1];
    card = document.getElementById("card-" + idnum),
        icon = document.getElementById("icon-" + idnum),
        color = getComputedStyle(card).getPropertyValue("background-color");
    card.style.backgroundColor = originalColor;
    if (!icon.classList.contains("checkbox")) {
        icon.classList.remove("empty-checkbox");
        icon.classList.add("icon");
    }
}

function getCurrentCategory() {
    const selected = document.getElementById('type').value;
    document.cookie = `current_category=${selected}`;
    elements = document.getElementsByTagName('input');
    for (i = 0; i < elements.length; i++)
        document.cookie = `${elements[i].name}=${elements[i].value}`;
    location.reload();
}