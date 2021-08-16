const fileInput = document.querySelector('input[type=file]');
const fileInputName = document.querySelector('.file-name');

fileInput.onchange = () => {
    if (fileInput.files.length) {
        fileInputName.textContent = fileInput.files[0].name;
        const strLen = fileInput.files.length - 1;
        if (strLen > 0) fileInputName.textContent += ` + ${strLen}шт.`;
    } else {
        fileInputName.textContent = '';
    }
}

const resetForm = () => {
    form.reset();
    fileInputName.textContent = '';
}

const popupRender = (text, type) => {
    textBox.innerHTML = text;
    messageBox.classList.add('is-' + type);
    messageBox.classList.remove('is-hidden');
    setTimeout(function () {
        messageBox.classList.add('is-hidden');
        messageBox.classList.remove('is-' + type);
        textBox.innerHTML = '';
    }, 2000);
}

const form = document.querySelector('form');
const messageBox = document.querySelector('#message');
const textBox = messageBox.querySelector('#message-text');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    console.log("Обработка...");

    const request = new XMLHttpRequest();
    request.open("POST", '/add.php', true);

    const elements = form.elements;
    const formData = new FormData();

    ['name', 'phone', 'email'].forEach(function (val) {
        formData.append(val, elements[val].value);
    });

    const files = elements.file.files;

    for (let i = 0; i < files.length; i++) {
        formData.append(i, files[i])
    }

    request.send(formData);

    request.onreadystatechange = () => {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.response === '') {
                popupRender('Пустой ответ', 'warning');
                return;
            }
            const result = JSON.parse(request.response);
            if (result.error) {
                popupRender(result.error, 'danger');
            }
            if (result.success) {
                popupRender('Запись добавлена.', 'success');
                resetForm();
            }
        }
    };
});
