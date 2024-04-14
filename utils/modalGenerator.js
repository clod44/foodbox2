class ModalTitle {
    constructor (label) {
        this.label = label;
        this.html = "";
        this.id = 'title_' + Math.floor(Math.random() * 10000);
        this.generateHtml();
    }
    generateHtml() {
        this.html =
            `<div class="mb-3">
                <h3 id="${this.id}" class="">${this.label}</h3>
            </div>`;
    }
}
class ModalLabel {
    constructor (label) {
        this.label = label;
        this.html = "";
        this.id = 'label_' + Math.floor(Math.random() * 10000);
        this.generateHtml();
    }
    generateHtml() {
        this.html =
            `<div class="mb-3">
                <label id="${this.id}" class="">${this.label}</label>
            </div>`;
    }
}
class ModalImage {
    constructor (url) {
        this.url = url;
        this.html = "";
        this.id = 'image_' + Math.floor(Math.random() * 10000);
        this.generateHtml();
    }
    generateHtml() {
        this.html =
            `<div class="mb-3">
                <img id="${this.id}"src="${this.url}" class="img-fluid">
            </div>`;
    }
}

class ModalInput {
    constructor (label, name) {
        this.label = label;
        this.name = name;
        this.html = "";
        this.id = 'input_' + Math.floor(Math.random() * 10000);
        this.generateHtml();
    }
    generateHtml() {
        this.html =
            `<div class="mb-3">
                <label for="${this.id}" class="form-label">${this.label}</label>
                <input type="text" class="form-control" name="${this.name}" id="${this.id}">
            </div>`;
    }
}

class ModalCheckbox {
    constructor (label, name, value, price) {
        this.label = label;
        this.name = name;
        this.price = price;
        this.id = 'input_' + Math.floor(Math.random() * 10000);
        this.html = "";
        this.generateHtml();
    }

    generateHtml() {
        this.html = `
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="${this.id}" name="${this.name}" value="${this.value}">
                <label class="form-check-label" for="${this.id}">${this.label}</label>` +
            (this.price > 0 ? `<span class="ms-2 badge text-bg-warning">$${this.price}</span>` : ``) +
            `</div>`;
    }
}




class ModalRadio {
    constructor (label, name, value, price, requireOne) {
        this.label = label;
        this.name = name;
        this.value = value;
        this.price = price;
        this.requireOne = requireOne;
        this.id = 'radio_' + Math.floor(Math.random() * 10000);
        this.html = "";
        this.generateHtml();
    }
    generateHtml() {
        this.html =
            `<div class="form-check">
                <input class="form-check-input" type="radio" name="${this.name}" id="${this.id}" value="option1" ` + (this.requireOne ? "required" : "") + `>
                    <label class="form-check-label" for="${this.id}">${this.label}</label>` +
            (this.price > 0 ? `<span class="ms-2 badge text-bg-warning">$${this.price}</span>` : ``) +
            `</div>`;

    }
}

class ModalCreator {
    constructor (title, data, buttonText, url = "") {
        this.title = title;
        this.data = data;
        this.buttonText = buttonText;
        this.url = url;
        this.html = "";
        this.id = 'modal_' + Math.floor(Math.random() * 10000);
        this.generateHtml();
    }

    generateHtml() {
        let content = "";

        //parse this.data and generate elements in the content with the given data
        this.data.forEach(element => {
            content += element.html;
        });

        this.html = `
            <div class="modal fade" id="${this.id}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${this.title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="${this.url}">
                            ${content}
                            <button type="submit" class="btn btn-primary">${this.buttonText}</button>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    show() {
        // Append the modal HTML to the body
        $('body').append(this.html);

        // Initialize the modal
        $('#' + this.id).modal('show');

        // Remove the modal from the DOM when it's hidden
        $('#' + this.id).on('hidden.bs.modal', function (e) {
            $(this).remove();
        });
    }
}

/*
//example usage
$('#openModalBtn').click(function () {
    // Example data for the modal content
    const modalData = [
        new ModalImage("https://example.com/image.jpg"),
        new ModalInput("Email", "email"),
        new ModalCheckbox("Agree to terms", "terms", 0, true),
        new ModalRadio("Choose an option", "options", "option1", 10, true),
        new ModalRadio("Choose another option", "options", "option2", 15, false)
    ];

    // Create an instance of ModalCreator
    const modal = new ModalCreator("Example Modal", modalData, "Submit", "/submit-url");

    // Show the modal
    modal.show();
});

^this is not up to date
*/

